<?php

//
function imputation_nombre($cnx, $id_dossier, $annee_relative=1)
{
	$req = "SELECT COUNT(id_imputation) AS N FROM imputations
		WHERE ref_dossier=" . $id_dossier . "
		AND annee_relative=" . $annee_relative ;
	$res = mysqli_query($cnx, $req) ;
	$enr = mysqli_fetch_assoc($res) ;
	$N = intval($enr["N"]) ;
	return $N ;
}

// Recupere les donnees du dossier a imputer dans un tableau
function imputation_dossier($cnx, $id_dossier, $annee_relative=1)
{
	// Le tableau à retourner, y compris  avec les erreurs.
	$imputation = array() ;

	// Deux valeurs possibles seulement
	if ( $annee_relative == 2 ) {
		$annee_relative = 2 ;
	}
	else {
		$annee_relative = 1 ;
	}

	$N = imputation_nombre($cnx, $id_dossier, $annee_relative) ;
	if ( $N > 0 ) {
		$imputation["erreur"] = "<p class='erreur'>Déjà imputé</p>" ;
	}

	$req = "SELECT intitule, universite, nb_annees,
		intit_ses, annee, session.imputation, tarifa, tarifp, 
		imputation2, tarif2a, tarif2p,
		imputations, imputations2,
		dossier.id_session, etat_dossier,
		candidat.id_candidat,
		civilite, nom, nom_jf, prenom, naissance, nationalite
		FROM dossier, candidat, session, atelier
		WHERE id_dossier=".$id_dossier."
		AND dossier.id_candidat=candidat.id_candidat
		AND dossier.id_session=session.id_session
		AND session.id_atelier=atelier.id_atelier" ;
	$res = mysqli_query($cnx, $req) ;
	if ( mysqli_num_rows($res) != 1 ) {
		$imputation["erreur"] = "<p class='erreur'>Numéro de dossier inexistant</p>" ;
		return $imputation ;
	}
	$enr = mysqli_fetch_assoc($res) ;

	// Numéro de dossier, et informations liées et non modifiables
	$imputation["ref_dossier"] = $id_dossier ;
	$imputation["intitule"] = $enr["intitule"] ;
	$imputation["annee"] = $enr["annee"] ;
	$imputation["nb_annees"] = $enr["nb_annees"] ;
	$imputation["universite"] = $enr["universite"] ;
	$imputation["intit_ses"] = $enr["intit_ses"] ;
	$imputation["tarifp"] = $enr["tarifp"] ;
	$imputation["tarifa"] = $enr["tarifa"] ;
	$imputation["code_imputation"] = $enr["imputation"] ;
	$imputation["tarif2p"] = $enr["tarif2p"] ;
	$imputation["tarif2a"] = $enr["tarif2a"] ;
	$imputation["code_imputation2"] = $enr["imputation2"] ;
	$imputation["etat"] = $enr["etat_dossier"] ;
	$imputation["id_candidat"] = $enr["id_candidat"] ;
	// Informations modifiables
	$imputation["civilite"] = $enr["civilite"] ;
	$imputation["nom"] = $enr["nom"] ;
	$imputation["nom_jf"] = $enr["nom_jf"] ;
	$imputation["prenom"] = $enr["prenom"] ;
//	$imputation["naissance"] = $enr["naissance"] ;
	$tab_naissance = explode("-", $enr["naissance"]) ;
	$imputation["annee_n"] = $tab_naissance[0] ;
	$imputation["mois_n"] = $tab_naissance[1] ;
	$imputation["jour_n"] = $tab_naissance[2] ;
	$imputation["nationalite"] = $enr["nationalite"] ;

	$imputation["annee_relative"] = $annee_relative ;
	$imputation["annee_absolue"] = intval($enr["annee"])
		+ intval($annee_relative) - 1 ;

	return $imputation ;
}

