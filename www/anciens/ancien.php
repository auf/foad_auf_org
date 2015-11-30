<?php
require_once("inc_session.php") ;
if ( !isset($_GET["id_ancien"]) ) {
	header("Location: /anciens/") ;
	exit ;
}
require_once("inc_mysqli.php");
$cnx = connecter() ;
require_once("inc_anciens.php") ;

$req = "SELECT * FROM anciens WHERE id_ancien=".$_GET["id_ancien"] ;
$res = mysqli_query($cnx, $req) ;
$T = mysqli_fetch_assoc($res) ;

$identite = identite($T) ;


$titre = strip_tags($identite) ;
require_once("inc_html.php");
echo $dtd1 ;
echo "<title>Ancien : $titre</title>" ;
echo $dtd2 ;
require_once("inc_menu.php");
echo $debut_chemin ;
echo "<a href='/bienvenue.php'>Accueil</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo "<a href='/anciens'>Anciens</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo $titre ;
echo $fin_chemin ;


afficheAncien($_GET["id_ancien"], $cnx, FALSE, TRUE, TRUE, TRUE) ;


deconnecter($cnx) ;
echo $end ;
?>
