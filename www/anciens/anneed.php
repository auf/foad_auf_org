<?php
require_once("inc_session.php") ;
if ( intval($_SESSION["id"]) > 0 ) {
	header("Location: /") ;
}
require_once("inc_mysqli.php");
$cnx = connecter() ;

$req = "UPDATE dossier_anciens
	SET anneed=".$_POST["anneed"]."
	WHERE ref_ancien=".$_POST["id_ancien"]."
	AND ref_dossier=".$_POST["id_dossier"] ;
mysqli_query($cnx, $req) ;

deconnecter($cnx) ;
header("Location: /anciens/ancien.php?id_ancien=".$_POST["id_ancien"]) ;
?>
