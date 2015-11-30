<?php
include_once("inc_session.php") ;


// Haut de page
include("inc_html.php") ;
$titre = "Sélections multiples" ;
echo $dtd1 ;
echo "<title>$titre</title>" ;
echo $dtd2 ;
include("inc_menu.php") ;
echo $debut_chemin ;
echo "<a href='/bienvenue.php'>Accueil</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo "<a href='/candidatures/'>Gestion des candidatures</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo $titre ;
echo $fin_chemin ;



if ( !isset($_SESSION["selections_multiples"]["annee"]) ) {
	$_SESSION["selections_multiples"]["annee"] = $_SESSION["derniere_annee"] ;
}
if ( !isset($_SESSION["selections_multiples"]["etat"]) ) {
	$_SESSION["selections_multiples"]["etat"] = "Allocataire" ;
}



echo "<p class='c'>Recherche des candidats sélectionnés
<span style='font-size: smaller'>(«&nbsp;<span class='allocataire'>Allocataire</span>&nbsp;»,
«&nbsp;<span class='scac'>Allocataire SCAC</span>&nbsp;» ou
«&nbsp;<span class='payant'>Payant</span>&nbsp;» ou
«&nbsp;<span class='payantetab'>Payant établissement</span>&nbsp;»)</span><br />
et éventuellement imputés
<span style='font-size: smaller'>(inscrits, qui ont payé, à partir de 2006)</span>
pour plusieurs promotions, d'une même année ou non.<br />
<span style='font-size:smaller'>A chaque candidature correspond un candidat. Deux candidats sont considérés 
identiques lorsqu'ils ont le même nom et la même date de naissance.</span></p>" ;


// Formulaire de selection d'annee
echo "<form method='post' action='criteres_selections_multiples.php'>" ;
echo "<table class='formulaire'>\n<tbody\n" ;
echo "<tr>\n" ;
echo "<th>Année : </th>\n" ;
echo "<td><select name='selection_annee'>\n" ;
echo "<option value=''></option>\n" ;
for ( $i = intval($_SESSION["derniere_annee"] ) ; $i>=2004 ; $i-- ) {
	echo "<option value='$i'" ; 
	if ( $_SESSION["selections_multiples"]["annee"] == $i ) {
		echo " selected='selected'" ;
	}
	echo ">$i</option>" ;
}
echo "</select></td>\n" ;
echo "</tr>\n" ;

echo "<tr>\n" ;
echo "<th>&Eacute;tat : </th>\n" ;
echo "<td><select name='selection_etat'>\n" ;
echo "<option " ;
if ( $_SESSION["selections_multiples"]["etat"] == "Allocataire" ) {
	echo " selected='selected'" ;
}
echo "value='Allocataire'><span class='allocataire'>Allocataire</span> ou <psan class='scac'>Allocataire SCAC</span></option>" ; 
echo "<option " ;
if ( $_SESSION["selections_multiples"]["etat"] == "imputable" ) {
	echo " selected='selected'" ;
}
echo "value='imputable'>Allocataire ou Allocataire SCAC ou Payant ou Payant établissement</option>" ; 
echo "</select></td>\n" ;
echo "</tr>\n" ;

echo "<tr>\n" ;
echo "<th>Limiter&nbsp;:</th>\n" ;
echo "<td><select name='selection_imputes'>\n" ;
echo "<option value=''></option>" ;
echo "<option value='imputations'" ;
if ( $_SESSION["selections_multiples"]["imputes"] == "imputations" ) {
	echo " selected='selected'" ;
}
echo ">aux imputations multiples (candidats inscrits, qui ont payé)</option>" ;
echo "</tr>\n" ;

echo "<tr>\n<td colspan='2'>" ;
echo "<p class='c'><input class='b' type='submit' value='Actualiser' /></p>\n" ;
echo "</td>\n</tr>\n" ;

echo "</tbody>\n</table>\n" ;
echo "</form>" ;




include_once("inc_etat_dossier.php") ;
include_once("inc_date.php") ;

include_once("inc_mysqli.php") ;
$cnx = connecter() ;															 










echo "<div style='float: right; width: 30%; background: #fff; padding-left: 5px;'>" ;
$req = "SELECT id_dossier, dossier.id_session, etat_dossier, diplome,
	id_imputation,
    intitule, annee, evaluations,
    candidat.civilite, candidat.nom, candidat.naissance, candidat.nom_jf
    FROM candidat, session, atelier, " ;
if ( $_SESSION["selections_multiples"]["imputes"] == "imputations" ) {
	$req .= " dossier JOIN imputations " ;
}
else {
	$req .= " dossier LEFT JOIN imputations " ;
}
$req .= " ON dossier.id_dossier=imputations.ref_dossier " ;

