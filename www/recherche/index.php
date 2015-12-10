<?php
include_once("inc_session.php") ;
include_once("inc_resultat.php") ;

function resultat_recherche($T)
{
	global $etat_dossier_img_class ;
	$RESULTAT = tab_resultats() ;
	$RESULTAT_IMG_CLASS = tab_resultats_img_class() ;

	$res = "<div class='res'>" ;

	// Pays, naissance
	$res .= "<div style='float: right'>\n" ;
		$res .= $T["nom_pays"] ;
		//$res .= " <sup class='m'><a class='critere' href=\"critere.php?pays=".rawurlencode($T["pays"])."\">+</a></sup>" ;

		if ( ($T["naissance"] != "0000-00-00") AND ($T["pays"] != "") ) {
			$res .= " <span class='sep'>-</span> " ;
		}

		$res .=  mysql2datenum($T["naissance"]) ;
		if ( ($_SESSION["id"] == "00") AND ($T["naissance"] != "0000-00-00") ) {
			$res .= " <sup class='m'><a class='critere' href=\"critere.php?naissance=".rawurlencode($T["naissance"])."\">+</a></sup>" ;
		}
	$res .= "</div>\n" ;

	// Civilite, nom, prenom
	$res .= "<div>" ;
	$res .= $T["civilite"] ;
	$res .= " <span class='majuscules'><em>" ;
	$res .= strtoupper($T["nom"]) ;
	$res .= "</em></span> " ;
	$res .= ucwords(strtolower($T["prenom"])) ;
	if ( ( trim($T["nom_jf"]) != "" ) AND ( $T["nom"] != $T["nom_jf"] ) ) {
		$res .= " <i>née</i> " ;
		$res .= " <span class='majuscules'><em>" ;
		$res .= strtoupper($T["nom_jf"]) ;
		$res .= "</em></span> " ;
	}
	$res .= " <span class='sep'>-</span> " ;
	$res .= $T["email1"] ;

	// Envoi mail
	if ( intval($_SESSION["id"]) < 1 ) {
		$res .= " <sup><a  class='critere' href='email.php?id_dossier=" ;
		$res .= $T["id_dossier"] ;
		$res .= "' title='Corriger cette adresse email et/ou envoyer un rappel de numéro de dossier et de mot de passe à cette adresse.' class='help'>" ;
		$res .= "@</a></sup>" ;
		}
	$res .= "</div>" ;

	// Année, formation
	$res .= "<div>" ;
	$res .= "<strong>".$T["annee"]."</strong>" ;
	$res .= " <span class='sep'>-</span> " ;
	$res .= "<span class='c ".$etat_dossier_img_class[$T["etat_dossier"]]."'>" ;
	$res .= $T["etat_dossier"] . "</span>" ;
	if ( strval($T["id_imputation1"]) != "" ) {
		$res .= ", <span class='paye'>".LABEL_INSCRIT."</span>" ;
	}
	if ( strval($T["id_imputation2"]) != "" ) {
		$res .= ", <span class='paye'>".LABEL_INSCRIT_2."</span>" ;
		if ( $T["etat2"] != $T["etat_dossier"] ) {
			$res .= " (<span class='small ".$etat_dossier_img_class[$T["etat2"]]."'>" ;
			$res .= $T["etat2"] ;
			$res .= "</span>)" ;
		}
	}
	// Diplome
	/*
	if ( $T["diplome"] == "Oui" ) {
		$res .= ", <span class='diplome'>".LABEL_DIPLOME." ".$T["anneed"]."</span>" ;
	}
	*/
	if ( $T["resultat"] != "0" ) {
		$res .= ", <span class='".$RESULTAT_IMG_CLASS[$T["resultat"]]."'>"
		. $RESULTAT[$T["resultat"]]
		. "</span>" ;
	}

	$res .= " <span class='sep'>-</span> " ;
	$res .= $T["intitule"] ;
	$res .= " (".$T["intit_ses"].")" ;
	$res .= "</div>" ;

	//
	// Diplome puis résultat
	// Pour administrateur et sélectionneurs
	if ( (intval($_SESSION["id"]) < 1) OR (intval($_SESSION["id"]) > 9) ) {
		if	(
				($T["etat_dossier"]=="Allocataire")
				OR ($T["etat_dossier"]=="Payant")
				OR ($T["etat_dossier"]=="Payant Nord")
				OR ($T["etat_dossier"]=="Payant établissement")
				OR ($T["etat_dossier"]=="Allocataire SCAC")
				OR ($T["etat_dossier"]=="Externe")
			)
		{
			if ( $T["resultat"] == "0" ) {
				$res .= "<div style='float: right'>\n" ;
				$res .= "<a target='_blank' " ;
				$res .= "href='/inscrits/reinitialiser.php?id_session=".$T["id_session"]."&nom=".urlencode($T["nom"])."'>" ;
				$res .= LIEN_RESULTAT ;
				$res .= "</a>" ;
				$res .= "</div>\n" ;
			}
		}
	}
	

	// Candidature, imputation
	$res .= "<div>" ;
	$lien_candidature = "<a target='_blank' href='/candidatures/candidature.php?id_dossier=".$T["id_dossier"]."'>Candidature</a>" ;
	$lien_dossier = "<a target='_blank' href='/candidatures/autre.php?id_dossier=".$T["id_dossier"]."'>Voir dossier</a>" ;

	// Candidature / Voir dossier
	if ( $T["evaluations"] == "Oui" ) {
		$res .= "<strong>$lien_candidature</strong>" ;
	}
	else {
		$res .= $lien_dossier ;
	}

	//
	// Imputation / Imputé
	//
	if	(
			( intval($_SESSION["id"]) < 3 )
			AND (
				( $T["etat_dossier"] == 'Allocataire' )
				OR ( $T["etat_dossier"] == 'Payant' )
				OR ( $T["etat_dossier"] == 'Allocataire SCAC' )
			)
		)
	{
		$imputable = TRUE ;
	}
	else {
		$imputable = FALSE ;
	}
	// 1ère année
	if ( strval($T["id_imputation1"]) == "" )
	{
		if 	( $imputable AND ($T["imputations"] == 'Oui') )
		{
			$res .= " <span class='sepp'>-</span> " ;
			$res .= "<a target='_blank' href='/imputations/imputer.php?id_dossier=" ;
			$res .= $T["id_dossier"] ;
			$res .= "'><strong>". LIEN_IMPUTER ."</strong></a>" ;
		}
	}
	else
	{
		$res .= " <span class='sepp'>-</span> " ;
		$res .= "<a target='_blank' href='/imputations/attestation.php?id=" ;
		$res .= $T["id_imputation1"] ;
		if ( $T["imputations"] == 'Oui' ) {
			$res .= "'><strong>".LIEN_IMPUTATION."</strong></a>" ;
		}
		else {
			$res .= "'>".LIEN_IMPUTATION."</a>" ;
		}
	}
	// 2ème année
	if ( strval($T["id_imputation2"]) == "" )
	{
		if ( $imputable AND ($T["imputations2"] == 'Oui') AND ($T["nb_annees"] == '2') )
		{
			$res .= " <span class='sepp'>-</span> " ;
			$res .= "<a target='_blank' href='/imputations/imputer.php?id_dossier=" ;
			$res .= $T["id_dossier"] . "&amp;annee_relative=2" ;
			$res .= "'><strong>". LIEN_IMPUTER_2 ."</strong></a>" ;
		}
	}
	else
	{
		$res .= " <span class='sepp'>-</span> " ;
		$res .= "<a target='_blank' href='/imputations/attestation.php?id=" ;
		$res .= $T["id_imputation2"] ;
		if ( ($T["imputations2"] == 'Oui') AND ($T["nb_annees"] == '2') ) {
			$res .= "'><strong>".LIEN_IMPUTATION_2."</strong></a>" ;
		}
		else {
			$res .= "'>".LIEN_IMPUTATION_2."</a>" ;
		}
	}


	// Ancien
	/*
	if ( $T["ref_ancien"] != 0 ) {
		$res .= " <span class='sepp'>-</span> " ;
		$res .= "<a target='_blank' href='/anciens/ancien.php?id_ancien=" ;
		$res .= $T["ref_ancien"] ;
		$res .= "'>".LIEN_ANCIEN."</a>" ;
	}
	*/
	if ( $T["resultat"] != "0" ) {
		$res .= " <span class='sepp'>-</span> " ;
		$res .= "<a target='_blank' " ;
		$res .= "href='/inscrits/reinitialiser.php?id_session=".$T["id_session"]."&nom=".urlencode($T["nom"])."'>" ;
		$res .= LIEN_ANCIEN ;
		$res .= "</a>" ;
	}


	$res .= "</div>" ;

	$res .= "</div>\n\n" ; // class='res'

//	print_r($T) ;
	echo $res ;
}

