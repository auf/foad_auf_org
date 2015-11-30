<?php
include("inc_session.php") ;
if ( intval($_SESSION["id"]) > 9 ) {
	header("Location: /bienvenue.php") ;
	exit() ;
}

if ( !isset($_GET["id_message"]) ) {
	header("Location: /messagerie_cnf/") ;
	exit() ;
}

require_once("inc_guillemets.php") ;
require_once("inc_fil.php") ;
require_once("inc_mysqli.php") ;
$cnx = connecter() ;

$req = "SELECT * FROM messages WHERE id_message=".$_GET["id_message"] ;
$res = mysqli_query($cnx, $req) ;
if ( mysqli_num_rows($res) != 1 ) {
	header("Location: /messagerie_cnf/") ;
	deconnecter($cnx) ;
	exit() ;
}
$message = mysqli_fetch_assoc($res) ;

$fil = fil_tab($cnx, $message["ref_fil"]) ;

require_once("inc_html.php") ;

$script = $htmlJquery172 . '
<script language="javascript">
//<![CDATA[ 
$(window).load(function(){
$("input[type=\'checkbox\']").change(function(){
	if($(this).is(":checked")){
		$(this).parent().parent().addClass("aex"); 
	}else{
		$(this).parent().parent().removeClass("aex");  
	}
});
});//]]>
</script>
' ;

$titre = $message["subject"] . " - " . $fil["annee"] . " - " . $fil["titre"] ;
$haut_page_1 = $dtd1 . "<title>$titre</title>\n" . $script .$dtd2 ;
$haut_page_2 = $debut_chemin
	. "<a href='/bienvenue.php'>Accueil</a>"
	. " <span class='arr'>&rarr;</span> "
	. "<a href='/messagerie_cnf/'>Messagerie <span>(CNF)</span></a>"
	. " <span class='arr'>&rarr;</span> "
	. "<a href='/messagerie_cnf/fil.php?id_fil=".$message["ref_fil"]."'>"
	. $fil["annee"] . " - " . $fil["titre"]
	. "</a>"
	. " <span class='arr'>&rarr;</span> " ;
$haut_page_2_sans = $haut_page_2
	. $message["subject"]
	. $fin_chemin ;
$haut_page_2_lien = $haut_page_2
	. "<a href='/messagerie_cnf/message.php?id_message=".$_GET["id_message"]."'>"
	. $message["subject"] . "</a>"
	. $fin_chemin ;

$url_message = "/messagerie_cnf/message.php?id_message=".$_GET["id_message"] ;

require_once("inc_message.php") ;

if ( (intval($_SESSION["id"]) < 2) AND ($_GET["action"] == "change") )
{
	if ( isset($_POST["submit"]) )
	{
		$req = "UPDATE messages SET
			commentaire='".mysqli_real_escape_string($cnx, trim($_POST["commentaire"]))."',
			secret='".$_POST["secret"]."'
			WHERE id_message=".$_GET["id_message"] ;
		$res = mysqli_query($cnx, $req) ;

		$req = "DELETE FROM messages_sessions WHERE ref_message=".$_GET["id_message"] ;
		//echo "$req <br />" ;
		$res = mysqli_query($cnx, $req) ;

		if ( count($_POST["promos"]) != 0 ) {
			$req = "INSERT INTO messages_sessions(ref_message, ref_session) VALUES" ;
			foreach ($_POST["promos"] as $promo) {
				$req .= "(".$_GET["id_message"].", ".$promo.")," ;
			}
			$req = substr($req, 0, -1) ;
			$res = mysqli_query($cnx, $req) ;
			//echo "$req <br />" ;
		}
		header("Location: $url_message") ;
	}
	else
	{
		echo $haut_page_1 ;
		include("inc_menu.php") ;
		echo $haut_page_2_lien ;
		$req = "SELECT ref_session FROM messages_sessions WHERE ref_message=".$_GET["id_message"] ;
		$res = mysqli_query($cnx, $req) ;
		$message["promos"] = array() ;
		while ( $enr = mysqli_fetch_assoc($res) ) {
			$message["promos"][] = $enr["ref_session"] ;
		}
		formulaire_message($cnx, $fil, $message) ;
		echo $end ;
	}
}
else
{
	echo $haut_page_1 ;
	include("inc_menu.php") ;
	echo $haut_page_2_sans ;
	if	( (intval($_SESSION["id"]) == 2) AND (strval($message["secret"])=="1") )
	{
		// Le cas est dÃ©jÃ  traitÃ© dans la fonction
		//echo "<p class='c'>L'archive de ce message n'est pas consultable par les CNF.</p>\n" ;
		affiche_message($cnx, $message) ;
	}
	else
	{
		affiche_message($cnx, $message) ;
	}
	echo $end ;
}
//diagnostic() ;
deconnecter($cnx) ;
/* "NgaoundÃ©rÃ©", */
?>