function imputation_imputation($cnx, $id_imputation)
{
	$imputation = array() ;

	$req = "SELECT imputations.*,
		intitule, nb_annees,
		intit_ses, universite, annee, tarifa, tarifp, tarif2a, tarif2p,
		session.imputation AS code_imputation,
		session.imputation2 AS code_imputation2,
		candidat.id_candidat,
		civilite, nom, prenom, nom_jf, naissance, nationalite
		FROM imputations, dossier, candidat, session, atelier
		WHERE id_imputation=".$id_imputation."
		AND ref_dossier=id_dossier
		AND dossier.id_candidat=candidat.id_candidat
		AND dossier.id_session=session.id_session
		AND session.id_atelier=atelier.id_atelier" ;
	$res = mysqli_query($cnx, $req) ;
	if ( mysqli_num_rows($res) != 1 ) {
		$imputation["erreur"] = "<p class='erreur'>Numéro d'imputation inexistant</p>" ;
		return $imputation ;
	}
	$enr = mysqli_fetch_assoc($res) ;

	// Numéro de dossier, et informations liées et non modifiables
	$imputation["id_imputation"] = $enr["id_imputation"] ;
	$imputation["ref_dossier"] = $enr["ref_dossier"] ;
	$imputation["intitule"] = $enr["intitule"] ;
	$imputation["annee"] = $enr["annee"] ;
	$imputation["nb_annees"] = $enr["nb_annees"] ;
	$imputation["intit_ses"] = $enr["intit_ses"] ;
	$imputation["universite"] = $enr["universite"] ;
	$imputation["code_imputation"] = $enr["code_imputation"] ;
	$imputation["code_imputation2"] = $enr["code_imputation2"] ;
	$imputation["etat"] = $enr["etat"] ;
	$imputation["annee"] = $enr["annee"] ;
	$imputation["tarifp"] = $enr["tarifp"] ;
	$imputation["tarifa"] = $enr["tarifa"] ;
	$imputation["tarif2p"] = $enr["tarif2p"] ;
	$imputation["tarif2a"] = $enr["tarif2a"] ;

	$imputation["id_candidat"] = $enr["id_candidat"] ;
	// Informations modifiables
	$imputation["civilite"] = $enr["civilite"] ;
	$imputation["nom"] = $enr["nom"] ;
	$imputation["nom_jf"] = $enr["nom_jf"] ;
	$imputation["prenom"] = $enr["prenom"] ;
	$tab_naissance = explode("-", $enr["naissance"]) ;
	$imputation["annee_n"] = $tab_naissance[0] ;
	$imputation["mois_n"] = $tab_naissance[1] ;
	$imputation["jour_n"] = $tab_naissance[2] ;
	$imputation["nationalite"] = $enr["nationalite"] ;

	// Paiement
	$imputation["annee_absolue"] = $enr["annee_absolue"] ;
	$imputation["annee_relative"] = $enr["annee_relative"] ;
	$imputation["lieu"] = $enr["lieu"] ;
	$imputation["montant"] = floatval($enr["montant"]) ;
	$imputation["monnaie"] = $enr["monnaie"] ;
	$imputation["montant_frais"] = floatval($enr["montant_frais"]) ;
	$imputation["monnaie_frais"] = $enr["monnaie_frais"] ;
	$imputation["particulier"] = $enr["particulier"] ;
	$imputation["commentaire"] = $enr["commentaire"] ;

	// Imputation (pour modification par Service des bourses (SCP) seulement
	$imputation["imputation"] = $enr["imputation"] ;

	return $imputation ;
}


function formate_erreurs($erreurs)
{
	$string = "" ;
	if ( count($erreurs) != 0 )
	{
		$string  = "<ul class='erreur'>\n" ;
		foreach($erreurs as $erreur) {
			$string .= "<li>$erreur</li>\n" ;
		}
		$string .= "</ul>\n" ;
	}
	return $string ;
}

function verif_imputation_1($enr)
{
	if ( $enr["nom"] == "" ) {
		$erreurs[] = "Le champ «&nbsp;Nom de famille&nbsp;» est obligatoire." ;
	}
	if ( ( $enr["civilite"] == "Madame" ) AND ( $enr["nom_jf"] == "" ) ) {
		$erreurs[] = "Le nom de jeune fille est obligatoire"
			." pour les femmes mariées." ;
	}
	if ( ( $enr["civilite"] != "Madame" ) AND ( $enr["nom_jf"] != "" ) ) {
		$erreurs[] = "Le nom de jeune fille ne doit être renseigné que "
			." pour les femmes mariées." ;
	}
	if ( $enr["prenom"] == "" ) {
		$erreurs[] = "Le champ «&nbsp;Prénoms&nbsp;» est obligatoire." ;
	}
	if (
		( $enr["annee_n"] == "" )
		OR ( $enr["mois_n"] == "" )
		OR ( $enr["jour_n"] == "" )
		)
	{
		$erreurs[] = "Erreur dans la date de naissance." ;
	}
	if ( $enr["nationalite"] == "" ) {
		$erreurs[] = "Le champ «&nbsp;Nationalité&nbsp;» est obligatoire." ;
	}
	
	return formate_erreurs($erreurs) ;
}

