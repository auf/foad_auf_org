<?php
include("inc_session.php") ;
if ( intval($_SESSION["id"]) > 9 ) {
	header("Location: /bienvenue.php") ;
	exit() ;
}

if ( !isset($_GET["id_fil"]) ) {
	header("Location: /messagerie_cnf/") ;
	exit() ;
}

require_once("inc_guillemets.php") ;
require_once("inc_date.php") ;
require_once("inc_fil.php") ;
require_once("inc_mysqli.php") ;
$cnx = connecter() ;

$fil = fil_tab($cnx, $_GET["id_fil"]) ;
if ( empty($fil) ) {
	header("Location: /messagerie_cnf/") ;
	deconnecter($cnx) ;
	exit() ;
}

require_once("inc_html.php") ;
$titre = $fil["annee"] . " - " . $fil["titre"] ;
$haut_page_1 = $dtd1 . "<title>$titre</title>\n" . $dtd2 ;
$haut_page_2 = $debut_chemin
	. "<a href='/bienvenue.php'>Accueil</a>"
	. " <span class='arr'>&rarr;</span> "
	. "<a href='/messagerie_cnf/index.php'>Messagerie <span>(CNF)</span></a>"
	. " <span class='arr'>&rarr;</span> " ;
if ( trim($fil["commentaire"]) != "" ) {
	$commentaire = "<span> - " . $fil["commentaire"] . "</span>" ;
}
else {
	$commentaire = "" ;
}
$haut_page_2_lien = $haut_page_2
	. "<a href='/messagerie_cnf/fil.php?id_fil=".$_GET["id_fil"]."'>" . $titre . $commentaire . "</a>"
	. $fin_chemin ;
$haut_page_2_sans = $haut_page_2
	. $titre . $commentaire
	. $fin_chemin ;


$url_fil = "/messagerie_cnf/fil.php?id_fil=".$_GET["id_fil"] ;

