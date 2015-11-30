<?php
include_once("inc_session.php") ;

$_SESSION["anciens_precedente"] = $_SESSION["anciens"] ;
unset($_SESSION["anciens"]) ;

list($key, $val) = each($_GET) ;

if ( $key == "naissance" ) {
	$nai = explode("-", $val) ;
	$_SESSION["anciens"]["jour_n"] = $nai[2] ;
	$_SESSION["anciens"]["mois_n"] = $nai[1] ;
	$_SESSION["anciens"]["annee_n"] = $nai[0] ;
	$_SESSION["anciens"]["tri1"] = "nom" ;
}
else {
	$_SESSION["anciens"]["$key"] = rawurldecode($_GET["$key"]) ;
}

$_SESSION["anciens"]["ok"] = "ok" ;

header("Location: /anciens/") ;
?>
