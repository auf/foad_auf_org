<?php
$EXPORTER = array(
	"civilite" => 1,
	"nom" => 1,
	"nom_jf" => 1,
	"prenom" => 1,
	"naissance" => 1,
	"pays_naissance" => 1,
	"genre" => 1,
	"nationalite" => 1,
	"situation_actu" => 1,
	"sit_autre" => 1,
	"adresse" => 1,
	"code_postal" => 1,
	"ville" => 1,
	"pays" => 1,
	"tel" => 1,
	"tlc_perso" => 1,
	"email1" => 1,
	"email2" => 1,
	"emploi_actu" => 1,
	"employeur" => 1,
	"service" => 1,
	"titre" => 1,
	"adresse_emp" => 1,
	"codepost_emp" => 1,
	"ville_emp" => 1,
	"pays_emp" => 1,
	"tel_emp" => 1,
	"fax_emp" => 1,
	"email_pro1" => 1,
	"email_pro2" => 1,
	"duree_exp" => 1,
	"niveau_dernier_dip" => 1,
	"dernier_dip" => 1,
	"info_dernier_dip" => 1,
	"inscri_europe" => 1,
	"code_ine" => 1,

	"exp_dist" => 2,
	"format_dist" => 2,
	"exp_internet" => 2,
	"exp_bureau" => 2,
	"projet_perso" => 2,
	"lettre_motiv" => 2,
	"cv" => 2,
	"bourse_auf" => 2,
	"financement_form" => 2,
	"autre_pec" => 2,
	"prix_sud" => 2,
	"financement_sud" => 2,
	"autre_sud" => 2,
	"nbre_heures" => 2,
	// Jusqu'en 2007
	"acces_pc" => 2,
	"appart_pc" => 2,
	"connexion_int" => 2,
	"autre_acces_internet" => 2,
	// Depuis 2008
	"ordipro" => 2,
	"netpro" => 2,
	"ordiperso" => 2,
	"fixeportable" => 2,
	"netperso" => 2,
	"service_cnf" => 2,
	"temps_dep" => 2,
	"nbre_dep" => 2
) ;
$DIPLOMES = array(
	"Année 1" => 7,
	"Titre du diplôme 1" => 30,
	"Mention 1" => 10,
	"Établissement 1" => 30,
	"Pays 1" => 10,
	"Année 2" => 7,
	"Titre du diplôme 2" => 30,
	"Mention 2" => 10,
	"Établissement 2" => 30,
	"Pays 2" => 10,
	"Année 3" => 7,
	"Titre du diplôme 3" => 30,
	"Mention 3" => 10,
	"Établissement 3" => 30,
	"Pays 3" => 10,
	"Année 4" => 7,
	"Titre du diplôme 4" => 30,
	"Mention 4" => 10,
	"Établissement 4" => 30,
	"Pays 4" => 10,
) ;
$STAGES = array(
	"Année 1" => 7,
	"Titre du stage ou de la certification 1" => 30,
	"Organisateur 1" => 30,
	"Année 2" => 7,
	"Titre du stage ou de la certification 2" => 30,
	"Organisateur 2" => 30,
	"Année 3" => 7,
	"Titre du stage ou de la certification 3" => 30,
	"Organisateur 3" => 30,
	"Année 4" => 7,
	"Titre du stage ou de la certification 4" => 30,
	"Organisateur 4" => 30,
) ;

