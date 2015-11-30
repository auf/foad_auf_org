<?php
include("inc_session.php") ;

include("inc_mysqli.php") ;
$cnx = connecter() ;

$req = "SELECT dossier.*, candidat.*,
	evaluations, imputations, imputations2, anneed,
	(SELECT id_imputation FROM imputations WHERE ref_dossier=id_dossier AND annee_relative=1)
	AS id_imputation1,
	(SELECT id_imputation FROM imputations WHERE ref_dossier=id_dossier AND annee_relative=2)
	AS id_imputation2,
	(SELECT etat FROM imputations WHERE ref_dossier=id_dossier AND annee_relative=2)
	AS etat2
	FROM session,
	dossier JOIN candidat ON dossier.id_candidat=candidat.id_candidat
	LEFT JOIN dossier_anciens ON dossier.id_dossier=dossier_anciens.ref_dossier
	WHERE dossier.id_dossier=".$_GET["id_dossier"]."
	AND dossier.id_session=session.id_session" ;
$res = mysqli_query($cnx, $req) ;
$T = mysqli_fetch_assoc($res) ;

// Chemin
$req = "SELECT intitule, intit_ses, evaluations, imputations, ref_institution
	FROM atelier, session
	WHERE atelier.id_atelier=session.id_atelier
	AND session.id_session=".$T["id_session"] ;
$res = mysqli_query($cnx, $req) ;
$enr = mysqli_fetch_assoc($res) ;

if	(
		( isset($formation["evaluations"]) AND ($formation["evaluations"]=="Non") )
		AND ( isset($formation["imputations"]) AND ($formation["imputations"]=="Non") )
	)
{
	deconnecter($cnx) ;
	header("Location: /candidatures/index.php") ;
	exit ;
}
if ( ( intval($_SESSION["id"]) > 4 )
	AND ( !in_array($T["id_session"], $_SESSION["tableau_toutes_promotions"]) ) )
{
	deconnecter($cnx) ;
	header("Location: /candidatures/index.php") ;
	exit ;
}

include("inc_html.php") ;
$titre = "Dossier de candidature" ;
echo $dtd1 ;
echo "<title>$titre</title>" ;
echo $dtd2 ;
include("inc_menu.php") ;

echo $debut_chemin ;
echo "<a href='/bienvenue.php'>Accueil</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo "<a href='index.php'>Gestion des candidatures</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo "<a href='candidatures.php?id_session=".$T["id_session"]."#d".$T["id_dossier"]."'>".$enr["intitule"]." <span class='normal'>(".$enr["intit_ses"].")</span></a>" ;
echo $fin_chemin ;

//Ajout de l'institution au tableau
$T["ref_institution"] = $enr["ref_institution"] ;

// Dossier
include_once("inc_formulaire_candidature.php") ;
include_once("inc_date.php");
include_once("inc_etat_dossier.php") ;
include_once("inc_dossier.php");
include_once("inc_guillemets.php");
affiche_dossier($T, $_SESSION, $cnx) ;

echo $end;
deconnecter($cnx) ;
?>
