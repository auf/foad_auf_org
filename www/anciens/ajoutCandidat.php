<?php
require_once("inc_session.php") ;
if ( intval($_SESSION["id"]) > 0 ) {
	header("Location: /") ;
}

require_once("inc_html.php");
$titre = "Ajout d'un candidat pour ajouter un diplôme ou un ancien" ;
$entete_page_1 = $dtd1
	. "<title>$titre</title>\n"
	. $htmlJquery
	. $htmlMakeSublist
	. $dtd2 ;
$entete_page_2 = $debut_chemin
	. "<a href='/bienvenue.php'>Accueil</a>"
	. " <span class='arr'>&rarr;</span> "
	. "<a href='/anciens/'>Anciens</a>"
	. " <span class='arr'>&rarr;</span> "
	. $titre
	. $fin_chemin ;

require_once("inc_mysqli.php");
$cnx = connecter() ;

require_once("inc_formulaire_candidat.php") ;
require_once("fonctions_formulaire_candidature.php") ;
require_once("inc_pays.php") ;
require_once("inc_guillemets.php") ;
require_once("inc_noms.php") ;
require_once("inc_promotions.php") ;

// Formulaire soumis
if ( isset($_POST["promotion"]) )
{
	unset($T) ;
	while ( list($key, $val) = each($_POST) ) {
		if ( ($key == "nom") OR ($key == "nom_jf") ) {
			$T[$key] = trim(enleve_guillemets(nom($val))) ;
		}
		else {
			$T[$key] = trim(enleve_guillemets($val)) ;
		}
	}

	include_once("controle_formulaire_candidat.php") ;
	// Erreurs
	if ( $erreurs != "" )
	{
		echo $entete_page_1 ;
		require_once("inc_menu.php");
		echo $entete_page_2 ;
		echo $erreurs ;
		echo "<form method='post' action='".$_SERVER["SCRIPT_NAME"]."'>\n" ;
		include_once("formulaire_candidat.php") ;
		echo "</form>\n" ;
		echo $end ;
	}
	// Pas d'erreurs
	else
	{
		if ( $T["annee_n"] != "" ) {
			$date_n = $T["annee_n"] ."-". $T["mois_n"] ."-".  $T["jour_n"] ;
		}
		else {
			$date_n = "0000-00-00" ;
		}
		while ( list($key, $val) = each($T) ) {
			$T[$key] = mysqli_real_escape_string($cnx, remets_guillemets($val)) ;
		}
		$req = "INSERT INTO candidat
			(email1, civilite, nom, nom_jf, prenom, naissance,
			nationalite, pays, emploi_actu, employeur)
			VALUES('".$T["courriel"]."', "
			. "'".$T["civilite"]."', "
			. "'".$T["nom"]."', "
			. "'".$T["nom_jf"]."', "
			. "'".$T["prenom"]."', "
			. "'".$date_n."', "
			. "'".$T["nationalite"]."', "
			. "'".$T["pays"]."', "
			. "'".$T["profession"]."', "
			. "'".$T["employeur"]."')" ;

		//echo $req ;
		mysqli_query($cnx, $req) ;
		$id_candidat = mysqli_insert_id($cnx) ;

		$req = "INSERT INTO dossier
			(id_candidat, id_session,
			date_inscrip, etat_dossier, date_maj)
			VALUES ('$id_candidat', '".$T["promotion"]."',
			CURRENT_DATE, 'Externe', CURRENT_DATE)" ;

		//echo $req ;
		mysqli_query($cnx, $req) ;
		$id_dossier = mysqli_insert_id($cnx) ;

		//header("Location: /recherche/diplome.php?id_dossier=".$id_dossier) ;
		header("Location: /inscrits/reinitialiser.php?id_session=".$T["promotion"]."&nom=".urlencode($T["nom"])) ;
	}
}
// Formulaire non soumis
else
{
	echo $entete_page_1 ;
	require_once("inc_menu.php");
	echo $entete_page_2 ;
	echo "<form method='post' action='".$_SERVER["SCRIPT_NAME"]."'>\n" ;
	include_once("formulaire_candidat.php") ;
	echo "</form>\n" ;
	echo $end ;
}


deconnecter($cnx) ;
?>
