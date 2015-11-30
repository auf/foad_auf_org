<?php

$erreurs = "" ;

// Champs obligatoires
if ( $T["promotion"] == "0" ) {
	$erreurs .= obligatoire("promotion") ;
}
if ( trim($T["civilite"]) == "" ) {
	$erreurs .= obligatoire("civilite") ;
}
if ( trim($T["nom"]) == "" ) {
	$erreurs .= obligatoire("nom") ;
}
if ( ( $T["civilite"] == "Madame" ) && ( trim($T["nom_jf"]) == "" ) ) {
	$erreurs .= obligatoire("nom_jf") ;
}
if ( ( $T["civilite"] != "Madame" ) && ( trim($T["nom_jf"]) != "" ) ) {
	$erreurs .= "<li>Le nom de jeune fille ne doit être renseigné que pour les femmes mariées.</li>\n" ;
}
if ( trim($T["prenom"]) == "" ) {
	$erreurs .= obligatoire("prenom") ;
}
if ( 
	( ($T["jour_n"]=="") OR ($T["mois_n"]=="") OR ($T["annee_n"]=="") )
	AND ( ($T["jour_n"]!="") OR ($T["mois_n"]!="") OR ($T["annee_n"]!="") )
	)
{
	 $erreurs .= "<li>La date de naissance est incomplète.</li>\n" ;
}

$erreurDoublon = FALSE ;
if ( (trim($T["nom"]) != "") AND ($T["promotion"] != "0") AND ($T["confirmation"] != "confirmation") )
{
	// Controle de doublon sur le nom des candidats de la même promotion
	$nom = mysqli_real_escape_string($cnx, remets_guillemets(trim($T["nom"]))) ;
	$courriel = mysqli_real_escape_string($cnx, remets_guillemets(trim($T["courriel"]))) ;
	
	$req = "SELECT *
		FROM candidat, dossier
		WHERE dossier.id_candidat=candidat.id_candidat
		AND dossier.id_session=".$T["promotion"]." AND (
		candidat.nom LIKE '%".$nom."%'
		OR candidat.nom_jf LIKE '%".$nom."%'" ;
	if ( trim($T["courriel"]) != "" ) {
		$req .= "OR email1='$courriel'" ;
	}
	$req .= ")" ;
	$res = mysqli_query($cnx, $req) ;
	if ( mysqli_num_rows($res) != 0 ) {
		$erreurDoublon = TRUE ;
		$erreurs .= "<li>Un candidat existant dans cette promotion a un nom similaire ou un courriel identique&nbsp;:" ;
		$erreurs .= "<ul>" ;
		while ( $enr = mysqli_fetch_assoc($res) ) {
			$erreurs .= "<li>" ;
			$erreurs .= "<a target='_blank' href='/candidatures/autre.php?id_dossier=".$enr["id_dossier"]."'>" ;
			$erreurs .= $enr["civilite"] ;
			$erreurs .= " <strong>".strtoupper($enr["nom"])."</strong> " ;
			if ( $enr["civilite"] == "Madame" ) {
				$erreurs .= " <span class='petit'>née</span> " ;
				$erreurs .=  "<strong>".strtoupper($T["nom_jf"])."</strong> " ;
			}
			$erreurs .= $enr["prenom"] ;
			$erreurs .= "</a>" ;
			$erreurs .= "</li>" ;
		}
		$erreurs .= "</ul>" ;
		$erreurs .= "Vérifier que le candidat à ajouter est différent.<br />" ;
		$erreurs .= "Si c'est bien le cas, cocher la case
	
	«Confirmer l'ajout de cette nouvelle candidature »
	
	" ;
		$erreurs .= "</li>\n" ;
	}
}


if ( $erreurs != "" ) {
	$erreurs = "<ul class='erreur'>\n" . $erreurs . "</ul>\n" ;
}

?>
