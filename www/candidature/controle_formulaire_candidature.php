<?php

$erreur_doublon = FALSE ;

$erreur_saisie1 = $erreur_saisie2 = $erreur_saisie3 = $erreur_saisie4 =
$erreur_saisie5 = $erreur_saisie6 = $erreur_saisie7 = $erreur_saisie8 = "" ;

//
// Controle des doublons
//
if ( ( trim($T["nom"]) != "" ) AND
	( $T["jour_n"] != "" ) AND 
	( $T["mois_n"] != "") AND
	( $T["annee_n"] != "" ) 
	// Pas pour les mises à jour
	AND ( $_POST['formulaire'] != "maj" )
) 
{
	$date_naissance = $T["annee_n"] . "-" . $T["mois_n"] . "-"
		. $T["jour_n"] ;

// 30 mai 2008 : ajout pays à recherche de doublons de candidats aux memes promotions
	$req_d = "SELECT COUNT(candidat.id_candidat)
		FROM candidat, dossier, session
		WHERE
		candidat.nom='".mysqli_real_escape_string($cnx, remets_guillemets(trim($T["nom"])))."'
		AND candidat.naissance='$date_naissance'
		AND (candidat.pays='".$T["pays"]."' OR candidat.pays='".$T["pays_emp"]."' OR candidat.pays_emp='".$T["pays"]."' OR candidat.pays_emp='".$T["pays_emp"]."')
		AND dossier.id_candidat=candidat.id_candidat
		AND dossier.id_session=$id_session" ;
	$res_d = mysqli_query($cnx, $req_d) ;
	$nbre_d = mysqli_fetch_row($res_d) ;
	if ( intval($nbre_d[0]) != 0 )
	{
		$erreur_doublon = TRUE ;
		$erreur_saisie = TRUE ;
	}
}

//
// Informations personnelles
//
if ( trim($T["civilite"]) == "" ) {
	$erreur_saisie1 .= obligatoire("civilite") ;
}
if ( trim($T["nom"]) == "" ) {
	$erreur_saisie1 .= obligatoire("nom") ;
}
if ( ( isset($T["civilite"]) AND ($T["civilite"] == "Madame") ) && ( trim($T["nom_jf"]) == "" ) ) {
	$erreur_saisie1 .= obligatoire("nom_jf") ;
}
if ( ( isset($T["civilite"]) AND ($T["civilite"] != "Madame") ) && ( trim($T["nom_jf"]) != "" ) ) {
	$erreur_saisie1 .= "<li>Le nom de jeune fille ne doit être renseigné que pour les femmes mariées.</li>\n" ;
}
if ( trim($T["prenom"]) == "" ) {
	$erreur_saisie1 .= obligatoire("prenom") ;
}
if ( ($T["jour_n"]=="") OR ($T["mois_n"]=="") OR ($T["annee_n"]=="") ) {
	$erreur_saisie1 .= obligatoire("naissance") ;
}
if ( ($T["pays_naissance"] == "-------") OR ($T["pays_naissance"] == "") ) {
	$erreur_saisie1 .= obligatoire("pays_naissance") ;
}
if ( !isset($T["genre"]) OR ($T["genre"] == "") ) {
	$erreur_saisie1 .= obligatoire("genre") ;
}
if ( $T["nationalite"] == "" ) {
	$erreur_saisie1 .= obligatoire("nationalite") ;
}
if ( $T["situation_actu"] == "-------" ) {
	$erreur_saisie1 .= obligatoire("situation_actu") ;
}
if ( ($T["situation_actu"] == "Autre") AND ( trim($T["sit_autre"])=="") ) {
	$erreur_saisie1 .= obligatoire("sit_autre") ;
}
if ( ( isset($T["civilite"]) AND ($T["civilite"] != "") ) AND ( isset($T["genre"]) AND ($T["genre"] != "") ) )
{
	if
	(
		(
			( $T["genre"] == "Homme" ) AND
			( $T["civilite"] != "Monsieur" )
		)
		OR
		(
			( $T["genre"] == "Femme" ) AND
			( $T["civilite"] == "Monsieur" )
		) 
	)
	{
		$erreur_saisie1 .= "<li>Les champs «&nbsp;<span class='erreur_champ'>Civilité</span>&nbsp;» et «&nbsp;<span class='erreur_champ'>Genre</span>&nbsp;» sont incohérents.</li>\n" ;
	}
}

