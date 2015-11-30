<?php
include_once("inc_session.php") ;
if ( intval($_SESSION["id"]) != 0 ) {
	header("Location: /bienvenue.php") ;
}
if	(
		!isset($_POST["id_dossier"])
		OR !isset($_POST["id_session"])
		OR !isset($_POST["id_atelier"])
		OR !isset($_POST["id_ancien"])
	)
{
	header("Location: /recherche/") ;
}

require_once("inc_mysqli.php");
$cnx = connecter() ;

$req = "SELECT * FROM anciens WHERE id_ancien=".$_POST["id_ancien"] ;
$res = mysqli_query($cnx, $req) ;
if ( mysqli_num_rows($res) == 0 ) {
	header("Location: /recherche/") ;
	deconnecter($cnx) ;
	exit ;
}

$req = "SELECT dossier.*, candidat.*,
	intitule, intit_ses, annee, atelier.id_atelier, nb_annees
	FROM candidat, atelier, session, dossier
	WHERE dossier.id_dossier=".$_POST["id_dossier"]."
	AND dossier.id_session=".$_POST["id_session"]."
	AND atelier.id_atelier=".$_POST["id_atelier"]."
	AND dossier.id_candidat=candidat.id_candidat
	AND atelier.id_atelier=session.id_atelier
	AND session.id_session=dossier.id_session" ;
$res = mysqli_query($cnx, $req) ;
if ( mysqli_num_rows($res) == 0 ) {
	header("Location: /recherche/") ;
	deconnecter($cnx) ;
	exit ;
}
else {
	$T = mysqli_fetch_assoc($res) ;
}

require_once("inc_anciens.php");

$req = "UPDATE dossier SET diplome='Oui', ref_ancien=".$_POST["id_ancien"]."
	WHERE id_dossier=".$T["id_dossier"] ;
//echo $req . "<br />\n" ;
$res = mysqli_query($cnx, $req) ;

$req = "INSERT INTO dossier_anciens
	(ref_dossier, ref_ancien, ref_session, ref_atelier, anneed)
	VALUES(
	". $T["id_dossier"] .",
	". $_POST["id_ancien"] .",
	". $T["id_session"] .",
	". $T["id_atelier"] .",
	".$_POST["anneed"]."
	)" ;
//echo $req . "<br />\n" ;
$res = mysqli_query($cnx, $req) ;


// Mise a jour de la fiche de l'ancien
// si la date de mise a jour du dossier pour lequel un diplome est ajoute
// est postérieure a celle de la fiche de l'ancien.
$dateDossier = strtotime($T["date_maj"]) ;
$req = "SELECT date_maj FROM anciens WHERE id_ancien=".$_POST["id_ancien"] ;
$res = mysqli_query($cnx, $req) ;
$enr = mysqli_fetch_assoc($res) ;
$dateAncien = strtotime($enr["date_maj"]) ;
if ( $dateDossier > $dateAncien )
{
	$A = candidat2anciens($T) ;
	$req = "UPDATE anciens SET
		courriel='".addslashes($A["courriel"])."',
		civilite='".addslashes($A["civilite"])."',
		nom='".addslashes($A["nom"])."',
		nom_jf='".addslashes($A["nom_jf"])."',
		prenom='".addslashes($A["prenom"])."',
		naissance='".addslashes($A["naissance"])."',
		nationalite='".addslashes($A["nationalite"])."',
		pays='".addslashes($A["pays"])."',
		profession='".addslashes($A["profession"])."',
		employeur='".addslashes($A["employeur"])."',
		date_maj='".addslashes($A["date_maj"])."'
		WHERE id_ancien=".$_POST["id_ancien"] ;
	mysqli_query($cnx, $req) ;
}

deconnecter($cnx) ;

header("Location: /anciens/ancien.php?id_ancien=".$_POST["id_ancien"]) ;
?>
