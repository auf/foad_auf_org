<?php
include_once("inc_session.php") ;
if ( intval($_SESSION["id"]) != 0 ) {
	header("Location: /bienvenue.php") ;
}
if	(
		!isset($_POST["id_dossier"])
		OR !isset($_POST["id_session"])
		OR !isset($_POST["id_atelier"])
	)
{
	header("Location: /recherche/") ;
}

require_once("inc_mysqli.php");
$cnx = connecter() ;

$req = "SELECT dossier.*, candidat.*,
	intitule, intit_ses, annee, atelier.id_atelier, nb_annees,
	(SELECT id_imputation FROM imputations WHERE ref_dossier=id_dossier AND annee_relative=1)
	AS id_imputation1,
	(SELECT id_imputation FROM imputations WHERE ref_dossier=id_dossier AND annee_relative=2)
	AS id_imputation2,
	(SELECT etat FROM imputations WHERE ref_dossier=id_dossier AND annee_relative=2)
	AS etat2
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
$A = candidat2anciens($T) ;

$req = "INSERT INTO anciens
	(courriel, civilite, nom, nom_jf, prenom, naissance, nationalite, pays,
	profession, employeur, date_maj) VALUES(
	'".mysqli_real_escape_string($cnx, $A["courriel"])."',
	'".mysqli_real_escape_string($cnx, $A["civilite"])."',
	'".mysqli_real_escape_string($cnx, $A["nom"])."',
	'".mysqli_real_escape_string($cnx, $A["nom_jf"])."',
	'".mysqli_real_escape_string($cnx, $A["prenom"])."',
	'".mysqli_real_escape_string($cnx, $A["naissance"])."',
	'".mysqli_real_escape_string($cnx, $A["nationalite"])."',
	'".mysqli_real_escape_string($cnx, $A["pays"])."',
	'".mysqli_real_escape_string($cnx, $A["profession"])."',
	'".mysqli_real_escape_string($cnx, $A["employeur"])."',
	'".mysqli_real_escape_string($cnx, $A["date_maj"])."'
	)" ;
//echo $req . "<br />\n" ;
$res = mysqli_query($cnx, $req) ;

$req = "SELECT LAST_INSERT_ID() AS N" ;
$res = mysqli_query($cnx, $req) ;
$enr = mysqli_fetch_assoc($res) ;
$id = $enr["N"] ;

$req = "UPDATE dossier SET diplome='Oui', ref_ancien=$id
	WHERE id_dossier=".$T["id_dossier"] ;
//echo $req . "<br />\n" ;
$res = mysqli_query($cnx, $req) ;

$req = "INSERT INTO dossier_anciens
	(ref_dossier, ref_ancien, ref_session, ref_atelier, anneed)
	VALUES(
	".$T["id_dossier"].",
	".$id.",
	".$T["id_session"].",
	".$T["id_atelier"].",
	".$_POST["anneed"]."
	)" ;
//	echo $req . "<br />\n" ;
$res = mysqli_query($cnx, $req) ;
deconnecter($cnx) ;

header("Location: /anciens/ancien.php?id_ancien=$id") ;
?>
