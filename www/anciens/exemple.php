<?php
include_once("inc_session.php") ;

$_SESSION["recherche_precedente"] = $_SESSION["rechercher"] ;
unset($_SESSION["rechercher"]) ;








$_SESSION["rechercher"]["nom"] = rawurldecode($_GET["nom"]) ;

$_SESSION["rechercher"]["tri1"] = "nom" ;
//$_SESSION["rechercher"]["tri2"] = "" ;
//$_SESSION["rechercher"]["tri3"] = "" ;

$_SESSION["rechercher"]["ok"] = "ok" ;

header("Location: /recherche/") ;
?>
