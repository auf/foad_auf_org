<?php
include("inc_session.php") ;
include_once("inc_etat_dossier.php") ;
include_once("inc_resultat.php") ;
$RESULTAT = tab_resultats() ;
$RESULTAT_IMG_CLASS = tab_resultats_img_class() ;


include("inc_mysqli.php") ;
$cnx = connecter() ;

$id_session = $_GET["id_session"] ;

$requete = "SELECT intitule, nb_annees,
	intit_ses, evaluations, imputations, imputations2
	FROM session, atelier 
	WHERE id_session=$id_session
	AND atelier.id_atelier=session.id_atelier" ;
$resultat = mysqli_query($cnx, $requete) ;
$formation = mysqli_fetch_assoc($resultat) ;

/*
if (($formation["evaluations"]=="Non") AND ($formation["imputations"]=="Non")) {
	deconnecter($cnx) ;
	header("Location: /inscrits/index.php") ;
	exit ;
}
*/
if ( ( intval($_SESSION["id"]) > 3 )
	AND ( !in_array($_GET["id_session"], $_SESSION["tableau_toutes_promotions"]) ) 
	)
{
	deconnecter($cnx) ;
	header("Location: /inscrits/index.php") ;
	exit ;
}

// Y a t-il plusieurs sélectionneurs
$req = "SELECT id_sel, nomsel, prenomsel
	FROM atxsel, selecteurs, atelier, session
	WHERE selecteurs.codesel=atxsel.id_sel
	AND atxsel.id_atelier=atelier.id_atelier
	AND session.id_atelier=atelier.id_atelier
	AND id_session=$id_session ORDER BY id_sel" ;
$res = mysqli_query($cnx, $req) ;
$nb_selectionneurs = mysqli_num_rows($res) ;
$selectionneurs = array() ;
$i = 0 ;
while ( $row = mysqli_fetch_assoc($res) ) {
	$selectionneurs[$i]["id"] = $row["id_sel"] ;
	$selectionneurs[$i]["selectionneur"] = $row["prenomsel"] ."<br />". strtoupper($row["nomsel"]) ;
	$selectionneurs[$i]["avis"] = array() ;
	$selectionneurs[$i]["comm"] = array() ;
	$i++ ;
}

for ( $i=0 ; $i < $nb_selectionneurs ; $i++ )
{
	$req = "SELECT ref_candidat, etat_sel, commentaire
		FROM comment_sel, dossier
		WHERE ref_selecteur=". $selectionneurs[$i]["id"] ."
		AND ref_candidat=id_candidat
		AND id_session=$id_session" ;
	$res = mysqli_query($cnx, $req) ;
	while ( $row = mysqli_fetch_assoc($res) ) {
		$selectionneurs[$i]["avis"][$row["ref_candidat"]] = $row["etat_sel"] ;
		$selectionneurs[$i]["comm"][$row["ref_candidat"]] = $row["commentaire"] ;
	}
}

function etatSelectionneur($etat, $commentaire, $est_selectionneur_courant)
{
	global $etat_dossier_img_class ;
	if ( isset($etat) ) {
		if ( trim($commentaire) != "" ) {
			$aide = " help " ;
			$commentaire = strtr($commentaire, '"', "'") ;
		}
		else {
			$aide = "" ;
		}
		if ( $est_selectionneur_courant ) {
			echo "\t<td class='c $aide ".$etat_dossier_img_class[$etat]."' title=\"$commentaire\">$etat</td>\n" ;
		}
		else {
			echo "\t<td class='c plusieurs $aide ".$etat_dossier_img_class[$etat]."' title=\"$commentaire\">$etat</td>\n" ;
		}
	}
	else {
		echo "\t<td></td>\n" ;
	}
}


