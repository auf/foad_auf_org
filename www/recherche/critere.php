<?php
include_once("inc_session.php") ;

$_SESSION["filtres"]["recherche_precedente"] = $_SESSION["filtres"]["recherche"] ;
unset($_SESSION["filtres"]["recherche"]) ;

list($key, $val) = each($_GET) ;

if ( $key == "naissance" ) {
	$nai = explode("-", $val) ;
	$_SESSION["filtres"]["recherche"]["jour_n"] = $nai[2] ;
	$_SESSION["filtres"]["recherche"]["mois_n"] = $nai[1] ;
	$_SESSION["filtres"]["recherche"]["annee_n"] = $nai[0] ;
	$_SESSION["filtres"]["recherche"]["tri1"] = "nom" ;
}
else {
	$_SESSION["filtres"]["recherche"]["$key"] = rawurldecode($_GET["$key"]) ;
}

$_SESSION["filtres"]["recherche"]["ok"] = "ok" ;

header("Location: /recherche/") ;
?>
