<?php
include("inc_session.php") ;
if ( intval($_SESSION["id"]) > 10 ) {
    header("Location: /logout.php") ;
}

if ( !isset($_GET["id_imputation"]) AND !isset($_GET["id_dossier"]) ) {
	header("Location: /imputations/statistiques.php") ;
	exit ;
}

require_once("inc_mysqli.php") ;
$cnx = connecter() ;
require_once("inc_imputations.php") ;
require_once("inc_devises.php") ;

if ( isset($_GET["id_dossier"]) ) {
	if ( isset($_GET["annee_relative"]) AND ($_GET["annee_relative"] == "2") ) {
		$annee_relative = 2 ;
	}
	else {
		$annee_relative = 1 ;
	}
	$T = imputation_dossier($cnx, $_GET["id_dossier"], $annee_relative) ;
}
else if ( isset($_GET["id_imputation"]) ) {
	$T = imputation_imputation($cnx, $_GET["id_imputation"]) ;
}
else {
	$T = array() ;
	$T["erreur"] = "<p class='erreur'>Erreur theoriquement impossible (pas d'id dans l'URL).</p>" ;
}

$candidat = strtoupper($T["nom"]) ." ". ucwords(strtolower($T["prenom"])) ;
require_once("inc_html.php") ;
$titre = "Imputation pour $candidat" ;
$haut_page_1 = $dtd1
	. "<title>$titre</title>\n"
	. $dtd2 ;
$haut_page_2 = "<div class='noprint'>"
	. $debut_chemin
	. "<a href='/bienvenue.php'>Accueil</a>"
	. " <span class='arr'>&rarr;</span> "
	. "<a href='/imputations/index.php'>Imputations</a>"
	. " <span class='arr'>&rarr;</span> "
	. $candidat
	. $fin_chemin
	. "</div>\n" ;

require_once("inc_noms.php") ;



