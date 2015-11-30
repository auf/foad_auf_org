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
	$_SESSION["filtres"]["candidatures"]["annee"] = $_POST["c_annee"] ;
	$_SESSION["filtres"]["candidatures"]["groupe"] = $_POST["groupe"] ;
	$_SESSION["filtres"]["candidatures"]["pays"] = $_POST["c_pays"] ;
	$_SESSION["filtres"]["candidatures"]["etat"] = $_POST["c_etat"] ;
	$_SESSION["filtres"]["candidatures"]["nom"]  = $_POST["c_nom"] ;
	$_SESSION["filtres"]["candidatures"]["max"]  = $_POST["c_max"] ;
	$_SESSION["filtres"]["candidatures"]["tri"]  = $_POST["c_tri"] ;

	header("Location: candidatures.php?id_session=".$_GET["id_session"]) ;
}
else {
	$_SESSION["filtres"]["candidatures"]["pays"] = $_POST["c_pays"] ;
	$_SESSION["filtres"]["candidatures"]["annee"] = $_POST["c_annee"] ;
	$_SESSION["filtres"]["candidatures"]["groupe"] = $_POST["groupe"] ;
	header("Location: index.php") ;
}
?>
