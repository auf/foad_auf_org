<?php
require_once("inc_session.php") ;
if ( intval($_SESSION["id"]) > 0 ) {
	header("Location: /") ;
}

if ( !isset($_GET["id_dossier"]) ) {
	header("Location: /anciens/") ;
	exit ;
}

// -------------------------------------
// Fonctions formulaires de confirmation
// -------------------------------------
function formulaireSuppressionDiplome($id_ancien, $id_dossier)
{
	$form  = "\n<form method='post' action='suppressionDiplome.php'>\n" ;
	$form .= "<input type='hidden' name='id_ancien' value='$id_ancien' />\n" ;
	$form .= "<input type='hidden' name='id_dossier' value='$id_dossier' />\n" ;
	$form .= "<p class='c'>\n" ;
	$form .= "<input type='submit' value='Supprimer ce diplôme' />\n" ;
	$form .= "</p>\n" ;
	$form .= "</form>\n" ;
	return $form ;
}
function formulaireSuppressionAncien($id_ancien, $id_dossier)
{
	$form  = "\n<form method='post' action='suppressionAncien.php'>\n" ;
	$form .= "<input type='hidden' name='id_ancien' value='$id_ancien' />\n" ;
	$form .= "<input type='hidden' name='id_dossier' value='$id_dossier' />\n" ;
	$form .= "<p class='c'>\n" ;
	$form .= "<input type='submit' value='Supprimer cet ancien' />\n" ;
	$form .= "</p>\n" ;
	$form .= "</form>\n" ;
	return $form ;
}
// -------------------------------------
// -------------------------------------

require_once("inc_mysqli.php");
$cnx = connecter() ;
require_once("inc_anciens.php") ;

// ------------------------------------------------------------
// Un ou plusieurs diplômes (N) pour cet ancien ?
// id_dossier dans l'URL correspond-il à id_ancien dans l'URL ?
// ------------------------------------------------------------
$req = "SELECT ref_dossier FROM dossier_anciens
	WHERE ref_ancien=".$_GET["id_ancien"] ;
$res = mysqli_query($cnx, $req) ;
$N = mysqli_num_rows($res) ;

$dossierCorrespond = FALSE ;
while ( $enr = mysqli_fetch_assoc($res) ) {
	if ( intval($enr["ref_dossier"]) == intval($_GET["id_dossier"]) ) {
		$dossierCorrespond = TRUE ;
	}
}

if ( ! $dossierCorrespond ) {
	header("Location: /anciens/") ;
	deconnecter($cnx) ;
	exit ;
}

// ---------------------------------------
// Si N == 1, un email a t-il été envoyé ?
// Fil d'ariane
// ---------------------------------------
$req = "SELECT * FROM anciens WHERE id_ancien=".$_GET["id_ancien"] ;
$res = mysqli_query($cnx, $req) ;
$T = mysqli_fetch_assoc($res) ;

$msgAlerteEnvoi = "<p class='erreur'>Attention, cet ancien a déjà reçu son mot de passe par courrier électronique... Il n'est pas censé pouvoir être supprimé.</p>\n" ;
$alerteEnvoi = FALSE ;
if ( intval($T["nb_envoi"]) != 0 ) {
	$alerteEnvoi = TRUE ;
}

// ------------
// Fil d'ariane
// ------------
$identite = identite($T) ;
$titre = strip_tags($identite) ;
require_once("inc_html.php");
echo $dtd1 ;
echo "<title>Suppression ? (Ancien : ".$titre.")</title>" ;
echo $dtd2 ;
require_once("inc_menu.php");
echo $debut_chemin ;
echo "<a href='/bienvenue.php'>Accueil</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo "<a href='/anciens/'>Anciens</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo "<a href='/anciens/ancien.php?id_ancien=".$_GET["id_ancien"]."'>" ;
echo "$titre</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo "Suppression ?" ;
echo $fin_chemin ;






// Plus de 1 diplomes
if ( $N > 1 ) {
	echo "<p>Confirmer la suppression de ce diplôme de cet ancien ?</p>\n" ;	
	echo "<div class='diplome'>\n" ;
	// Affichage du diplome a supprimer
	$req = "SELECT intitule, universite, intit_ses, annee, etat_dossier, diplome,
		dossier.id_dossier, id_imputation
		FROM session, atelier, dossier
		LEFT JOIN imputations ON dossier.id_dossier=imputations.ref_dossier
		WHERE dossier.id_dossier=".$_GET["id_dossier"]."
		AND dossier.ref_ancien=".$_GET["id_ancien"]."
		AND dossier.id_session=session.id_session
		AND session.id_atelier=atelier.id_atelier" ;
	$res = mysqli_query($cnx, $req) ;
	$D = mysqli_fetch_assoc($res) ;
	echo ancienDiplome($D, TRUE) ;
	echo formulaireSuppressionDiplome($_GET["id_ancien"], $_GET["id_dossier"]) ;
	echo "</div>\n" ;
}
// Un unique diplome => supprimer l'ancien aussi
// FIXME ssi anciens.nb_envoi = 0 ?
else {
	echo "<p>Confirmer la suppression de ce diplôme <strong>et</strong> de cet ancien ?</p>\n" ;
	echo formulaireSuppressionAncien($_GET["id_ancien"], $_GET["id_dossier"]) ;
}

echo "<div class='ancien'>\n" ;
afficheAncien($_GET["id_ancien"], $cnx, FALSE, TRUE, TRUE, TRUE) ;
echo "</div>\n" ;


deconnecter($cnx) ;
echo $end ;
?>
