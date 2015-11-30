<?php
include_once("inc_session.php") ;
$_SESSION["filtres"]["fils"] = array() ;
$_SESSION["filtres"]["fils"]["annee"] = $_POST["fils_annee"] ;
$_SESSION["filtres"]["fils"]["tri"] = $_POST["fils_tri"] ;
header("Location: /messagerie_cnf/") ;
?>
