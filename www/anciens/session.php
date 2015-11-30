<?php
include_once("inc_session.php") ;

$_SESSION["anciens_precedente"] = $_SESSION["anciens"] ;
unset($_SESSION["anciens"]) ;

if ( $_POST["anciens_annee"] != "" ) {
	$_SESSION["anciens"]["annee"] = $_POST["anciens_annee"] ;
}
if ( $_POST["anciens_formation"] != "" ) {
	$_SESSION["anciens"]["formation"] = $_POST["anciens_formation"] ;
}
if ( $_POST["anciens_promo"] != "" ) {
	$_SESSION["anciens"]["promo"] = $_POST["anciens_promo"] ;
}
if ( $_POST["anciens_etat"] != "" ) {
	$_SESSION["anciens"]["etat"] = $_POST["anciens_etat"] ;
}
if ( $_POST["anciens_imputation"] != "" ) {
	$_SESSION["anciens"]["imputation"] = $_POST["anciens_imputation"] ;
}
//
if ( $_POST["anciens_genre"] != "" ) {
	$_SESSION["anciens"]["genre"] = $_POST["anciens_genre"] ;
}
if ( trim($_POST["anciens_nom"]) != "" ) {
	$_SESSION["anciens"]["nom"] = $_POST["anciens_nom"] ;
}
if ( trim($_POST["anciens_prenom"]) != "" ) {
	$_SESSION["anciens"]["prenom"] = $_POST["anciens_prenom"] ;
}
if ( $_POST["anciens_annee_n"] != "" ) {
	if ( ($_POST["anciens_jour_n"]!="") 
		AND ($_POST["anciens_mois_n"]!="") )
	{
		$_SESSION["anciens"]["jour_n"] = $_POST["anciens_jour_n"] ;
		$_SESSION["anciens"]["mois_n"] = $_POST["anciens_mois_n"] ;
	}
	$_SESSION["anciens"]["annee_n"] = $_POST["anciens_annee_n"] ;
}
if ( trim($_POST["anciens_courriel"]) != "" ) {
	$_SESSION["anciens"]["courriel"] = $_POST["anciens_courriel"] ;
}
if ( trim($_POST["anciens_pays"]) != "" ) {
	$_SESSION["anciens"]["pays"] = trim($_POST["anciens_pays"]) ;
}
if ( trim($_POST["anciens_bureau"]) != "" ) {
	$_SESSION["anciens"]["bureau"] = trim($_POST["anciens_bureau"]) ;
}
//
if ( trim($_POST["anciens_tri1"]) != "" ) {
	$_SESSION["anciens"]["tri1"] = trim($_POST["anciens_tri1"]) ;
}
if ( trim($_POST["anciens_tri2"]) != "" ) {
	$_SESSION["anciens"]["tri2"] = trim($_POST["anciens_tri2"]) ;
}
if ( trim($_POST["anciens_tri3"]) != "" ) {
	$_SESSION["anciens"]["tri3"] = trim($_POST["anciens_tri3"]) ;
}


$_SESSION["anciens"]["ok"] = "ok" ;

header("Location: /anciens/") ;
?>