//
// 2. Adresse personnelle
//
if ( trim($T["adresse"]) == "" ) {
	$erreur_saisie2 .= obligatoire("adresse") ;
}
if ( $T["code_postal"] == "" ) {
	$erreur_saisie2 .= obligatoire("code_postal") ;
}
if ( trim($T["ville"]) == "" ) {
	$erreur_saisie2 .= obligatoire("ville") ;
}
if ( ($T["pays"] == "-------") OR ($T["pays"] == "") ) {
	$erreur_saisie2 .= obligatoire("pays") ;
}
if ( trim($T["tel"]) == "" ) {
	$erreur_saisie2 .= obligatoire("tel") ;
}
if ( trim($T["email1"]) == "" ) {
	$erreur_saisie2 .= obligatoire("email1") ;
}
else if ( !filter_var($T["email1"], FILTER_VALIDATE_EMAIL) ) {
	$erreur_saisie2 .= obligatoire("email1", "n'est pas valide") ;
}
else if ( $T["email1"] != $T["verif_email1"] ) {
	$erreur_saisie2 .= obligatoire("email1", "ne contient pas deux fois la même adresse.") ;
}

//
// 3. Informations professionnelles
//
if ( $T["situation_actu"] == "Enseignant(e)" ||
	$T["situation_actu"] == "Fonctionnaire" ||
	$T["situation_actu"] == "Salarié(e) du secteur privé" ||
	$T["situation_actu"] == "Employé(e) d'une ONG ou d'une coopération" ||
	$T["situation_actu"] == "Autre" )
{
	if ( trim($T["emploi_actu"]) == "" ) {
		$erreur_saisie3 .= obligatoire("emploi_actu") ;
	}
	if ( trim($T["employeur"]) == "" ) {
		$erreur_saisie3 .= obligatoire("employeur") ;
	}
	if ( trim($T["service"]) == "" ) {
		$erreur_saisie3 .= obligatoire("service") ;
	}
	if ( trim($T["titre"]) == "" ) {
		$erreur_saisie3 .= obligatoire("titre") ;
	}
	if ( trim($T["adresse_emp"]) == "" ) {
		$erreur_saisie3 .= obligatoire("adresse_emp") ;
	}
	if ( trim($T["codepost_emp"]) == "" ) {
		$erreur_saisie3 .= obligatoire("codepost_emp") ;
	}
	if ( trim($T["ville_emp"]) == "" ) {
		$erreur_saisie3 .= obligatoire("ville_emp") ;
	}
	if ( ($T["pays_emp"] == "-------") OR ($T["pays_emp"] == "") ) {
		$erreur_saisie3 .= obligatoire("pays_emp") ;
	}
	if ( trim($T["tel_emp"]) == "" ) {
		$erreur_saisie3 .= obligatoire("tel_emp") ;
	}
	if ( trim($T["fax_emp"]) == "" ) {
		$erreur_saisie3 .= obligatoire("fax_emp") ;
	}
	if ( $T["email_pro1"] == "" ) {
		$erreur_saisie3 .= obligatoire("email_pro1") ;
	}
	else if ( !filter_var($T["email_pro1"], FILTER_VALIDATE_EMAIL) ) {
		$erreur_saisie3 .= obligatoire("email_pro1", "n'est pas valide") ;
	}
}
if ( (trim($T["duree_exp"]) == "-------") OR (trim($T["duree_exp"]) == "") ) {
	$erreur_saisie3 .= obligatoire("duree_exp") ;
}

//
// 4. Diplômes et certifications
//
if ( ($T["niveau_dernier_dip"] == "-------") ) {
	$erreur_saisie4 .= obligatoire("niveau_dernier_dip") ;
}
if ( trim($T["dernier_dip"]) == "" ) {
	$erreur_saisie4 .= obligatoire("niveau_dernier_dip") ;
}
if ( trim($T["info_dernier_dip"]) == "" ) {
	$erreur_saisie4 .= obligatoire("info_dernier_dip") ;
}
//
$erreur_dip = FALSE ;
if	(
		(trim($T["titre_dip1"])=="")
		OR (trim($T["annee_dip1"])=="")
		OR (trim($T["etab_dip1"])=="")
		OR (trim($T["pays_dip1"])=="")
	)
{
	$erreur_dip = TRUE ;
}
for ( $i=2 ; $i<=4 ; $i++ ) {
	if	(
			(
				(trim($T["titre_dip$i"])!="")
				OR (trim($T["annee_dip$i"])!="")
				OR (trim($T["etab_dip$i"])!="")
				OR (trim($T["pays_dip$i"])!="")
			)
			AND
			(
				(trim($T["titre_dip$i"])=="")
				OR (trim($T["annee_dip$i"])=="")
				OR (trim($T["etab_dip$i"])=="")
				OR (trim($T["pays_dip$i"])=="")
			)
		)
	{
		$erreur_dip = TRUE ;
	}
}
if ( $erreur_dip ) {
	$erreur_saisie4 .= "<li>Erreur dans la liste de vos diplômes académiques</li>\n" ;
}


