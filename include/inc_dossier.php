<?php
include_once("inc_guillemets.php") ;
include_once("inc_etat_dossier.php") ;
include_once("inc_resultat.php") ;
global $etat_dossier_img_class ;

function estDefini($champ) {
	if ( $champ == "-------" ) {
		return FALSE;
	}
	else if ( $champ == "" ) {
		return FALSE;
	}
	else {
		return TRUE;
	}
}

//
// Mise en page
//
function intitule_champ($champ)
{
	global $CANDIDATURE ;
	if ( $CANDIDATURE[$champ][1] == "" ) {
		$str = $CANDIDATURE[$champ][0] ;
	}
	else {
		$str  = "<span class='help' title=\"" ;
		$str .= strip_tags($CANDIDATURE[$champ][0]) ."\">" ;
		$str .= $CANDIDATURE[$champ][1] ;
		$str .= "</span>" ;
	}
	return $str ;
}
function affiche_tr($champ, $t, $space=FALSE)
{
	echo "<tr>\n\t<th><span class='chp'>" ;
	if ( $space ) {
		echo str_replace(" ", "&nbsp;", intitule_champ($champ)) ;
	}
	else {
		echo intitule_champ($champ) ;
	}
	echo "&nbsp;:</span></th>\n " ;
	echo "\t<td>" ;
	echo $t[$champ] ;
	echo "</td>\n</tr>\n" ;
}
function affiche_p($champ, $t, $newline=FALSE)
{
	echo "<p>" ;
	echo "<span class='chp'>" ;
	echo intitule_champ($champ) ;
	echo "</span><br />" ;
	if ( $newline == FALSE ) {
		echo $t[$champ] ;
	}
	else {
		if ( $champ == "cv" ) {
			echo "<span class='cv'>" ;
			echo nl2br($t[$champ]) ;
			echo "</span>" ;
		}
		else {
			echo nl2br($t[$champ]) ;
			//echo "\n\n<br />\n\n" ;
			//echo $t[$champ] ;
		}
	}
	echo "</p>\n" ;
}
function affiche_p1($champ, $t, $newline=FALSE)
{
	echo "<p>" ;
	echo "<span class='chp1'>" ;
	echo intitule_champ($champ) ;
	echo "</span><br />" ;
	if ( $newline == FALSE ) {
		echo $t[$champ] ;
	}
	else {
		echo nl2br($t[$champ]) ;
	}
	echo "</p>\n" ;
}
function affiche_p1alter($intitule, $champ, $t, $newline=FALSE)
{
	echo "<p>" ;
	echo "<span class='chp1'>" ;
	echo intitule_champ($intitule) ;
	echo "</span><br />" ;
	if ( $newline == FALSE ) {
		echo $t[$champ] ;
	}
	else {
		echo nl2br($t[$champ]) ;
	}
	echo "</p>\n" ;
}


