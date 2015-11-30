<?php
require_once("inc_session.php") ;
require_once("inc_anciens.php") ;

function resultat_recherche($T)
{
	global $etat_dossier_img_class ;
	$res = "<div class='res'>" ;
	$res .= ancienPublic($T) ;
//	$res .= ancienPrive($T) ;
	$res .= "</div>\n\n" ; // class='res'
	echo $res ;
}

function liste_tri($name, $value)
{
	$TRI = array(
		"" => "",
		"nom" => "Nom",
		"naissance" => "Date de naissance",
		"courriel" => "Courriel",
		"nom_pays" => "Pays de résidence",
		"date_maj DESC" => "Date de mise à jour",
	) ;

	echo "<select name='$name'>\n" ;
	while ( list($key, $val) = each($TRI) )
	{
		echo "<option value='$key'" ;
		if ( $value == $key ) {
			echo " selected='selected'" ;
		}
		echo ">$val</option>\n" ;
	}
	echo "</select>" ;
}

$titre = "Anciens" ;
require_once("inc_html.php");
echo $dtd1 ;
echo "<title>$titre</title>" ;
echo $htmlJquery ;
echo $htmlMakeSublist ;
echo $dtd2 ;
require_once("inc_menu.php");
echo $debut_chemin ;
echo "<a href='/bienvenue.php'>Accueil</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo $titre ;
echo $fin_chemin ;


if ( $_SESSION["id"] == "00" ) {
	echo "<p class='c'>L'ajout d'un ancien pour lequel il existe une candidature se fait à partir de la <a href='/recherche/'>recherche</a> (<a target='_blank' href='/documentation/ajout_diplome_ancien.php'>documentation</a>).<br />
Sinon, il faut commencer par <strong><a href='ajoutCandidat.php'>ajouter une candidature</a></strong>.</p>\n" ;
}








/*
echo "<pre>" ;
print_r($_SESSION["anciens"]) ;
echo "</pre>" ;
*/

require_once("inc_mysqli.php");
$cnx = connecter() ;

// Au lieu de inc_formations.php inc_promotions.php
require_once("inc_annuaire.php") ;
// Mais necessaire quand meme pour select en chaine
require_once("inc_promotions.php") ;
require_once("inc_formations.php") ;

require_once("inc_etat_dossier.php") ;
require_once("inc_pays.php") ;
require_once("inc_date.php") ;




//
// Formulaire
//
echo "<form method='post' action='/anciens/session.php'>" ;
echo "<table class='formulaire'>\n<tbody\n" ;

echo "<tr>\n" ;
echo "<th>Année&nbsp;: </th>\n" ;
echo "<td colspan='2'>" ;
echo liste_annees_anciens($cnx, "anciens_annee",
	( isset($_SESSION["anciens"]["annee"]) ? $_SESSION["anciens"]["annee"] : "" )
	) ;
echo "</td>\n</tr>\n" ;

echo "<tr>\n" ;
echo "<th>Formation : </th>\n" ;
echo "<td colspan='2'>" ;
if ( intval($_SESSION["id"]) < 4 )
{
	$req = "SELECT DISTINCT atelier.id_atelier, groupe, niveau, intitule
		FROM atelier, session
		WHERE session.id_atelier=atelier.id_atelier
		AND id_session IN
			(SELECT DISTINCT ref_session FROM dossier_anciens)
		ORDER BY groupe, niveau, intitule" ;
	$formForma = chaine_liste_formations("anciens_formation",
		( isset($_SESSION["anciens"]["formation"]) ? $_SESSION["anciens"]["formation"] : "" ),
		$req, $cnx) ;
	echo $formForma["form"] ;
	echo $formForma["script"] ;
}
else
{
	echo liste_formations_anciens($cnx, "anciens_formation",
		( isset($_SESSION["anciens"]["formation"]) ? $_SESSION["anciens"]["formation"] : "" )
		) ;
}
echo "</td>\n</tr>\n" ;