function verif_imputation_2($imputation)
{
	if ( $imputation["lieu"] == "" ) {
		$erreurs[] = "Le champ «&nbsp;Lieu d'enregistrement&nbsp;» "
			."est obligatoire." ;
	}

	//
	// Frais de dossier
	//
	if ( $imputation["montant_frais"] == "" ) {
		$erreurs[] = "Le champ «&nbsp;Montant acquitté (hors frais de dossier)&nbsp;» est obligatoire." ;
	}
	if (
		( $imputation["montant_frais"] != "" ) AND
		!is_numeric($imputation["montant_frais"]) 
		)
	{
		$erreurs[] = "Erreur dans le champ «&nbsp;Frais de dossier acquittés&nbsp;»." ;
	}
	if ( $imputation["monnaie_frais"] == "" ) {
		$erreurs[] = "Le choix d'une monnaie pour les «&nbsp;Frais de dossier acquittés&nbsp;» est obligatoire." ;
	}

	//
	// Montant hors frais de dossier
	//
	if ( $imputation["montant"] == "" ) {
		$erreurs[] = "Le champ «&nbsp;Montant acquitté (hors frais de dossier)&nbsp;» est obligatoire." ;
	}
	if (
		( $imputation["montant"] != "" ) AND
		!is_numeric($imputation["montant"]) 
		)
	{
		$erreurs[] = "Erreur dans le «&nbsp;Montant acquitté (hors frais de dossier)&nbsp;»." ;
	}
	if ( $imputation["monnaie"] == "" ) {
		$erreurs[] = "Le choix d'une monnaie pour le  «&nbsp;Montant acquitté (hors frais de dossier)&nbsp;» est obligatoire." ;
	}

	//
/*
	if ( $imputation["frais"] == "" ) {
		$erreurs[] = "Les «&nbsp;Frais de dossier&nbsp;» ont-ils été"
		. " acquittés&nbsp;?" ;
	}
	if ( $imputation["frais"] == "Non" ) {
		$erreurs[] = "Les «&nbsp;Frais de dossier&nbsp;» doivent être"
		. " acquittés." ;
	}
*/
	
	return formate_erreurs($erreurs) ;
}

require_once("inc_traitements_caracteres.php") ;
function calcule_imputation($tableau)
{
	if ( $tableau["annee_relative"] == "1" ) {
		$code  = $tableau["code_imputation"] ;
	}
	else {
		$code  = $tableau["code_imputation2"] ;
	}
	$code .= "/" ;
	$code .= substr($tableau["prenom"], 0, 1) ;
	$code .= "." ;
	
	$nom = strtr($tableau["nom"], array(
		"'" => "-",
		" " => "-"
	) ) ;
	if ( strlen($nom) > 18 ) {
		$nom = substr($nom, 0, 18) ;
	}
	$code .= $nom ;

	$code .= "/" ;
	if ( $tableau["etat"] == "Allocataire" ) {
		$code .= "A" ;
	}
	else if ( $tableau["etat"] == "Allocataire SCAC" ) {
		$code .= "A" ;
	}
	else if ( $tableau["etat"] == "Payant" ) {
		$code .= "P" ;
	}
	else {
		die("Etat indéterminé pour le calcul du code d'imputation.") ;
	}

	$code = strtoupper($code) ;
	$code = sansDiacritiques($code) ;

	return $code ;
}

