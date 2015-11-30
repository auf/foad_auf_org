<?php
require_once("inc_config.php") ;
include_once("inc_html.php");

$titre = "Mise à jour d'un dossier de candidature" ;

if ( SITE_EN_LECTURE_SEULE )
{
    echo $dtd1
        . "<title>".$titre."</title>"
        . $dtd2
        . "<div style='padding: 0.5em; '>\n"
        . $logoAufFoad
        . "<h1 class='noprint'>".$titre."</h1>\n"
        . "<h2><p class='c erreur'>" . EN_MAINTENANCE . "</p></h2>\n"
        . $end ;
}
else
{
	echo $dtd1
		. "<title>$titre</title>\n"
		. $dtd2
		. "<div style='margin: 0.5em'>\n"
		. $logoAufFoad
		. "<h1 class='accueil'>$titre</h1>\n" ;
	
	if ( $_GET["erreur"] == "1" ) {
		echo "<p class='c erreur' style='font-size: larger;'>Erreur</p>\n" ;
		echo "<p class='c'>Majuscules et minuscules sont significatives dans le champ «&nbsp;Mot de passe&nbsp;».<br />
	Attention aussi à ne pas confondre «&nbsp;0&nbsp;» (zéro) et «&nbsp;O&nbsp;» (o majuscule), ...</p>" ;
	}
	
	?>
	<form action="candidature.php" method="post">
	<input type="hidden" name="formulaire" value="maj" />
	<table class='formulaire'>
	<tr>
		<th>Numéro de dossier :</th>
		<td><input type='text' name='id_dossier' value=''/></td>
	</tr><tr>
		<th>Mot de passe :</th>
		<td><input type='password' name='pwd' /></td>
	</tr>
	</table>
	<p class='c'><input type="submit" value="Entrer"></p>
	</form>
	
	<? 
	echo $end ;
}
?>
