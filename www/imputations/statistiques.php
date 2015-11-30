<?php
include("inc_session.php") ;

include("inc_mysqli.php") ;
$cnx = connecter() ;

include("inc_date.php") ;

include("inc_html.php") ;
$titre = "Imputations (statistiques)" ;
echo $dtd1 ;
echo "<title>$titre</title>\n" ;
echo $dtd2 ;
include("inc_menu.php") ;
echo "<div class='noprint'>" ;
echo $debut_chemin ;
echo "<a href='/bienvenue.php'>Accueil</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo $titre ;
echo "</div>" ;
echo $fin_chemin ;


if ( !isset($_SESSION["filtres"]["imputations"]["annee"]) )
{
	$req ="SELECT MAX(annee) FROM session, dossier, imputations
		WHERE ref_dossier=id_dossier
		AND dossier.id_session=session.id_session" ;
	$res = mysqli_query($cnx, $req) ;
	$row = mysqli_fetch_row($res) ;
	$_SESSION["filtres"]["imputations"]["annee"] = $row[0] ;
}


echo "<form action='criteres.php' method='post'>" ;
echo "<input type='hidden' name='redirect' value='".$_SERVER["SCRIPT_NAME"]."' />\n" ;
echo "<table class='formulaire'>\n" ;
echo "<tbody>\n" ;
include("inc_promotions.php") ;
include("inc_form_select.php") ;
include("inc_cnf.php") ;


$req = "SELECT DISTINCT annee FROM session
	WHERE annee>2005
	ORDER BY annee DESC" ;
$res = mysqli_query($cnx, $req) ;

echo "<tr>\n" ;
echo "<th rowspan='2'>Limiter&nbsp;:</th>\n" ;
echo "<th>Année&nbsp;:</th>\n" ;
echo "<td><select name='i_annee'>\n" ;
echo "<option value=''></option>\n" ;
while ( $enr = mysqli_fetch_assoc($res) ) {
	echo "<option value='".$enr["annee"]."'" ;
	if ( isset($_SESSION["filtres"]["imputations"]["annee"]) AND ($_SESSION["filtres"]["imputations"]["annee"] == $enr["annee"]) ) {
	    echo " selected='selected'" ;
	}
	echo ">".$enr["annee"]."</option>" ;
}
echo "</select></td>\n" ;
echo "</td>\n" ;
echo "</tr>\n" ;

echo "<tr>\n" ;
echo "<th class='help' title=\"Lieu d'enregistrement\">Lieu&nbsp;:</th>\n" ;
echo "<td>" ;
form_select_1($CNF, "i_lieu", 
	( isset($_SESSION["filtres"]["imputations"]["lieu"]) ? $_SESSION["filtres"]["imputations"]["lieu"] : "" )
	) ;
echo "</td>\n" ;
echo "</tr>\n" ;
echo "<tr>\n<td colspan='3'><div class='c'>"
	. "<a class='reinitialiser' href='reinitialiser.php?redirect=".urlencode($_SERVER["SCRIPT_NAME"])."'>".LABEL_REINITIALISER."</a>"
	. BOUTON_ACTUALISER
	. "</div></td>\n</tr>\n" ;
echo "</tbody>\n" ;
echo "</table>\n" ;
echo "</form>" ;
echo "<br />" ;




$req_debut = "SELECT COUNT(id_imputation) AS N,
	session.id_session, annee_absolue, groupe, intitule, intit_ses
	FROM imputations, dossier, candidat, session, atelier
	WHERE dossier.id_candidat=candidat.id_candidat
	AND ref_dossier=id_dossier " ;
if ( !empty($_SESSION["filtres"]["imputations"]["lieu"]) ) {
	$req_debut .= "AND lieu='".$_SESSION["filtres"]["imputations"]["lieu"]."' " ;
}
if ( !empty($_SESSION["filtres"]["imputations"]["annee"]) ) {
	$req_debut .= "AND annee_absolue='".$_SESSION["filtres"]["imputations"]["annee"]."' " ;
}
$req_debut .= " AND dossier.id_session=session.id_session
	AND atelier.id_atelier=session.id_atelier " ;
if ( intval($_SESSION["id"]) > 3 ) {
	$req_debut .= " AND session.id_session IN (".$_SESSION["liste_toutes_promotions"].") " ;
}
$req_fin = " GROUP BY session.id_session
	ORDER BY annee_absolue DESC, groupe, niveau, intitule" ;