include("inc_html.php") ;
$titre = "Liste des inscrits" ;
echo $dtd1 ;
echo "<title>$titre</title>" ;
?>
<script type="text/javascript" language="javascript">
<!--
function rappel(lien)
{
	if(confirm("Etes vous sûr de vouloir envoyer à ce candidat un courriel contenant le numéro de son dossier et le mot de passe associé ?"))	
	{	
		var Left=window.screen.width/2-300;
		var Top=window.screen.height/2-250;
		var Configuration="'toolbar=no, menubar=no, location=no, directories=no, status=no, scrollbars=no, resizeable=yes, copyhistory=no, width=600, height=400, left=" + Left + ", top=" + Top;
		window.open(lien,'Rappel de mot de passe',Configuration) ;
	}
}
function checkAll() {
	lg=document.forms[1].elements.length;
	for ( i=0;i<lg;i++) {
		if (document.forms[1].elements[i].type=="checkbox") {
			document.forms[1].elements[i].checked=true;
		}
	}   
}	  
function unCheck() {
	lg=document.forms[1].elements.length;
	for ( i=0;i<lg;i++) {
		if (document.forms[1].elements[i].type=="checkbox") {
			document.forms[1].elements[i].checked=false; 
		}
	}
}
-->
</script>
<?
echo $dtd2 ;
include("inc_menu.php") ;

echo $debut_chemin ;
echo "<a href='/bienvenue.php'>Accueil</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo "<a href='/inscrits/index.php'>Résultat des inscrits</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo $formation["intitule"] . " (".$formation["intit_ses"].")" ;
echo $fin_chemin ;

// Nombre et liste des numéros de dossier des inscrits
$req_inscrits = "SELECT id_dossier FROM dossier
	LEFT JOIN imputations ON dossier.id_dossier=imputations.ref_dossier
	WHERE id_session=".$_GET["id_session"]."
	AND (
		etat_dossier IN ($listeEtatsInscritsAutre)
		OR	(
				etat_dossier IN ($listeEtatsImputables)
				AND (id_imputation IS NOT NULL)
			)
		)
	ORDER BY id_dossier" ;

$res_inscrits = mysqli_query($cnx, $req_inscrits) ;
$total_inscrits = mysqli_num_rows($res_inscrits) ;
$liste_inscrits = "NULL, " ;
while ($enr_inscrits = mysqli_fetch_assoc($res_inscrits) )
{
	$liste_inscrits .= $enr_inscrits["id_dossier"] . ", " ;
}
$liste_inscrits = substr($liste_inscrits, 0, -2) ;




echo "<form action='criteres.php?id_session=$id_session' method='post'>" ;
echo "<table class='formulaire'>\n" ;
// Etat
echo "<tr>\n" ;
echo "<th rowspan='4'>Limiter l'affichage&nbsp;:" ;
echo "<br /><div style='font-size: smaller; font-weight: normal; text-align: center;'>Un champ vide<br />signifie&nbsp;:<br />aucune limite.</div>" ;
echo "</th>\n" ;
echo "<th>&Eacute;tat de la candidature&nbsp;:</th>\n<td>" ;
if ( isset($_SESSION["filtres"]["inscrits"]["etat"]) ) {
	liste_etats_inscrit("inscrits_etat", $_SESSION["filtres"]["inscrits"]["etat"], TRUE) ;
}
else {
	liste_etats_inscrit("inscrits_etat", "", TRUE) ;
}
echo "</td>\n</tr>\n" ;
// Pays

include("inc_pays.php") ;
echo "<tr>\n" ;
echo "<th>Pays (de résidence)&nbsp;:</th>\n" ;
echo "<td>" ;
$req = "SELECT DISTINCT candidat.pays, ref_pays.code AS code, ref_pays.nom AS nom
	FROM dossier, candidat
		LEFT JOIN ref_pays ON candidat.pays=ref_pays.code
	WHERE candidat.id_candidat=dossier.id_candidat
	AND id_session=".$_GET["id_session"]."
	AND id_dossier IN ($liste_inscrits)
	ORDER BY nom" ;
//echo $req ;
echo selectPays($cnx, "inscrits_pays",
	( isset($_SESSION["filtres"]["inscrits"]["pays"]) ? $_SESSION["filtres"]["inscrits"]["pays"] : "" ),
	$req) ;