echo "<tr>\n" ;
echo "<th>Promotion : </th>\n" ;
echo "<td colspan='2' style='width: 50em;'>" ;
if ( intval($_SESSION["id"]) < 4 )
{
	$req = "SELECT id_session, annee, groupe, niveau, intitule, intit_ses
		FROM atelier, session
		WHERE session.id_atelier=atelier.id_atelier
		AND id_session IN
			(SELECT DISTINCT ref_session FROM dossier_anciens)
		ORDER BY annee DESC, groupe, niveau, intitule" ;
	$formPromo = chaine_liste_promotions("anciens_promo",
		( isset($_SESSION["anciens"]["promo"]) ? $_SESSION["anciens"]["promo"] : "" ),
		$req, $cnx) ;
	echo $formPromo["form"] ;
	echo $formPromo["script"] ;
}
else {
	echo liste_promotions_anciens($cnx, "anciens_promo",
		( isset($_SESSION["anciens"]["promo"]) ? $_SESSION["anciens"]["promo"] : "" )
		) ;
}
echo "</td>\n</tr>\n" ;

/*
echo "<tr>\n" ;
echo "<th>&Eacute;tat : </th>\n" ;
echo "<td colspan='2'>" ;
liste_etats("anciens_etat", $_SESSION["anciens"]["etat"], TRUE, TRUE) ;
echo "</td>\n" ;
echo "</tr>\n" ;
*/




echo "<tr><td colspan='3' style='padding: 1px; background: #777; height: 1px;'></td></tr>" ;




// 
echo "<tr>\n" ;
echo "<th>Genre : </th>\n" ;
echo "<td colspan='2'><select name='anciens_genre'>" ;
echo "<option value=''></option>\n" ;
echo "<option value='Femme'" ;
if ( isset($_SESSION["anciens"]["genre"]) AND ($_SESSION["anciens"]["genre"] == "Femme") ) {
	echo " selected='selected'" ;
}
echo ">Femme</option>\n" ;
echo "<option value='Homme'" ;
if ( isset($_SESSION["anciens"]["genre"]) AND ($_SESSION["anciens"]["genre"] == "Homme") ) {
	echo " selected='selected'" ;
}
echo ">Homme</option>\n" ;
echo "</td></select>\n" ;
echo "</tr>\n" ;
// 
echo "<tr>\n" ;
echo "<th>Nom : </th>\n" ;
echo "<td><input type='text' name='anciens_nom' size='20' " ;
if ( isset($_SESSION["anciens"]["nom"]) ) {
	echo "value=\"".$_SESSION["anciens"]["nom"]."\"" ;
}
echo "/></td>\n" ;
echo "<td rowspan='2' style='font-size: smaller;'>" ;
echo "<p style='margin: 0;'>La recherche est insensible à la casse <span class='aide' title='Majuscules et minuscules sont équivalentes'>?</span>
et aux caractères diacritiques <span class='aide' title='Lettres accentuées et ç cédille'>?</span>.<br />
L'astérisque «&nbsp;<code>*</code>&nbsp;» remplace 0 ou plusieurs caractères." ;
echo "</p>\n" ;
echo "</td>\n" ;
echo "</tr>\n" ;
// 
echo "<tr>\n" ;
echo "<th>Prénom : </th>\n" ;
echo "<td><input type='text' name='anciens_prenom' size='20' " ;
if ( isset($_SESSION["anciens"]["prenom"]) ) {
	echo "value=\"".$_SESSION["anciens"]["prenom"]."\"" ;
}
echo "/></td>\n" ;
echo "</tr>\n" ;
/*
//
echo "<tr>\n" ;
echo "<th>Date de naissance&nbsp;: </th>\n" ;
echo "<td colspan='2'>" ;
echo "</td>\n" ;
echo "</tr>\n" ;
//
echo "<tr>\n" ;
echo "<th>Bureau&nbsp;: </th>\n" ;
echo "<td colspan='2'>" ;
echo "</td>\n" ;
echo "</tr>\n" ;
*/
//
echo "<tr>\n" ;
echo "<th title='Date de naissance' class='help'>Naissance&nbsp;: </th>\n" ;
echo "<td>" ;
selectJour("anciens_jour_n",
	( isset($_SESSION["anciens"]["jour_n"]) ? $_SESSION["anciens"]["jour_n"] : "" )
	) ;
selectMoisNum("anciens_mois_n",
	( isset($_SESSION["anciens"]["mois_n"]) ? $_SESSION["anciens"]["mois_n"] : "" )
	) ;
selectAnneeN("anciens_annee_n",
	( isset($_SESSION["anciens"]["annee_n"]) ? $_SESSION["anciens"]["annee_n"] : "" )
	) ;
