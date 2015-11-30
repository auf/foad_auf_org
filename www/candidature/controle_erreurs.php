<?php

$erreurs = FALSE ;

$message_doublon = "" ;
if ( $erreur_doublon ) 
{
	$erreurs = TRUE ;
	$message_doublon = "<p class='erreur'>"
		. "Vous avez déjà déposé un dossier de candidature pour cette formation.<br />"
		. "Vous pouvez mettre à jour votre dossier en utilisant votre numéro "
		. "de dossier et le mot de passe qui vous ont été envoyés par courrier électronique.</p>\n"
		. "<p class='erreur'>Si vous avez perdu votre code confidentiel, "
		. "envoyez un courriel à <a href='mailto:".EMAIL_CONTACT."'>".EMAIL_CONTACT."</a> en précisant vos nom et prénom, la formation, et en utilisant la même adresse électronique que celle que vous avez indiquée pour vous contacter (champ «&nbsp;Courrier électronique 1&nbsp;»).</p>\n" ;
}

if (
	( $erreur_saisie1 != "" ) OR
	( $erreur_saisie2 != "" ) OR
	( $erreur_saisie3 != "" ) OR
	( $erreur_saisie4 != "" ) OR
	( $erreur_saisie5 != "" ) OR
	( $erreur_saisie6 != "" ) OR
	( $erreur_saisie7 != "" ) OR
	( $erreur_saisie8 != "" ) OR
	( $erreur_signature != "" ) OR
	( $erreur_questions != "" )
	)
{
	$erreurs = TRUE ;
} 



if ( $erreurs )
{
	$message_erreur = "<p class='erreur'>"
		. "Votre candidature ne peut pas être enregistrée car elle est "
		. "incomplète, ou contient une ou plusieurs erreurs "
		. "détaillées ci-dessous, dans chaque section du formulaire.</p>\n" ;

	$message_erreur .= $message_doublon ;
}

?>
