<?php
require_once("inc_etat_dossier.php") ;
require_once("inc_dossier.php") ;
require_once("inc_date.php") ;

function  identite($T)
{
	$identite = "" ;
	$identite .= $T["civilite"] ;
	$identite .= " <span class='nom'>" ;
	$identite .= strtoupper($T["nom"]) ;
	$identite .= "</span>" ;
	if ( $T["civilite"] == "Madame" ) {
		$identite .= " <span class='petit'>née</span> " ;
		$identite .=  "<span class='nom'>".strtoupper($T["nom_jf"])."</span>" ;
	}
	$identite .= " <span class='prenom'>" ;
	$identite .= ucwords(strtolower($T["prenom"])) ;
	$identite .= "</span>" ;
	return $identite ;
}
?>