echo "</td>\n" ;
echo "<td style='font-size: smaller;'>Ne sont pris en compte qu'une date complète ou l'année.</td>\n" ;
echo "</tr>\n" ;
//
echo "<tr>\n" ;
echo "<th>Courriel : </th>\n" ;
echo "<td colspan='2'><input type='text' name='anciens_courriel' size='50' " ;
if ( isset($_SESSION["anciens"]["courriel"]) ) {
	echo "value=\"".$_SESSION["anciens"]["courriel"]."\"" ;
}
echo "/></td>\n" ;
echo "</tr>\n" ;
//
echo "<tr>\n" ;
echo "<th title='Pays de résidence' class='help'>Pays&nbsp;: </th>\n" ;
echo "<td colspan='2'>" ;
echo liste_pays_anciens($cnx, "anciens_pays",
	( isset($_SESSION["anciens"]["pays"]) ? $_SESSION["anciens"]["pays"] : "" )
	, "pays") ;
echo "</td>\n" ;
echo "</tr>\n" ;

/*
// Administrateur et service des bourses
if ( intval($_SESSION["id"]) < 2 )
{
	$BAC = "'Burundi', 'Cameroun', 'Congo', 'Congo (R.D)',
		'Gabon', 'République Centrafricaine', 'Rwanda', 'Tchad'" ;
	$BAO = "'Bénin', 'Burkina Faso', 'Cap-Vert', 'Côte d\'Ivoire', 'Guinée',
		'Guinée équatoriale', 'Mali', 'Mauritanie', 'Niger', 'Sénégal', 'Togo'" ;
	echo "<tr>\n" ;
	echo "<th>Bureau&nbsp;: </th>\n" ;
	echo "<td colspan='2'><select name='anciens_bureau'>" ;
	echo "<option value=''></option>\n" ;
	echo "<option value='BAC'" ;
	if ( $_SESSION["anciens"]["bureau"] == "BAC" ) {
		echo " selected='selected'" ;
	}
	echo ">BAC</option>\n" ;
	echo "<option value='BAO'" ;
	if ( $_SESSION["anciens"]["bureau"] == "BAO" ) {
		echo " selected='selected'" ;
	}
	echo ">BAO</option>\n" ;
	echo "<option value='Autres'" ;
	if ( $_SESSION["anciens"]["bureau"] == "Autres" ) {
		echo " selected='selected'" ;
	}
	echo ">Autres</option>\n" ;
	echo "</select></td>\n" ;
	//echo "<td style='font-size: smaller;'>" ;
	//echo "BAC : " . str_replace("'", "", $BAC) ."<br />" ;
	//echo "BAO : " . str_replace("_", "'",
	//					str_replace("'", "",
	//						str_replace("\'", "_", $BAO))) ;
	//echo "</td>\n" ;
	echo "</tr>\n" ;
}
*/

echo "<tr><td colspan='3' style='padding: 1px; background: #777; height: 1px;'></td></tr>" ;
//
echo "<tr>\n" ;
echo "<th>Trier par&nbsp;: </th>\n" ;
echo "<td colspan='2'>" ;
// Tri 1
liste_tri("anciens_tri1",
	( isset($_SESSION["anciens"]["tri1"]) ? $_SESSION["anciens"]["tri1"] : "" )
	) ;
echo ", " ;
// Tri 2
liste_tri("anciens_tri2",
	( isset($_SESSION["anciens"]["tri2"]) ? $_SESSION["anciens"]["tri2"] : "" )
	) ;
echo ", " ;
// Tri 3
liste_tri("anciens_tri3",
	( isset($_SESSION["anciens"]["tri3"]) ? $_SESSION["anciens"]["tri3"] : "" )
	) ;
echo "</td>\n" ;
echo "</tr>\n" ;
echo "<tr>\n<td colspan='3'>" ;
echo "<p class='c'>
<span style='font-size: smaller; padding-right: 1em;'><a href='reinitialiser.php'>Réinitialiser</a></span>\n" ;
if ( isset($_SESSION["anciens_precedente"]) ) {
	echo "<span style='font-size: smaller; padding-right: 1em;'><a href='precedente.php'>Recherche précédente</a></span>\n" ;
}
echo "<input type='submit' value='Rechercher' />\n" ;
echo "</p>\n" ;
echo "</td>\n</tr>\n" ;
echo "</tbody>\n</table>\n" ;
echo "</form>\n" ;

