<?php
include("inc_session.php") ;
require_once("inc_guillemets.php");
/*
while (list($key, $val) = each($_POST)) {
   echo "$key => $val<br />";
}
*/
if ( isset($_GET["id_session"]) ) 
{
//	$_SESSION["filtres"]["inscrits"]["annee"] = $_POST["inscrits_annee"] ;
//	$_SESSION["filtres"]["inscrits"]["groupe"] = $_POST["groupe"] ;
	$_SESSION["filtres"]["inscrits"]["pays"] = $_POST["inscrits_pays"] ;
	$_SESSION["filtres"]["inscrits"]["etat"] = $_POST["inscrits_etat"] ;
	$_SESSION["filtres"]["inscrits"]["nom"]  = $_POST["inscrits_nom"] ;
	$_SESSION["filtres"]["inscrits"]["resultat"]  = $_POST["inscrits_resultat"] ;
	$_SESSION["filtres"]["inscrits"]["tri"]  = $_POST["inscrits_tri"] ;

	header("Location: inscrits.php?id_session=".$_GET["id_session"]) ;
}
else {
	$_SESSION["filtres"]["inscrits"]["pays"] = $_POST["inscrits_pays"] ;
	$_SESSION["filtres"]["inscrits"]["annee"] = $_POST["inscrits_annee"] ;
	$_SESSION["filtres"]["inscrits"]["groupe"] = $_POST["groupe"] ;
	header("Location: index.php") ;
}
?>
