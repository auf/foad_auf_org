<?php

define("URL_ANNUAIRE_ANCIENS", "/anciens.html") ;

require_once("inc_mysqli.php");
require_once("inc_annuaire.php") ;
require_once("inc_identite.php");

function formulaire_annuaire($cnx, $T)
{
	$form  = "<form class='form_annuaire' method='post' action='".URL_ANNUAIRE_ANCIENS."'>\n" ;

/*
	$form .= "<p>" ;
	$form .= "<label for='annuaire_annee'>Année</label>" ;
	$form .= liste_annees_anciens($cnx, "annuaire_annee",
		$_SESSION["annuaire"]["annee"]) ;
	$form .= "</p>\n" ;
*/

	$form .= "<p>" ;
	$form .= "<label for='formation'>Formation</label>" ;
	$form .= liste_formations_anciens($cnx, "formation", $T["formation"], FALSE) ;
	$form .= "</p>\n" ;

/*
	$form .= "<p>" ;
	$form .= "<label for='annuaire_promo'>Promotion</label>" ;
	$form .= liste_promotions_anciens($cnx, "annuaire_promo", $T["promo"]) ;
	$form .= "</p>\n" ;
*/

	$form .= "<p>" ;
	$form .= "<label for='pays'>Pays de résidence</label>" ;
	$form .= liste_pays_anciens($cnx, "pays", $T["pays"], "pays", FALSE) ;
	$form .= "</p>\n" ;

	$form .= "<p>" ;
	$form .= "<label for='nom'>Nom</label>" ;
	$form .= "<input type='text' name='nom' size='20' " ;
	if ( isset($T["nom"]) ) {
		$form .= "value=\"".$T["nom"]."\"" ;
	}
	$form .= "/>\n" ;
	$form .= "</p>\n" ;

	$form .= "<p class='c'>" ;
	$form .= "<input type='hidden' name='criteres' value='criteres' />\n" ;
	$form .= "<input type='submit' value='Rechercher' />" ;
	$form .= "</p>\n" ;

	$form .= "</form>\n" ;
	return $form ;
}

function requete_annuaire($T)
{
//	$T = $_SESSION["annuaire"] ;

	$req = " FROM anciens LEFT JOIN dossier_anciens
		ON anciens.id_ancien=dossier_anciens.ref_ancien
		WHERE ref_atelier NOT IN ".LISTE_FORMATIONS_TEST ;
	if ( isset($T["formation"]) AND ($T["formation"] != "") ) {
		$req .= " AND dossier_anciens.ref_atelier=".mysqli_real_escape_string($cnx, $T["formation"]) ;
	}
	if ( isset($T["pays"]) AND ($T["pays"] != "") ) {
		$req .= " AND pays='".mysqli_real_escape_string($cnx, $T["pays"])."'" ;
	}
	if ( isset($T["nom"]) AND (trim($T["nom"]) != "") ) {
		$req .= " AND nom LIKE '";
		$req .= str_replace("*", "%", mysqli_real_escape_string($cnx, trim($T["nom"]))) ;
		$req .= "'" ;
	}
	return $req ;
}

function traitementPost($post)
{
	$P = array() ;
	reset($post) ;
	while ( list($key, $val) = each($post) )
	{
		$val = trim($val) ;
		$P[$key] = $val ;
	}
	return $P ;
}




// =============================================================================

$cnx = connecter() ;


