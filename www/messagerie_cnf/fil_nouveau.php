<?php
include("inc_session.php") ;
if ( intval($_SESSION["id"]) > 1 ) {
	header("Location: /bienvenue.php") ;
	exit() ;
}

require_once("inc_html.php") ;
$titre = "Nouveau fil de messages" ;
$haut_page_1 = $dtd1 . "<title>$titre</title>\n" . $dtd2 ;
$haut_page_2 = $debut_chemin
	. "<a href='/bienvenue.php'>Accueil</a>"
	. " <span class='arr'>&rarr;</span> "
	. "<a href='/messagerie_cnf/index.php'>Messagerie <span>(CNF)</span></a>"
	. " <span class='arr'>&rarr;</span> "
	. $titre
	. $fin_chemin ;

require_once("inc_guillemets.php") ;
require_once("inc_institutions.php") ;
require_once("inc_fil.php") ;
require_once("inc_mysqli.php") ;
$cnx = connecter() ;

if ( !isset($_POST["submit"]) )
{
	echo $haut_page_1 ;
	include("inc_menu.php") ;
	echo $haut_page_2 ;
	formulaire_fil_nouveau($cnx, array()) ;
	echo $end ;
}
else
{
	$T = array() ;
	while ( list($key, $val) = each($_POST) ) {
		$T[$key] = trim($val) ;
	}

	$erreurs = controle_fil_nouveau($T) ;
	
	if ( $erreurs != "" )
	{
		echo $haut_page_1 ;
		include("inc_menu.php") ;
		echo $haut_page_2 ;
		echo $erreurs ;
		formulaire_fil_nouveau($cnx, $T) ;
		echo $end ;
	}
	else
	{
		if ( $T["titre"] == "" )
		{
			$req = "SELECT institution FROM institutions
				WHERE id_institution=".$T["ref_institution"] ;
			$res = mysqli_query($cnx, $req) ;
			if ( mysqli_num_rows($res) != 1 )
			{
				echo $haut_page_1 ;
				include("inc_menu.php") ;
				echo $haut_page_2 ;
				echo "<p class='erreur'>Erreur : titre !</p>\n" ;
				formulaire_fil_nouveau($cnx, $T) ;
				echo $end ;
			}
			else
			{
				$enr = mysqli_fetch_assoc($res) ;
				$T["titre"] = $enr["institution"] ;
			}
		}

		$req = "INSERT INTO fils
			(ref_institution, titre, annee, commentaire)
			VALUES(".$T["ref_institution"].",
			'".mysqli_real_escape_string($cnx, $T["titre"])."',
			'".mysqli_real_escape_string($cnx, $T["annee"])."',
			'".mysqli_real_escape_string($cnx, $T["commentaire"])."')" ;
		$res = mysqli_query($cnx, $req) ;
		$req = "SELECT LAST_INSERT_ID() AS N" ;
		$res = mysqli_query($cnx, $req) ;
		$enr = mysqli_fetch_assoc($res) ;
		$id_fil = $enr["N"] ;

		fil_sessions($cnx, $id_fil, $T["ref_institution"], $T["annee"]) ;

		header("Location: /messagerie_cnf/fil.php?id_fil=".$id_fil) ;
	}
}

deconnecter($cnx) ;
?>