//
// Recherche des autres candidatures
//
function recherche($id_dossier, $id_candidat, $nom, $nom_jf, $naissance, $email1, $cnx)
{
	// Alerte si le candidat est déjà allocataire ou payant dans une autre promotion de la même année
	// C'està dire si il y une candidature allocataire ou payant sans imputation
	$alerte = "" ;

	global $etat_dossier_img_class ;

	$RESULTAT = tab_resultats() ;
	$RESULTAT_IMG_CLASS = tab_resultats_img_class() ;

	echo "<div class='recherche'><div>" ;

	$nom = mysqli_real_escape_string($cnx, trim($nom)) ;
	$nom_jf = mysqli_real_escape_string($cnx, trim($nom_jf)) ;

	$req = "SELECT id_candidat FROM candidat
		WHERE id_candidat!='$id_candidat' AND (
			email1='$email1' OR
			(
				naissance='$naissance' AND
				(
					nom LIKE '".$nom."%'
					OR  ( 
							(civilite!='Monsieur')
							AND (
									( (nom_jf LIKE '".$nom."%') AND (nom_jf!='') ) " ;
		
	if ( trim($nom_jf) != '' ) {
		$req.= " OR ( (nom LIKE '".$nom_jf."%') ) " ;
	}
	$req .= ")
						)
				)
			)
		)" ;

//	echo $req ;

	$res = mysqli_query($cnx, $req) ;
	$N = @mysqli_num_rows($res) ;
	if ( $N > 0 )
	{
		$liste_id_candidat = "" ;
		while ( $enr = mysqli_fetch_assoc($res) ) {
			$liste_id_candidat .= $enr["id_candidat"] . ", " ;
		}
		$liste_id_candidat = substr($liste_id_candidat, 0, -2) ;

		if ( $N > 1 ) {
			$s = "s" ;
		}
		else {
			$s = "" ;
		}
		echo "<p>$N autre".$s." candidature".$s." pour le même nom et la même date de naissance ou la même adresse électronique&nbsp;:</p>" ;
		echo "<ul>\n" ;

		$req = "SELECT dossier.etat_dossier, dossier.id_dossier,
			dossier.date_inscrip, dossier.diplome, dossier.resultat,
			session.intit_ses, annee, evaluations, imputations, imputations2,
			atelier.intitule, anneed,
			(SELECT id_imputation FROM imputations WHERE ref_dossier=id_dossier AND annee_relative=1)
			AS id_imputation1,
			(SELECT id_imputation FROM imputations WHERE ref_dossier=id_dossier AND annee_relative=2)
			AS id_imputation2,
			(SELECT etat FROM imputations WHERE ref_dossier=id_dossier AND annee_relative=2)
			AS etat2
			FROM atelier, session, candidat, dossier
			LEFT JOIN dossier_anciens ON dossier.id_dossier=dossier_anciens.ref_dossier
			WHERE dossier.id_candidat=candidat.id_candidat
			AND dossier.id_session=session.id_session
			AND session.id_atelier=atelier.id_atelier
			AND candidat.id_candidat IN ($liste_id_candidat)
			ORDER BY annee DESC, session.candidatures, dossier.date_inscrip DESC" ;

		$res = mysqli_query($cnx, $req) ;
		while ( $enr = mysqli_fetch_assoc($res) ) {
			echo "<li>" ;
			if ( $enr["evaluations"] == "Oui" ) {
				echo "<strong>".$enr["annee"]."</strong> - " ;
			}
			else {
				echo $enr["annee"]." - " ;
			}
			if ( $enr["imputations"] == "Oui" ) {
				echo "<strong>" ;
			}
			echo "<span class='".$etat_dossier_img_class[$enr["etat_dossier"]]."'>" ;
			echo $enr["etat_dossier"] ;
			echo "</span>" ;
			if ( $enr["id_imputation1"] != "" ) {
				echo " <strong class='paye'>".LABEL_INSCRIT."</strong>" ;
			}
			if ( $enr["id_imputation2"] != "" ) {
				echo " <strong class='paye'>".LABEL_INSCRIT_2."</strong>" ;
				if ( $enr["etat2"] != $enr["etat_dossier"] ) {
					echo " (<span class='small ".$etat_dossier_img_class[$enr["etat2"]]."'>" ;
					echo $enr["etat2"] ;
					echo "</span>)" ;
				}
			}
			if ( $enr["imputations"] == "Oui" ) {
				echo "</strong>" ;
			}
			/*
			if ( $enr["diplome"] == "Oui" ) {
				echo " <span class='diplome'>".LABEL_DIPLOME." ".$enr["anneed"]."</span>" ;
			}
			*/
			if ( $enr["resultat"] != "0" ) {
				echo " <span class='".$RESULTAT_IMG_CLASS[$enr["resultat"]]."'>"
					. $RESULTAT[$enr["resultat"]]
					. "</span>" ;
			}
			
			echo " - <a target='_blank' " ;
			echo "href='/candidatures/autre.php?id_dossier=".$enr["id_dossier"]."'>" ;
			echo $enr["intitule"] ;
			echo "</a>" ;
			echo " (".mysql2datenum($enr["date_inscrip"]).")" ;
			echo "</li>\n" ;
		}
		echo "</ul>\n" ;
	}
	else {
		echo "<p>Pas d'autre candidature pour le même nom et la même date de naissance.</p>\n" ;
	}
	
	echo "</div></div>\n" ;
}

//
// Commentaires, et état du dossier
//

/*
Les commentaires des sélectionneurs peuvent avoir un champ ref_selecteur
correspondant à un sélectionneur qui a été supprimé de la base.
*/

// Retourne la liste (tableau) des sélectionneurs d'une formation
function liste_selecteurs($id_session, $cnx)
{
	$req = "SELECT codesel, nomsel, prenomsel
		FROM selecteurs, atxsel, atelier, session
		WHERE selecteurs.codesel=atxsel.id_sel
		AND atxsel.id_atelier=atelier.id_atelier
		AND atelier.id_atelier=session.id_atelier
		AND session.id_session=$id_session" ;
	$res = mysqli_query($cnx, $req) ;
	$selecteurs = array() ;
	while ( $enr = mysqli_fetch_assoc($res) ) {
		$selecteurs[] = $enr ;
	}
	return $selecteurs ;
}

function comment_auf($T, $id, $cnx, $formulaire=TRUE)
{
	$req = "SELECT * FROM comment_auf
		WHERE ref_candidat=".$T["id_candidat"] ;
	$res = mysqli_query($cnx, $req) ;
	$enr = mysqli_fetch_assoc($res) ;

	if ($formulaire  AND ( ($T["evaluations"]=="Oui") OR ($_SESSION["id"]=="00") ) )
	{
		echo "<tr class='noprint'>\n" ;
		echo "<th>AUF&nbsp;:</th>\n" ;
		echo "<td colspan='2'>" ;
		if ( intval($id) < 3 ) {
			echo "<input type='hidden' name='id_comment_auf' " ;
			echo "value='".$enr["id_comment_auf"]."' />" ;
			echo "<textarea name='comment_auf' cols='70' rows='4'>" ;
		}
		echo $enr["commentaire"] ;
		if ( intval($id) < 3 ) {
			echo "</textarea>" ;
		}
		echo "</td>\n" ;
		echo "</tr>\n" ;

		echo "<tr class='printonly'>\n" ;
		echo "<th>AUF&nbsp;:</th>\n" ;
		echo "<td>" ;
		echo $enr["commentaire"] ;
		echo "</td>\n" ;
		echo "</tr>\n" ;
	}
	else {
		echo "<tr>\n" ;
		echo "<th>AUF&nbsp;:</th>\n" ;
		echo "<td>" ;
		echo nl2br($enr["commentaire"]) ;
		echo "</td>\n" ;
		echo "</tr>\n" ;
	}
}

function comment_sel($T, $selecteurs, $id, $cnx, $formulaire=TRUE)
{
	global $etat_dossier_img_class ;

	$plusieurs = FALSE ;
	$nombre = count($selecteurs) ;
	if ( $nombre > 1 ) {
		$plusieurs = TRUE ;
	}

	foreach($selecteurs as $selecteur)
	{
		$req = "SELECT * FROM comment_sel
			WHERE ref_candidat=".$T["id_candidat"]."
			AND ref_selecteur=".$selecteur["codesel"] ;
		$res = mysqli_query($cnx, $req) ;
		$enr = mysqli_fetch_assoc($res) ;

		if ($formulaire  AND ( ($T["evaluations"]=="Oui") OR ($_SESSION["id"]=="00") ) )
		{
			// Formulaire, non imprimé
			echo "<tr class='noprint'>\n" ;
			echo "\t<th>".$selecteur["prenomsel"]." <span class='majuscules'>". $selecteur["nomsel"]."</span>&nbsp;:</th>\n" ;
			if ( $plusieurs ) { echo "<td>" ; } else { echo "<td colspan='2'>" ; }
			if ( $selecteur["codesel"] == $id ) {
				echo "<input type='hidden' name='id_comment_sel' " ;
				echo "value='".$enr["id_comment_sel"]."' />" ;
				echo "<textarea name='comment_sel' cols='70' rows='4'>" ;
			}
			echo $enr["commentaire"] ;
			if ( $selecteur["codesel"] == $id ) {
				echo "</textarea>" ;
			}
			echo "</td>\n" ;
			if ( $plusieurs ) {
				if ( $selecteur["codesel"] == $id ) {
					echo "<td style='vertical-align: top' class='".$etat_dossier_img_class[$enr["etat_sel"]]."'>" ;
					liste_etats("etat_sel", $enr["etat_sel"]) ;
					echo "</td>\n" ;
				}
				else {
					if ( $enr["etat_sel"]!="" ) { 
						echo "<td style='vertical-align: top' class='".$etat_dossier_img_class[$enr["etat_sel"]]."'>".$enr["etat_sel"]."</td>\n" ;
					} else { echo "<td class='nonetudie'>Non étudié</td>\n" ; }
				}
			}
			echo "</tr>\n" ;
	
			// Affiché seulement à l'impression
			echo "<tr class='printonly'>\n" ;
			echo "\t<th>".$selecteur["prenomsel"]." <span class='majuscules'>". $selecteur["nomsel"]."</span>&nbsp;:</th>\n" ;
			if ( $plusieurs ) { echo "<td>" ; } else { echo "<td colspan='2'>" ; }
			echo $enr["commentaire"] ;
			echo "</td>\n" ;
			if ( $plusieurs ) {
				// Si il n'y a pas de commentaire, il n'y a pas d'état, afficher Non étudié
				if ( $enr["etat_sel"]!="" ) { 
					echo "<td style='vertical-align: top' class='".$etat_dossier_img_class[$enr["etat_sel"]]."'>".$enr["etat_sel"]."</td>\n" ;
				} else { echo "<td class='nonetudie'>Non étudié</td>\n" ; }
			}
			echo "</tr>\n" ;
		}
		else {
			echo "<tr>\n" ;
			echo "\t<th>".$selecteur["prenomsel"]." <span class='majuscules'>". $selecteur["nomsel"]."</span>&nbsp;:</th>\n" ;
			if ( $plusieurs ) { echo "<td>" ; } else { echo "<td colspan='2'>" ; }
			echo nl2br($enr["commentaire"]) ;
			echo "</td>\n" ;
			if ( $plusieurs ) {
				// Si il n'y a pas de commentaire, il n'y a pas d'état, afficher Non étudié
				if ( $enr["etat_sel"]!="" ) { 
					echo "<td style='vertical-align: top' class='".$etat_dossier_img_class[$enr["etat_sel"]]."'>".$enr["etat_sel"]."</td>\n" ;
				} else { echo "<td class='nonetudie'>Non étudié</td>\n" ; }
			}
			echo "</tr>\n" ;
		}
	}
}

/*
      _                       _               
   __| |   ___    ___   ___  (_)   ___   _ __ 
  / _` |  / _ \  / __| / __| | |  / _ \ | '__|
 | (_| | | (_) | \__ \ \__ \ | | |  __/ | |   
  \__,_|  \___/  |___/ |___/ |_|  \___| |_|

$formulaire=TRUE : Afficher le formulaire du dossier (pour les sélectionneurs
	mais pas por les vieux dossiers).
$selectionneur=TRUE : Si sélectionneur, afficher autres candidatures
	(ou admin ou bourses ou CNF)
$modification=FALSE : Si (sélectionneur ou) candidat qui modifie son dossier
	utiliser les champs provenant de la BDD
	Sinon utiliser les variables provenant du formulaire en POST
	(Doit être TRUE pour un candidat qui modifie son dossier
$boutons=FALSE : TRUE pour afficher :
	- le bouton pour renvoyer un mail
	- le bouton pour joindre un fichier,
	- les liens pour supprimer les fichiers joints
*/
require_once("inc_pays.php") ;
function affiche_dossier($T, $session, $cnx,
	$formulaire=TRUE, $selectionneur=TRUE, $modification=FALSE, $boutons=FALSE)
{
	// Un tableau des code => nom de pays
	// pour éviter plusieurs jointures supplémentaires pour l'affichage des pays d'un dossier
	// et pour tenir compte du fait que ces pays ne sont pas toujours renseignés
	$statiquePays = statiquePays($cnx) ;

	while ( list($key, $val) = each($T) ) {
		$T[$key] = sans_balise($val) ;
	}

	global $SECTION_CANDIDATURE ;
	global $etat_dossier_img_class;
	$RESULTAT = tab_resultats() ;
	$RESULTAT_IMG_CLASS = tab_resultats_img_class() ;
	echo "<div class='dossier_candidature'>\n" ;
	
	if ( $selectionneur )
	{
		recherche($T["id_dossier"], $T["id_candidat"], $T["nom"], $T["nom_jf"], $T["naissance"], $T["email1"], $cnx) ;

		require_once("inc_historique.php") ;
		echo historiqueShow($cnx, $T["id_dossier"]) ;

		require_once("inc_historique_resultat.php") ;
		echo historiqueResultatShow($cnx, $T["id_dossier"]) ;
	}
	
	echo "<h1>" ;
	echo "<span style='font-size: smaller;'>" ;
	echo $T["civilite"] ;
	echo "</span> " ;
	echo " <span class='majuscules'><em>" ;
	echo strtoupper($T["nom"]) ;
	echo "</em></span> " ;
	echo ucwords(strtolower($T["prenom"])) ;
	if ( $T["civilite"] == "Madame" ) {
		echo " <span style='font-size: smaller;'>née " ;
		echo strtoupper($T["nom_jf"]) ;
		echo "</span> " ;
	}
	echo "</h1>\n" ;

	if ( $selectionneur ) {
		if ( $T["transferts"] != "" ) {
			echo "<p><span class='transferts'>Dossier transféré</span>&nbsp;: " ;
			echo $T["transferts"]."</p>\n" ;
		}
	}

//	echo "<div style='float:right'>\n" ;
	echo "<table class='donnees' style='margin: 0;'>\n" ;

	if ( $selectionneur ) {
		echo "<tr>\n" ;
		echo "<th><span class='chp'>&Eacute;tat du dossier&nbsp;:</span></th>\n" ;
		echo "<td><span class='".$etat_dossier_img_class[$T["etat_dossier"]]."'>" ;
		echo "<strong>".$T["etat_dossier"]."</strong>" ;
		if ( ($T["etat_dossier"]=="En attente") AND ($T["classement"]!="") ) {
			echo " (<strong>".$T["classement"]."</strong>)" ;
		}
		echo "</span>" ;
		// Imputation
		if ( isset($T["id_imputation1"]) AND ($T["id_imputation1"] != "") ) {
			echo " <strong class='paye'>".LABEL_INSCRIT."</strong>" ;
		}
		if ( isset($T["id_imputation2"]) AND ($T["id_imputation2"] != "") ) {
			echo " <strong class='paye'>".LABEL_INSCRIT_2."</strong>" ;
			if ( isset($T["etat2"]) AND ($T["etat2"] != $T["etat_dossier"]) ) {
				echo " (<span class='small ".$etat_dossier_img_class[$T["etat2"]]."'>" ;
				echo $T["etat2"] ;
				echo "</span>)" ;
			}
		}
		/*
		if ( isset($T["diplome"]) AND ($T["diplome"] == "Oui") ) {
			echo " <span class='diplome'>".LABEL_DIPLOME." ".$T["anneed"]."</span>" ;
		}
		*/
		if ( $T["resultat"] != "0" ) {
			echo " <span class='".$RESULTAT_IMG_CLASS[$T["resultat"]]."'>"
				. $RESULTAT[$T["resultat"]]
				. "</span>" ;
		}

		echo "</td>\n" ;
		echo "</tr>\n" ;
	}
	if ( $selectionneur ) {
		echo "<tr>\n" ;
		echo "<th><span class='chp'>Candidature le&nbsp;:</span></th>\n" ;
		echo "<td>".mysql2datealpha($T["date_inscrip"])."</td>\n" ;
		echo "</tr>\n" ;
	}

	if ( $T["date_inscrip"] != $T["date_maj"] ) {
		echo "<tr>\n" ;
		echo "<th><span class='chp'>Mise à jour le&nbsp;:</span></th>\n" ;
		echo "<td>".mysql2datealpha($T["date_maj"])."</td>\n" ;
		echo "</tr>\n" ;
	}
	?></table><?php
//	echo "</div>\n" ;
	
	
	?><table class='donnees'><?php
	if ( isset($T["naissance"]) AND ($T["naissance"] != "") ) {
		$tab["naissance"] = mysql2datealpha($T["naissance"]) ;
	}
	else {
		$tab["naissance"] = mysql2datealpha($T["annee_n"] ."-". $T["mois_n"] ."-". $T["jour_n"]) ;
	}
	affiche_tr("naissance", $tab, TRUE) ;
	affiche_tr("situation_actu", $T, TRUE) ;
	if ( $T["sit_autre"] != "" ) {
		echo "<tr><td></td><td>" . $T["sit_autre"] ."</td></tr>" ;
	}
	?></table><?php
	?><table class='donnees'><?php
	echo "<tr>\n\t<th><span class='chp'>" . intitule_champ("pays_naissance") . "&nbsp;:</span></th>\n " ;
		echo "\t<td>" . refPays($T["pays_naissance"], $statiquePays) . "</td>\n</tr>\n" ;
	affiche_tr("nationalite", $T) ;
	echo "<tr>\n\t<th><span class='chp'>" . intitule_champ("pays") . "&nbsp;:</span></th>\n " ;
		echo "\t<td>" . refPays($T["pays"], $statiquePays) . "</td>\n</tr>\n" ;
	?></table><?php
	
	echo "<h2>".$SECTION_CANDIDATURE["2"]."</h2>\n" ;
	
	echo "<p>" ;
	echo $T["adresse"] ;
	echo " - " ;
	echo $T["code_postal"] ;
	echo " - " ;
	echo $T["ville"] ;
	echo " - " ;
	echo refPays($T["pays"], $statiquePays) ;
	echo "<br />" ;
	echo "<span class='chp1'>Tél :</span> ".$T["tel"] ;
	if ( strlen($T["tlc_perso"]) > 3 ) {
		echo " - <span class='chp1'>Tlc :</span> ". $T["tlc_perso"] ;
	}
	echo "<br />" ;
	if ( $boutons ) {
		echo "<span style='float: right;'><strong>" ;
		echo "<input class='b' type='submit' name='submit' value='Envoyer un courriel' " ;
		echo "title='Envoyer un courriel contenant votre identifiant et votre mot de passe à ".$T["email1"]."' style='cursor: help;' />";
		echo "</strong></span>" ;
	}
	echo "<strong><a href='mailto:".$T["email1"]."'>".$T["email1"]."</a></strong>" ;
	if ( $T["email2"] != "" ) {
		echo "<br />" . $T["email2"] ;
	}
	echo "</p>\n\n" ;
	
	
	echo "<h2>".$SECTION_CANDIDATURE["3"]."</h2>\n" ;
	
	?><table class='donnees'><?php
	affiche_tr("duree_exp", $T) ;
	?></table><?php
	
	if (
		(
			( $T["situation_actu"] != "Etudiant(e)" )
			AND ( $T["situation_actu"] != "Sans emploi" ) 
		)
	)
	{
		?><table class='donnees'><?php
		affiche_tr("emploi_actu", $T) ;
		affiche_tr("employeur", $T) ;
		affiche_tr("service", $T) ;
		affiche_tr("titre", $T) ;
		?></table><?php
		echo "<p>" ;
		echo $T["adresse_emp"] ;
		echo " - " ;
		echo $T["codepost_emp"] ;
		echo " - " ;
		echo $T["ville_emp"] ;
		echo " - " ;
		echo refPays($T["pays_emp"], $statiquePays) ;
		echo "<br />" ;
		echo "<span class='chp1'>Tél :</span> ".$T["tel_emp"] ;
		if ( $T["tlc_perso"] != "" ) {
			echo " - <span class='chp1'>Tlc :</span> ". $T["fax_emp"] ;
		}
		echo "<br />" ;
		echo "<a href='mailto:".$T["email_pro1"]."'>".$T["email_pro1"]."</a>" ;
		if ( $T["email_pro2"] != "" ) {
			echo ", ".$T["email_pro2"] ;
		}
		echo "</p>\n\n" ;
	}
	
	// Sans les Externes
	if ( ($T["etat_dossier"] != "Externe") )
	{
		echo "<h2>".$SECTION_CANDIDATURE["4"]."</h2>\n" ;
		?><table class='donnees'><?php
		affiche_tr("niveau_dernier_dip", $T) ;
		affiche_tr("dernier_dip", $T) ;
		affiche_tr("info_dernier_dip", $T) ;
		?></table><?php
		// Aussi dans www/candidature/formulaire_candidature.php
		if ( isset($T["ref_institution"]) AND ($T["ref_institution"] == "27") )
		{
			if ( $T["inscri_europe"] != "" ) {
				affiche_p1alter("inscri_ouaga", "inscri_europe", $T, TRUE) ;
			}
			if ( $T["code_ine"] != "" ) {
				affiche_p1alter("code_ouaga", "code_ine", $T) ;
			}
		}
		else
		{
			if ( $T["inscri_europe"] != "" ) {
				affiche_p1("inscri_europe", $T, TRUE) ;
			}
			if ( $T["code_ine"] != "" ) {
				affiche_p1("code_ine", $T) ;
			}
		}
		
		echo "<div><span class='chp'>Derniers diplômes académiques en cours et obtenus</span></div>\n" ;
		echo "<table class='tableau'>\n" ;
		echo "<tr>\n" ;
		echo "\t<th>Année</th>\n" ;
		echo "\t<th>Titre du diplôme</th>\n" ;
		echo "\t<th>Mention</th>\n" ;
		echo "\t<th>Établissement</th>\n" ;
		echo "\t<th>Pays</th>\n" ;
		echo "</tr>\n" ;
		if ( $selectionneur OR $modification )
		{
			$req = "SELECT * FROM diplomes WHERE id_candidat=".$T["id_candidat"] ;
			$res = mysqli_query($cnx, $req) ;
			while ( $enr = mysqli_fetch_assoc($res) ) 
			{
				if	(
						( $enr["annee_dip"] != "" )
						AND ( $enr["titre_dip"] != "" )
						AND ( $enr["etab_dip"] != "" )
					)
				{
					echo "<tr>\n" ;
					echo "<td>".$enr["annee_dip"]."</td>\n" ;
					echo "<td>".$enr["titre_dip"]."</td>\n" ;
					echo "<td>".$enr["mention_dip"]."</td>\n" ;
					echo "<td>".$enr["etab_dip"]."</td>\n" ;
					echo "<td>".refPays($enr["pays_dip"], $statiquePays)."</td>\n" ;
					echo "</tr>\n" ;
				}
			}
		}
		else {
			for ( $i=1; $i <=4 ; $i++ ) {
				if ( ($T["annee_dip$i"]!="") AND ($T["titre_dip$i"]!="")
					AND ($T["etab_dip$i"]!="") )
				{
					echo "<tr>\n" ;
					echo "<td>".$T["annee_dip$i"]."</td>\n" ;
					echo "<td>".$T["titre_dip$i"]."</td>\n" ;
					echo "<td>".$T["mention_dip$i"]."</td>\n" ;
					echo "<td>".$T["etab_dip$i"]."</td>\n" ;
					echo "<td>".$T["pays_dip$i"]."</td>\n" ;
					echo "</tr>\n" ;
				}
			}
		}
		echo "</table>\n" ;
		
		echo "<div><span class='chp'>Stages de formation suivis ou certifications professionnelles obtenues</span></div>\n" ;
		echo "<table class='tableau'>\n" ;
		echo "<tr>\n" ;
		echo "\t<th>Année</th>\n" ;
		echo "\t<th>Titre du stage ou de la certification</th>\n" ;
		echo "\t<th>Organisateur</th>\n" ;
		echo "</tr>\n" ;
		if ( $selectionneur OR $modification )
		{
			$req = "SELECT * FROM stage WHERE id_candidat=".$T["id_candidat"] ;
			$res = mysqli_query($cnx, $req) ;
			while ( $enr = mysqli_fetch_assoc($res) )
			{
				if ( $enr["annee_stage"] != "" AND $enr["titre_stage"] != ""
					AND $enr["org_stage"] != "" )
				{
					echo "<tr>\n" ;
					echo "<td>".$enr["annee_stage"]."</td>\n" ;
					echo "<td>".$enr["titre_stage"]."</td>\n" ;
					echo "<td>".$enr["org_stage"]."</td>\n" ;
					echo "</tr>\n" ;
				}
			}
		}
		else {
			for ( $i=1; $i <=4 ; $i++ ) {
				if ( ($T["annee_stage$i"]!="") AND ($T["titre_stage$i"]!="")
					AND ($T["org_stage$i"]!="") )
				{
					echo "<tr>\n" ;
					echo "<td>".$T["annee_stage$i"]."</td>\n" ;
					echo "<td>".$T["titre_stage$i"]."</td>\n" ;
					echo "<td>".$T["org_stage$i"]."</td>\n" ;
					echo "<tr>\n" ;
				}
			}
		}
		echo "</table>\n" ;
		
		
		
		echo "<h2>".$SECTION_CANDIDATURE["5"]."</h2>\n" ;
		affiche_p("exp_dist", $T, TRUE) ;
		if ( $T["exp_dist"] == "Oui" ) {
			affiche_p("format_dist", $T, TRUE) ;
		}
		affiche_p("exp_internet", $T, TRUE) ;
		affiche_p("exp_bureau", $T, TRUE) ;
		
		echo "<h2>".$SECTION_CANDIDATURE["6"]."</h2>\n" ;
		affiche_p("projet_perso", $T, TRUE) ;
		affiche_p("lettre_motiv", $T, TRUE) ;
		affiche_p("cv", $T, TRUE) ;
		
		echo "<h2>".$SECTION_CANDIDATURE["7"]."</h2>\n" ;
		
		affiche_p1("bourse_auf", $T) ;
		affiche_p1("financement_form", $T) ;
		if ( $T["financement_form"] == "Autre" ) {
			affiche_p1("autre_pec", $T) ;
		}
		affiche_p1("prix_sud", $T) ;
		if ( $T["prix_sud"] == "Oui" ) {
			affiche_p1("financement_sud", $T) ;
			if ( $T["financement_sud"] == "Autre" ) {
				affiche_p1("autre_sud", $T) ;
			}
		}
		
		
		echo "<h2>".$SECTION_CANDIDATURE["8"]."</h2>\n" ;
		
		?><table class='donnees'><?php
		affiche_tr("nbre_heures", $T) ;
		// Après 2008
		// on compte sur les controles du formulaire, on ne teste que le 1er
		if ( estDefini($T["ordipro"]) )
		{
			affiche_tr("ordipro", $T) ;
			affiche_tr("netpro", $T) ;
			echo "<tr>\n\t<th><span class='chp'>"
				.intitule_champ("ordiperso")."&nbsp;:</span></th>\n\t<td>"
				. $T["ordiperso"] ;
			if ( $T["ordiperso"] == "Oui" ) {
				echo " : ". $T["fixeportable"] ;
			}
			echo "</td>\n</tr>\n" ;
			affiche_tr("netperso", $T) ;
		}
		// Avant 2008 :
		// on compte sur les controles du formulaire, on ne teste que le 1er
		if ( isset($T["acces_pc"]) AND estDefini($T["acces_pc"]) )
		{
			affiche_tr("acces_pc", $T) ;
			if ( $T["acces_pc"] == "Oui" ) {
				affiche_tr("appart_pc", $T) ;
			}
			affiche_tr("connexion_int", $T) ;
			if ( $T["connexion_int"] == "Non" ) {
				affiche_tr("autre_acces_internet", $T) ;
			}
		}
		affiche_tr("service_cnf", $T) ;
		if ( $T["service_cnf"] == "Oui" ) {
			affiche_tr("temps_dep", $T) ;
			affiche_tr("nbre_dep", $T) ;
		}
		?></table><?php
		
	
		include("../candidature/questions.php") ;
		
		
		if ( $nombre_questions > 0 )
		{
			echo "<h2>".$SECTION_CANDIDATURE["9"]."</h2>\n" ;

			$req = "SELECT * FROM reponse WHERE id_dossier=".$T["id_dossier"]."
				ORDER BY id_question" ;
		
			//echo $req ;
			$res = mysqli_query($cnx, $req) ;
		
			$i = 1 ;
			foreach($Questions as $question)
			{
				$ligne = mysqli_fetch_assoc($res) ;
				echo "<p><span class='chp'>" ;
				echo $question["texte_quest"] ;
				echo "</span><br />" ;
				echo nl2br($ligne["texte_rep"]) ;
				echo "</p>\n" ;
			}
		}
		
	
		
		echo "<h2>".$SECTION_CANDIDATURE["fichiers"]."</h2>\n" ;
		
		if ( $boutons)
		{
			echo "<div style='float: right'>" ;
			echo "<strong><input class='b' type='submit' name='submit' value='Joindre un fichier' /></strong>" ;
			echo "</div>\n" ;
		}
	
		$req = "SELECT * FROM pj WHERE ref_dossier=".$T["id_dossier"] ;
		$res = mysqli_query($cnx, $req) ;
		if ( @mysqli_num_rows($res) == 0 ) {
			echo "<p>Aucun fichier.</p>\n" ;
		}
		else {
			echo "<ul>\n" ;
			while ( $row=mysqli_fetch_assoc($res) )
			{
				if ( $row["poubelle"] == 0 )
				{
					$extension4 = strtolower(substr($row["fichier"], -4, 4)) ;
					if	(
							( $extension4 == ".jpg" )
							OR ( $extension4 == ".png" )
							OR ( $extension4 == ".gif" )
						)
					{
						$blank = "target='_blank'" ;
					}
					else
					{
						$blank = "" ;
					}
					echo "<li>" ;
					echo "<a $blank href='/candidature/pj.php?id_pj=".$row["id_pj"] ;
					echo "&amp;ref_dossier=".$row["ref_dossier"] ;
					echo "&amp;fichier=".urlencode($row["fichier"])."'>" ;
					echo $row["fichier"] ;
					echo "</a>" ;
					echo " (". intval($row["taille"]/1024.0)."ko)" ;
					if ( $boutons ) {
						echo " <input type='submit' name='".$row["id_pj"]."' " ;
						echo "value='Supprimer ce fichier' />" ;
					}
					echo "</li>\n" ;
				}
				else {
					echo "<li>" ;
					echo $row["fichier"] ;
					echo " (". intval($row["taille"]/1024.0)."ko)" ;
					echo " <small><i>(supprimé)</i></small>" ;
					echo "</li>\n" ;
				}
			}
			echo "</ul>\n" ;
		}
		
		
		
		echo "<h2>".$SECTION_CANDIDATURE["signature"]."</h2>\n" ;
		echo "<p>Je soussigné(e) ".$T["signature"]. " certifie sur l'honneur l'exactitude des renseignements ci-dessus.</p>
	<p>Fait à ".$T["ville_res"] . " le ".$T["date_sign"].".</p>" ;
		
	
		//
		// Commentaires etat
		//
		if ( $selectionneur ) {
	
			// Sélectionneurs
			if ( intval($session["id"]) > 3 ) {
				if (
					in_array($T["id_session"], $session["tableau_promotions"])
					AND ( $formulaire == TRUE ) AND ( ($T["evaluations"]=="Oui") OR ($_SESSION["id"]=="00") ) 
				)
			{
					echo "<h2>".$SECTION_CANDIDATURE["commentaires"]."</h2>\n" ;
				}
				else {
					echo "<h2>Évaluations</h2>\n" ;
				}
			}
			else 
			{
				// CNF
				if ( ( $session["id"] != "02" ) AND ( $session["id"] != "03" ) AND ( $formulaire == TRUE ) AND ($T["evaluations"]=="Oui") ) {
					echo "<h2>".$SECTION_CANDIDATURE["commentaires"]."</h2>\n" ;
				}
				else {
					echo "<h2>Évaluations</h2>\n" ;
				}
			}
	

			require_once("inc_historique.php") ;
			echo historiqueShow($cnx, $T["id_dossier"]) ;

			echo "<form action='maj_dossier.php' method='post'>" ;
			echo "<input type='hidden' name='id_dossier' value='".$T["id_dossier"]."' />\n" ;
			echo "<input type='hidden' name='id_candidat' value='".$T["id_candidat"]."' />\n" ;
			echo "<input type='hidden' name='id_session' value='".$T["id_session"]."' />\n" ;
			// Pour l'historique
			echo "<input type='hidden' name='evaluations' value='"
				. ( isset($T["evaluations"]) ? $T["evaluations"] : "" )
				. "' />\n" ;
	
			echo "<table class='formulaire' style='margin: 0;'>\n" ;
			comment_auf($T, $session["id"], $cnx, $formulaire) ;
			$selecteurs = liste_selecteurs($T["id_session"], $cnx) ;
			comment_sel($T, $selecteurs, $session["id"], $cnx, $formulaire) ;
	
			if ($formulaire  AND ( ($T["evaluations"]=="Oui") OR ($_SESSION["id"]=="00") ) )
			{
				// Pas de changement d'état pour CNF ni SCAC
				// Ni si imputation
				if ( 
					($session["id"] != "02") AND ($session["id"] != "03")
					// FIXME bogue
					//AND ( isset($T["ref_dossier"]) AND ($T["ref_dossier"] == "") )
					AND ( ($T["ref_dossier"] == "") )
					)
				{
					echo "<tr class='noprint'>\n" ;
					echo "<th>&Eacute;tat du dossier&nbsp;:</th>\n" ;
					echo "<td colspan='2' class='".$etat_dossier_img_class[$T["etat_dossier"]]."'>" ;
					liste_etats("etat", $T["etat_dossier"]) ;
					echo "</td>\n" ;
					echo "</tr>\n" ;
				}
				// Transfert pour les sélectionneurs avec plusieurs promotions
				// ssi non imputé
				if ( 
					( intval($session["id"]) > 3 )
					AND in_array($T["id_session"], $_SESSION["transferable"])
					AND ( $session["transfert"] == "Oui" )
					AND ( count($session["tableau_promotions"]) > 1 )
					AND ( $T["ref_dossier"] == "" )
					)
				{
					$i = 0 ;
					foreach($session["tableau_promotions"] as $trans) {
						if ( $trans != $T["id_session"] ) {
							if ( $i == 0 ) {
								$liste_transferts = "$trans" ;
							}
							else {
								$liste_transferts .= ", $trans" ;
							}
							$i++ ;
						}
					}
					echo "<tr class='noprint'>\n" ;
					echo "<th>Transférer en&nbsp;:</th>\n" ;
					echo "<td colspan='2'>" ;
					echo "<select name='transfert'>\n" ;
					echo "<option value=''></option>\n" ;
					$req = "SELECT id_session, intitule, intit_ses
						FROM atelier, session
						WHERE session.id_atelier=atelier.id_atelier
						AND session.id_session IN ($liste_transferts)
						ORDER BY niveau, intitule" ;
					$res = mysqli_query($cnx, $req) ;
					while ( $enr = mysqli_fetch_assoc($res) ) {
						echo "<option value='".$enr["id_session"]."'>" ;
						echo $enr["intitule"] ;
	//					echo " (".$enr["intit_ses"].")" ;
						echo "</option>\n" ;
					}
					echo "</select>\n" ;
					echo "</td>\n" ;
					echo "</tr>\n" ;
				}
	
				// Ni commentaire, no changement etat pour SCAC
				if ( intval($session["id"]) != 3 ) {
					echo "<tr><td colspan='2' style='background: #fff'>" ;
					echo "<p class='noprint c'><input type='submit' style='font-weight: bold;' value='Enregistrer' /></p>\n" ;
					echo "</td></tr>" ;
				}
			}
	
			echo "</table>\n" ;
	
			echo "</form>\n" ;
		}
	// Sans les Externes
	}
	echo "</div>\n\n\n" ;
}
?>