// Tableau des champs à exporter, par table
// (Issu du tabeau enregistré dans la session)
// Pour les exports par annee, il faudrait mutiplier les colonnes qui
// dependent de la promotion (question, selectionneurs) ; ce serait
// très couteux en memoire ; et vraissemblablement inutile
// Ces colonnes sont supprimees das index.php
function exporter2champs($exporter)
{
	global $EXPORTER ;

	$champs = array(
		"candidat" => array(),
		"candidat1" => array(),
		"candidat2" => array(),
		"dossier" => array(),
		"diplomes" => "",
		"stages" => "",
		"questions" => "",
		"commentaires" => "",
		"liste_candidat" => "",
		"liste_dossier" => ""
	/*
	*/
	) ;
	if ( count($exporter) == 0 ) {
		return $champs ;
	}
	$speciaux = array(
		"diplomes" => "diplomes",
		"stages" => "stages",
		"questions" => "questions",
		"commentaires" => "commentaires",
		"date_inscrip" => "dossier",
		"date_maj" => "dossier",
		"transferts" => "dossier",
		"etat_dossier" => "dossier",
		"date_maj_etat" => "dossier",
		"classement" => "dossier",
	//	"diplome" => "dossier",
		"resultat" => "dossier",
		"date_maj_resultat" => "dossier",
	) ;
	$nombre = array(
		"candidat" => 0,
		"dossier" => 0
	) ;
	while ( list($key, $val) = each($exporter) )
	{
		// Champ rajoute : formation
		if ( $key == "intitule" ) {
				$champs["$key"] = $val ;
		}

		// Champ qui n'est pas dans la table candidat
		else if ( array_key_exists($key, $speciaux) ) {
			// Champs de la table dossier
			if ( $speciaux[$key] == "dossier" ) {
				$champs["dossier"][] = $val ;
				if ( $nombre["dossier"] > 0 ) {
					$champs["liste_dossier"] .= ", " ;
				}
				$champs["liste_dossier"] .= $val ;
				$nombre["dossier"]++ ;
			}
			// Autre
			else {
				$champs["$key"] = $val ;
			}
		}
		// Cahmps de la table candidat
		else {
			if ( $nombre["candidat"] > 0 ) {
				$champs["liste_candidat"] .= ", " ;
			}
			$champs["liste_candidat"] .= $val ;
			$champs["candidat"][] = $val ;
			$nombre["candidat"]++ ;
			if ( $EXPORTER[$key] == 1 ) {
				$champs["candidat1"][] = $val ;
			}
			else if ( $EXPORTER[$key] == 2 ) {
				$champs["candidat2"][] = $val ;
			}
			else {
				echo "<p class='erreur'>Erreur dans exporter2champs()</p>" ;
			}
		}
	}
	return $champs ;
}

$exp_dossier = array(
	"date_inscrip" => array(
		"Date de dépôt de la candidature",
		10
		),
	"date_maj" => array(
		"Date de mise à jour de la candidature",
		10
		),
	"transferts" => array(
		"Transfert du dossier",
		15
		),
	"etat_dossier" => array(
		"Etat du dossier",
		13
		),
	"date_maj_etat" => array(
		"Date de mise à jour de l'état du dossier",
		10
		),
	"classement" => array(
		"Classement des candidatures en attente",
		5
		),
/*
	"diplome" => array(
		"Diplôme",
		5
		),
*/
	"resultat" => array(
		"Résultat",
		7
		),
	"date_maj_resultat" => array(
		"Date de mise à jour du résultat",
		10
		),
) ;
function longueurd($champ)
{
	global $exp_dossier ;
	return $exp_dossier[$champ][1] ;
}

function libeld($champ)
{
	global $exp_dossier ;
	return $exp_dossier[$champ][0] ;
}