if ( $_SESSION["selections_multiples"]["etat"] == "Allocataire" ) {
	$req .= " WHERE (etat_dossier='Allocataire' OR etat_dossier='Allocataire SCAC') " ;
}
else {
	$req .= " WHERE (etat_dossier='Allocataire' OR etat_dossier='Allocataire SCAC' OR etat_dossier='Payant' OR etat_dossier='Payant établissement') " ;
}

$req .= " AND civilite!='Monsieur' " ;

if ( $_SESSION["selections_multiples"]["annee"] != "" ) {
	$req .= " AND annee='".$_SESSION["selections_multiples"]["annee"]."' " ;
}
$req .= " AND dossier.id_candidat=candidat.id_candidat
	AND dossier.id_session=session.id_session
	AND session.id_atelier=atelier.id_atelier
	ORDER BY naissance, nom, annee" ;
//	ORDER BY nom, naissance, annee" ;
// echo $req ;
$res = mysqli_query($cnx, $req) ;
$nb = mysqli_num_rows($res) ;
$courant = $precedent = array() ;
$index_multiples = array() ;
$multiples = array() ;
for ( $i=0 ; $i < $nb ; $i++ ) {
	$row = mysqli_fetch_assoc($res) ;
	$courant = $row ;
	$nomcourant = strtolower($courant["nom"]) ;
	$nomprecedent = strtolower($precedent["nom"]) ;
	$nomjfcourant = strtolower($courant["nom_jf"]) ;
	$nomjfprecedent = strtolower($precedent["nom_jf"]) ;
	if (
		( $courant["naissance"] == $precedent["naissance"] )
		AND (
				( $nomcourant == $nomjfprecedent )
				OR ( $nomjfcourant == $nomprecedent )
			)
		AND (
			( substr_count($nomcourant, $nomprecedent) == 0 )
			AND ( substr_count($nomprecedent, $nomcourant) == 0 )
			)
		)
	{
		if ( !in_array(($i-1), $index_multiples) ) 
		{
			$index_multiples[] = $i-1 ;
			$multiples[] = $precedent ;
		}
		if ( !in_array(($i), $index_multiples) ) 
		{
			$index_multiples[] = $i ;
			$multiples[] = $courant ;
		}
	}
	$precedent = $row ;
}

$nombre_candidatures =  count($multiples) ;


reset($multiples) ;
$candidats = array() ;
foreach($multiples as $mu)
{
	$cle = mysql2datealpha($mu["naissance"]) ;
	$candidats[$cle][] = $mu ;
}

$nombre_candidats = count($candidats) ;




if ( intval($_SESSION["id"]) > 3 )
{
	$nombre_candidatures = $nombre_candidats = 0 ;
	$selections_mutiples = array() ;
	while ( list($key, $candidat) = each($candidats) )
	{
		$selectionneur = FALSE ;
		foreach($candidat as $c) {
			if ( in_array($c["id_session"], $_SESSION["tableau_toutes_promotions"]) )
			{
				$selectionneur = TRUE ;
				
			}
		}
		if ( $selectionneur )
		{
			$selections_mutiples[$key] = $candidat ;
			$nombre_candidats += 1 ;
			$nombre_candidatures += count($candidat) ;
		}
	}
}
else
{
	$selections_mutiples = $candidats ;
}


/*
if ( intval($_SESSION["id"]) < 3 )
{
}
else {
	echo "<br />" ;
}
*/

echo "<p class='c'><strong>" ;
if ( $nombre_candidats == 0 ) {
}
else {
	echo "+ " ;
	echo $nombre_candidatures ;
	if ( $_SESSION["selections_multiples"]["imputes"] == "imputations" ) {
		echo " imputations de " ;
	}
	else {
		echo " sélections de " ;
	}
	echo $nombre_candidats ;
	echo " femme" ;
	if ( $nombre_candidats > 1 ) {
		echo "s" ;
	}
}
echo "</strong></p>\n" ;

