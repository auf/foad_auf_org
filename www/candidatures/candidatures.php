<?php
include("inc_session.php") ;
include("inc_etat_dossier.php") ;

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
	header("Location: /candidatures/index.php") ;
	exit ;
}
*/
if ( ( intval($_SESSION["id"]) > 3 )
	AND ( !in_array($_GET["id_session"], $_SESSION["tableau_toutes_promotions"]) ) 
	)
{
	deconnecter($cnx) ;
	header("Location: /candidatures/index.php") ;
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
$titre = "Liste des candidatures" ;
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
echo "<a href='/candidatures/index.php'>Gestion des candidatures</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo $formation["intitule"] . " (".$formation["intit_ses"].")" ;
echo $fin_chemin ;


echo "<form action='criteres.php?id_session=$id_session' method='post'>" ;
echo "<table class='formulaire'>\n" ;
// Etat
echo "<tr>\n" ;
echo "<th rowspan='4'>Limiter l'affichage&nbsp;:" ;
echo "<br /><div style='font-size: smaller; font-weight: normal; text-align: center;'>Un champ vide<br />signifie&nbsp;:<br />aucune limite.</div>" ;
echo "</th>\n" ;
echo "<th>&Eacute;tat de la candidature&nbsp;:</th>\n<td>" ;
if ( isset($_SESSION["filtres"]["candidatures"]["etat"]) ) {
	liste_etats("c_etat", $_SESSION["filtres"]["candidatures"]["etat"], TRUE, TRUE) ;
}
else {
	liste_etats("c_etat", "", TRUE, TRUE) ;
}
echo "</td>\n</tr>\n" ;
// Pays

include("inc_pays.php") ;
echo "<tr>\n" ;
echo "<th>Pays (de résidence)&nbsp;:</th>\n" ;
echo "<td>" ;
$req = "SELECT DISTINCT candidat.pays, ref_pays.code AS code, ref_pays.nom AS nom FROM dossier, candidat
	LEFT JOIN ref_pays ON candidat.pays=ref_pays.code
	WHERE candidat.id_candidat=dossier.id_candidat
	AND id_session=".$_GET["id_session"]."
	ORDER BY nom" ;
echo selectPays($cnx, "c_pays",
	( isset($_SESSION["filtres"]["candidatures"]["pays"]) ? $_SESSION["filtres"]["candidatures"]["pays"] : "" ),
	$req) ;
/*
echo "<select name='c_pays'>\n" ;
echo "<option value=''></option>\n" ;
$req = "SELECT DISTINCT pays FROM candidat, dossier
	WHERE candidat.id_candidat=dossier.id_candidat
	AND id_session=".$_GET["id_session"]."
	ORDER BY pays" ;
$res = mysqli_query($cnx, $req) ;
while ( $enr = mysqli_fetch_assoc($res) ) {
	$pays = $enr["pays"] ;
	echo "<option value=\"$pays\"" ;
	if ( isset($_SESSION["filtres"]["candidatures"]["pays"]) AND ($_SESSION["filtres"]["candidatures"]["pays"] == $pays) ) {
		echo " selected='selected'" ;
	}
	echo ">$pays</option>\n" ;
}
echo "</select>" ;
*/
/*
foreach($PAYS as $pays) {
	echo "<option value=\"$pays\"" ;
	if ( $_SESSION["filtres"]["candidatures"]["pays"] == $pays ) {
		echo " selected='selected'" ;
	}
	echo ">$pays</option>\n" ;
}
*/
echo "</td>\n" ;
echo "</tr>\n" ;
// Nom
echo "<tr>\n" ;
echo "<th><label for='c_nom'>Le nom du candidat contient&nbsp;:</label></th>\n" ;
echo "<td><input type='text' name='c_nom' id='c_nom' size='20' maxlength='40' value=\"" ;
if ( isset($_SESSION["filtres"]["candidatures"]["nom"]) ) {
	echo $_SESSION["filtres"]["candidatures"]["nom"] ;
}
echo "\" /></td>\n" ;
echo "</tr>\n" ;

echo "<tr>\n" ;
	echo "<th><label for='c_max'>Nombre maximum&nbsp;:</label></th>\n" ;
	echo "<td><select name='c_max'>\n" ;
	echo "<option value=''>50</option>\n" ;
	echo "<option value='100'" ;
	if ( isset($_SESSION["filtres"]["candidatures"]["max"]) AND ($_SESSION["filtres"]["candidatures"]["max"] == "100") ) {
		echo " selected='selected'" ;
	}
	echo ">100</option>\n" ;
	echo "<option value='toutes'" ;
	if ( isset($_SESSION["filtres"]["candidatures"]["max"]) AND ($_SESSION["filtres"]["candidatures"]["max"] == "toutes") ) {
		echo " selected='selected'" ;
	}
	echo ">Toutes</option>\n" ;
	echo "</select></td>\n" ;
	echo "</tr>\n" ;


// Tri
include("inc_tri.php") ;
if ( empty($_SESSION["filtres"]["candidatures"]["tri"]) ) {
	$_SESSION["filtres"]["candidatures"]["tri"] = "date_maj" ;
}
echo "<tr>\n" ;
echo "<th>Trier par :</th>\n" ;
echo "<td colspan='2'>" ;
liste_tri("c_tri", $_SESSION["filtres"]["candidatures"]["tri"]) ;
echo "</td>\n" ;
echo "</tr>\n" ;
echo "<tr>\n<td colspan='3'><p class='c'>" ;
echo "<input type='submit' value=\"Appliquer ces critères d'affichage\"/>" ;
echo "</td>\n</tr>\n" ;
echo "</table>\n" ;
echo "</form>" ;

echo "<hr />" ;

$requeteSELECT = "SELECT
	id_dossier, etat_dossier, date_inscrip, dossier.date_maj, transferts, date_maj_etat,
	classement, diplome,
	session.imputations,
	session.imputations2,
	candidat.civilite, candidat.nom, candidat.prenom, candidat.naissance,
	(DATEDIFF(date_deb, naissance) DIV 365.25 ) AS age,
	candidat.pays, candidat.id_candidat,
	(SELECT nom FROM ref_pays WHERE code=candidat.pays) AS nom_pays,
	(SELECT id_imputation FROM imputations WHERE ref_dossier=id_dossier AND annee_relative=1)
	AS id_imputation1,
	(SELECT id_imputation FROM imputations WHERE ref_dossier=id_dossier AND annee_relative=2)
	AS id_imputation2,
	(SELECT MAX(id_etat_hist) FROM etat_hist WHERE ref_dossier=id_dossier)
	AS id_etat_hist
" ;

$requeteCOUNT = "SELECT COUNT(id_dossier) AS N " ;

$requete = " FROM session, dossier
	JOIN candidat ON dossier.id_candidat=candidat.id_candidat
	WHERE dossier.id_session=$id_session
	AND session.id_session=dossier.id_session " ;
if ( !empty($_SESSION["filtres"]["candidatures"]["etat"]) ) {
	if ( $_SESSION["filtres"]["candidatures"]["etat"] == "imputable") {
		$requete .= " AND etat_dossier IN ('Allocataire', 'Payant', 'Allocataire SCAC')" ;
	}
	else {
		$requete .= " AND etat_dossier='".$_SESSION["filtres"]["candidatures"]["etat"]."'" ;
	}
}
if ( !empty($_SESSION["filtres"]["candidatures"]["pays"]) ) {
	$requete .= " AND pays='".$_SESSION["filtres"]["candidatures"]["pays"]."'" ;
}
if ( !empty($_SESSION["filtres"]["candidatures"]["nom"]) ) {
	$requete .= " AND candidat.nom LIKE '%" . $_SESSION["filtres"]["candidatures"]["nom"] ."%'" ;
}

$requete_tri = "" ;
// On ajoute le tri par id_dossier pour pouvoir tenir compte des doublons
// du fait des doubles imputations
if ( !empty($_SESSION["filtres"]["candidatures"]["tri"]) ) {
	if ( $_SESSION["filtres"]["candidatures"]["tri"] == "classement" ) {
		$requete_tri .= " ORDER BY classement, nom, id_dossier" ;
	}
	else if ( $_SESSION["filtres"]["candidatures"]["tri"] == "age" ) {
		$requete_tri .= " ORDER BY naissance DESC, nom, id_dossier" ;
	}
	else {
		$requete_tri .= " ORDER BY ". $_SESSION["filtres"]["candidatures"]["tri"] ;
		if ( ( $_SESSION["filtres"]["candidatures"]["tri"] == "date_maj" ) OR ( $_SESSION["filtres"]["candidatures"]["tri"] == "id_etat_hist" ) ) {
			$requete_tri .= " DESC" ;
		}
		$requete_tri .= ", id_dossier" ;
	}
}

$req = $requeteCOUNT . $requete ;
$result = mysqli_query($cnx, $req) ;
$enr = mysqli_fetch_assoc($result) ;
$nombre_candidatures = $enr["N"] ;

if ( $nombre_candidatures == 0 ) {
	echo "<p class='c'>Aucune candidature pour les critères sélectionnés.</p>\n" ;
}
else
{
	$req = $requeteSELECT . $requete . $requete_tri ;

	if ( !isset($_SESSION["filtres"]["candidatures"]["max"]) OR ($_SESSION["filtres"]["candidatures"]["max"] == "") ) {
		$req .= " LIMIT 50" ;
		$limite = 50 ;
	}
	else if ( $_SESSION["filtres"]["candidatures"]["max"] == "100" ) {
		$req .= " LIMIT 100" ;
		$limite = 100;
	}
	else {
		// Une limite infranchissable de nombre de candidatures
		$limite = 9999999 ;
	}
	//echo $req ;

	$result = mysqli_query($cnx, $req) ;

	$url = "candidatures.php?id_session=".$id_session ;

	$CIV = array(
		"Madame" => "Mme",
		"Mademoiselle" => "Mlle",
		"Monsieur" => "M."
	) ;
	
	include("inc_date.php") ;

	if ( isset($_GET["erreur"]) AND ($_GET["erreur"] == "zero") ) {
		echo "<p class='erreur'>Vous devez sélectionner au moins un candidat.</p>\n" ;
	}

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
		// Pour l'historique
		echo "<input type='hidden' name='evaluations' value='".$formation["evaluations"]."' />\n" ;
		
		echo "<div style='width: 20em; float: left;'>\n" ;
		echo "<table class='formulaire' style='margin-bottom: 4px;'>\n" ;
		echo "<tr>\n" ;
		echo "<th rowspan='2'>Action&nbsp;sur&nbsp;les<br />candidatures<br />cochées&nbsp;:</th>\n" ;
		echo "<td>Changer&nbsp;l'état&nbsp;en" ;
		liste_etats("nouvel_etat", "") ;
		echo "<input type='submit' style='font-weight:bold' name='changement' " ;
		echo "value='OK' />" ;
		echo "</td>\n</tr>\n" ;
		echo "<tr>\n<td>" ;
		echo "<input type='submit' style='font-weight:bold' name='impression' " ;
		echo "value='Imprimer' />\n" ;
		echo "</td>\n</tr>\n" ;
		echo "<tr>\n<th colspan='3' style='text-align: center;'>" ;
		echo "<a href='javascript:checkAll()'>Tout cocher</a> - " ;
		echo "<a href='javascript:unCheck()'>Tout décocher</a>\n" ;
		echo "</th>\n</tr>\n" ;
		echo "</table>\n" ;
		echo "</div>\n" ;
	}

	if ( isset($_SESSION["filtres"]["candidatures"]["etat"]) AND ($_SESSION["filtres"]["candidatures"]["etat"] == "En attente") AND $flag_edition ) 
	{
		echo "<div style='width: 20em; float: right; margin-top: 6em;'>\n" ;
		echo "<div class='c' style='font-weight:bold;'>" ;
		echo "<input type='submit' style='font-weight:bold' name='classement' " ;
		echo "value=\"Enregistrer l'ordre de classement\" /><br />\n" ;
		echo "</div>\n" ;
		echo "</div>\n" ;
	}


	if ( $nombre_candidatures > 1 ) { $s = "s" ; } else { $s = "" ; }
	if ( $flag_edition )
	{
		if ( isset($_SESSION["filtres"]["candidatures"]["etat"]) AND ($_SESSION["filtres"]["candidatures"]["etat"] == "En attente") ) {
			echo "<p class='c' style='padding-top: 3em;'>" ;
		}
		else {
			echo "<p class='c' style='margin-right: 20em; padding-top: 3em;'>" ;
		}
	}
	else {
		echo "<p class='c'>" ;
	}
	
	echo "<strong>" ;
	if ( $nombre_candidatures > $limite ) {
		echo "$limite </strong>/<strong> " ;
	}

	echo "$nombre_candidatures</strong> candidature$s" ;
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
	
		// Administrateur : suppression
		if ( $_SESSION["id"] == "00" )
		{
			echo "<th colspan='1' rowspan='2'>Action</th>" ;
		}

		echo "<th colspan='3'>Candidat</th>";
	
		if ( in_array($_GET["id_session"], $_SESSION["transferable"]) ) {
			echo "\t<th rowspan='2' class='help' title='Transfert'>T</th>\n" ;
		}

		// Plusieurs selectionneurs
		if ( $nb_selectionneurs > 1 )
		{
			for ( $s=0 ; $s < $nb_selectionneurs ; $s++ ) {
				if ( $selectionneurs[$s]["id"] == $_SESSION["id"] ) {
					echo "\t<th rowspan='2'>". $selectionneurs[$s]["selectionneur"] . "</th>\n" ;
				}
				else {
					echo "\t<th rowspan='2' class='plusieurs'>". $selectionneurs[$s]["selectionneur"] . "</th>\n" ;
				}
			}
		}
	
		echo "\t<th rowspan='2'>&Eacute;tat</th>\n" ;
	
		// FIXME date_maj_etat
			echo "<th colspan='1' rowspan='2' class='petit' title=\"Date de mise à jour de l'état du dossier\">Mise à jour<br />de l'état</th>" ;
		if ( $_SESSION["id"] == "00" )
		{
		}

		if ( isset($_SESSION["filtres"]["candidatures"]["etat"]) AND ($_SESSION["filtres"]["candidatures"]["etat"] == "En attente") AND $flag_edition )
		{
			echo "<th rowspan='2' style='border: 0px; background: transparent; color: #000; font-size: 2em; vertical-align: top;'>&darr;</th>\n" ;
		}
		else if ( intval($_SESSION["id"]) < 3 )
		{
			echo "<th rowspan='2' class='invisible'></th>\n" ;
		}

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
			// Pas de case pour les imputés ni les Externes
			if ( ($enr["id_imputation1"] == "") AND ($enr["etat_dossier"] != "Externe") ) {
				echo "\t<td><input type='checkbox' name='imprimer[]' " ;
				echo "value='".$enr["id_dossier"]."' /></td>\n" ;
			}
			else {
				echo "\t<td></td>\n" ;
			}
		}
	
		echo "\t<td>" ;
		echo mysql2datenum($enr["date_maj"]) ;
		echo "</td>\n" ;
	
		// Administrateur : suppression
		if ( $_SESSION["id"] == "00" )
		{
			echo "\t<td><span>" ;
//			echo intval($enr["ref_dossier"]) ;
			if ( ($enr["diplome"]=="Non") AND (intval($enr["id_imputation1"])==0) )
			{
				echo "<a href='supprimer.php?id_dossier=".$enr["id_dossier"]
					."'>Supprimer</a>" ;
			}
			echo "</span></td>\n" ;
		}

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
		echo "<a class='bl' href='candidature.php".$lien ;
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
		echo ">" ;
		if ( ($enr["id_imputation1"] != "") OR ($enr["id_imputation2"] != "") )  {
			echo "<strong>" . $enr["etat_dossier"] . "</strong>" ;
		}
		else {
			echo $enr["etat_dossier"] ;
		}
		if ( ($enr["etat_dossier"] == "En attente") 
			AND ($enr["classement"] != "") ) 
		{
			echo " (" . $enr["classement"] .")" ;
		}
		echo "</td>\n" ;

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
		if ( $_SESSION["id"] == "00" )
		{
		}
		
		if	(
				isset($_SESSION["filtres"]["candidatures"]["etat"]) AND ($_SESSION["filtres"]["candidatures"]["etat"] == "En attente")
				AND $flag_edition
			)
		{
			echo "<td><input type='text' class='r b' size='2' maxlength='4' " ;
			echo "name='ordre[".$enr["id_dossier"]."]' " ;
			echo "value='".$enr["classement"]."' /></td>\n" ;
		}
		//
		// Imputation
		//
		else if ( intval($_SESSION["id"]) < 3 )
		{
			// Affichage d'un <td/> visible ou non
			if	(
					( $enr["etat_dossier"] == "Allocataire" )
					OR ( $enr["etat_dossier"] == "Payant" )
					OR ( $enr["etat_dossier"] == "Allocataire SCAC" )
				)
			{
				// <td/> visible
				if	(
						($enr["id_imputation1"] != "")
						OR ($enr["id_imputation2"] != "")
						OR ($enr["imputations"] == "Oui")
						OR ($enr["imputations2"] == "Oui")
					)
				{
					echo "<td class='l'>" ;
					// 1ère année
					if ( $enr["id_imputation1"] == "" )
					{
						if ($enr["imputations"] == "Oui")
						{
							echo "<span><a class='bl' href='/imputations/imputer.php"
							. "?id_dossier=" . $enr["id_dossier"]
							. "'>". LIEN_IMPUTER ."</a></span>\n" ;
						}
					}
					else
					{
						echo "<span><a class='bl' " ;
						echo "href='/imputations/attestation.php?id=" ;
						echo $enr["id_imputation1"]."'>" ;
						echo LIEN_IMPUTATION ;
						echo "</a></span>" ;
					}
					// 2ème année
					if ( $enr["id_imputation2"] == "" )
					{
						if ( ($enr["imputations2"] == "Oui") AND ($formation["nb_annees"]=="2") )
						{
							echo "<span><a class='bl' href='/imputations/imputer.php"
							. "?id_dossier=" . $enr["id_dossier"] . "&amp;annee_relative=2"
							. "'>". LIEN_IMPUTER_2 ."</a></span>\n" ;
						}
					}
					else
					{
						echo "<span><a class='bl' " ;
						echo "href='/imputations/attestation.php?id=" ;
						echo $enr["id_imputation2"]."'>" ;
						echo LIEN_IMPUTATION_2 ;
						echo "</a></span>" ;
					}
					echo "</td>" ;
				}
				else {
					echo "\t<td class='invisible'></td>\n" ;
				}
			}
			else {
				echo "\t<td class='invisible'></td>\n" ;
			}
		}

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