// Première partie (non editable) d'un formulaire d'imputation
function formulaire_imputation_0($T)
{
	$form = "" ;
	$form .= "<tr>\n" ;
		$form .= "<th>Formation&nbsp;:</th>\n" ;
		$form .= "<td>" ;
		$form .= $T["intitule"] ;
		if ( $T["nb_annees"] != "1" ) {
			$form .= "<br />" ;
			if ( $T["annee_relative"] == "1" ) {
				$form .= "1<sup>ère</sup> année" ;
			}
			else {
				$form .= "2<sup>ème</sup> année" ;
			}
		}
		$form .= "</td>\n" ;
	$form .= "</tr>\n" ;
	$form .= "<tr>\n" ;
		$form .= "<th>Promotion&nbsp;:</th>\n" ;
		$form .= "<td>\n" ;
		$form .=  $T["annee"] . " (" . $T["intit_ses"] . ")" ;
		$form .= "</td>\n" ;
	$form .= "</tr>\n" ;
	$form .= "<tr>\n" ;
		$form .= "<th>Université&nbsp;:</th>\n" ;
		$form .= "<td>\n" ;
		$form .= $T["universite"] ;
		$form .= "</td>\n" ;
	$form .= "</tr>\n" ;
	$form .= "<tr>\n" ;
		$form .= "<th>Tarif « Payant »&nbsp;:</th>\n" ;
		$form .= "<td>\n" ;
		if ( $T["annee_relative"] == "1" ) {
			$form .= $T["tarifp"] ;
		}
		else {
			$form .= $T["tarif2p"] ;
		}
		$form .= " EUR" ;
		$form .= "</td>\n" ;
	$form .= "</tr>\n" ;
	$form .= "<tr>\n" ;
		$form .= "<th>Tarif « Allocataire »&nbsp;:</th>\n" ;
		$form .= "<td>\n" ;
		if ( $T["annee_relative"] == "1" ) {
			$form .= $T["tarifa"] ;
		}
		else {
			$form .= $T["tarif2a"] ;
		}
		$form .= " EUR" ;
		$form .= "</td>\n" ;
	$form .= "</tr>\n" ;
	$form .= "<tr>\n" ;
		$form .= "<th>Etat&nbsp;:</th>\n" ;
		$form .= "<td>\n" ;
		$form .= "<span class='" . strtolower($T["etat"]) . "'>" ;
		$form .= $T["etat"] ;
		$form .= "</span>" ;
		$form .= "</td>\n" ;
	$form .= "</tr>\n" ;
	echo $form ;
}

require_once("inc_formulaire_candidature.php") ;
require_once("fonctions_formulaire_candidature.php") ;

// Identite
function formulaire_imputation_1($T, $affiche_erreurs=FALSE)
{
	global $tab_civilite ;
	global $jour ;
	global $mois ;
	global $annee_nai ;

	echo "<tr>\n<td colspan='2' class='invisible'>" ;
		echo "<br />" ;
		echo "<strong>Les informations suivantes doivent être vérifiées et modifiées si nécessaire.</strong>" ;
		if ( $affiche_erreurs ) {
			$erreurs = verif_imputation_1($T) ;
			echo $erreurs ;
		}
	echo "</td>\n</tr>\n" ;
	
	echo "<tr>\n<th>" ;
	libelle("civilite") ;
	echo "</th>\n<td>" ;
	radio($tab_civilite, "civilite", $T["civilite"]) ;
	echo "</td>\n</tr>\n" ;
	
	echo "<tr>\n<th>" ;
	libelle("nom") ;
	echo "</th>\n<td>" ;
	inputtxt("nom", strtoupper($T["nom"]), 35, 50) ;
	echo "</td>\n</tr>\n" ;
	
	echo "<tr>\n<th>" ;
	libelle("nom_jf") ;
	echo "</th>\n<td>" ;
	inputtxt("nom_jf", strtoupper($T["nom_jf"]), 35, 50) ;
	echo "</td>\n</tr>\n" ;
	
	echo "<tr>\n<th>" ;
	libelle("prenom") ;
	echo "</th>\n<td>" ;
	inputtxt("prenom", ucwords(strtolower($T["prenom"])), 40, 100) ;
	echo "</td>\n</tr>\n" ;
	
	echo "<tr>\n<th>" ;
	libelle("naissance") ;
	echo "</th>\n<td>" ;
	liste_der1($jour, "jour_n", $T["jour_n"]) ;
	echo " / " ;
	liste_der2($mois, "mois_n", $T["mois_n"]) ;
	echo " / " ;
	liste_der1($annee_nai, "annee_n", $T["annee_n"]) ;
	echo "</td>\n</tr>\n" ;
	
	echo "<tr>\n<th>" ;
	libelle("nationalite") ;
	echo "</th>\n<td>" ;
	inputtxt("nationalite", strtolower($T["nationalite"]), 30, 50);
	echo "</td>\n</tr>\n" ;
}

require_once("inc_form_select.php") ;
require_once("inc_cnf.php") ;
require_once("inc_monnaie.php") ;
require_once("inc_devises.php") ;

