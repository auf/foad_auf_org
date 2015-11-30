<?php
include_once("inc_session.php") ;
include_once("inc_etat_dossier.php") ;
include_once("inc_pays.php") ;
include("inc_statistiques.php");

/*
if ( isset($_SESSION["tableau_toutes_promotions"]) AND (count($_SESSION["tableau_toutes_promotions"]) == 1) ) {
    header("Location: /statistiques/promotion.php?session="
        .$_SESSION["tableau_toutes_promotions"][0]) ;
    exit ;
}
*/

$titre = "Statistiques" ;
include("inc_html.php");
echo $dtd1 ;
echo "<title>$titre</title>" ;
echo $dtd2 ;
include("inc_menu.php");
echo $debut_chemin ;
echo "<a href='/bienvenue.php'>Accueil</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo $titre ;
echo $fin_chemin ;

// Ajout de l'état externe pôur les statistiques et la recherche
$etat_dossier[] = "Externe" ;
$etat_dossier_img_class["Externe"] = "externe" ;

include("inc_mysqli.php");
$cnx = connecter() ;

/*
if ( intval($_SESSION["id"]) < 4 ) {
	echo "<p class='c'><strong><a href='imputes.php'>Détails pour les candidats imputés</a></strong></p>" ;
}
*/



echo "<form method='post' action='criteres.php'>" ;
echo "<table class='formulaire'>\n<tbody>\n" ;
filtreStatsAnnee($cnx) ;
filtreStatsRegion($cnx) ;
filtreStatsPays($cnx) ;
filtreStatsEtat() ;

echo "<tr><td colspan='2'>" ;
echo "<label class='bl formulaire'><input type='checkbox' name='stats_limiter' value='imputes'" ;
if ( isset($_SESSION["filtres"]["statistiques"]["limiter"]) AND ($_SESSION["filtres"]["statistiques"]["limiter"] == "imputes") ) {
	echo " checked='checked'" ;
}
echo " /> &nbsp; Imputés</label></td></tr>" ;

echo "<tr><td colspan='2'>" ;
echo "<label class='bl formulaire'><input type='checkbox' name='stats_limiter' value='diplomes'" ;
if ( isset($_SESSION["filtres"]["statistiques"]["limiter"]) AND ($_SESSION["filtres"]["statistiques"]["limiter"] == "diplomes") ) {
	echo " checked='checked'" ;
}
echo " /> &nbsp; Diplomés</label></td></tr>" ;

echo "<tr>\n<th>Afficher&nbsp;:</th>\n<td colspan='2'>" ;
echo "<label class='bl formulaire'><input type='checkbox' name='stats_details' value='details'" ;
if ( isset($_SESSION["filtres"]["statistiques"]["details"]) AND ($_SESSION["filtres"]["statistiques"]["details"] == "details") ) {
	echo " checked='checked'" ;
}
echo " /> &nbsp; Statistiques sur les réponses</label></td></tr>" ;

echo "<tr>\n<td colspan='3'>" . FILTRE_BOUTON_LIEN . "</td>\n</tr>\n" ;
echo "</tbody>\n</table>\n" ;
echo "</form>" ;
echo "<br />" ;

if ( !isset($_SESSION["filtres"]["statistiques"]["annee"]) )
{
	$_SESSION["filtres"]["statistiques"]["annee"] = $_SESSION["derniere_annee"] ;
}