if  (
		( $_POST["criteres"] == "criteres" )
		OR ( isset($_GET["p"]) AND is_numeric($_GET["p"]) )
	)
{
	if ( $_POST["criteres"] == "criteres" )
	{
		unset($_SESSION["annuaire"]) ;
		$_SESSION["annuaire"] = traitementPost($_POST) ;
	}

	$reqCount = "SELECT COUNT(DISTINCT anciens.id_ancien) AS N " ;
	$reqSelect = "SELECT DISTINCT anciens.* " ;
	$reqOrder = " ORDER BY nom" ;

	$requete = requete_annuaire($_SESSION["annuaire"]) ;

	$req = $reqCount . $requete ;
	$res = mysqli_query($cnx, $req) ;
	$enr = mysqli_fetch_assoc($res) ;
	$nbAnciens = $enr["N"] ;

	echo formulaire_annuaire($cnx, $_SESSION["annuaire"]) ;
	
	if ( $nbAnciens == 0 ) {
		echo "<p><strong>Aucun ancien pour ces critères</strong>.</p>" ;
	}
	else {
		if ( $nbAnciens > 1 ) { $s = "s" ; } else { $s = "" ; }
		echo "<p class='c'><strong>$nbAnciens ancien".$s."</strong></p>\n" ;

		define("NB_PAR_PAGE", 20) ;
		$nbPages = intval($nbAnciens / NB_PAR_PAGE) ;
		if ( ($nbAnciens % NB_PAR_PAGE) != 0 ) {
			$nbPages++ ;
		}
		if ( $nbPages > 1 ) {
			if ( !isset($_GET["p"]) ) {
				$start = "" ;
			}
			else {
				$start = ( intval($_GET["p"]) -1 ) * NB_PAR_PAGE ;
				$start = strval($start).", " ;
			}
		}
		else {
			$start = "" ;
		}

		$nav = "" ;
		if ( $nbPages > 1 )
		{
			if ( !isset($_GET["p"]) ) {
				$_GET["p"] = 1 ;
			}
			$p = intval($_GET["p"]) ;
			$nav  = "<p class='pagination'>" ;
			if ( $p > 2 ) {
				$nav .= "<a href='?p=1'>" ;
				$nav .= "<img src='/squelettes/navAnnuaire/premiere.gif' " ;
				$nav .= "alt='Première page' title='Première page' " ;
				$nav .= "width='27' height='15' /></a> " ;
			}
			if ( $p > 1 ) {
				$nav .= "<a href='?p=".strval($p -1)."'>" ;
				$nav .= "<img src='/squelettes/navAnnuaire/precedente.gif' " ;
				$nav .= "alt='Page précédente' title='Page précédente' " ;
				$nav .= "width='27' height='15' /></a> " ;
			}
			$nav .= "<strong>Page ". $p ." / ".$nbPages."</strong>" ;
			if ( $p < $nbPages) {
				$nav .= " <a href='?p=".strval($p +1)."'>" ;
				$nav .= "<img src='/squelettes/navAnnuaire/suivante.gif' " ;
				$nav .= "alt='Page suivante' title='Page suivante' " ;
				$nav .= "width='27' height='15' /></a> " ;
			}
			if ( $p < ($nbPages - 1) ) {
				$nav .= " <a href='?p=".$nbPages."'>" ;
				$nav .= "<img src='/squelettes/navAnnuaire/derniere.gif' " ;
				$nav .= "alt='Dernière page' title='Dernière page' " ;
				$nav .= "width='27' height='15' /></a>" ;
			}
			$nav .= "</p>\n" ;
		}

		echo $nav ;

		$req = $reqSelect . $requete . $reqOrder . " LIMIT $start ". NB_PAR_PAGE ;
		$res = mysqli_query($cnx, $req) ;

		while ( $row = mysqli_fetch_assoc($res) )
		{
			echo "<div class='ancien'>\n" ;
			echo "<a href='/ancien".$row["id_ancien"].".html'>" ;
			echo identite($row) ;
			echo "</a>" ;
			echo " (" . $row["pays"] . ") " ;
			echo afficheAncienDiplomesSpip($cnx, $row["id_ancien"]) ;
			echo "</div>\n\n" ;
		}

		echo $nav ;
	}
}
else {
	echo formulaire_annuaire($cnx, $_SESSION["annuaire"]) ;
}

deconnecter($cnx) ;
?>