if ( isset($_GET["action"]) )
{
	if ( $_GET["action"] == "fil" )
	{
		if ( !isset($_POST["submit"]) )
		{
			echo $haut_page_1 ;
			include("inc_menu.php") ;
			echo $haut_page_2_lien ;
			formulaire_fil_fil($cnx, $fil) ;
			echo $end ;
		}
		else
		{
			// FIXME changement promotions
			$req = "UPDATE fils SET
				annee='".$_POST["annee"]."',
				ref_institution='".$_POST["ref_institution"]."'
				WHERE id_fil=".$_GET["id_fil"] ;
			$res = mysqli_query($cnx, $req) ;
			header("Location: " . $url_fil . "#ancre_fil") ;

			if	(
					( $_POST["annee"] != $fil["annee"] )
					OR ( $_POST["ref_institution"] != $fil["ref_institution"] )
				)
			{
				fil_sessions($cnx, $_GET["id_fil"], $_POST["ref_institution"], $_POST["annee"]) ;
			}
		}
	}
	//
	else if ( $_GET["action"] == "meta" )
	{
		if ( !isset($_POST["submit"]) )
		{
			echo $haut_page_1 ;
			include("inc_menu.php") ;
			echo $haut_page_2_lien ;
			formulaire_fil_meta($fil) ;
			echo $end ;
		}
		else
		{
			$erreurs = controle_fil_meta($_POST) ;
			if ( $erreurs != "" )
			{
				echo $haut_page_1 ;
				include("inc_menu.php") ;
				echo $haut_page_2_lien ;
				echo $erreurs ;
				formulaire_fil_meta($fil) ;
				echo $end ;
			}
			else
			{
				$req = "UPDATE fils SET
					titre='".mysqli_real_escape_string($cnx, trim($_POST["titre"]))."',
					commentaire='".mysqli_real_escape_string($cnx, trim($_POST["commentaire"]))."'
					WHERE id_fil=".$_GET["id_fil"] ;
				$res = mysqli_query($cnx, $req) ;
				header("Location: " . $url_fil . "#ancre_meta") ;
			}
		}
	}
	//
	else if ( $_GET["action"] == "sessions" )
	{
		if ( !isset($_POST["submit"]) )
		{
			echo $haut_page_1 ;
			include("inc_menu.php") ;
			echo $haut_page_2_lien ;
			formulaire_fil_sessions($cnx, $fil) ;
			echo $end ;
		}
		else
		{
			$req = "DELETE FROM fils_sessions WHERE ref_fil=".$_GET["id_fil"] ;
			$res = mysqli_query($cnx, $req) ;
			if ( count($_POST["promos"]) != 0 ) {
				$req = "INSERT INTO fils_sessions(ref_fil, ref_session) VALUES" ;
				foreach ($_POST["promos"] as $promo) {
					$req .= "(".$_GET["id_fil"].", ".$promo.")," ;
				}
				$req = substr($req, 0, -1) ;
				$res = mysqli_query($cnx, $req) ;
				//echo "$req <br />" ;
			} 
			header("Location: " . $url_fil . "#ancre_sessions") ;
		}
	}
	//
	else if ( $_GET["action"] == "individus" )
	{
		if ( !isset($_POST["submit"]) )
		{
			echo $haut_page_1 ;
			include("inc_menu.php") ;
			echo $haut_page_2_lien ;
			formulaire_fil_individus($cnx, $fil) ;
			echo $end ;
		}
		else
		{
			$req = "DELETE FROM fils_individus WHERE ref_fil=".$_GET["id_fil"] ;
			$res = mysqli_query($cnx, $req) ;
			if ( count($_POST["destin"]) != 0 ) {
				$req = "INSERT INTO fils_individus(ref_fil, ref_individu) VALUES" ;
				foreach ($_POST["destin"] as $promo) {
					$req .= "(".$_GET["id_fil"].", ".$promo.")," ;
				}
				$req = substr($req, 0, -1) ;
				$res = mysqli_query($cnx, $req) ;
				//echo "$req <br />" ;
			} 
			header("Location: " . $url_fil . "#ancre_individus") ;
		}
	}
}
else
{
	echo $haut_page_1 ;
	include("inc_menu.php") ;
	echo $haut_page_2_sans ;

	if ( intval($_SESSION["id"]) < 2 ) {
		echo "<p class='c'><strong><a href='message_nouveau.php?id_fil=".$_GET["id_fil"]."'>Nouveau message</a></strong></p>\n" ;
	}

	$req = "SELECT messages.*,
		(SELECT COUNT(ref_session) FROM messages_sessions WHERE ref_message=id_message)
		AS nb_sessions,
		(SELECT COUNT(ref_individu) FROM messages_individus WHERE ref_message=id_message)
		AS nb_individus
		FROM messages WHERE ref_fil='".$_GET["id_fil"]."'
		ORDER BY `date` DESC, id_message DESC" ;
	$res = mysqli_query($cnx, $req) ;
	$nb_messages = mysqli_num_rows($res) ;
	if ( $nb_messages == 0 ) {
		echo "<p class='c'>Aucun message envoyé</p>\n" ;
	}
	else {
		echo "<table class='tableau'>\n" ;
		echo "<thead>\n" ;
		echo "<tr>\n" ;
		echo "<th>Date</th>\n" ;
		echo "<th>Expéditeur</th>\n" ;
		echo "<th>Sujet<br /><span class='normal'>Commentaire</span></th>\n" ;
		echo "<th>Archive<br />consultable<br />par les CNF</th>\n" ;
		echo "<th>Promotions</th>\n" ;
		echo "<th>Destinataires</th>\n" ;
		echo "</tr>\n" ;
		echo "</thead>\n" ;
		echo "<tbody>\n" ;
		while ( $enr = mysqli_fetch_assoc($res) ) {
			echo "<tr>\n" ;
			echo "<td>".mysql2datenum($enr["date"])."</td>\n" ;
			echo "<td class='c'>".$enr["from"]."</td>\n" ;
			echo "<td><strong><a href='message.php?id_message=" ;
			echo $enr["id_message"]."'>".$enr["subject"]."</a></strong><br />" ;
			echo $enr["commentaire"]."</td>\n" ;
			if ( strval($enr["secret"]) == "0" ) {
				echo "<td class='c'>oui</td>\n" ;
			}
			else {
				echo "<td class='c Non'>non</td>\n" ;
			}
			echo "<td class='r'>".$enr["nb_sessions"]."</td>\n" ;
			echo "<td class='r'>".$enr["nb_individus"]."</td>\n" ;
			echo "</tr>\n" ;
		}
		echo "</tbody>\n" ;
		echo "</table>\n" ;
	}

	echo "<br />" ;
	echo "<br />" ;
//	echo "<p class='c'>Caractéristiques de ce fil de messages :</p>" ;

	if ( intval($_SESSION["id"]) < 2 ) {
		affiche_fil($fil) ;

/*
		if ( $nb_messages == 0 )
		{
			echo "<br />" ;
			echo "<br />" ;
			echo "<p class='c'><a href='".$url_fil."&action=delete'>Supprimer</a> ce fil de messages</p>" ;
		}
*/


	}
	/*
	echo "<pre>" ;
	print_r($fil) ;
	echo "</pre>" ;
	*/
//	diagnostic() ;
	echo $end ;
}
deconnecter($cnx) ;
?>
