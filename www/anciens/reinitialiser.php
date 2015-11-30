<?php
include_once("inc_session.php") ;
$_SESSION["anciens_precedente"] = $_SESSION["anciens"] ;
unset($_SESSION["anciens"]) ;
header("Location: /anciens/") ;
?>