function requete_principale($champs, $post, $cnx)
{
	// virgule entre les champs de candidat et ceux de dossier
	$liste_champs = $champs["liste_candidat"] ;
	if ( ($champs["liste_candidat"]!="") AND ($champs["liste_dossier"]!="") ) {
		$liste_champs .= ", " ;
	}
	$liste_champs .= $champs["liste_dossier"] ;

	// Annee
	if ( ($post["promotion"] == "0") )
	{
		$req  = "SELECT dossier.id_candidat, id_dossier" ;
		$req .= ", intitule, intit_ses, groupe, universite, lieu, " ;
		$req .= "((DATEDIFF(date_deb, naissance)) DIV 365.25) AS age" ;
		if ( $liste_champs != "" ) {
			$req .= ", " ;
		}
		$req .= $liste_champs ;
		$req .= " FROM atelier, session, candidat, dossier LEFT JOIN imputations " ;
		$req .= " ON dossier.id_dossier=imputations.ref_dossier " ;
		$req .= "WHERE dossier.id_candidat=candidat.id_candidat " ;
		$req .= "AND session.id_atelier=atelier.id_atelier " ;
		$req .= "AND session.id_session=dossier.id_session " ;
		if ( isset($post["annee"]) AND ($post["annee"] != "") ) {
			$req .= " AND session.annee=".$post["annee"] ;
		}
		
		if ( isset($post["uniquement"]) ) {
			if ( $post["uniquement"] == "imputes" ) {
				$req .= " AND ref_dossier IS NOT NULL " ;
			}
			if ( $post["uniquement"] == "diplomes" ) {
				//$req .= " AND ref_ancien!=0 " ;
				$req .= " AND resultat=1 " ;
			}
		}
		if ( isset($post["etat"]) AND !empty($post["etat"]) ) {
			if ( $post["etat"] == "inscrit" ) {
				$req .= " AND etat_dossier IN ('Allocataire', 'Payant', 'Allocataire SCAC', 'Payant établissement') " ;
			}
			else {
				$req .= " AND etat_dossier='".$post["etat"]."'" ;
			}
		}
		if ( intval($_SESSION["id"]) > 3 ) {
			$req .= " AND dossier.id_session IN ("
				.$_SESSION["liste_toutes_promotions"].") " ;
		}
	}
	// Promotion
	else
	{
		$req = "SELECT dossier.id_candidat, id_dossier" ;
		if ( $liste_champs != "" ) {
			$req .= ", " ;
		}
		$req .= $liste_champs ;
		if ( isset($post["uniquement"]) AND ($post["uniquement"] == "imputes") ) {
			$req .= " FROM candidat, dossier LEFT JOIN imputations " ;
			$req .= " ON dossier.id_dossier=imputations.ref_dossier " ;
			$req .= "WHERE dossier.id_candidat=candidat.id_candidat " ;
			$req .= "AND dossier.id_session=".$post["promotion"] ;
			$req .= " AND ref_dossier IS NOT NULL " ;
		}
		else {
			$req .= " FROM dossier, candidat " ;
			$req .= "WHERE dossier.id_candidat=candidat.id_candidat " ;
			$req .= "AND dossier.id_session=".$post["promotion"] ;
		}
		if ( isset($post["uniquement"]) AND ($post["uniquement"] == "diplomes") ) {
			//$req .= " AND ref_ancien!=0 " ;
			$req .= " AND resultat=1 " ;
		}
	}
	if ( !empty($post["etat"]) ) {
		if ( $post["etat"] == "inscrit" ) {
			$req .= " AND etat_dossier IN ('Allocataire', 'Payant', 'Allocataire SCAC', 'Payant établissement') " ;
		}
		else {
			$req .= " AND etat_dossier='".$post["etat"]."'" ;
		}
	}
	if ( !empty($post["pays"]) ) {
		$req .= " AND pays='".mysqli_real_escape_string($cnx, $post["pays"])."'" ;
	}

	// Annee
	if ( ($post["promotion"] == "0") )
	{
		$req .= " ORDER BY groupe, niveau, intitule ASC, " .$post["tri"] ;
	}
	// Promotion
	else {
		$req .= " ORDER BY ".$post["tri"] ;
	}
	if ( $post["tri"] == "date_maj" ) {
		$req .= " DESC " ;
	}

/*
	echo "<pre>" ;
	print_r($_SESSION) ;
	echo "</pre>" ;
	print_r($_POST) ;
	echo $req ;
*/

	$resultat = array() ;
	//echo $req . "\n" ;
	$res = mysqli_query($cnx, $req) ;
	while ( $enr = mysqli_fetch_assoc($res) ) {
		$resultat[] = $enr ;
	}
	return $resultat ;
}

function entete_sylk()
{
	// en-tête du fichier SYLK
	$flux  = "ID;PASTUCES-phpInfo.net\n" ; // ID;Pappli
	$flux .= "\n" ;
	// formats
	$flux .= "P;PGeneral\n" ;
	$flux .= "P;P#,##0.00\n" ; // P;Pformat_1 (reels)
	$flux .= "P;P#,##0\n" ;    // P;Pformat_2 (entiers)
	$flux .= "P;P@\n" ;        // P;Pformat_3 (textes)
	$flux .= "\n" ;
	// polices
	$flux .= "P;EArial;M200\n";
	$flux .= "P;EArial;M200\n";
	$flux .= "P;EArial;M200\n";
	$flux .= "P;FArial;M200;SB\n";
	$flux .= "\n";

	return $flux ;
}

function txtexp($str)
{
	$str = str_replace("&apos;", "'", $str) ;
	$str = str_replace("&quot;", "''", $str) ;
	$str = str_replace("\"", "''", $str) ;
	$str = str_replace(";", ";;", $str) ;
	$str = str_replace("\r\n", " ", $str) ;
	$str = str_replace("\n", " ", $str) ;
	return $str ;
}
?>
