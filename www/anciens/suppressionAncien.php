<?php
require_once("inc_session.php") ;
if ( intval($_SESSION["id"]) > 0 ) {
header("Location: /") ;
}

if ( !isset($_POST["id_ancien"]) OR !isset($_POST["id_dossier"]) ) {
	header("Location: /anciens/") ;
	exit ;
}

require_once("inc_mysqli.php");
$cnx = connecter() ;

$req = "UPDATE dossier SET diplome='Non', ref_ancien='0'
	WHERE id_dossier=".$_POST["id_dossier"];
mysqli_query($cnx, $req) ;

$req = "DELETE FROM dossier_anciens WHERE ref_ancien=".$_POST["id_ancien"]
	. " AND ref_dossier=".$_POST["id_dossier"] ;
mysqli_query($cnx, $req) ;

$req = "DELETE FROM anciens WHERE id_ancien=".$_POST["id_ancien"] ;
mysqli_query($cnx, $req) ;

deconnecter($cnx) ;                                                              

$_SESSION["anciens"]["ok"] = "ok" ;
header("Location: /anciens/") ;
?>