while ( list($key, $candidat) = each($selections_mutiples) )
{
	$denomination = $cand = "" ;
	$i = 0 ;
	foreach($candidat as $mu)
	{
		$cand .=  "<br />" ;

		if ( $_SESSION["selections_multiples"]["annee"] == "" ) {
			if ( $mu["annee"] == $_SESSION["derniere_annee"] ) {
				$cand .=  "<strong>".$mu["annee"] . "</strong> - " ;
			}
			else {
				$cand .=  $mu["annee"] . " - " ;
			}
		}

		if ( $i > 0 ) {
			$denomination .= " / " ;
		}
		$denomination .= "<span style='font-size:smaller'>" . $mu["civilite"]."</span> ".strtoupper($mu["nom"]) . " " ;
		if ( $mu["nom_jf"] != "" ) {
			$denomination .= "née ".strtoupper($mu["nom_jf"]) . " " ;
		}

		$cand .=  " <span class='".$etat_dossier_img_class[$mu["etat_dossier"]]."'>" ;
		$cand .=  $mu["etat_dossier"] . "</span> " ;

		if ( strval($mu["id_imputation"]) != "" ) {
			$cand .=  " <span class='paye'>".LABEL_INSCRIT."</span> " ;
		}
		if ( strval($mu["diplome"]) == "Oui" ) {
			$cand .=  " <span class='paye'>".LABEL_DIPLOME."</span> " ;
		}
	
		if ( isset($_SESSION["tableau_toutes_promotions"]) AND in_array($mu["id_session"], $_SESSION["tableau_toutes_promotions"]) ) {
			$cand .=  " - <strong>".$mu["intitule"]."</strong> - " ;
		} else {
			$cand .=  " - " . $mu["intitule"] . " - " ;
		}

		if ( ($mu["evaluations"] == "Oui")
			AND ( (intval($_SESSION["id"])<4) OR (in_array($mu["id_session"], $_SESSION["tableau_toutes_promotions"]) ) )
			) {
			$cand .=  " <a href='candidature.php?id_dossier=".$mu["id_dossier"]."'>Candidature</a> " ;
		}
		else {
			$cand .=  " <a href='autre.php?id_dossier=".$mu["id_dossier"]."'>Voir dossier</a> " ;
		}

		if ( strval($mu["id_imputation"]) != "" ) {
			$cand .=  " - <a href='/imputations/attestation.php?id="
				.$mu["id_imputation"] . "'>Imputé</a>" ;
		}
		$i++ ;
	}
	echo "<div class='res'>$denomination ($key)$cand</div>" ;
}
echo "</div>" ;


















$req = "SELECT id_dossier, dossier.id_session, etat_dossier, id_imputation,
    intitule, annee, evaluations,
    candidat.civilite, candidat.nom, candidat.naissance
    FROM candidat, session, atelier, " ;
if ( isset($_SESSION["selections_multiples"]["imputes"]) AND ($_SESSION["selections_multiples"]["imputes"] == "imputations") ) {
	$req .= " dossier JOIN imputations " ;
}
else {
	$req .= " dossier LEFT JOIN imputations " ;
}
$req .= " ON dossier.id_dossier=imputations.ref_dossier " ;

if ( $_SESSION["selections_multiples"]["etat"] == "Allocataire" ) {
	$req .= " WHERE (etat_dossier='Allocataire' OR etat_dossier='Allocataire SCAC') " ;
}
else {
	$req .= " WHERE (etat_dossier='Allocataire' OR etat_dossier='Allocataire SCAC' OR etat_dossier='Payant' OR etat_dossier='Payant établissement') " ;
}

if ( $_SESSION["selections_multiples"]["annee"] != "" ) {
	$req .= " AND annee='".$_SESSION["selections_multiples"]["annee"]."' " ;
}

$req .= " AND dossier.id_candidat=candidat.id_candidat
	AND dossier.id_session=session.id_session
	AND session.id_atelier=atelier.id_atelier
	ORDER BY naissance, nom, annee" ;
//	ORDER BY nom, naissance, annee" ;
//echo $req;
$res = mysqli_query($cnx, $req) ;
$nb = mysqli_num_rows($res) ;
//echo " $nb hommes <br />" ;
$courant = $precedent = array() ;
$index_multiples = array() ;
$multiples = array() ;
for ( $i=0 ; $i < $nb ; $i++ )
{
	$row = mysqli_fetch_assoc($res) ;
	$courant = $row ;
	$nomcourant = strtolower($courant["nom"]) ;
	$nomprecedent = strtolower($precedent["nom"]) ;
	if (
		( $courant["naissance"] == $precedent["naissance"] )
		AND ( 
				( $nomcourant == $nomprecedent )
				OR ( substr_count($nomcourant, $nomprecedent) > 0 )
				OR ( substr_count($nomprecedent, $nomcourant) > 0 )
			)
		)
	{
		if ( !in_array(($i-1), $index_multiples) ) 
		{
			$index_multiples[] = $i-1 ;
			$multiples[] = $precedent ;
		}
		if ( !in_array(($i), $index_multiples) ) 
		{
			$index_multiples[] = $i ;
			$multiples[] = $courant ;
		}
	}
	$precedent = $row ;
}

$nombre_candidatures =  count($multiples) ;
//echo " $nombre_candidatures multiples <br />" ;


