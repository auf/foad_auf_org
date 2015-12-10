<?php
include_once("inc_session.php") ;
unset($_SESSION["filtres"]["inscrits"]) ;
// Il doit y avoir au moins un parametre $_GET["id_session"]
// Le second, $_GET["nom"]
$url = "/inscrits/inscrits.php?id_session=".$_GET["id_session"] ;
if ( isset($_GET["nom"]) ) {
	$url .= "&nom=".$_GET["nom"] ;
}
header("Location: ".$url) ;
?>