if ( 
	( isset($_SESSION["anciens"]["ok"]) AND ($_SESSION["anciens"]["ok"] == "ok") )
	OR ( isset($_GET["page"]) )
	)
{
	$req = " FROM anciens
		LEFT JOIN ref_pays ON anciens.pays=ref_pays.code
		LEFT JOIN dossier_anciens ON
		anciens.id_ancien=dossier_anciens.ref_ancien
		WHERE TRUE " ;
	if ( intval($_SESSION["id"]) > 3 ) {
		$req .= " AND ref_session IN (".$_SESSION["liste_toutes_promotions"].")" ;
	}
	// FIXME filtrer d'abord les id_anciens par promotion pour les selctionneurs
	//
	//
	// Année
	if ( isset($_SESSION["anciens"]["annee"]) ) {
		$req .= " AND ref_session IN
			(SELECT id_session FROM session
			WHERE annee=".$_SESSION["anciens"]["annee"].") " ;
	}
	// Formation
	if ( isset($_SESSION["anciens"]["formation"]) AND ($_SESSION["anciens"]["formation"]!=0) ) {
		$req .= " AND dossier_anciens.ref_atelier=".$_SESSION["anciens"]["formation"] ;
	}
	// Promotion
	if ( isset($_SESSION["anciens"]["promo"]) AND ($_SESSION["anciens"]["promo"]!=0) ) {
		$req .= " AND dossier_anciens.ref_session=".$_SESSION["anciens"]["promo"] ;
	}
	// Etat
	if ( isset($_SESSION["anciens"]["etat"]) ) {
		if ( $_SESSION["anciens"]["etat"] == "imputable") {
			$req .= " AND etat_dossier IN ('Allocataire', 'Payant', 'Allocataire SCAC')" ;
		}
		else {
			$req .= " AND etat_dossier='".$_SESSION["anciens"]["etat"]."'" ;
		}
	}
	// Genre
	if ( isset($_SESSION["anciens"]["genre"]) ) {
		if ( $_SESSION["anciens"]["genre"] == "Homme" ) {
			$req .= " AND civilite='Monsieur'" ;
		}
		else if ( $_SESSION["anciens"]["genre"] == "Femme" ) {
			$req .= " AND civilite IN ('Madame', 'Mademoiselle')" ;
		}
	}
	// Nom
	if ( isset($_SESSION["anciens"]["nom"]) ) {
		$req .= " AND anciens.nom LIKE '";
		$req .= str_replace("*", "%", mysqli_real_escape_string($cnx, $_SESSION["anciens"]["nom"])) ;
		$req .= "'" ;
	}
	// Prénom
	if ( isset($_SESSION["anciens"]["prenom"]) ) {
		$req .= " AND prenom LIKE '";
		$req .= str_replace("*", "%", mysqli_real_escape_string($cnx, $_SESSION["anciens"]["prenom"])) ;
		$req .= "'" ;
	}
	// Naissance
	if ( 
		isset($_SESSION["anciens"]["annee_n"]) 
		AND isset($_SESSION["anciens"]["mois_n"]) 
		AND isset($_SESSION["anciens"]["jour_n"]) 
	) {
		$req .= " AND naissance='"
			. $_SESSION["anciens"]["annee_n"]
			 ."-"
			. $_SESSION["anciens"]["mois_n"]
			 ."-"
			. $_SESSION["anciens"]["jour_n"]
			."'" ;
	}
	else if ( isset($_SESSION["anciens"]["annee_n"]) ) {
		$req .= " AND naissance>='"
			. $_SESSION["anciens"]["annee_n"]."-01-01'"
			. " AND naissance<='"
			. $_SESSION["anciens"]["annee_n"]."-12-31'" ;
	}
	// Courriel
	if ( isset($_SESSION["anciens"]["courriel"]) ) {
		$req .= " AND courriel='".mysqli_real_escape_string($cnx, $_SESSION["anciens"]["courriel"])."'" ;
	}
	// Pays
	if ( isset($_SESSION["anciens"]["pays"]) ) {
		$req .= " AND pays='".$_SESSION["anciens"]["pays"]."'" ;
	}
	//
	//
	// Tri
	$order = "" ;
	if ( isset($_SESSION["anciens"]["tri1"]) ) {
		$order .= " ORDER BY ".$_SESSION["anciens"]["tri1"] ;
		if ( isset($_SESSION["anciens"]["tri2"]) ) {
			$order .= ", ".$_SESSION["anciens"]["tri2"] ;
			if ( isset($_SESSION["anciens"]["tri3"]) ) {
				$order .= ", ".$_SESSION["anciens"]["tri3"] ;
			}
		}
	}

	$req_count = "SELECT COUNT(DISTINCT anciens.id_ancien) $req" ;
//	echo $req_count ;
	$res_count = mysqli_query($cnx, $req_count) ;
	$row_count = mysqli_fetch_row($res_count) ;
	$Nd = intval($row_count[0]) ;
	
	$req_count = "SELECT COUNT(anciens.id_ancien) $req" ;
	//echo $req_count ;
	$res_count = mysqli_query($cnx, $req_count) ;
	$row_count = mysqli_fetch_row($res_count) ;
	$N = intval($row_count[0]) ;

	if ( $N == 0 ) {
		echo "<p class='c'><strong>Aucun ancien pour ces critères</strong>.</p>" ;
	}
	else
	{
		echo "<p class='c'><strong>$Nd anciens</strong> ($N diplômes)</p>\n" ;

		define("NB_PAR_PAGE", 30) ;
		$nbPages = intval($N / NB_PAR_PAGE) ;
		if ( ($N % NB_PAR_PAGE) != 0 ) {
			$nbPages++ ;
		}

		if ( $nbPages > 1 ) {
			if ( !isset($_GET["page"]) ) {
				$start = "" ;
			}
			else {
				$start = ( intval($_GET["page"]) -1 ) * NB_PAR_PAGE ;
				$start = strval($start).", " ;
			}
		}
		else {
			$start = "" ;
		}

		$nav = "" ;
		if ( $nbPages > 1 )
		{
			if ( !isset($_GET["page"]) ) {
				$_GET["page"] = 1 ;
			}
			$page = intval($_GET["page"]) ;
			$nav  = "<p class='c pagination'>" ;
			if ( $page > 2 ) {
				$nav .= "<a href='?page=1'>" ;
				$nav .= "<img src='/img/pagination/premiere.gif' " ;
				$nav .= "alt='Première page' title='Première page' " ;
				$nav .= "width='39' height='30' /></a>" ;
			}
			if ( $page > 1 ) {
				$nav .= "<a href='?page=".strval($page -1)."'>" ;
				$nav .= "<img src='/img/pagination/precedente.gif' " ;
				$nav .= "alt='Page précédente' title='Page précédente' " ;
				$nav .= "width='30' height='30' /></a>" ;
			}
			$nav .= "<strong>Page ". $page ." / ".$nbPages."</strong>" ;
			if ( $page < $nbPages) {
				$nav .= " <a href='?page=".strval($page +1)."'>" ;
				$nav .= "<img src='/img/pagination/suivante.gif' " ;
				$nav .= "alt='Page suivante' title='Page suivante' " ;
				$nav .= "width='30' height='30' /></a>" ;
			}
			if ( $page < ($nbPages - 1) ) {
				$nav .= " <a href='?page=".$nbPages."'>" ;
				$nav .= "<img src='/img/pagination/derniere.gif' " ;
				$nav .= "alt='Dernière page' title='Dernière page' " ;
				$nav .= "width='39' height='30' /></a>" ;
			}
			$nav .= "</p>\n" ;
		}

		echo $nav ;

		$req_select = "SELECT DISTINCT anciens.*, ref_pays.nom AS nom_pays $req $order LIMIT $start "
			.NB_PAR_PAGE ;
//		echo $req_select ;
		$res =  mysqli_query($cnx, $req_select) ;	
		while ( $row = mysqli_fetch_assoc($res) )
		{
			echo "<div class='ancien'>\n" ;
			afficheAncien($row["id_ancien"], $cnx, TRUE, TRUE, FALSE, FALSE) ;
			echo "</div>\n" ;
		}

		echo $nav ;
	}
	
	
	unset($_SESSION["anciens"]["ok"]) ;
}
deconnecter($cnx) ;
echo $end ;
?>