// Paiement
function formulaire_imputation_2($cnx, $T, $affiche_erreurs=FALSE)
{
	global $CNF ;
	global $MONNAIE ;

	echo "<tr>\n<td colspan='2' class='invisible'>" ;
		echo "<br />" ;
		echo "<strong>Paiement (en une seule fois)&nbsp;:</strong>" ;
		if ( $affiche_erreurs ) {
			$erreurs = verif_imputation_2($T) ;
			echo $erreurs ;
		}
	echo "</td>\n</tr>\n" ;
	
	echo "<tr>\n<th>" ;
	echo "<label for='lieu'>Lieu d'enregistrement&nbsp;:</label>" ;
	echo "</th>\n<td>" ;
	form_select_1($CNF, "lieu", ( isset($T["lieu"]) ? $T["lieu"] : "" )) ;
	echo "</td>\n</tr>\n" ;
	
	echo "<tr>\n<th>" ;
	echo "<label for='montant_frais'>Frais de dossier acquittés&nbsp;:</label>" ;
	echo "</th>\n<td>" ;
	echo "<input type='text' name='montant_frais' id='montant_frais' size='12' " ;
	echo " value=\"".$T["montant_frais"]."\" /> " ;
	echo selectDevise($cnx, "monnaie_frais", ( isset($T["monnaie_frais"]) ? $T["monnaie_frais"] : "" )) ;
	//form_select_2($MONNAIE, "monnaie_frais", ( isset($T["monnaie_frais"]) ? $T["monnaie_frais"] : "" )) ;
	echo "</td>\n</tr>\n" ;

	echo "<tr>\n<th>" ;
	echo "Montant acquitté (hors frais de dossier)&nbsp;:</label>" ;
	echo "</th>\n<td>" ;
	echo "<input type='text' name='montant' id='montant' size='12'" ;
	echo " value=\"".$T["montant"]."\" /> " ;
	echo selectDevise($cnx, "monnaie", ( isset($T["monnaie"]) ? $T["monnaie"] : "" )) ;
	//form_select_2($MONNAIE, "monnaie", ( isset($T["monnaie"]) ? $T["monnaie"] : "" )) ;
	echo "</td>\n</tr>\n" ;

	if ( $_SESSION["utilisateur"] == "Service des bourses (SCP)" )
	{
		echo "</tr>\n<tr>\n" ;
			echo "\t<td colspan='2' class='invisible'><br />" ;
			echo "<strong>Pour le service des bourses (SCP) uniquement&nbsp;:</strong></td>\n" ;
		echo "</tr>\n<tr>\n" ;
			echo "\t<th>" ;
			echo "Marquer cette attestation comme un cas particulier&nbsp;:</th>\n" ;
			echo "\t<td>" ;
			echo "<label><input type='radio' name='particulier' id='particulier' value='Non'" ;
			if ( ( !isset($T["particulier"])) OR ( $T["particulier"] == 'Non' ) ) {
				echo " checked='checked'" ;
			}
			echo " /> Non</label><br />\n" ;
			echo "<label><input type='radio' name='particulier' id='particulier' value='Oui'" ;
			if ( $T["particulier"] == 'Oui' ) {
				echo " checked='checked'" ;
			}
			echo " /> <span class='Non'>Oui</span></label>\n" ;
			echo "</td>" ;
		echo "</tr>\n<tr>\n" ;
			echo "\t<th><label for='imputation'>" ;
			echo "Modification du code d'imputation comptable pour les cas particuliers&nbsp;:</label></th>\n" ;
			echo "\t<td><input type='text' name='imputation' id='imputation' " ;
			echo "size='36' value='".$T["imputation"]."' /></td>" ;
		echo "</tr>\n<tr>\n" ;
			echo "\t<th><label for='commentaire'>" ;
			echo "Commentaire&nbsp;:</label></th>\n" ;
			echo "\t<td><textarea name='commentaire' id='commentaire' " ;
			echo "rows='7' cols='70'>".$T["commentaire"]."</textarea></td>" ;
		echo "</tr>\n" ;
	}
}

function formulaire_imputation($cnx, $T, $affiche_erreurs=FALSE)
{
	echo "<form method='post' action='imputation.php?".$_SERVER["QUERY_STRING"]."'>\n" ;
	echo "<table class='formulaire'>\n" ;
	formulaire_imputation_0($T) ;
	formulaire_imputation_1($T, $affiche_erreurs) ;
	formulaire_imputation_2($cnx, $T, $affiche_erreurs) ;
	echo "<tr>\n<td colspan='2' class='invisible'>" ;

	echo "<br /><div class='c'><input type='submit' name='bouton' " ;
	if ( isset($T["id_imputation"]) ) {
		echo "value='Modifier' " ;
	}
	else {
		echo "value='Enregistrer' " ;
	}
	echo "style='font-weight: bold;' /></div><br />" ;

	echo "</td>\n</tr>\n" ;
	echo "</table>\n" ;
	echo "</form>\n" ;
}
?>
