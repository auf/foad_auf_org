<?php
include("inc_session.php") ;
require_once("inc_imputations.php") ;
require_once("inc_noms.php") ;

while ( list($key, $val) = each($_SESSION["imputation"]) )
{
	$imputation[$key] = $val ;
}
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
	$imputation[$key] = trim($val) ;
}


$erreurs = verif_imputation_1($imputation)
	. verif_imputation_2($imputation) ;

// FIXME Empecher les doublons d'attestation


if ( $erreurs != "" )
{
	$_SESSION["imputation"] = $imputation ;
	header("Location: /imputations/imputation.php") ;
}
else {
	include("inc_mysqli.php") ;
	$cnx = connecter() ;

	$naissance = $imputation["annee_n"] . "-"
		. $imputation["mois_n"] . "-" . $imputation["jour_n"] ;

	$code = calcule_imputation($imputation) ;

	// INSERT ou UPDATE
	// Modification des civilite, nom, prenom, nom_jf, naissance, nationalite
	// s'ils sont différents
	// FIXME Il faudrait aussi, sans doute, modifier à ce stade les autres
	// candidatures du même candidat.

	$req = "SELECT candidat.id_candidat, civilite, nom, nom_jf, prenom, nationalite, naissance
		FROM candidat, dossier
		WHERE dossier.id_candidat=candidat.id_candidat
		AND id_dossier=".$imputation["ref_dossier"] ;
	$res = mysqli_query($cnx, $req) ;
	$enr = mysqli_fetch_assoc($res) ;
	$candidat = array(
		"id_candidat" => $enr["id_candidat"],
		"civilite" => $enr["civilite"],
		"nom" => strtoupper(nom($enr["nom"])),
		"nom_jf" => strtoupper(nom($enr["nom_jf"])),
		"prenom" => ucwords(strtolower(nom($enr["prenom"]))),
		"nationalite" => $enr["nationalite"],
		"naissance" => $enr["naissance"]
	) ;

/*
	if ( $candidat["civilite"] != $imputation["civilite"] ) {
		echo "<p>civilite</p>" ;
	}
	if ( $candidat["nom"] != $imputation["nom"] ) {
		echo "<p>nom</p>" ;
	}
	if ( $candidat["nom_jf"] != $imputation["nom_jf"] ) {
		echo "<p>nom_jf</p>" ;
	}
	if ( $candidat["prenom"] != $imputation["prenom"] ) {
		echo "<p>prenom</p>" ;
	}
	if ( $candidat["nationalite"] != $imputation["nationalite"] ) {
		echo "<p>nationalite</p>" ;
	}
	if ( $candidat["naissance"] != $naissance ) {
		echo "<p>naissance</p>" ;
	}
*/

	if	(
			( $candidat["civilite"] != $imputation["civilite"] )
			OR ( $candidat["nom"] != $imputation["nom"] )
			OR ( $candidat["nom_jf"] != $imputation["nom_jf"] )
			OR ( $candidat["prenom"] != $imputation["prenom"] )
			OR ( $candidat["naissance"] != $naissance )
			OR ( $candidat["nationalite"] != $imputation["nationalite"] )
		)
	{
		$req = "UPDATE candidat SET " ;
		$req .= " civilite='".$imputation["civilite"]."', " ;
		$req .= " nom='".mysqli_real_escape_string($cnx, $imputation["nom"])."', " ;
		$req .= " nom_jf='".mysqli_real_escape_string($cnx, $imputation["nom_jf"])."', " ;
		$req .= " prenom='".mysqli_real_escape_string($cnx, $imputation["prenom"])."', " ;
		$req .= " naissance='".$naissance."', " ;
		$req .= " nationalite='".mysqli_real_escape_string($cnx, $imputation["nationalite"])."' " ;
		$req .= " WHERE id_candidat=".$candidat["id_candidat"] ;
//		echo "<p>$req</p>" ;
		mysqli_query($cnx, $req) ;

		// Pour une imputation en 2ème année avec une imputation en 1ère année existante
		if ( $imputation["annee_relative"] == "2" )
		{
			$req = "UPDATE imputations SET " ;
			$req .= " civilite='".$imputation["civilite"]."', " ;
			$req .= " nom='".mysqli_real_escape_string($cnx, $imputation["nom"])."', " ;
			$req .= " nom_jf='".mysqli_real_escape_string($cnx, $imputation["nom_jf"])."', " ;
			$req .= " prenom='".mysqli_real_escape_string($cnx, $imputation["prenom"])."', " ;
			$req .= " naissance='".$naissance."', " ;
			$req .= " nationalite='".mysqli_real_escape_string($cnx, $imputation["nationalite"])."' " ;
			$req .= " WHERE ref_dossier=".$imputation["ref_dossier"]." AND annee_relative=1" ;
//			echo "<p>$req</p>" ;
			mysqli_query($cnx, $req) ;
		}
	}


	// INSERT
	if ( !isset($imputation["id_imputation"]) )
	{
		$req = "INSERT INTO imputations(ref_dossier, annee_relative, annee_absolue, etat,
			lieu, montant, monnaie, montant_frais, monnaie_frais,
			imputation, date_creation, date_mod) VALUES(
			".$imputation["ref_dossier"].",
			'".$imputation["annee_relative"]."',
			'".$imputation["annee_absolue"]."',
			'".$imputation["etat"]."',
			'".mysqli_real_escape_string($cnx, $imputation["lieu"])."',
			".$imputation["montant"].",
			'".$imputation["monnaie"]."',
			".$imputation["montant_frais"].",
			'".$imputation["monnaie_frais"]."',
			'$code',
			CURDATE(),
			CURDATE()
			)" ;
		mysqli_query($cnx, $req) ;
		$id_imputation = mysqli_insert_id($cnx) ;
		unset($_SESSION["imputation"]) ;
		header("Location: /imputations/attestation.php?id=$id_imputation") ;
		deconnecter($cnx) ;
	}
	// UPDATE
	else 
	{
		$req = "UPDATE imputations SET
			lieu='".mysqli_real_escape_string($cnx, $imputation["lieu"])."',
			montant=".$imputation["montant"].",
			monnaie='".$imputation["monnaie"]."',
			montant_frais=".$imputation["montant_frais"].",
			monnaie_frais='".$imputation["monnaie_frais"]."',
			date_mod=CURDATE(),
			particulier='".$imputation["particulier"]."'" ;

		if ( isset($imputation["imputation"]) AND
			( substr($code, 0, 13) != substr($imputation["imputation"], 0, 13) )
			AND ( $imputation["imputation"] != "" )
		)
		{
			$req .= ", imputation='".$imputation["imputation"]."',
				mod_imputation='Oui'" ;
		}
		else {
			$req .= ", imputation='$code', mod_imputation='Non'" ;
		}

		if ( isset($imputation["commentaire"]) ) {
			$req .= ", commentaire='".mysqli_real_escape_string($cnx, $imputation["commentaire"])."'" ;
		}

		$req .= " WHERE id_imputation=".$imputation["id_imputation"] ;
//		echo "<p>$req</p>" ;
		mysqli_query($cnx, $req) ;

		unset($_SESSION["imputation"]) ;
		header("Location: /imputations/attestation.php?id=".$imputation["id_imputation"]) ;
		deconnecter($cnx) ;
	}
}
?>
