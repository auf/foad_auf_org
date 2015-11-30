<?php
include("inc_session.php") ;
if ( !is_numeric($_GET["id_imputation"]) OR !is_numeric($_GET["ref_dossier"]) )
{
	header("Location: /") ;
	exit ;
}
if ( $_SESSION["utilisateur"] != "Service des bourses (SCP)" )
{
	header("Location: attestation.php?id=".$_GET["id_imputation"]) ;
	exit ;
}

include("inc_mysqli.php") ;
$cnx = connecter() ;

$req = "SELECT imputation, annee_relative, commentaire
	FROM imputations
	WHERE id_imputation=".$_GET["id_imputation"]."
	AND ref_dossier=".$_GET["ref_dossier"]."
	AND etat='Payant'" ;
$res = mysqli_query($cnx, $req) ;
if ( mysqli_num_rows($res) == 0 ) {
	header("Location: attestation.php?id=".$_GET["id_imputation"]) ;
	deconnecter($cnx) ;
	exit ;
}
$enr = mysqli_fetch_assoc($res) ;

$commentaire = "Payant devenu Allocataire le ".date("d/m/Y", time()).".\n" 
	. $enr["commentaire"] ;
$imputation = substr($enr["imputation"], 0, -1) . "A" ;

$req = "UPDATE imputations SET
	commentaire='".mysqli_real_escape_string($cnx, $commentaire)."',
	imputation='".mysqli_real_escape_string($cnx, $imputation)."',
	etat='Allocataire'
	WHERE id_imputation=".$_GET["id_imputation"]."
	AND ref_dossier=".$_GET["ref_dossier"] ;
mysqli_query($cnx, $req) OR mysqli_error($cnx) ;


if ( $enr["annee_relative"] == "1" ) {
	$req = "UPDATE dossier SET etat_dossier='Allocataire'
		WHERE id_dossier=".$_GET["ref_dossier"] ;
	mysqli_query($cnx, $req) OR mysqli_error($cnx) ;
}

header("Location: attestation.php?id=".$_GET["id_imputation"]) ;

deconnecter($cnx) ;
?>
