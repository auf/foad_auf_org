<?php
include_once("inc_session.php") ;
if ( $_SESSION["id"] != "00" ) {
	header("Location: /recherche/") ;
	exit ;
}
if ( isset($_GET["id_dossier"]) AND is_numeric($_GET["id_dossier"]) ) {
	$id_dossier = $_GET["id_dossier"] ;
}
else if ( isset($_POST["id_dossier"]) AND is_numeric($_POST["id_dossier"]) ) {
	$id_dossier = $_POST["id_dossier"] ;
}
else {
	header("Location: /recherche/") ;
	exit ;
}



$titre = "Rappel de mot de passe OU modification d'adresse électronique" ;
include("inc_html.php");
echo $dtd1 ;
echo "<title>$titre</title>" ;
echo $dtd2 ;
include("inc_menu.php");
echo $debut_chemin ;
echo "<a href='/bienvenue.php'>Accueil</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo "<a href='/recherche/'>Recherche</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo $titre ;
echo $fin_chemin ;

/*
echo "<pre>" ;
print_r($_SESSION["filtres"]["recherche"]) ;
echo "</pre>" ;
*/

include("inc_mysqli.php");
$cnx = connecter() ;



$req = "SELECT * FROM candidat, dossier
	WHERE dossier.id_dossier=$id_dossier
	AND dossier.id_candidat=candidat.id_candidat" ;
$res = mysqli_query($cnx, $req) ;
$T = mysqli_fetch_assoc($res) ;

$civilite = $T["civilite"] ;
$nom = $T["nom"] ;
$prenom = $T["prenom"] ;
$pwd = $T["pwd"] ;
$id_candidat = $T["id_candidat"] ;



if ( isset($_POST["action"]) AND ($_POST["action"] == "modif") )
{
	$req = "UPDATE candidat SET email1='".trim($_POST["email1"])."' WHERE
		id_candidat=$id_candidat" ;
	mysqli_query($cnx, $req) ;

	$T["email1"] = $_POST["email1"] ;
}


$req = "SELECT intitule, intit_ses, universite FROM atelier, session
    WHERE atelier.id_atelier=session.id_atelier
    AND session.id_session=".$T["id_session"] ;
$res = mysqli_query($cnx, $req) ;
$enr = mysqli_fetch_assoc($res) ;

$intitule = $enr["intitule"] ;
$intit_ses = $enr["intit_ses"] ;
$universite = $enr["universite"] ;


$message = "Bonjour $civilite $nom $prenom,

Le numéro de dossier et le mot de passe de votre candidature pour :
$universite
$intitule ($intit_ses)
sur le site https://" . URL_DOMAINE . "/candidature/ sont :

Numéro de dossier : $id_dossier
Mot de passe      : $pwd

Pensez à modifier votre adresse électronique dans votre candidature
si elle n'est pas fiable !

Cordialement,

Agence universitaire de la Francophonie
http://" . URL_DOMAINE_PUBLIC . "
" ;

$sujet = "Numéro et mot de passe de votre dossier" ;

$debut_email = "<table class='formulaire'>
<tr>
<th>Expéditeur :</th>
<td>".EMAIL_FROMNAME." &lt;".EMAIL_FROM."&gt;</td>
</tr>
<tr>
<th>Destinataire :</th>\n" ;

$adresse = "<td><input type='text' name='email1' size='50' value='".$T['email1']."' /></td>" ;
$adresse_envoyee = "<td><strong>"
	. ( isset($_POST["email1"]) ? $_POST["email1"] : "" )
	. "</strong></td>" ;

$suite1_email = "</tr>
<tr>
<th>Sujet :</th>
<td>$sujet</td>
</tr>
<tr>
<th>Message :</th>
<td>".nl2br($message)."</td>
</tr>\n" ;

$suite2_email = "<tr><td colspan='2' class='c'>
<p class='c'><strong><input type='submit' value='Envoyer ce courriel' /></strong></p>
</td></tr>\n" ;

$fin_email = "</table>" ;





// Envoi ou modification du courriel
if ( isset($_POST["action"]) AND ($_POST["action"] == "email") )
{
	require_once("inc_aufPhpmailer.php") ;
	$mail = new aufPhpmailer() ;
	$mail->From = EMAIL_FROM ;
	$mail->FromName = EMAIL_FROMNAME ;
	$mail->AddReplyTo(EMAIL_REPLYTO, "") ;
	$mail->Sender = EMAIL_SENDER ;
	$mail->Subject = $sujet ;
	$mail->Body = $message ;
	$mail->AddAddress($_POST["email1"]) ;
	if ( $mail->Send() )
	{
		echo "<p class='msgok c'>Courriel envoyé :</p>" ;
		echo $debut_email ;
		echo $adresse_envoyee ;
		echo $suite1_email ;
		echo $fin_email ;
	}
	else {
		echo "<p class='erreur'>Echec de l'envoi du courriel</p>" ;
	}
}
else
{
	if ( isset($_POST["action"]) AND ($_POST["action"] == "modif") )
	{
		echo "<p class='msgok c'>Adresse modifiée.</p>" ;
	}
	// Envoi de courriel
	echo "<div style='float: left'>\n" ;
		echo "<form method='post' action='email.php'>
		<input type='hidden' name='id_dossier' value='$id_dossier' />\n" ;
		echo "<input type='hidden' name='action' value='email' />\n" ;
		echo $debut_email ;
		echo $adresse ;
		echo $suite1_email ;
		echo $suite2_email ;
		echo $fin_email ;
		echo "</form>\n" ;
	echo "</div>\n" ;

	// Modification adresse
	echo "<div>\n" ;
		echo "<form method='post' action='email.php?id_dossier=".$id_dossier."'>\n" ;
		echo "<table class='formulaire'>\n" ;
		echo "<input type='hidden' name='id_dossier' value='$id_dossier' />\n" ;
		echo "<input type='hidden' name='action' value='modif' />\n" ;
		echo "<tr><th>Remplacer :</th><td>".$T['email1']."</td></tr>\n" ;
		echo "<tr><th>Par :</th><td><input type='text' size='50' name='email1' /></td></tr>\n" ;
		echo "<tr><td colspan='2' class='c'><p class='c'><strong><input type='submit' value='Enregistrer' /></strong></p></td></tr>\n" ;
		echo "</table>\n" ;
		echo "</form>\n" ;
	echo "</div>\n" ;

	echo "<div style='clear: both;'></div>\n" ;

	echo "<h2>$intitule <span class='normal'>($intit_ses)</span></h2>\n" ;
	
	include("inc_formulaire_candidature.php") ;
	include("inc_date.php");
	include("inc_etat_dossier.php") ;
	include("inc_dossier.php");
	affiche_dossier($T, $_SESSION, $cnx, FALSE, TRUE) ;

}


echo $end ;
deconnecter($cnx) ;
?>
