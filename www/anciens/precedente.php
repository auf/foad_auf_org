<?php
include_once("inc_session.php") ;

$intermediaire = $_SESSION["anciens"] ;
$_SESSION["anciens"] = $_SESSION["anciens_precedente"] ;
$_SESSION["anciens_precedente"] = $intermediaire ;

$_SESSION["anciens"]["ok"] = "ok" ;

header("Location: /anciens/") ;
?>