if ( ($_SESSION["filtres"]["statistiques"]["annee"] == "2005") AND (intval($_SESSION["id"]) < 4 ) )
{
	include("_2005.php") ;
}
else
{
	
	// Sessions
	$req  = "SELECT groupe, niveau, id_session, intitule, intit_ses
		FROM atelier, session
		WHERE atelier.id_atelier=session.id_atelier " ;

	if ( !isset($_SESSION["filtres"]["statistiques"]["annee"]) ) {
		$req .= "AND session.annee=".$_SESSION["derniere_annee"] ;
	}
	else {
		$req .= "AND session.annee=".$_SESSION["filtres"]["statistiques"]["annee"] ;
	}

	if ( intval($_SESSION["id"]) > 4 ) {
		$req .=	" AND session.id_session IN (".$_SESSION["liste_toutes_promotions"].") " ;
	}

	$req .= " ORDER BY annee DESC, groupe, niveau, intitule" ;

	$resultat = mysqli_query($cnx, $req) ;
	while ( $ligne = mysqli_fetch_assoc($resultat) )
	{
		$sessionsActives[$ligne["id_session"]] = array(
			$ligne["intitule"],
			$ligne["intit_ses"],
			$ligne["groupe"],
			$ligne["niveau"]
		) ;
	}

	// entete, total; corps; pied
	$htmlStatsEntete = "" ;
	$htmlStatsTotal = "" ;
	$htmlStatsCorps = "" ;
	$htmlStatsPied = "" ;

	if ( count($sessionsActives) != 0 )
	{
		// Entete
		$htmlStatsEntete .= "<table class='stats'>\n" ;
		$htmlStatsEntete .= "<thead>\n" ;
		$htmlStatsEntete .= "<tr>\n" ;
		$htmlStatsEntete .= "<th colspan='".count($etat_dossier)."'>&Eacute;tats des dossiers de candidature</th>\n" ;
		$htmlStatsEntete .= "<th rowspan='2'>Total</th>\n" ;
		$htmlStatsEntete .= "<th rowspan='2'>Formation (promotion)" ;
		$htmlStatsEntete .= "<p class='normal'>Les liens mènent aux statistiques de chaque promotion.</p></th>\n" ;
		$htmlStatsEntete .= "</tr>\n" ;
		$htmlStatsEntete .= "<tr>\n" ;
		foreach($etat_dossier as $etat) {
			$htmlStatsEntete .= "<th>" ;
			$htmlStatsEntete .= "<img src='/img/etat/".$etat_dossier_img_class[$etat].".gif' " ;
			$htmlStatsEntete .= "height='120' width='18' alt='$etat' />" ;
			$htmlStatsEntete .= "</th>\n" ;
			$totalEtat[$etat] = 0 ;
		}
		$htmlStatsEntete .= "</tr>\n" ;
		$htmlStatsEntete .= "</thead>\n" ;
		$htmlStatsEntete .= "<tbody>\n" ;
		// Corps
		$total = 0 ;
		$groupe = "" ;
		foreach(array_keys($sessionsActives) as $idSession)
		{
			if ( ( intval($_SESSION["id"]) < 4 ) AND
				( $groupe != $sessionsActives[$idSession][2] ) )
			{
				$groupe = $sessionsActives[$idSession][2] ;
				$htmlStatsCorps .= "<tr><td style='background: #ccc;'" ;
				$htmlStatsCorps .= " colspan='".strval(count($etat_dossier)+2)."' class='r'>" ;
				$htmlStatsCorps .= "<b style='font-size: 100%;'>$groupe</b></td></tr>" ;
			}
			$htmlStatsCorps .= "<tr>\n" ;
			$req  = "SELECT etat_dossier, COUNT(etat_dossier) AS N, id_imputation
				FROM candidat
					LEFT JOIN ref_pays ON candidat.pays=ref_pays.code
					LEFT JOIN ref_region ON ref_pays.region=ref_region.id,
				dossier
				LEFT JOIN imputations ON imputations.ref_dossier=dossier.id_dossier
				WHERE id_session=$idSession
				AND dossier.id_candidat=candidat.id_candidat " ;

			if ( isset($_SESSION["filtres"]["statistiques"]["region"]) AND ($_SESSION["filtres"]["statistiques"]["region"]!="") )
			{
				$req .= "AND ref_pays.region='". $_SESSION["filtres"]["statistiques"]["region"] ."' " ;
			}
			if ( isset($_SESSION["filtres"]["statistiques"]["pays"]) AND ($_SESSION["filtres"]["statistiques"]["pays"]!="") )
			{
				$req .= "AND pays='". $_SESSION["filtres"]["statistiques"]["pays"] ."' " ;
			}
	
			if ( isset($_SESSION["filtres"]["statistiques"]["etat"]) AND ($_SESSION["filtres"]["statistiques"]["etat"] != "") )
			{
				if ( $_SESSION["filtres"]["statistiques"]["etat"] == "imputable" ) {
					$req.= " AND etat_dossier IN ('Allocataire', 'Payant', 'Allocataire SCAC') " ;
				}
				else {
					$req.= " AND etat_dossier='".$_SESSION["filtres"]["statistiques"]["etat"] ."' " ;
				}
			}

			if ( isset($_SESSION["filtres"]["statistiques"]["limiter"]) AND ($_SESSION["filtres"]["statistiques"]["limiter"] == "diplomes") )
			{
				//$req .= " AND dossier.diplome='Oui' AND ref_ancien!='0' " ;
				$req .= " AND dossier.resultat='1' " ;
			}

			if ( isset($_SESSION["filtres"]["statistiques"]["limiter"]) AND ($_SESSION["filtres"]["statistiques"]["limiter"] == "imputes") )
			{
				$req .= " AND id_imputation IS NOT NULL " ;
			}

			$req .= " GROUP BY dossier.etat_dossier" ;
			$resultat = mysqli_query($cnx, $req) ;
			unset($etatDossier) ;
			while ( $ligne = mysqli_fetch_assoc($resultat) ) {
				$etatDossier[$ligne["etat_dossier"]] = $ligne["N"] ;
			}
			$sousTotal = 0 ;
			unset($accepte) ;
			foreach($etat_dossier as $etat) {
				if ( !isset($etatDossier[$etat]) ) {
					$htmlStatsCorps .= "<td class='".$etat_dossier_img_class[$etat]."'>0</td>\n" ;
				}
				else {
					$htmlStatsCorps .= "<td class='".$etat_dossier_img_class[$etat]."'>".$etatDossier[$etat]."</td>\n" ;
					$sousTotal += $etatDossier[$etat] ;
					$totalEtat[$etat] += $etatDossier[$etat] ;
				}
				// Les stats des candidats acceptés
				if ( ( $etat == "" )
					OR ( $etat == "Allocataire" )
					OR ( $etat == "Payant" )
					OR ( $etat == "Confirmé A" )
					OR ( $etat == "Confirmé P" )
					 )
				{
					if ( !isset($etatDossier[$etat]) ) {
						$accepte[$etat] = 0 ;
					}
					else {
						$accepte[$etat] = $etatDossier[$etat] ;
					}
				}
			}
			$acceptes[$idSession] = $accepte ;
			$htmlStatsCorps .= "<th class='total'>$sousTotal</th>\n" ;
		
			$htmlStatsCorps .= "<th><a class='bl' href='promotion.php?session=$idSession'>" ;
			$htmlStatsCorps .= $sessionsActives[$idSession][0] ;
			$htmlStatsCorps .= " <span style='font-weight: normal'>(" ;
			$htmlStatsCorps .= $sessionsActives[$idSession][1].")</span></a></th>\n " ;
			$htmlStatsCorps .= "</tr>\n" ;
			$total += $sousTotal ;
		}
		// Total
		$htmlStatsTotal .= "<tr>\n" ;
		foreach($etat_dossier as $etat) {
			$htmlStatsTotal .= "<th class='total'>".$totalEtat[$etat]."</th>" ;
		}
		$htmlStatsTotal .= "<th class='total'>".$total."</th>" ;
		$htmlStatsTotal .= "<th style='text-align: center;'>Total</th>\n" ;
		$htmlStatsTotal .= "</tr>\n" ;
		// Pied
		$htmlStatsPied .= "</tbody>\n" ;
		$htmlStatsPied .= "</table>" ;
		$aucun = FALSE ;

		echo $htmlStatsEntete . $htmlStatsTotal . $htmlStatsCorps . $htmlStatsTotal . $htmlStatsPied ;
		
	}
	else {
		$aucun = TRUE ;
		echo "<p class='c'>Aucune promotion pour ces critères.</p>" ;
	}
}





if ( intval($_SESSION["id"]) < 4 )
{
	$S = 0 ;
}
else {
	$S = $_SESSION["liste_promotions"] ;
}


if	(
		isset($_SESSION["filtres"]["statistiques"]["details"]) AND ($_SESSION["filtres"]["statistiques"]["details"] == "details")
		AND !$aucun
	)
{
	afficheDetails($cnx, $S) ;
}

deconnecter($cnx) ;
echo $end ;
?>
