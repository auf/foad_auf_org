<?php
require_once("inc_session.php") ;
if ( intval($_SESSION["id"]) > 0 ) {
	header("Location: /") ;
}
require_once("inc_mysqli.php");
$cnx = connecter() ;

$req = "SELECT annee, ref_dossier
	FROM session, dossier_anciens
	WHERE ref_session=id_session
	AND anneed=0
	ORDER BY ref_dossier" ;
$res = mysqli_query($cnx, $req) ;
while ( $enr = mysqli_fetch_assoc($res) ) {
	$anneed = intval($enr["annee"]) + 1 ;
	$ref_dossier = $enr["ref_dossier"] ;
	$req = "UPDATE dossier_anciens SET anneed=$anneed WHERE ref_dossier=$ref_dossier" ;
	echo $req . "<br />" ;
	mysqli_query($cnx, $req) ;
}

?>