//
// 5.
//
if (
	( $T["exp_dist"] == "-------" ) OR
	( ($T["exp_dist"] == "Oui") AND (trim($T["format_dist"]) == "") )
	) {
	$erreur_saisie5 .= reponse("exp_dist") ;
}
if ( trim($T["exp_internet"]) == "" ) {
	$erreur_saisie5 .= reponse("exp_internet") ;
}
if ( trim($T["exp_bureau"]) == "" ) {
	$erreur_saisie5 .= reponse("exp_bureau") ;
}

//
// 6.
//
if ( trim($T["projet_perso"]) == "" ) {
	$erreur_saisie6 .= reponse("projet_perso") ;
}
if ( trim($T["lettre_motiv"]) == "" ) {
	$erreur_saisie6 .= reponse("lettre_motiv") ;
}
if ( trim($T["cv"]) == "" ) {
	$erreur_saisie6 .= reponse("cv") ;
}

//
// 7.
//
if ( $T["bourse_auf"] == "-------" ) {
	$erreur_saisie7 .= reponse("bourse_auf") ;
}
if (
	( $T["financement_form"] == "-------" ) OR
	( ($T["financement_form"] == "Autre") AND 
		(trim($T["autre_pec"]) == "") )
	)
	{
	$erreur_saisie7 .= reponse("financement_form") ;
}
if ( $T["bourse_auf"] == "Oui" )
{
	if (
		( $T["prix_sud"] == "-------" )
			OR
			( 
			($T["prix_sud"] == "Oui")
				AND 
				(
					( $T["financement_sud"] == "-------" )
					OR
					(
						( $T["financement_sud"] == "Autre" ) AND
						( trim($T["autre_sud"]) == "" )
					)
				)
			)
		) 
		{
		$erreur_saisie7 .= reponse("prix_sud") ;
	}
}

//
// 8.
//
if ( $T["nbre_heures"] == "-------" ) {
	$erreur_saisie8 .= reponse("nbre_heures") ;
}
// A partir de 2008
if ( $T["ordipro"] == "-------" ) {
	$erreur_saisie8 .= reponse("ordipro") ;
}
if ( $T["netpro"] == "-------" ) {
	$erreur_saisie8 .= reponse("netpro") ;
}
if ( $T["ordiperso"] == "-------" ) {
	$erreur_saisie8 .= reponse("ordiperso") ;
}
if ( ($T["fixeportable"] == "-------") AND ($T["ordiperso"] == "Oui") ) {
	$erreur_saisie8 .= reponse("fixeportable") ;
}
if ( $T["netperso"] == "-------" ) {
	$erreur_saisie8 .= reponse("netperso") ;
}
// Avant 2008
/*
if (
	( $T["acces_pc"] == "-------" ) OR
	( ($T["acces_pc"] == "Oui") AND ($T["appart_pc"] == "-------") )
	) {
	$erreur_saisie8 .= reponse("acces_pc") ;
}
if (
	( $T["connexion_int"] == "-------" ) OR
	( ($T["connexion_int"] == "Non") 
		AND (trim($T["autre_acces_internet"]) == "") )
	) {
	$erreur_saisie8 .= reponse("connexion_int") ;
}
*/
if ( $T["service_cnf"] == "-------" ) {
	$erreur_saisie8 .= reponse("service_cnf") ;
}
if ( $T["service_cnf"] == "Oui" ) {
	if ( $T["temps_dep"] == "-------" ) {
		$erreur_saisie8 .= reponse("temps_dep") ;
	}
	if ( $T["nbre_dep"] == "-------" ) {
		$erreur_saisie8 .= reponse("nbre_dep") ;
	}
}

?>