function liste_tri($name, $value)
{
	$TRI = array(
		"" => "",
		"session.annee DESC" => "Année",
		"atelier.id_atelier" => "Formation",
		"etat_dossier" => "&Eacute;tat du dossier",
		"nom" => "Nom",
		"naissance" => "Date de naissance",
		"email1" => "Courriel",
		"nom_pays" => "Pays de résidence",
		"dossier.date_maj DESC" => "Date de mise à jour des candidatures",
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

$titre = "Recherche" ;
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

/*
echo "<pre>" ;
print_r($_SESSION["filtres"]["recherche"]) ;
echo "</pre>" ;
*/

require_once("inc_mysqli.php");
$cnx = connecter() ;

require_once("inc_formations.php") ;
require_once("inc_promotions.php") ;
require_once("inc_etat_dossier.php") ;
require_once("inc_pays.php") ;
require_once("inc_date.php") ;




//
// Formulaire
//
echo "<form method='post' action='/recherche/session.php'>" ;
echo "<table class='formulaire'>\n<tbody\n" ;
// 
echo "<tr>\n" ;
echo "<th>Année&nbsp;: </th>\n" ;
echo "<td colspan='2'><select name='rechercher_annee'>\n" ;
echo "<option value=''></option>\n" ;
$req = "SELECT DISTINCT(annee) FROM session " ;
if ( intval($_SESSION["id"]) > 3 ) {
	$req .= " WHERE id_session IN (".$_SESSION["liste_toutes_promotions"].")" ;
}
$req .= " ORDER BY annee DESC" ;
$res = mysqli_query($cnx, $req) ;
while ( $enr = mysqli_fetch_assoc($res) )
{
	echo "<option value='".$enr["annee"]."'" ;
	if ( isset($_SESSION["filtres"]["recherche"]["annee"]) AND ($_SESSION["filtres"]["recherche"]["annee"] == $enr["annee"]) ) {
		echo " selected='selected'" ;
	}
	echo ">".$enr["annee"]."</option>" ;
}
echo "</select></td>\n" ;
echo "</tr>\n" ;
// 

if ( intval($_SESSION["id"]) < 9 )
{
	require_once("inc_institutions.php") ;
	echo "<tr>\n" ;
	echo "<th>Institution : </th>\n" ;
	echo "<td colspan='2'>" ;
	liste_institutions($cnx, "rechercher_ref_institution",
		( isset($_SESSION["filtres"]["recherche"]["ref_institution"]) ? $_SESSION["filtres"]["recherche"]["ref_institution"] : "" ),
		"formations"
		) ;
	echo "</td>\n" ;
	echo "</tr>\n" ;

	echo "<tr>\n" ;
	echo "<th>Formation : </th>\n" ;
	echo "<td colspan='2'>" ;
	$formForma = chaine_liste_formations("rechercher_formation",
		( isset($_SESSION["filtres"]["recherche"]["formation"]) ? $_SESSION["filtres"]["recherche"]["formation"] : "" ),
		"", $cnx) ;
	echo $formForma["form"] ;
	echo $formForma["script"] ;
	echo "</td>\n" ;
	echo "</tr>\n" ;
}
else
{
	echo "<tr>\n" ;
	echo "<th>Formation : </th>\n" ;
	echo "<td colspan='2'>" ;
	liste_formations($cnx,
		"rechercher_formation",
		( isset($_SESSION["filtres"]["recherche"]["formation"]) ? $_SESSION["filtres"]["recherche"]["formation"] : "" ),
		( isset($_SESSION["liste_toutes_promotions"]) ? $_SESSION["liste_toutes_promotions"] : "" )) ;
	echo "</td>\n" ;
	echo "</tr>\n" ;
}


echo "<tr>\n" ;
echo "<th>Promotion : </th>\n" ;
echo "<td colspan='2' style='width: 50em;'>" ;
if ( intval($_SESSION["id"]) < 9 )
{
	$req = "SELECT id_session, annee, groupe, niveau, intitule, intit_ses
		FROM atelier, session
		WHERE session.id_atelier=atelier.id_atelier
		ORDER BY annee DESC, groupe, niveau, intitule" ;
	$formPromo = chaine_liste_promotions("rechercher_promo",
		( isset($_SESSION["filtres"]["recherche"]["promo"]) ? $_SESSION["filtres"]["recherche"]["promo"] : "" ),
		$req, $cnx) ;
	echo $formPromo["form"] ;
	echo $formPromo["script"] ;
}
else {
	echo liste_promotions("rechercher_promo",
		( isset($_SESSION["filtres"]["recherche"]["promo"]) ? $_SESSION["filtres"]["recherche"]["promo"] : "" ),
		$cnx, TRUE) ;
}
echo "</td>\n</tr>\n" ;




// Ajout de l'état externe pôur les statistiques et la recherche
$etat_dossier[] = "Externe" ;
// 
echo "<tr>\n" ;
echo "<th>&Eacute;tat : </th>\n" ;
echo "<td colspan='2'>" ;
if ( isset($_SESSION["filtres"]["recherche"]["etat"]) ) {
	liste_etats("rechercher_etat", $_SESSION["filtres"]["recherche"]["etat"], TRUE, TRUE, TRUE) ;
}
else {
	liste_etats("rechercher_etat", "", TRUE, TRUE, TRUE) ;
}
echo "</td>\n" ;
echo "</tr>\n" ;
// 
//if ( intval($_SESSION["id"]) < 3 ) {
	echo "<tr>\n" ;
	echo "<th>Imputation : </th>\n" ;
	echo "<td><select name='rechercher_imputation'>\n" ;
	echo "<option value=''></option>\n" ;
	echo "<option value='Impute'" ;
	if ( isset($_SESSION["filtres"]["recherche"]["imputation"]) AND ($_SESSION["filtres"]["recherche"]["imputation"] == "Impute") ) {
		echo " selected='selected'" ;
	}
	echo ">Imputé</option>\n" ;
	echo "<option value='A imputer'" ;
	if ( isset($_SESSION["filtres"]["recherche"]["imputation"]) AND ($_SESSION["filtres"]["recherche"]["imputation"] == "A imputer") ) {
		echo " selected='selected'" ;
	}
	echo ">À imputer</option>\n" ;
	echo "</select></td>\n" ;
	echo "<td style='font-size: smaller;'>À partir de 2006 seulement.
	Concerne les états «&nbsp;<span class='allocataire'>Allocataire</span>&nbsp;», «&nbsp;<span class='payant'>Payant</span>&nbsp;» et «&nbsp;<span class='scac'>Allocataire SCAC</span>&nbsp;».</td>\n" ;
echo "</tr>\n" ;
//}
// 
/*
	echo "<tr>\n" ;
	echo "<th>Diplomé : </th>\n" ;
	echo "<td colspan='2'><select name='rechercher_diplome'>\n" ;
	echo "<option value=''></option>\n" ;
	echo "<option value='1'" ;
	if ( isset($_SESSION["filtres"]["recherche"]["diplome"]) AND ($_SESSION["filtres"]["recherche"]["diplome"] == "1") ) {
		echo " selected='selected'" ;
	}
	echo ">Diplomé</option>\n" ;
	echo "<option value='0'" ;
	if ( isset($_SESSION["filtres"]["recherche"]["diplome"]) AND ($_SESSION["filtres"]["recherche"]["diplome"] == "0") ) {
		echo " selected='selected'" ;
	}
	echo ">Non diplomé</option>\n" ;
	echo "</select></td>\n" ;
	echo "</tr>\n" ;
*/
//
	echo "<tr>\n" ;
	echo "<th>Résultat : </th>\n" ;
	echo "<td colspan='2'>" ;
	liste_resultats("rechercher_resultat",
		( isset($_SESSION["filtres"]["recherche"]["resultat"]) ? $_SESSION["filtres"]["recherche"]["resultat"] : "" ),
		TRUE) ;
	echo "</td>\n</tr>\n" ;

//
echo "<tr><td colspan='3' style='padding: 1px; background: #777; height: 1px;'></td></tr>" ;
// 
echo "<tr>\n" ;
echo "<th>Genre : </th>\n" ;
echo "<td colspan='2'><select name='rechercher_genre'>" ;
echo "<option value=''></option>\n" ;
echo "<option value='Femme'" ;
if ( isset($_SESSION["filtres"]["recherche"]["genre"]) AND ($_SESSION["filtres"]["recherche"]["genre"] == "Femme") ) {
	echo " selected='selected'" ;
}
echo ">Femme</option>\n" ;
echo "<option value='Homme'" ;
if ( isset($_SESSION["filtres"]["recherche"]["genre"]) AND ($_SESSION["filtres"]["recherche"]["genre"] == "Homme") ) {
	echo " selected='selected'" ;
}
echo ">Homme</option>\n" ;
echo "</td></select>\n" ;
echo "</tr>\n" ;
// 
echo "<tr>\n" ;
echo "<th>Nom (de jeune fille) : </th>\n" ;
echo "<td><input type='text' name='rechercher_nom' size='20' " ;
if ( isset($_SESSION["filtres"]["recherche"]["nom"]) ) {
	echo "value=\"".$_SESSION["filtres"]["recherche"]["nom"]."\"" ;
}
echo "/></td>\n" ;
echo "<td rowspan='2' style='font-size: smaller;'>" ;
// Rechercher «&nbsp;<code>A*B*</code>&nbsp;» trouve «&nbsp;AB&nbsp;», «&nbsp;AARIBA&nbsp;» ou «&nbsp;ABAH&nbsp;».</p>\n" ;
//echo "<p style='margin-bottom: 0;'>Majuscules et minuscules sont équivalentes, et les accents et la cédille ne sont pas pris en compte&nbsp;:<br />
//Rechercher «&nbsp;Aérça&nbsp;» ou «&nbsp;aerca&nbsp;» donne les mêmes résutats.</p>\n" ;
//echo "<p><span class='erreur'>Attention</span>, les noms peuvent contenir 1 ou plusieurs espaces.</p>\n" ;
function exemple($nom, $first=FALSE)
{
	if ( !$first ) {
		echo "&nbsp;; " ;
	}
	echo "<a href=\"exemple.php?nom=".rawurlencode($nom)."\"><code>".str_replace(" ", "&nbsp;", $nom)."</code></a>" ;
}
echo "<p style='margin: 0;'>La recherche est insensible à la casse <span class='aide' title='Majuscules et minuscules sont équivalentes'>?</span>
et aux caractères diacritiques <span class='aide' title='Lettres accentuées et ç cédille'>?</span>.<br />
L'astérisque «&nbsp;<code>*</code>&nbsp;» remplace 0 ou plusieurs caractères.
<br />Exemple : «&nbsp;<code>e*</code>&nbsp;» recherche les noms commençant par
<code>E</code> ou
<code>É</code> ou
<code>È</code> ou
<code>Ê</code>." ;
/*
exemple("a*", TRUE) ;
exemple("é*") ;
exemple("*é") ;
exemple("A*B*") ;
echo ".<br />Attention aux espaces : " ;
exemple("* *", TRUE) ;
exemple("*  *") ;
exemple("a* *") ;
exemple("*a* *") ;
echo "<br />et aux cas particuliers&nbsp;:&nbsp;" ;
exemple("*.*", TRUE) ;
*/
//exemple("*/*") ;

echo "</p>\n" ;
echo "</td>\n" ;
echo "</tr>\n" ;
// 
echo "<tr>\n" ;
echo "<th>Prénom : </th>\n" ;
echo "<td><input type='text' name='rechercher_prenom' size='20' " ;
if ( isset($_SESSION["filtres"]["recherche"]["prenom"]) ) {
	echo "value=\"".$_SESSION["filtres"]["recherche"]["prenom"]."\"" ;
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
selectJour("rechercher_jour_n",
	( isset($_SESSION["filtres"]["recherche"]["jour_n"]) ? $_SESSION["filtres"]["recherche"]["jour_n"] : "" )
) ;
selectMoisNum("rechercher_mois_n",
	( isset($_SESSION["filtres"]["recherche"]["mois_n"]) ? $_SESSION["filtres"]["recherche"]["mois_n"] : "" )
) ;
selectAnneeN("rechercher_annee_n",
	( isset($_SESSION["filtres"]["recherche"]["annee_n"]) ? $_SESSION["filtres"]["recherche"]["annee_n"] : "" )
) ;
echo "</td>\n" ;
echo "<td style='font-size: smaller;'>Ne sont pris en compte qu'une date complète ou l'année.</td>\n" ;
echo "</tr>\n" ;
//
echo "<tr>\n" ;
echo "<th title='Adresse électronique' class='help'>Courriel&nbsp;: </th>\n" ;
echo "<td colspan='2'><input type='text' name='rechercher_email' size='50' " ;
if ( isset($_SESSION["filtres"]["recherche"]["email"]) ) {
	echo "value=\"".$_SESSION["filtres"]["recherche"]["email"]."\"" ;
}
echo "</td>\n" ;
echo "</tr>\n" ;
//
echo "<tr>\n" ;
echo "<th title='Pays de résidence' class='help'>Pays de résidence&nbsp;: </th>\n" ;
echo "<td colspan='2'>" ;
//liste_pays("rechercher_pays", ( isset($_SESSION["filtres"]["recherche"]["pays"]) ? $_SESSION["filtres"]["recherche"]["pays"] : "" ), TRUE) ;
echo selectPays($cnx, "rechercher_pays",
	( isset($_SESSION["filtres"]["recherche"]["pays"]) ? $_SESSION["filtres"]["recherche"]["pays"] : "" )
	) ;
echo "</td>\n" ;
echo "</tr>\n" ;
//
// Administrateur et service des bourses
if ( intval($_SESSION["id"]) < 2 )
{
	echo "<tr>\n" ;
	echo "<th>Région de résidence&nbsp;: </th>\n" ;
	echo "<td colspan='2'>" ;
	echo selectRegion($cnx, "rechercher_region",
		( isset($_SESSION["filtres"]["recherche"]["region"]) ? $_SESSION["filtres"]["recherche"]["region"] : "" )
		) ;
	echo "</td>\n" ;
	echo "</tr>\n" ;
/*
	$BAC = "'Burundi', 'Cameroun', 'Congo', 'Congo (R.D)',
		'Gabon', 'République Centrafricaine', 'Rwanda', 'Tchad'" ;
	$BAO = "'Bénin', 'Burkina Faso', 'Cap-Vert', 'Côte d\'Ivoire', 'Guinée',
		'Guinée équatoriale', 'Mali', 'Mauritanie', 'Niger', 'Sénégal', 'Togo'" ;
	echo "<select name='rechercher_bureau'>" ;
	echo "<option value=''></option>\n" ;
	echo "<option value='BAC'" ;
	if ( isset($_SESSION["filtres"]["recherche"]["bureau"]) AND ($_SESSION["filtres"]["recherche"]["bureau"] == "BAC") ) {
		echo " selected='selected'" ;
	}
	echo ">BAC</option>\n" ;
	echo "<option value='BAO'" ;
	if ( isset($_SESSION["filtres"]["recherche"]["bureau"]) AND ($_SESSION["filtres"]["recherche"]["bureau"] == "BAO") ) {
		echo " selected='selected'" ;
	}
	echo ">BAO</option>\n" ;
	echo "<option value='Autres'" ;
	if ( isset($_SESSION["filtres"]["recherche"]["bureau"]) AND ($_SESSION["filtres"]["recherche"]["bureau"] == "Autres") ) {
		echo " selected='selected'" ;
	}
	echo ">Autres</option>\n" ;
	echo "</select>" ;
*/
/*
	echo "<td style='font-size: smaller;'>" ;
	echo "BAC : " . str_replace("'", "", $BAC) ."<br />" ;
	echo "BAO : " . str_replace("_", "'",
						str_replace("'", "",
							str_replace("\'", "_", $BAO))) ;
	echo "</td>\n" ;
*/
}
//
echo "<tr><td colspan='3' style='padding: 1px; background: #777; height: 1px;'></td></tr>" ;
//
echo "<tr>\n" ;
echo "<th>Trier par&nbsp;: </th>\n" ;
echo "<td colspan='2'>" ;
// Tri 1
liste_tri("rechercher_tri1",
	(isset($_SESSION["filtres"]["recherche"]["tri1"]) ? $_SESSION["filtres"]["recherche"]["tri1"] : "")
) ;
echo ", " ;
// Tri 2
liste_tri("rechercher_tri2",
	(isset($_SESSION["filtres"]["recherche"]["tri2"]) ? $_SESSION["filtres"]["recherche"]["tri2"] : "")
) ;
echo ", " ;
// Tri 3
liste_tri("rechercher_tri3",
	(isset($_SESSION["filtres"]["recherche"]["tri3"]) ? $_SESSION["filtres"]["recherche"]["tri3"] : "")
) ;
echo "</td>\n" ;
echo "</tr>\n" ;

echo "</tr>\n<td colspan='3'>" ;
echo "<p class='c'>" ;
echo "<span style='font-size: smaller; padding-right: 1em;'><a href='reinitialiser.php'>Réinitialiser</a></span>\n" ;
if ( isset($_SESSION["filtres"]["recherche_precedente"]) ) {
	echo "<span style='font-size: smaller; padding-right: 1em;'><a href='precedente.php'>Recherche précédente</a></span>\n" ;
}
echo "<input type='submit' value='Rechercher' />\n" ;
echo "</p>\n" ;
echo "</td></tr>\n" ;

echo "</tbody>\n</table>\n" ;
echo "</form>" ;

if ( 
	( isset($_SESSION["filtres"]["recherche"]["ok"]) AND ($_SESSION["filtres"]["recherche"]["ok"] == "ok") )
	OR ( isset($_GET["page"]) )
	)
{
	$subselects = "	(SELECT id_imputation FROM imputations WHERE ref_dossier=id_dossier AND annee_relative=1)
			AS id_imputation1,
			(SELECT id_imputation FROM imputations WHERE ref_dossier=id_dossier AND annee_relative=2)
			AS id_imputation2,
			(SELECT etat FROM imputations WHERE ref_dossier=id_dossier AND annee_relative=2)
			AS etat2 " ;
	$req = "FROM atelier, session, candidat LEFT JOIN ref_pays ON candidat.pays=ref_pays.code
		LEFT JOIN ref_region ON ref_pays.region=ref_region.id,
		dossier
		LEFT JOIN dossier_anciens ON dossier.id_dossier=dossier_anciens.ref_dossier
		WHERE atelier.id_atelier=session.id_atelier
		AND session.id_session=dossier.id_session
		AND dossier.id_candidat=candidat.id_candidat" ;
	if ( intval($_SESSION["id"]) > 4 ) {
		$req = "FROM atelier, atxsel, session, candidat
			LEFT JOIN ref_pays ON candidat.pays=ref_pays.code
			LEFT JOIN ref_region ON ref_pays.region=ref_region.id,
			dossier
			LEFT JOIN dossier_anciens ON dossier.id_dossier=dossier_anciens.ref_dossier
			WHERE atelier.id_atelier=atxsel.id_atelier
			AND atxsel.id_sel=".$_SESSION["id"]."
			AND atelier.id_atelier=session.id_atelier
			AND session.id_session=dossier.id_session
			AND dossier.id_candidat=candidat.id_candidat" ;
		
	}
	//
	//
	// Année
	if ( isset($_SESSION["filtres"]["recherche"]["annee"]) ) {
		$req .= " AND session.annee=".$_SESSION["filtres"]["recherche"]["annee"] ;
	}
	// Institution
	if ( isset($_SESSION["filtres"]["recherche"]["ref_institution"]) AND ($_SESSION["filtres"]["recherche"]["ref_institution"]!=0) ) {
		$req .= " AND atelier.ref_institution=".$_SESSION["filtres"]["recherche"]["ref_institution"] ;
	}
	// Formation
	if ( isset($_SESSION["filtres"]["recherche"]["formation"]) AND ($_SESSION["filtres"]["recherche"]["formation"]!=0) ) {
		$req .= " AND atelier.id_atelier=".$_SESSION["filtres"]["recherche"]["formation"] ;
	}
	// Promotion
	if ( isset($_SESSION["filtres"]["recherche"]["promo"]) AND ($_SESSION["filtres"]["recherche"]["promo"]!=0) ) {
		$req .= "  AND dossier.id_session=".$_SESSION["filtres"]["recherche"]["promo"] ;
	}
	// Etat
	if ( isset($_SESSION["filtres"]["recherche"]["etat"]) ) {
		if ( $_SESSION["filtres"]["recherche"]["etat"] == "imputable") {
			$req .= " AND etat_dossier IN ('Allocataire', 'Payant', 'Allocataire SCAC')" ;
		}
		else if ( $_SESSION["filtres"]["recherche"]["etat"] == "inscrit") {
			$req .= " AND etat_dossier IN ('Allocataire', 'Payant', 'Allocataire SCAC', 'Payant établissement')" ;
		}
		else {
			$req .= " AND etat_dossier='".$_SESSION["filtres"]["recherche"]["etat"]."'" ;
		}
	}
	//
	//
	// Genre
	if ( isset($_SESSION["filtres"]["recherche"]["genre"]) ) {
		if ( $_SESSION["filtres"]["recherche"]["genre"] == "Homme" ) {
			$req .= " AND candidat.civilite='Monsieur'" ;
		}
		else if ( $_SESSION["filtres"]["recherche"]["genre"] == "Femme" ) {
			$req .= " AND candidat.civilite IN ('Madame', 'Mademoiselle')" ;
		}
	}
	// Nom
	if ( isset($_SESSION["filtres"]["recherche"]["nom"]) ) {
		$req .= " AND (";
			$req .= " (candidat.nom LIKE '";
			$req .= str_replace("*", "%", mysqli_real_escape_string($cnx, $_SESSION["filtres"]["recherche"]["nom"])) ;
			$req .= "')" ;
			$req .= " OR (candidat.nom_jf LIKE '";
			$req .= str_replace("*", "%", mysqli_real_escape_string($cnx, $_SESSION["filtres"]["recherche"]["nom"])) ;
			$req .= "')" ;
		$req .= " )" ;
	}
	// Prénom
	if ( isset($_SESSION["filtres"]["recherche"]["prenom"]) ) {
		$req .= " AND candidat.prenom LIKE '";
		$req .= str_replace("*", "%", mysqli_real_escape_string($cnx, $_SESSION["filtres"]["recherche"]["prenom"])) ;
		$req .= "'" ;
	}
	// Naissance
	if ( 
		isset($_SESSION["filtres"]["recherche"]["annee_n"]) 
		AND isset($_SESSION["filtres"]["recherche"]["mois_n"]) 
		AND isset($_SESSION["filtres"]["recherche"]["jour_n"]) 
	) {
		$req .= " AND candidat.naissance='"
			. $_SESSION["filtres"]["recherche"]["annee_n"]
			 ."-"
			. $_SESSION["filtres"]["recherche"]["mois_n"]
			 ."-"
			. $_SESSION["filtres"]["recherche"]["jour_n"]
			."'" ;
	}
	else if ( isset($_SESSION["filtres"]["recherche"]["annee_n"]) ) {
		$req .= " AND candidat.naissance>='"
			. $_SESSION["filtres"]["recherche"]["annee_n"]."-01-01'"
			. " AND candidat.naissance<='"
			. $_SESSION["filtres"]["recherche"]["annee_n"]."-12-31'" ;
	}
	// Courriel
	if ( isset($_SESSION["filtres"]["recherche"]["email"]) ) {
		$req .= " AND candidat.email1='".$_SESSION["filtres"]["recherche"]["email"]."'" ;
/*
		$req .= " AND (candidat.email1='".$_SESSION["filtres"]["recherche"]["email"]."'
			OR candidat.email2='".$_SESSION["filtres"]["recherche"]["email"]."'
			OR candidat.email_pro1='".$_SESSION["filtres"]["recherche"]["email"]."'
			OR candidat.email_pro2='".$_SESSION["filtres"]["recherche"]["email"]."')" ;
*/
	}
	// Pays
	if ( isset($_SESSION["filtres"]["recherche"]["pays"]) ) {
		$req .= " AND candidat.pays='".mysqli_real_escape_string($cnx, $_SESSION["filtres"]["recherche"]["pays"])."'" ;
	}
	// Région
	if ( isset($_SESSION["filtres"]["recherche"]["region"]) AND ($_SESSION["filtres"]["recherche"]["region"] != "") )
	{
		$req .= " AND ref_region.id=".$_SESSION["filtres"]["recherche"]["region"] ;
	}
	//
	// Imputation
	if ( isset($_SESSION["filtres"]["recherche"]["imputation"]) ) {
		if ( $_SESSION["filtres"]["recherche"]["imputation"] == "Impute" ) {
			$req .= " HAVING ( (id_imputation1 IS NOT NULL) OR (id_imputation2 IS NOT NULL) ) " ;
		}
		else if ( $_SESSION["filtres"]["recherche"]["imputation"] == "A imputer" ) {
			$req .= " AND session.annee>=2006
				AND etat_dossier IN ('Allocataire', 'payant', 'Allocataire SCAC')
				HAVING ( (id_imputation1 IS NULL) AND (id_imputation2 IS NULL) ) " ;
		}
	}
	// Ancien
	if ( isset($_SESSION["filtres"]["recherche"]["diplome"]) ) {
		if ( $_SESSION["filtres"]["recherche"]["diplome"] == "1" ) {
			$req .= " AND dossier_anciens.ref_dossier IS NOT NULL " ;
		}
		else if ( $_SESSION["filtres"]["recherche"]["diplome"] == "0" ) {
			$req .= " AND dossier_anciens.ref_dossier IS NULL " ;
		}
	}
	// Résultat
	if ( isset($_SESSION["filtres"]["recherche"]["resultat"]) AND ($_SESSION["filtres"]["recherche"]["resultat"] != "") )
	{
		$req .= " AND resultat=".$_SESSION["filtres"]["recherche"]["resultat"] ;
	}
	//
	//
	// Tri
	$order = "" ;
	if ( isset($_SESSION["filtres"]["recherche"]["tri1"]) ) {
		$order .= " ORDER BY ".$_SESSION["filtres"]["recherche"]["tri1"] ;
		if ( isset($_SESSION["filtres"]["recherche"]["tri2"]) ) {
			$order .= ", ".$_SESSION["filtres"]["recherche"]["tri2"] ;
			if ( isset($_SESSION["filtres"]["recherche"]["tri3"]) ) {
				$order .= ", ".$_SESSION["filtres"]["recherche"]["tri3"] ;
			}
		}
	}

	$req_count = "SELECT candidat.id_candidat, $subselects $req" ;
	//echo $req_count ;
	$res_count = mysqli_query($cnx, $req_count) ;
	$N = mysqli_num_rows($res_count) ;
	
	if ( $N == 0 ) {
		echo "<p class='c'><strong>Aucune candidature pour ces critères</strong>.</p>" ;
	}
	else
	{
		echo "<p class='c'><strong>$N candidatures</strong></p>\n" ;

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

		$req_select = "SELECT candidat.civilite, candidat.nom, candidat.nom_jf,
			candidat.prenom, candidat.naissance, candidat.pays, email1,
			intitule, nb_annees,
			annee, intit_ses, evaluations, imputations, imputations2,
			anneed,
			dossier.*,
			ref_pays.nom AS nom_pays,
			ref_region.nom AS ref_region,
			$subselects
			$req $order LIMIT $start "
			.NB_PAR_PAGE ;
		//echo $req_select ;
		$res =  mysqli_query($cnx, $req_select) ;	
		while ( $row = mysqli_fetch_assoc($res) )
		{
			resultat_recherche($row) ;
		}

		echo $nav ;
	}
	
	
	unset($_SESSION["filtres"]["recherche"]["ok"]) ;
}
deconnecter($cnx) ;
echo $end ;
?>