// Formulaire soumis
if ( isset($_POST["bouton"]) )
{
	// Ici, $T contient les informations existant avant soumission du formuulaire
	// On les conserve pour comparer
	$dossier = array(
		"id_candidat" => $T["id_candidat"],
		"civilite" => $T["civilite"],
		"nom" => strtoupper(nom($T["nom"])),
		"nom_jf" => strtoupper(nom($T["nom_jf"])),
		"prenom" => ucwords(strtolower(nom($T["prenom"]))),
		"nationalite" => $T["nationalite"],
		"naissance" => $T["naissance"]
	) ;

	// Ici, on ecrase les donnees de T par celles de _POST
	while ( list($key, $val) = each($_POST) )
	{
		// . comme separateur pour les float
		if ( ( $key == "montant" ) OR ( $key == "montant_frais" ) ) {
			$val = str_replace(",", ".", $val) ;
		}
		// Traitement des noms
		if ( ( $key == "nom" ) OR ( $key == "nom_jf" ) OR ( $key == "prenom" ) ) {
			$val = nom($val) ;
		}
		if ( ( $key == "nom" ) OR ( $key == "nom_jf" ) ) {
			$val = strtoupper($val) ;
		}
		if ( $key == "prenom" ) {
			$val = ucwords(strtolower($val)) ;
		}
		$T[$key] = trim($val) ;
	}

	$erreurs = verif_imputation_1($T) . verif_imputation_2($T) ;

	$naissance = $T["annee_n"] . "-" . $T["mois_n"] . "-" . $T["jour_n"] ;
	$code = calcule_imputation($T) ;

	// INSERT ou UPDATE
	// Modification des civilite, nom, prenom, nom_jf, naissance, nationalite
	// s'ils sont diffÃ©rents
	// FIXME Il faudrait aussi, sans doute, modifier Ã  ce stade les autres
	// candidatures du mÃªme candidat.
	if ( $erreurs == "" )
	{
		/*
		if ( $dossier["civilite"] != $T["civilite"] ) {
			echo "<p>civilite</p>" ;
		}
		if ( $dossier["nom"] != $T["nom"] ) {
			echo "<p>nom</p>" ;
		}
		if ( $dossier["nom_jf"] != $T["nom_jf"] ) {
			echo "<p>nom_jf</p>" ;
		}
		if ( $dossier["prenom"] != $T["prenom"] ) {
			echo "<p>prenom</p>" ;
		}
		if ( $dossier["nationalite"] != $T["nationalite"] ) {
			echo "<p>nationalite</p>" ;
		}
		if ( $dossier["naissance"] != $naissance ) {
			echo "<p>naissance</p>" ;
		}
		*/
		if  (
	            ( $dossier["civilite"] != $T["civilite"] )
	            OR ( $dossier["nom"] != $T["nom"] )
	            OR ( $dossier["nom_jf"] != $T["nom_jf"] )
	            OR ( $dossier["prenom"] != $T["prenom"] )
	            OR ( $dossier["naissance"] != $naissance )
	            OR ( $dossier["nationalite"] != $T["nationalite"] )
	        )
	    {
	        $req = "UPDATE candidat SET " ;
	        $req .= " civilite='".$T["civilite"]."', " ;
	        $req .= " nom='".mysqli_real_escape_string($cnx, $T["nom"])."', " ;
	        $req .= " nom_jf='".mysqli_real_escape_string($cnx, $T["nom_jf"])."', " ;
	        $req .= " prenom='".mysqli_real_escape_string($cnx, $T["prenom"])."', " ;
	        $req .= " naissance='".$naissance."', " ;
	        $req .= " nationalite='".mysqli_real_escape_string($cnx, $T["nationalite"])."' " ;
	        $req .= " WHERE id_candidat=".$dossier["id_candidat"] ;
	//      echo "<p>$req</p>" ;
	        mysqli_query($cnx, $req) ;
		}

		//
		// INSERT
		//
		if ( isset($_GET["id_dossier"]) )
		{
			$req = "INSERT INTO imputations
				(ref_dossier, annee_relative, annee_absolue, etat,
				lieu, montant, monnaie, montant_frais, monnaie_frais,
				imputation, date_creation, date_mod) VALUES(
				".$T["ref_dossier"].",
				'".$T["annee_relative"]."',
				'".$T["annee_absolue"]."',
				'".$T["etat"]."',
				'".mysqli_real_escape_string($cnx, $T["lieu"])."',
				".$T["montant"].",
				'".$T["monnaie"]."',
				".$T["montant_frais"].",
				'".$T["monnaie_frais"]."',
				'$code',
				CURDATE(),
				CURDATE()
				)" ;
			mysqli_query($cnx, $req) ;
			$id_imputation = mysqli_insert_id($cnx) ;
			deconnecter($cnx) ;
			header("Location: /imputations/attestation.php?id=$id_imputation") ;
		}
		//
		// UPDATE
		//
		else if ( isset($_GET["id_imputation"]) )
		{
			$req = "UPDATE imputations SET
				lieu='".mysqli_real_escape_string($cnx, $T["lieu"])."',
				montant=".$T["montant"].",
				monnaie='".$T["monnaie"]."',
				montant_frais=".$T["montant_frais"].",
				monnaie_frais='".$T["monnaie_frais"]."',
				date_mod=CURDATE(),
				particulier='".$T["particulier"]."'" ;
	
			if	(
					isset($T["imputation"])
					AND ( substr($code, 0, 9) != substr($T["imputation"], 0, 9) )
					AND ( $T["imputation"] != "" )
				)
			{
				$req .= ", imputation='".$T["imputation"]."',
					mod_imputation='Oui'" ;
			}
			else
			{
				$req .= ", imputation='$code', mod_imputation='Non'" ;
			}
	
			if ( isset($T["commentaire"]) ) {
				$req .= ", commentaire='".mysqli_real_escape_string($cnx, $T["commentaire"])."'" ;
			}
	
			$req .= " WHERE id_imputation=".$T["id_imputation"] ;
			//echo "<p>$req</p>" ;
			mysqli_query($cnx, $req) ;
	
			deconnecter($cnx) ;
			header("Location: /imputations/attestation.php?id=".$T["id_imputation"]) ;
		}
		// Erreur theoriquement impossible
		else {
			echo "<p class='erreur'>Erreur theoriquement impossible " ;
			echo "(pas d'id dans l'URL apres soumission du formulaire).</p>" ;
		}
	}
	else
	{
		echo $haut_page_1 ;
		include("inc_menu.php") ;
		echo $haut_page_2 ;
		if ( isset($T["erreur"]) AND ($T["erreur"] != "") ) {
			echo $T["erreur"] ;
		}
		else {
			formulaire_imputation($cnx, $T, TRUE) ;
		}
	}
}
// Arrivee dans la page ou le formulaire
else
{
	echo $haut_page_1 ;
	include("inc_menu.php") ;
	echo $haut_page_2 ;
	if ( isset($T["erreur"]) AND ($T["erreur"] != "") ) {
		echo $T["erreur"] ;
	}
	else {
		formulaire_imputation($cnx, $T) ;
	}
}

//diagnostic() ;
deconnecter($cnx) ;
echo $end ;
?>