reset($multiples) ;
$candidats = array() ;
$cle_precedente = $nom_precedent = $naissance_precedente = "" ;
foreach($multiples as $mu)
{
	$cle = strtoupper($mu["nom"])." (".mysql2datealpha($mu["naissance"]).")" ;
	if (
		( $mu["naissance"] == $naissance_precedente ) AND
		(
			( substr_count(strtoupper($mu["nom"]), $nom_precedent) > 0 )
			OR ( substr_count($nom_precedent, strtoupper($mu["nom"])) > 0 )
		)
	)
	{
		$candidats[$cle_precedente][] = $mu ;
	}
	else {
		$candidats[$cle][] = $mu ;
	}

	$nom_precedent = strtoupper($mu["nom"]) ;
	$cle_precedente = $cle ;
	$naissance_precedente = $mu["naissance"] ;
}

$nombre_candidats = count($candidats) ;
//echo " $nombre_candidats candidats multiples <br />" ;



if ( intval($_SESSION["id"]) > 3 )
{
	$nombre_candidatures = $nombre_candidats = 0 ;
	$selections_mutiples = array() ;
	while ( list($key, $candidat) = each($candidats) )
	{
		$selectionneur = FALSE ;
		foreach($candidat as $c) {
			if ( in_array($c["id_session"], $_SESSION["tableau_toutes_promotions"]) )
			{
				$selectionneur = TRUE ;
				
			}
		}
		if ( $selectionneur )
		{
			$selections_mutiples[$key] = $candidat ;
			$nombre_candidats += 1 ;
			$nombre_candidatures += count($candidat) ;
		}
	}
}
else
{
	$selections_mutiples = $candidats ;
}


/*
if ( intval($_SESSION["id"]) < 3 )
{
}
else {
	echo "<br />" ;
}
*/

echo "<p class='c'><strong>" ;
if ( $nombre_candidats == 0 ) {
	if ( isset($_SESSION["selections_multiples"]["imputes"]) AND ($_SESSION["selections_multiples"]["imputes"] == "imputations") ) {
		echo "Aucune imputation multiple." ;
	}
	else {
		echo "Aucune sélection multiple." ;
	}
}
else {
	echo $nombre_candidatures ;
	if ( $_SESSION["selections_multiples"]["imputes"] == "imputations" ) {
		echo " imputations de " ;
	}
	else {
		echo " sélections de " ;
	}
	echo $nombre_candidats ;
	echo " candidat" ;
	if ( $nombre_candidats > 1 ) {
		echo "s" ;
	}
}
echo "</strong></p>\n" ;




while ( list($key, $candidat) = each($selections_mutiples) )
{
	$resultat = "" ;
	foreach($candidat as $mu)
	{
		$resultat .= "<br />" ;

		if ( $_SESSION["selections_multiples"]["annee"] == "" ) {
			if ( $mu["annee"] == $_SESSION["derniere_annee"] ) {
				$resultat .= "<strong>".$mu["annee"] . "</strong> - " ;
			}
			else {
				$resultat .= $mu["annee"] . " - " ;
			}
		}

		$resultat .= " <span class='".$etat_dossier_img_class[$mu["etat_dossier"]]."'>" ;
		$resultat .= $mu["etat_dossier"] . "</span> " ;

		if ( strval($mu["id_imputation"]) != "" ) {
			$resultat .= " <span class='paye'>".LABEL_INSCRIT."</span> " ;
		}
		if ( isset($mu["diplome"]) AND (strval($mu["diplome"]) == "Oui") ) {
			$cand .=  " <span class='paye'>".LABEL_DIPLOME."</span> " ;
		}
	
		if ( isset($_SESSION["tableau_toutes_promotions"]) AND in_array($mu["id_session"], $_SESSION["tableau_toutes_promotions"]) ) {
			$resultat .= " - <strong>".$mu["intitule"]."</strong> - " ;
		} else {
			$resultat .= " - " . $mu["intitule"] . " - " ;
		}

		if ( ($mu["evaluations"] == "Oui")
			AND ( (intval($_SESSION["id"])<4) OR (in_array($mu["id_session"], $_SESSION["tableau_toutes_promotions"]) ) )
			) {
			$resultat .= " <a href='candidature.php?id_dossier=".$mu["id_dossier"]."'>Candidature</a> " ;
		}
		else {
			$resultat .= " <a href='autre.php?id_dossier=".$mu["id_dossier"]."'>Voir dossier</a> " ;
		}

		if ( strval($mu["id_imputation"]) != "" ) {
			$resultat .= " - <a href='/imputations/attestation.php?id="
				.$mu["id_imputation"] . "'>Imputé</a>" ;
		}
	}
	echo "<div class='res'><span style='font-size:smaller'>". $mu["civilite"] . "</span> " . $key ;
	echo $resultat ;
	echo "</div>" ;
}



echo $end ;
deconnecter($cnx) ;

?>
