<?php
include("inc_session.php") ;

$_SESSION["filtres"]["statistiques"]["annee"] = $_POST["stats_annee"] ;
$_SESSION["filtres"]["statistiques"]["region"] = $_POST["stats_region"] ;
$_SESSION["filtres"]["statistiques"]["pays"] = $_POST["stats_pays"] ;
$_SESSION["filtres"]["statistiques"]["etat"] = $_POST["stats_etat"] ;
$_SESSION["filtres"]["statistiques"]["limiter"] = $_POST["stats_limiter"] ;
$_SESSION["filtres"]["statistiques"]["details"] = $_POST["stats_details"] ;

header("Location: /statistiques/") ;
?>
