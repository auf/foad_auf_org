<?php
$id_pj = $_GET["id_pj"] ;
$ref_dossier = $_GET["ref_dossier"] ;
$fichier = urldecode($_GET["fichier"]) ;

$extension4 = strtolower(substr($fichier, -4, 4)) ;
if ( $extension4 == ".jpg" ) {
	$ContentType = "image/jpeg" ;
}
else if ( $extension4 == ".png" ) {
	$ContentType = "image/png" ;
}
else if ( $extension4 == ".gif" ) {
	$ContentType = "image/gif" ;
}
else if ( $extension4 == ".bmp" ) {
	$ContentType = "image/bmp" ;
}
if ( $extension4 == ".pdf" ) {
	$ContentType = "application/pdf" ;
}
else if ( $extension4 == ".rtf" ) {
	$ContentType = "application/rtf" ;
}
else if ( $extension4 == ".doc" ) {
	$ContentType = "application/msword" ;
}
else if ( $extension4 == "docx" ) {
	$ContentType = "application/vnd.openxmlformats-officedocument.wordprocessingml.document" ;
}
else {
	$ContentType = "application/octet-stream" ;
}

include("inc_mysqli.php") ;
$cnx = connecter() ; 

$req = "SELECT * FROM pj
	WHERE id_pj=$id_pj
	AND ref_dossier=$ref_dossier
	AND fichier='".mysqli_real_escape_string($cnx, $fichier)."'" ;
$res = mysqli_query($cnx, $req) ;
if ( mysqli_num_rows($res) != 0 )
{
	$chemin = $_SERVER["DOCUMENT_ROOT"] . "/../pj/" . $ref_dossier . "/" . $fichier ;
	header("Content-disposition: filename=$fichier") ;
	header("Content-type: $ContentType") ;
	@readfile($chemin) ;
}
else
{
	echo "Accès interdit" ;
}
deconnecter($cnx) ;
?>
