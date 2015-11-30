<?php
include("inc_session.php") ;
include("inc_etat_dossier.php") ;

include("inc_mysqli.php") ;
$cnx = connecter() ;

$id_session = $_GET["id_session"] ;

$requete = "SELECT intitule, intit_ses, session.id_session
	FROM session, atelier 
	WHERE id_session=$id_session
	AND atelier.id_atelier=session.id_atelier" ;
$resultat = mysqli_query($cnx, $requete) ;
$formation = mysqli_fetch_assoc($resultat) ;

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
	$selectionneurs[$i]["selectionneur"] = $row["prenomsel"] ." ". $row["nomsel"] ;
	$selectionneurs[$i]["avis"] = array() ;
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
$titre = "Payant établissement" ;
echo $dtd1 ;
echo "<title>$titre</title>" ;
echo $dtd2 ;
include("inc_menu.php") ;

echo $debut_chemin ;
echo "<a href='/bienvenue.php'>Accueil</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo "<a href='/candidatures/index.php'>Gestion des candidatures</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo "<a href='/candidatures/candidatures.php?id_session=".$formation["id_session"]."'>" . $formation["intitule"] . " (".$formation["intit_ses"].")</a>" ;
echo $fin_chemin ;



$requeteSELECT = "SELECT
	id_dossier, etat_dossier, date_inscrip, dossier.date_maj, date_maj_etat, transferts,
	candidat.civilite, candidat.nom, candidat.prenom, candidat.naissance,
	(DATEDIFF(date_deb, naissance) DIV 365.25 ) AS age,
	candidat.pays, candidat.id_candidat
" ;

$requeteCOUNT = "SELECT COUNT(id_dossier) AS N " ;

$requete = " FROM session, dossier
	JOIN candidat ON dossier.id_candidat=candidat.id_candidat
	WHERE dossier.id_session=$id_session
	AND session.id_session=dossier.id_session " ;
$requete .= " AND etat_dossier='Payant établissement'" ;

if ( isset($_GET["pays"]) AND !empty($_GET["pays"]) ) {
	$requete .= " AND pays='".mysqli_real_escape_string($cnx, urldecode($_GET["pays"]))."'" ;
}
$requete .= " ORDER BY nom" ;
//echo $requete ;

$req = $requeteCOUNT . $requete ;
$result = mysqli_query($cnx, $req) ;
$enr = mysqli_fetch_assoc($result) ;
$nombre_candidatures = $enr["N"] ;

if ( $nombre_candidatures == 0 ) {
	echo "<p class='c'>Aucune candidature pour les critères sélectionnés.</p>\n" ;
}
else
{
	$req = $requeteSELECT . $requete ;
	$result = mysqli_query($cnx, $req) ;

	$url = "candidatures.php?id_session=".$id_session ;

	$CIV = array(
		"Madame" => "Mme",
		"Mademoiselle" => "Mlle",
		"Monsieur" => "M."
	) ;
	
	include("inc_date.php") ;

	if ( $nombre_candidatures > 1 ) { $s = "s" ; } else { $s = "" ; }
	
	echo "<p class='c'>" ;
	echo "<strong>$nombre_candidatures</strong> candidature$s" ;
	echo " « <span class='payantetab'>Payant établissement</span> »" ;
	if ( !empty($_GET["pays"]) ) {
		echo " dans le pays : " . urldecode($_GET["pays"]) ;
	}
	echo "</p>" ;


	
	echo "<div style='clear: both;'></div>\n" ;



	echo "<table class='tableau'>\n";
	echo "<thead>\n" ;
	echo "<tr>\n" ;

		echo "<th rowspan='2'>" ;
		echo "<span class='aide' style='float: right' " ;
		echo "title=\"La date de mise à jour est égale à la date de candidature lorsque le candidat n'a pas mis à jour son dossier.\">?</span>" ;
		echo "Date de <br />mise à jour" ;
		echo "</th>\n" ;
	
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

		echo "<th colspan='1' rowspan='2' title=\"Date de mise à jour de l'état du dossier\">Mise à jour<br />de l'état</th>" ;
	
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
	
		echo "\t<td>" ;
		echo mysql2datenum($enr["date_maj"]) ;
		echo "</td>\n" ;
	
		// Pays
		if (  $enr["pays"] == "République Centrafricaine" ) {
			echo "\t<td class='c' title='République Centrafricaine' class='help'>R. Centrafricaine</td>\n" ;
		}
		else {
			echo "\t<td class='c'>". $enr["pays"] ."</td>\n" ;
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
					etatSelectionneur($selectionneurs[$s]["avis"][$enr["id_candidat"]], $selectionneurs[$s]["comm"][$enr["id_candidat"]], TRUE) ;
				}
				else {
					etatSelectionneur($selectionneurs[$s]["avis"][$enr["id_candidat"]], $selectionneurs[$s]["comm"][$enr["id_candidat"]], FALSE) ;
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
		if ( ($nb_selectionneurs == 1) AND isset($selectionneurs[0]["comm"][$enr["id_candidat"]]) AND (trim($selectionneurs[0]["comm"][$enr["id_candidat"]]) != "") ) {
			echo " title=\"".strtr(trim($selectionneurs[0]["comm"][$enr["id_candidat"]]), '"', "'")."\" " ;
		}
		echo ">" . $enr["etat_dossier"] . "</td>\n" ;

		echo "<td>" . mysql2datenum($enr["date_maj_etat"]) . "</td>\n" ;
		
		echo "</tr>\n" ;
	
		$i++;
	}
	echo "</tbody></table>\n";
}

deconnecter($cnx) ;
echo $end ;
?>