// Total
$totalTotal = 0 ;
$req = $req_debut . $req_fin ;
$res = mysqli_query($cnx, $req) ;
$tabSession = array() ;
while ( $enr = mysqli_fetch_assoc($res) )
{
	$tabSession[$enr["id_session"]] = array(
		"intitule" => $enr["intitule"],
		"intit_ses" => $enr["intit_ses"],
		"annee_absolue" => $enr["annee_absolue"],
		"groupe" => $enr["groupe"],
		"total" => $enr["N"],
	) ;
	$totalTotal += $enr["N"] ;
}
// Allocataire
$totalAllocataire = 0 ;
$req = $req_debut . " AND etat='Allocataire' " . $req_fin ;
$res = mysqli_query($cnx, $req) ;
while ( $enr = mysqli_fetch_assoc($res) )
{
	$tabSession[$enr["id_session"]]["Allocataire"] = $enr["N"] ;
	$totalAllocataire += $enr["N"] ;
}
// Allocataire SCAC
$totalAllocataireSCAC = 0 ;
$req = $req_debut . " AND etat='Allocataire SCAC' " . $req_fin ;
$res = mysqli_query($cnx, $req) ;
while ( $enr = mysqli_fetch_assoc($res) )
{
	$tabSession[$enr["id_session"]]["AllocataireSCAC"] = $enr["N"] ;
	$totalAllocataireSCAC += $enr["N"] ;
}
// Payant
$totalPayant = 0 ;
$req = $req_debut . " AND etat='Payant' " . $req_fin ;
$res = mysqli_query($cnx, $req) ;
while ( $enr = mysqli_fetch_assoc($res) )
{
	$tabSession[$enr["id_session"]]["Payant"] = $enr["N"] ;
	$totalPayant += $enr["N"] ;
}
// Femmes
$totalFemme = 0 ;
$req = $req_debut .
	" AND (civilite='Madame' OR civilite='Mademoiselle') "
	. $req_fin ;
$res = mysqli_query($cnx, $req) ;
while ( $enr = mysqli_fetch_assoc($res) )
{
	$tabSession[$enr["id_session"]]["femme"] = $enr["N"] ;
	$totalFemme += $enr["N"] ;
}
// Femmes allocataire
$totalFemmeAllocataire = 0 ;
$req = $req_debut .
	" AND (civilite='Madame' OR civilite='Mademoiselle')
	AND etat='Allocataire' "
	. $req_fin ;
$res = mysqli_query($cnx, $req) ;
while ( $enr = mysqli_fetch_assoc($res) )
{
	$tabSession[$enr["id_session"]]["femmeAllocataire"] = $enr["N"] ;
	$totalFemmeAllocataire += $enr["N"] ;
}
// Femmes allocataire SCAC
$totalFemmeAllocataireSCAC = 0 ;
$req = $req_debut .
	" AND (civilite='Madame' OR civilite='Mademoiselle')
	AND etat='Allocataire SCAC' "
	. $req_fin ;
$res = mysqli_query($cnx, $req) ;
while ( $enr = mysqli_fetch_assoc($res) )
{
	$tabSession[$enr["id_session"]]["femmeAllocataireSCAC"] = $enr["N"] ;
	$totalFemmeAllocataireSCAC += $enr["N"] ;
}

// Jeunes
$totalJeune = 0 ;
$req = $req_debut .
	" AND ( (DATEDIFF(date_deb, naissance) DIV 365.25 ) < 35 ) "
	. $req_fin ;
$res = mysqli_query($cnx, $req) ;
while ( $enr = mysqli_fetch_assoc($res) )
{
	$tabSession[$enr["id_session"]]["jeune"] = $enr["N"] ;
	$totalJeune += $enr["N"] ;
}
// Jeunes Allocataire
$totalJeuneAllocataire = 0 ;
$req = $req_debut .
	" AND ( (DATEDIFF(date_deb, naissance) DIV 365.25 ) < 35 )
	AND etat='Allocataire' "
	. $req_fin ;
$res = mysqli_query($cnx, $req) ;
while ( $enr = mysqli_fetch_assoc($res) )
{
	$tabSession[$enr["id_session"]]["jeuneAllocataire"] = $enr["N"] ;
	$totalJeuneAllocataire += $enr["N"] ;
}
// Jeunes Allocataire SCAC
$totalJeuneAllocataireSCAC = 0 ;
$req = $req_debut .
	" AND ( (DATEDIFF(date_deb, naissance) DIV 365.25 ) < 35 )
	AND etat='Allocataire SCAC' "
	. $req_fin ;
$res = mysqli_query($cnx, $req) ;
while ( $enr = mysqli_fetch_assoc($res) )
{
	$tabSession[$enr["id_session"]]["jeuneAllocataireSCAC"] = $enr["N"] ;
	$totalJeuneAllocataireSCAC += $enr["N"] ;
}


