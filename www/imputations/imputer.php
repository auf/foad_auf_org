<?php
include("inc_session.php") ;

/*
if ( isset($_GET["id_dossier"]) ) 
{
	include("inc_mysqli.php") ;
	$cnx = connecter() ;

	if ( $_GET["annee_relative"] == "2" ) {
		$annee_relative = 2 ;
	}
	else {
		$annee_relative = 1 ;
	}

	$req = "SELECT COUNT(id_imputation) AS N FROM imputations
		WHERE ref_dossier=" . $_GET["id_dossier"] . "
		AND annee_relative=" . $annee_relative ;
	$res = mysqli_query($cnx, $req) ;
	$enr = mysqli_fetch_assoc($res) ;
	if ( intval($enr["N"]) > 0 ) {
		echo "</p>Déjà imputé</p>" ;
		exit() ;
	}

	$req = "SELECT intitule, universite, nb_annees,
		intit_ses, annee, session.imputation, tarifa, tarifp, 
		imputation2, tarif2a, tarif2p,
		imputations, imputations2,
		dossier.id_session, etat_dossier,
		civilite, nom, nom_jf, prenom, naissance, nationalite
		FROM dossier, candidat, session, atelier
		WHERE id_dossier=".$_GET["id_dossier"]."
		AND dossier.id_candidat=candidat.id_candidat
		AND dossier.id_session=session.id_session
		AND session.id_atelier=atelier.id_atelier" ;
	$res = mysqli_query($cnx, $req) ;
	$enr = mysqli_fetch_assoc($res) ;

	// Numéro de dossier, et informations liées et non modifiables
	$imputation["ref_dossier"] = $_GET["id_dossier"] ;
	$imputation["intitule"] = $enr["intitule"] ;
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

	// C'est un ajout, pas une édition
	unset($imputation["id_imputation"]) ;


	$_SESSION["imputation"] = $imputation ;

	deconnecter($cnx) ;
	header("Location: /imputations/imputation.php") ;
}
else {
	header("Location: /imputations/index.php") ;
}
*/
$url = "/imputations/imputation.php" ;
if ( isset($_GET["id_dossier"]) ) {
	$url .= "?id_dossier=" . $_GET["id_dossier"] ;
	if ( isset($_GET["annee_relative"]) ) {
		$url .= "&annee_relative=" . $_GET["annee_relative"] ;
	}
} 
header("Location: $url") ;
?>
