<?php

function formulaireIdentification($tab)
{
	$form  = "" ;
	$form .= "<form class='form_annuaire' method='post' action=''>\n" ;
	$form .= "<p>" ;
	$form .= "<label for='courriel'>Courrier électronique</label>" ;
	$form .= "<input type='text' size='50' id='courriel' name='courriel' />" ;
	$form .= "</p>\n" ;
	$form .= "<p>" ;
	$form .= "<label for='mdp'>Mot de passe</label>" ;
	$form .= "<input type='text' size='20' id='mdp' name='mdp' />" ;
	$form .= "</p>\n" ;
	$form .= "<p class='c'>" ;
	$form .= "<input type='submit' value='OK' />" ;
	$form .= "</p>\n" ;
	$form .= "</form>\n\n" ;
	return $form ;
}





require_once("inc_db.php");

$cnx = connecter() ;

$membreTitre = "Accès membre" ;

$membrePage  = "" ;
$membrePage .= formulaireIdentification($tab) ;

deconnecter($cnx) ;
?>