if ( isset($tabSession) AND (count($tabSession) == 0) )
{
	echo "<p class='c'>Aucune imputation pour ces critères</p>\n" ;
}
else
{
	$entete  = "" ;
	$entete .= "<tr>\n" ;
	$entete .= "<th colspan='3'>Femmes</th>" ;
	$entete .= "<th colspan='3' class='aide' title='Moins de 35 ans au moment du début de la formation'>Jeunes</th>" ;
	$entete .= "<th colspan='4'>Tous</th>" ;
	$entete .= "<th rowspan='2'>Promotion</th>" ;
	$entete .= "</tr>\n" ;
	$entete .= "<tr>\n" ;
	$entete .= "<th class='aide' title='Allocataire SCAC'>AS</th>" ;
	$entete .= "<th class='aide' title='Allocataire'>A</th>" ;
	$entete .= "<th></th>" ;
	$entete .= "<th class='aide' title='Allocataire SCAC'>AS</th>" ;
	$entete .= "<th class='aide' title='Allocataire'>A</th>" ;
	$entete .= "<th></th>" ;
	$entete .= "<th class='aide' title='Payant'>P</th>" ;
	$entete .= "<th class='aide' title='Allocataire SCAC'>AS</th>" ;
	$entete .= "<th class='aide' title='Allocataire'>A</th>" ;
	$entete .= "<th></th>" ;
	$entete .= "</tr>\n" ;

	$totaux  = "<tr>\n" ;
	$totaux .= "<td class='r scac'><strong>$totalFemmeAllocataireSCAC</strong></td>\n" ;
	$totaux .= "<td class='r allocataire'><strong>$totalFemmeAllocataire</strong></td>\n" ;
	$totaux .= "<td class='r'><strong>$totalFemme</strong></td>\n" ;
	$totaux .= "<td class='r scac'><strong>$totalJeuneAllocataireSCAC</strong></td>\n" ;
	$totaux .= "<td class='r allocataire'><strong>$totalJeuneAllocataire</strong></td>\n" ;
	$totaux .= "<td class='r'><strong>$totalJeune</strong></td>\n" ;
	$totaux .= "<td class='r payant'><strong>$totalPayant</strong></td>\n" ;
	$totaux .= "<td class='r scac'><strong>$totalAllocataireSCAC</strong></td>\n" ;
	$totaux .= "<td class='r allocataire'><strong>$totalAllocataire</strong></td>\n" ;
	$totaux .= "<td class='r'><strong>$totalTotal</strong></td>\n" ;
	$totaux .= "<td class='c'><strong>Totaux</strong></td>\n" ;
	$totaux .= "</tr>\n" ;

	$groupe = "" ;
	$annee = "" ;
	echo "<table class='tableau'>\n" ;
	echo "<thead>\n" ;
	echo $entete ;
	echo "</thead>\n" ;
	echo "<tbody>\n" ;
	echo $totaux ;
	while ( list($promo, $val) = each($tabSession) )
	{
		if ( $annee != $val["annee_absolue"] ) {
			$annee = $val["annee_absolue"] ;
			echo "<tr><th class='annee r' colspan='11'>" ;
			echo "<b>$annee</b></th></tr>" ;
		}
		if ( $groupe != $val["groupe"] ) {
			$groupe = $val["groupe"] ;
			echo "<tr><td style='background: #ccc' class='r' colspan='11'>" ;
			echo "<b>$groupe</b></td></tr>" ;
		}
		echo "<tr>\n" ;
		echo "<td class='r scac'>"
			. ( isset($val["femmeAllocataireSCAC"]) ? $val["femmeAllocataireSCAC"] : "" )
			. "</td>\n" ;
		echo "<td class='r allocataire'>"
			. ( isset($val["femmeAllocataire"]) ? $val["femmeAllocataire"] : "" )
			. "</td>\n" ;
		echo "<td class='r'>"
			. ( isset($val["femme"]) ? $val["femme"] : "" )
			. "</td>\n" ;
		echo "<td class='r scac'>"
			. ( isset($val["jeuneAllocataireSCAC"]) ? $val["jeuneAllocataireSCAC"] : "" )
			. "</td>\n" ;
		echo "<td class='r allocataire'>"
			. ( isset($val["jeuneAllocataire"]) ? $val["jeuneAllocataire"] : "" )
			. "</td>\n" ;
		echo "<td class='r'>"
			. ( isset($val["jeune"]) ? $val["jeune"] : "" )
			. "</td>\n" ;
		echo "<td class='r payant'><strong>"
			. ( isset($val["Payant"]) ? $val["Payant"] : "" )
			. "</strong></td>\n" ;
		echo "<td class='r scac'><strong>"
			. ( isset($val["AllocataireSCAC"]) ? $val["AllocataireSCAC"] : "" )
			. "</strong></td>\n" ;
		echo "<td class='r allocataire'><strong>"
			. ( isset($val["Allocataire"]) ? $val["Allocataire"] : "" )
			. "</strong></td>\n" ;
		echo "<td class='r'><strong>"
			. ( isset($val["total"]) ? $val["total"] : "" )
			. "</strong></td>\n" ;
		echo "<td><a class='bl' id='p$promo' href='promotion.php?promotion=$promo'>" ;
		echo "<strong>".$val["intitule"]."</strong>" ;
		echo " (".$val["intit_ses"].")" ;
		echo "</a></td>\n" ;
		echo "</tr>\n" ;
	}
	echo "</tbody>\n" ;
	echo "</table>\n" ;
}


deconnecter($cnx) ;
echo $end ;
?>
