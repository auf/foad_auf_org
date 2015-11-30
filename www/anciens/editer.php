<?php
require_once("inc_session.php") ;
if ( intval($_SESSION["id"]) > 0 ) {
	header("Location: /") ;
}

if ( !isset($_GET["id_dossier"]) OR !isset($_GET["id_ancien"]) ) {
	header("Location: /anciens/") ;
	exit ;
}

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
// 
// ---------------------------------------
$req = "SELECT * FROM anciens, dossier_anciens
	WHERE id_ancien=ref_ancien
	AND ref_dossier=".$_GET["id_dossier"]."
	AND id_ancien=".$_GET["id_ancien"] ;
$res = mysqli_query($cnx, $req) ;
$T = mysqli_fetch_assoc($res) ;

// ------------
// Fil d'ariane
// ------------
$identite = identite($T) ;
$titre = strip_tags($identite) ;
require_once("inc_html.php");
echo $dtd1 ;
echo "<title>Édition de l'année d'obtention d'un diplôme de ".$titre."</title>" ;
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
echo "Édition de l'année d'obtention d'un diplôme" ;
echo $fin_chemin ;






afficheAncien($_GET["id_ancien"], $cnx, FALSE, TRUE, TRUE, TRUE) ;


deconnecter($cnx) ;
echo $end ;
?>