echo "</td>\n" ;
echo "</tr>\n" ;
// Nom
echo "<tr>\n" ;
echo "<th><label for='inscrits_nom'>Le nom du candidat contient&nbsp;:</label></th>\n" ;
echo "<td><input type='text' name='inscrits_nom' id='inscrits_nom' size='20' maxlength='40' value=\"" ;
if ( isset($_SESSION["filtres"]["inscrits"]["nom"]) ) {
	echo $_SESSION["filtres"]["inscrits"]["nom"] ;
}
echo "\" /></td>\n" ;
echo "</tr>\n" ;

echo "<tr>\n" ;
	echo "<th><label for='inscrits_resultat'>Résultat&nbsp;:</label></th>\n" ;
	echo "<td>" ;
	if ( isset($_SESSION["filtres"]["inscrits"]["etat"]) ) {
		liste_resultats("inscrits_resultat", $_SESSION["filtres"]["inscrits"]["resultat"], TRUE) ;
	}
	else {
		liste_resultats("inscrits_resultat", "", TRUE) ;
	}
echo "</td>\n</tr>\n" ;

// Tri
function liste_tri($name, $value)
{
	$TRI = array(
		"civilite" => "Civilité",
		"nom" => "Nom",
		"age" => "Age (âge au début de la formation)",
		"nom_pays" => "Pays de résidence",
		"etat_dossier" => "&Eacute;tat du dossier",
		"id_etat_hist" => "Date de mise à jour de l'état du dossier (ordre chronologique inverse)",
		"resultat" => "Résultat du dossier",
		"id_resultat_hist" => "Date de mise à jour du résultat (ordre chronologique inverse)",
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
if ( empty($_SESSION["filtres"]["inscrits"]["tri"]) ) {
	$_SESSION["filtres"]["inscrits"]["tri"] = "nom" ;
}
echo "<tr>\n" ;
echo "<th>Trier par :</th>\n" ;
echo "<td colspan='2'>" ;
liste_tri("inscrits_tri", $_SESSION["filtres"]["inscrits"]["tri"]) ;
echo "</td>\n" ;
echo "</tr>\n" ;
echo "<tr>\n<td colspan='3'><p class='c'>" ;
echo "<input type='submit' value=\"Appliquer ces critères d'affichage\"/>" ;
echo "</td>\n</tr>\n" ;
echo "</table>\n" ;
echo "</form>" ;

echo "<hr />" ;

$requeteSELECT = "SELECT
	id_dossier, etat_dossier, date_inscrip, dossier.date_maj, transferts, date_maj_etat, diplome, resultat, date_maj_resultat,
	id_imputation,
	candidat.civilite, candidat.nom, candidat.prenom, candidat.naissance,
	(DATEDIFF(date_deb, naissance) DIV 365.25 ) AS age,
	candidat.pays, candidat.id_candidat,
	(SELECT nom FROM ref_pays WHERE code=candidat.pays) AS nom_pays,
	(SELECT MAX(id_etat_hist) FROM etat_hist WHERE ref_dossier=id_dossier) AS id_etat_hist,
	(SELECT MAX(id_resultat_hist) FROM resultat_hist WHERE ref_dossier=id_dossier) AS id_resultat_hist
" ;
/*

	(SELECT id_imputation FROM imputations WHERE ref_dossier=id_dossier AND annee_relative=1)
	AS id_imputation1,
	(SELECT id_imputation FROM imputations WHERE ref_dossier=id_dossier AND annee_relative=2)
	AS id_imputation2,
*/

$requeteCOUNT = "SELECT COUNT(id_dossier) AS N " ;

$requete = " FROM session, dossier
	JOIN candidat ON dossier.id_candidat=candidat.id_candidat
	LEFT JOIN imputations ON dossier.id_dossier=imputations.ref_dossier
	WHERE dossier.id_session=$id_session
	AND session.id_session=dossier.id_session 
	AND (
		etat_dossier IN ($listeEtatsInscritsAutre)
		OR	(
				etat_dossier IN ($listeEtatsImputables)
				AND (id_imputation IS NOT NULL)
			)
		) " ;
if ( !empty($_SESSION["filtres"]["inscrits"]["etat"]) ) {
	if ( $_SESSION["filtres"]["inscrits"]["etat"] == "imputable") {
		$requete .= " AND etat_dossier IN ($listeEtatsImputables)" ;
	}
	else {
		$requete .= " AND etat_dossier='".$_SESSION["filtres"]["inscrits"]["etat"]."'" ;
	}
}
if ( !empty($_SESSION["filtres"]["inscrits"]["pays"]) ) {
	$requete .= " AND pays='".$_SESSION["filtres"]["inscrits"]["pays"]."'" ;
}
if ( !empty($_SESSION["filtres"]["inscrits"]["nom"]) ) {
	$requete .= " AND candidat.nom LIKE '%" . $_SESSION["filtres"]["inscrits"]["nom"] ."%'" ;
}
if ( ($_SESSION["filtres"]["inscrits"]["resultat"]!="") ) {
	$requete .= " AND dossier.resultat='" . $_SESSION["filtres"]["inscrits"]["resultat"] ."'" ;
}

$requete_tri = "" ;
// On ajoute le tri par id_dossier pour pouvoir tenir compte des doublons
// du fait des doubles imputations
if ( !empty($_SESSION["filtres"]["inscrits"]["tri"]) ) {
	if ( $_SESSION["filtres"]["inscrits"]["tri"] == "classement" ) {
		$requete_tri .= " ORDER BY classement, nom, id_dossier" ;
	}
	else if ( $_SESSION["filtres"]["inscrits"]["tri"] == "age" ) {
		$requete_tri .= " ORDER BY naissance DESC, nom, id_dossier" ;
	}
	else {
		$requete_tri .= " ORDER BY ". $_SESSION["filtres"]["inscrits"]["tri"] ;
		if 	(
				( $_SESSION["filtres"]["inscrits"]["tri"] == "date_maj" )
				OR ( $_SESSION["filtres"]["inscrits"]["tri"] == "id_etat_hist" )
				OR ( $_SESSION["filtres"]["inscrits"]["tri"] == "id_resultat_hist" )
			)
		{
			$requete_tri .= " DESC" ;
		}
		$requete_tri .= ", nom" ;
	}
}

$req = $requeteCOUNT . $requete ;
//echo $req ;
$result = mysqli_query($cnx, $req) ;
$enr = mysqli_fetch_assoc($result) ;
$nombre_inscrits = $enr["N"] ;

if ( $nombre_inscrits == 0 ) {
	echo "<p class='c'>Aucun inscrit pour les critères sélectionnés.</p>\n" ;
}
else
{
	$req = $requeteSELECT . $requete . $requete_tri ;

	//echo $req ;

	$result = mysqli_query($cnx, $req) ;

	$url = "inscrits.php?id_session=".$id_session ;

	$CIV = array(
		"Madame" => "Mme",
		"Mademoiselle" => "Mlle",
		"Monsieur" => "M."
	) ;
	
	include("inc_date.php") ;


	// Droit d'édition de l'etat d'une candidature
	$flag_edition = FALSE ;

	if (
			( $_SESSION["id"] == "00" )
			OR
			( ($_SESSION["id"] == "01") AND ($formation["evaluations"] == "Oui") )
			OR
			( (intval($_SESSION["id"]) > 3 ) AND ($formation["evaluations"] == "Oui") )
		)
	{
		$flag_edition = TRUE ;
	}

	if ( $flag_edition )
	{
		echo "<form method='post' action='action.php'>\n" ;
		echo "<input type='hidden' name='promotion' value='".$id_session."' />\n" ;
		
		echo "<div style='width: 30em; float: left;'>\n" ;
		echo "<table class='formulaire'>\n" ;
		if ( isset($_GET["erreur"]) AND ($_GET["erreur"] == "zero") ) {
			echo "<tr><td colspan='2'><p class='erreur'>Vous devez sélectionner au moins un inscrit.</p>\n</td></tr>\n" ;
		}
		echo "<tr>\n" ;
		echo "<th>Changer le résultat des inscrits cochés en&nbsp;:</th>\n" ;
		echo "<td>" ;
		liste_resultats("nouveau_resultat", "") ;
		echo "<input type='submit' style='font-weight:bold' name='changement' " ;
		echo "value='OK' />" ;
		echo "</td>\n</tr>\n" ;
		echo "<tr>\n<th colspan='3' style='text-align: center;'>" ;
		echo "<a href='javascript:checkAll()'>Tout cocher</a> - " ;
		echo "<a href='javascript:unCheck()'>Tout décocher</a>\n" ;
		echo "</th>\n</tr>\n" ;
		echo "</table>\n" ;
		echo "</div>\n" ;
	}

	if ( $nombre_inscrits > 1 ) { $s = "s" ; } else { $s = "" ; }
	if ( $flag_edition )
	{
		echo "<p class='c' style='margin-right: 30em; padding-top: 1.55555em;'>" ;
	}
	else {
		echo "<p class='c'>" ;
	}
	
	echo "<strong>" ;

	echo "$nombre_inscrits / $total_inscrits</strong> inscrit$s" ;
	echo "<br />pour ces critères</p>" ;


	
	echo "<div style='clear: both;'></div>\n" ;



	echo "<table class='tableau'>\n";
	echo "<thead>\n" ;
	echo "<tr>\n" ;

		if ( $flag_edition ) {
			echo "<th rowspan='2' style='border: 0px; background: transparent; color: #000; font-size: 2em; vertical-align: top;'>&darr;</th>" ;
		}

		echo "<th rowspan='2'>" ;
		echo "<span class='aide' style='float: right' " ;
		echo "title=\"La date de mise à jour est égale à la date de candidature lorsque le candidat n'a pas mis à jour son dossier.\">?</span>" ;
		echo "Date de <br />mise à jour" ;
		echo "</th>\n" ;
	
		echo "<th colspan='3'>Candidat</th>";
	
		if ( in_array($_GET["id_session"], $_SESSION["transferable"]) ) {
			echo "\t<th rowspan='2' class='help' title='Transfert'>T</th>\n" ;
		}

		echo "\t<th rowspan='2'>&Eacute;tat</th>\n" ;
		echo "\t<th rowspan='2' class='petit' title=\"Date de mise à jour de l'état du dossier\">Mise à jour<br />de l'état</th>\n" ;

		echo "\t<th rowspan='2'>Résultat</th>\n" ;
		echo "\t<th rowspan='2' class='petit' title=\"Date de mise à jour de l'état du dossier\">Mise à jour<br />du résultat</th>\n" ;

	echo "</tr>\n" ;
	echo "<tr>\n" ;
		echo "<th class='help' title='Pays de résidence'>Pays</th>";
		echo "<th>Civilité &nbsp; NOM &nbsp; Prénoms</th>";
		echo "<th class='help' title='Age au début de la formation'>Age " ;
	echo "</th>";
	echo "</tr>\n" ; 
	echo "</thead>\n" ;
	echo "<tbody>\n" ;
	
	$i=0;
	while ( $enr = mysqli_fetch_assoc($result) )
	{
		// ligne
		$class = $i % 2 ? "pair" : "impair" ;
		$lien = "?id_dossier=".$enr["id_dossier"] ;
		echo "<tr class='$class' id='d".$enr["id_dossier"]."'>\n" ;
	
		// Case à cocher pour changements
		if ( $flag_edition ) {
			echo "\t<td><input type='checkbox' name='imprimer[]' " ;
			echo "value='".$enr["id_dossier"]."' /></td>\n" ;
		}
	
		echo "\t<td>" ;
		echo mysql2datenum($enr["date_maj"]) ;
		echo "</td>\n" ;
	
		// Pays
		if (  $enr["pays"] == "République Centrafricaine" ) {
			echo "\t<td class='c' title='République Centrafricaine' class='help'>R. Centrafricaine</td>\n" ;
		}
		else {
			echo "\t<td class='c'>". $enr["nom_pays"] ."</td>\n" ;
			//echo "\t<td class='c help' title=\"".$enr["nom_pays"]."\">".$enr["pays"]."</td>\n" ;
		}
	
		echo "\t<td>" ;
		echo "<span>" ;
		echo "<a class='bl' target='_blank' href='/candidatures/candidature.php".$lien ;
		echo "'>".$CIV[$enr["civilite"]] ;
		echo " <strong class='majuscules' style='font-size: 110%'>" ;
		echo strtoupper($enr["nom"]) . "</strong> " ;
		echo ucwords(strtolower($enr["prenom"])) . "</a> </span>" ;
		echo "</td>\n" ;
	
		echo "\t<td class='c'>". $enr["age"] ."</td>\n" ;
	
		if ( in_array($_GET["id_session"], $_SESSION["transferable"]) ) {
			if ( $enr["transferts"] != "" ) {
				echo "\t<td title=\"".$enr["transferts"]."\" " ;
				echo "class='transferts help'>T</td>\n" ;
			}
			else {
				echo "\t<td></td>\n" ;
			}
		}

		// Plusieurs selectionneurs
		/*
		if ( $nb_selectionneurs > 1 )
		{
			for ( $s=0 ; $s < $nb_selectionneurs ; $s++ ) {
				if ( $selectionneurs[$s]["id"] == $_SESSION["id"] ) {
					@etatSelectionneur($selectionneurs[$s]["avis"][$enr["id_candidat"]], $selectionneurs[$s]["comm"][$enr["id_candidat"]], TRUE) ;
				}
				else {
					@etatSelectionneur($selectionneurs[$s]["avis"][$enr["id_candidat"]], $selectionneurs[$s]["comm"][$enr["id_candidat"]], FALSE) ;
				}
			}
			$selectionneurUnique = FALSE ;
		}
		else {
			$selectionneurUnique = TRUE ;
		}
		*/

		echo "\t<td class='c " ;
		if ( ($nb_selectionneurs == 1) AND ( trim($selectionneurs[0]["comm"][$enr["id_candidat"]]) != "" ) ) {
			echo " help " ;
		}
		echo $etat_dossier_img_class[$enr["etat_dossier"]]."'" ;
		if	(
				($nb_selectionneurs == 1)
				AND isset($selectionneurs[0]["comm"][$enr["id_candidat"]])
				AND (trim($selectionneurs[0]["comm"][$enr["id_candidat"]]) != "")
			)
		{
			echo " title=\"".strtr(trim($selectionneurs[0]["comm"][$enr["id_candidat"]]), '"', "'")."\" " ;
		}
		echo ">" . $enr["etat_dossier"] . "</td>\n" ;

		// FIXME date_maj_etat
		require_once("inc_historique.php") ;
		echo "\t<td class='c petit'>" ;
			if ( $enr["date_maj_etat"] != "0000-00-00" )
			{
				if ( $_SESSION["id"] == "00" )
				{
					$hTitle = historiqueTitle($cnx, $enr["id_dossier"]) ;
					if ( $hTitle != "" ) {
						echo $hTitle ;
					}
				}
				echo mysql2date($enr["date_maj_etat"]) ;
				if ( $_SESSION["id"] == "00" )
				{
					if ( $hTitle != "" ) {
						echo "</span>" ;
					}
				}
			}
			echo "</td>\n" ;

		echo "\t<td class='c " ;
			echo $RESULTAT_IMG_CLASS[$enr["resultat"]] . "'>" . $RESULTAT[$enr["resultat"]] . "</td>\n" ;
		require_once("inc_historique_resultat.php") ;
		echo "\t<td class='c petit'> " ;
			if ( $enr["date_maj_resultat"] != "0000-00-00" )
			{
				if ( $_SESSION["id"] == "00" )
				{
					$hTitle = historiqueResultatTitle($cnx, $enr["id_dossier"]) ;
					if ( $hTitle != "" ) {
						echo $hTitle ;
					}
				}
				echo mysql2date($enr["date_maj_resultat"]) ;
				if ( $_SESSION["id"] == "00" )
				{
					if ( $hTitle != "" ) {
						echo "</span>" ;
					}
				}
			}
			else {
				echo "&nbsp;" ;
			}

			echo "</td>\n" ;

		echo "</tr>\n" ;
	
		$i++;
	}
	echo "</tbody></table>\n";
	
	if ( $flag_edition ) {
		echo "</form>\n" ;
	}
}

deconnecter($cnx) ;
echo $end ;
?>
