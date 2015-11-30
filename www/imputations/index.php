<?php
include("inc_session.php") ;

include("inc_mysqli.php") ;
$cnx = connecter() ;

include("inc_date.php") ;

include("inc_cnf.php") ;

// Export
if ( isset($_SESSION["filtres"]["imputations"]["action"]) AND ($_SESSION["filtres"]["imputations"]["action"] == "Exporter") )
{
	unset($_SESSION["filtres"]["imputations"]["action"]) ;

	define("FORMAT_REEL",   1); // #,##0.00
	define("FORMAT_ENTIER", 2); // #,##0
	define("FORMAT_TEXTE",  3); // @

	$cfg_formats[FORMAT_ENTIER] = "FF0";
	$cfg_formats[FORMAT_REEL]   = "FF2";
	$cfg_formats[FORMAT_TEXTE]  = "FG0";

	$champs = array(
		array('groupe', 'Domaine', FORMAT_TEXTE, 'L', 20),					//  0
		array('id_atelier', 'Id formation', FORMAT_TEXTE, 'L', 8),			//  1
		array('intitule', 'Formation', FORMAT_TEXTE, 'L', 36),				//  2
		array('intit_ses', 'Promotion', FORMAT_TEXTE, 'L', 12),				//  3
		array('universite', 'Université', FORMAT_TEXTE, 'L', 21),			//  4
		array('niveau', 'Niveau', FORMAT_TEXTE, 'L', 8),					//  5
		array('institution', 'Etablissement', FORMAT_TEXTE, 'L', 30),		//  6
		array('id_institution', 'code_etab', FORMAT_TEXTE, 'L', 8),		//  7
		array('etat', 'Etat', FORMAT_TEXTE, 'L', 9),						//  8
		array('genre', 'Genre', FORMAT_TEXTE, 'L', 8),						//  9
		array('civilite', 'Civilité', FORMAT_TEXTE, 'L', 10),				// 10
		array('nom', 'Nom de famille', FORMAT_TEXTE, 'L', 15),				// 11
		array('nom_jf', 'Nom de jeune fille', FORMAT_TEXTE, 'L', 10),		// 12
		array('prenom', 'Prénoms', FORMAT_TEXTE, 'L', 15),					// 13
		array('naissance', 'Naissance', FORMAT_TEXTE, 'L', 10),				// 14 10
		array('age', 'Age', FORMAT_TEXTE, 'L', 3),							// 15
		array('nationalite', 'Nationalité', FORMAT_TEXTE, 'L', 10),			// 16
		array('lieu', "Lieu d'enregistrement", FORMAT_TEXTE, 'L', 10),		// 17
		array('bureau', "Bureau", FORMAT_TEXTE, 'L', 5),					// 18
		array('montant', 'Montant acquitté', FORMAT_TEXTE, 'L', 7),			// 19
		array('monnaie', 'Monnaie', FORMAT_TEXTE, 'L', 7),					// 20
		array('montant_frais', 'Frais de dossier', FORMAT_TEXTE, 'L', 7),	// 21
		array('monnaie_frais', 'Monnaie', FORMAT_TEXTE, 'L', 7),			// 22
		array('imputation', 'Imputation comptable', FORMAT_TEXTE, 'L', 36),	// 23
		array('date_creation', "Date de création", FORMAT_TEXTE, 'L', 10),	// 24 20
		array('date_maj', "Date de modification", FORMAT_TEXTE, 'L', 10),	// 25
		array('particulier', "Cas particulier", FORMAT_TEXTE, 'L', 7),		// 26
		array('mod_imputation', "Imputation particulière", FORMAT_TEXTE, 'L', 7),
		array('commentaire', "Commentaire", FORMAT_TEXTE, 'L', 40),			// 28
	) ;

	$req = "SELECT groupe, atelier.id_atelier, intitule, intit_ses, universite, niveau, 
		ref_etablissement.nom AS institution, ref_etablissement.id AS id_institution,
		etat, genre, civilite, candidat.nom, nom_jf, prenom, naissance,
		((DATEDIFF(date_deb, naissance)) DIV 365.25) AS age, nationalite,
		lieu, lieu, montant, monnaie, montant_frais, monnaie_frais,
		imputations.imputation, date_creation, date_maj,
		particulier, mod_imputation, imputations.commentaire
		FROM imputations, dossier, candidat, session, atelier
		LEFT JOIN ref_etablissement ON ref_etablissement.id=atelier.ref_institution
		WHERE dossier.id_candidat=candidat.id_candidat
		AND ref_dossier=id_dossier " ;
	if ( !empty($_SESSION["filtres"]["imputations"]["annee"]) ) {
		$req .= "AND annee_absolue='".$_SESSION["filtres"]["imputations"]["annee"]."' " ;
	}
	if ( !empty($_SESSION["filtres"]["imputations"]["promotion"]) ) {
		$req .= "AND dossier.id_session='".$_SESSION["filtres"]["imputations"]["promotion"]."' " ;
	}
	if ( !empty($_SESSION["filtres"]["imputations"]["lieu"]) ) {
		$req .= "AND lieu='".$_SESSION["filtres"]["imputations"]["lieu"]."' " ;
	}
	$req .= " AND dossier.id_session=session.id_session
		AND atelier.id_atelier=session.id_atelier
		ORDER BY groupe, niveau, intitule, nom" ;
// AND session.imputations='Oui'
	//echo $req ;
	$res = mysqli_query($cnx, $req) ;

	$nb_lignes  = mysqli_num_rows($res) ;
	$nb_colonnes = count($champs) ;

	// en-tête du fichier SYLK
	$flux  = "ID;PASTUCES-phpInfo.net\n" ; // ID;Pappli
	$flux .= "\n" ;
	// formats
	$flux .= "P;PGeneral\n" ;
	$flux .= "P;P#,##0.00\n" ; // P;Pformat_1 (reels)
	$flux .= "P;P#,##0\n" ;	// P;Pformat_2 (entiers)
	$flux .= "P;P@\n" ;		// P;Pformat_3 (textes)
	$flux .= "\n" ;
	// polices
	$flux .= "P;EArial;M200\n";
	$flux .= "P;EArial;M200\n";
	$flux .= "P;EArial;M200\n";
	$flux .= "P;FArial;M200;SB\n";
	$flux .= "\n";
	// nb lignes * nb colonnes :  B;Yligmax;Xcolmax
	$flux .= "B;Y".($nb_lignes+1) ;
	$flux .= ";X". $nb_colonnes ;
	$flux .= "\n";

	// récupération des infos de formatage des colonnes
	for ($cpt = 0; $cpt < $nb_colonnes; $cpt++)
	{
		$num_format[$cpt] = $champs[$cpt][2] ;
		$format[$cpt] = $cfg_formats[$num_format[$cpt]].$champs[$cpt][3] ;
	}
	// largeurs des colonnes
	for ($cpt = 1; $cpt <= $nb_colonnes; $cpt++)
	{
		// F;Wcoldeb colfin largeur
		$flux .= "F;W".$cpt." ".$cpt." ".$champs[$cpt-1][4]."\n";
	}
	$flux .= "F;W".$cpt." 256 8\n"; // F;Wcoldeb colfin largeur
	$flux .= "\n";
	// en-tête des colonnes (en gras --> SDM4)
	for ($cpt = 1; $cpt <= $nb_colonnes; $cpt++)
	{
		$flux .= "F;SDM4;FG0C;".($cpt == 1 ? "Y1;" : "")."X".$cpt."\n";
		$flux .= "C;N;K\"".$champs[$cpt-1][1]."\"\n";
	}
	$flux .= "\n";
	// Données utiles
	$ligne = 2;
	while ($enr = mysqli_fetch_row($res))
	{
/*
		$lieu = $enr[8] ;
		$promotion = $enr[0] ;
*/
		// parcours des champs
		for ($cpt = 0; $cpt < $nb_colonnes; $cpt++)
		{
			// format
			$flux .= "F;P".$num_format[$cpt].";".$format[$cpt] ;
			$flux .= ($cpt == 0 ? ";Y".$ligne : "").";X".($cpt+1)."\n";
			// valeur
			if ($num_format[$cpt] == FORMAT_TEXTE)
			{
				// Dates à formater
				if ( ($cpt==14) OR ($cpt==24) OR ($cpt==25) ) {
					$flux .= "C;N;K\"".mysql2date($enr[$cpt])."\"\n";
				}
				// Genre
				else if ($cpt == 9) {	
					if ( $enr[$cpt] == "Monsieur" ) {
						$flux .= "C;N;K\"".str_replace(';', ';;', "Homme")."\"\n";
					}
					else {
						$flux .= "C;N;K\"".str_replace(';', ';;', "Femme")."\"\n";
					}
				}
				// Nom
				else if ($cpt == 11) {
					$flux .= "C;N;K\"".str_replace(';', ';;', strtoupper($enr[$cpt]))."\"\n";
				}
				// Prénom
				else if ($cpt == 13) {
					$flux .= "C;N;K\"".str_replace(';', ';;', ucwords(strtolower($enr[$cpt])))."\"\n";
				}
				// Bureau
				else if ($cpt == 18) {
					$flux .= "C;N;K\"".str_replace(';', ';;', $implantationBureau[$enr[$cpt-1]])."\"\n";
				}
				// Commentaire : enlever les retours à la ligne
				else if ($cpt == 28) {
					$flux .= "C;N;K\"".str_replace(';', ';;', str_replace("\r\n", " ", $enr[$cpt])   )."\"\n";
				}
				else {
					$flux .= "C;N;K\"".str_replace(';', ';;', $enr[$cpt])."\"\n";
				}
			}
			else
				$flux .= "C;N;K".$enr[$cpt]."\n";
		}
		$flux .= "\n" ;
		$ligne++ ;
	}
	// fin du fichier
	$flux .= "E\n";

	// UTF-8
	if ( isset($_SESSION["filtres"]["imputations"]["latin1"]) AND ($_SESSION["filtres"]["imputations"]["latin1"] == "latin1") ) {
		$flux = utf8_decode($flux) ;
	}

	//Nom de fichier
	$datecourante = date("Y-m-d", time()) ;
	$fichier = "Imputations" . "__" . $datecourante ;
/*
	if ( !empty($_SESSION["filtres"]["imputations"]["promotion"]) ) {
		$fichier .= "__" . $promotion ;
	}
	if ( !empty($_SESSION["filtres"]["imputations"]["lieu"]) ) {
		$fichier .= "__" . $lieu ;
	}
*/
	$fichier .= ".xls" ;
	$fichier = strtr($fichier, "éèêàâôû", "eeeaaou") ;
	$fichier = strtr($fichier, "',", "__") ;

	header("Content-disposition: filename=$fichier") ;
	if ( isset($_SESSION["filtres"]["imputations"]["latin1"]) AND ($_SESSION["filtres"]["imputations"]["latin1"] == "latin1") ) {
		header('Content-type: application/vnd.ms-excel; ; charset=iso-8859-1') ;
	}		   
	else {  
		header('Content-type: application/vnd.ms-excel; ; charset=UTF-8') ;
	}
	header('Pragma: no-cache') ;
	header('Expires: 0') ;
	echo $flux ;
}

// Pas d'export : affichage
else
{
	if	(
		( !isset($_SESSION["filtres"]["imputations"]["annee"]) OR ($_SESSION["filtres"]["imputations"]["annee"] == "") )
		AND 
		( !isset($_SESSION["filtres"]["imputations"]["promotion"]) OR ($_SESSION["filtres"]["imputations"]["promotion"] == "0") )
		)
	{
		$req ="SELECT MAX(annee) FROM session, dossier, imputations
			WHERE ref_dossier=id_dossier
			AND dossier.id_session=session.id_session" ;
		$res = mysqli_query($cnx, $req) ;
		$row = mysqli_fetch_row($res) ;
		$_SESSION["filtres"]["imputations"]["annee"] = $row[0] ;
	}
/*
	// Imputation 2ème année : On ne peut plus supprimer le choix d'année quand on a choisi une promo
	if ( isset($_SESSION["filtres"]["imputations"]["promotion"]) AND ($_SESSION["filtres"]["imputations"]["promotion"] != "0") ) {
		unset($_SESSION["filtres"]["imputations"]["annee"]) ;
	}
*/

	include("inc_html.php") ;
	$titre = "Imputations (listes et exports)" ;
	echo $dtd1 ;
	echo "<title>$titre</title>\n" ;
	echo $htmlJquery ;
	echo $htmlMakeSublist ;
	echo $dtd2 ;
	include("inc_menu.php") ;
	echo "<div class='noprint'>" ;
	echo $debut_chemin ;
	echo "<a href='/bienvenue.php'>Accueil</a>" ;
	echo " <span class='arr'>&rarr;</span> " ;
	echo "<a href='/imputations/statistiques.php'>Imputations (statistiques)</a>" ;
	echo " <span class='arr'>&rarr;</span> " ;
	echo $titre ;
	echo "</div>" ;
	echo $fin_chemin ;
	


	echo "<form action='criteres.php' method='post'>" ;
	echo "<input type='hidden' name='redirect' value='".$_SERVER["SCRIPT_NAME"]."' />\n" ;
	echo "<table class='formulaire'>\n" ;
	echo "<tbody>\n" ;

	include("inc_promotions.php") ;
	require_once("inc_formations.php") ;
	echo "<tr>\n" ;
	echo "<th rowspan='4'>Limiter à&nbsp;:</th>\n" ;
	echo "<th>Année&nbsp;:</th>\n" ;
	echo "<td><select name='i_annee'>\n" ;
	echo "<option value=''></option>\n" ;

	$req = "SELECT DISTINCT annee FROM session
		WHERE annee>2005
		ORDER BY annee DESC" ;
	$res = mysqli_query($cnx, $req) ;
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
	echo "<th>Promotion&nbsp;:</th>\n<td style='width: 50em'>" ;
	if ( intval($_SESSION["id"]) < 4 ) {
		$req = "SELECT id_session, annee, groupe, niveau, intitule, intit_ses
			FROM atelier, session
			WHERE session.id_atelier=atelier.id_atelier
			AND session.annee>=2006
			ORDER BY annee DESC, groupe, niveau, intitule" ;
		$formPromo = chaine_liste_promotions("i_promotion",
			( isset($_SESSION["filtres"]["imputations"]["promotion"]) ? $_SESSION["filtres"]["imputations"]["promotion"] : "" ),
			$req, $cnx) ;
		echo $formPromo["form"] ;
		echo $formPromo["script"] ;
	}
	else {
		liste_promotions("i_promotion", $_SESSION["filtres"]["imputations"]["promotion"], $cnx, TRUE, TRUE) ;
	}
	echo "</td>\n</tr>\n" ;

	include("inc_form_select.php") ;
	echo "<tr>\n" ;
	echo "<th class='help' title=\"Lieu d'enregistrement\">Lieu&nbsp;:</th>\n" ;
	echo "<td>" ;
	form_select_1($CNF, "i_lieu",
		( isset($_SESSION["filtres"]["imputations"]["lieu"]) ? $_SESSION["filtres"]["imputations"]["lieu"] : "" )
		) ;
	echo "</td>\n" ;
	echo "</tr>\n" ;
	echo "<tr>\n" ;
	echo "<th><label for='i_etat'>&Eacute;tat&nbsp;:</th>\n" ;
	echo "<td>" ;
	echo "<select name='i_etat' id='i_etat'>\n" ;
	echo "<option value=''></option>" ;
	echo "<option value='Allocataire'" ;
	if ( isset($_SESSION["filtres"]["imputations"]["etat"]) AND ($_SESSION["filtres"]["imputations"]["etat"] == "Allocataire") ) {
		echo " selected='selected'" ;
	}
	echo ">Allocataire</option>" ;
	echo "<option value='Payant'" ;
	if ( isset($_SESSION["filtres"]["imputations"]["etat"]) AND ($_SESSION["filtres"]["imputations"]["etat"] == "Payant") ) {
		echo " selected='selected'" ;
	}
	echo ">Payant</option>\n" ;
	echo "<option value='Allocataire SCAC'" ;
	if ( isset($_SESSION["filtres"]["imputations"]["etat"]) AND ($_SESSION["filtres"]["imputations"]["etat"] == "Allocataire SCAC") ) {
		echo " selected='selected'" ;
	}
	echo ">Allocataire SCAC</option>" ;
	echo "</select>" ;
	echo "</td>\n" ;
	echo "</tr>\n" ;

function liste_tri($name, $value)
{
	$TRI = array(
		"" => "",
		"nom" => "Nom",
		"civilite" => "Civilité",
		"date_creation" => "Date de création",
		"date_mod" => "Date de modification",
		"lieu" => "Lieu d'enregistrement",
		"etat" => "&Eacute;tat",
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

	echo "<tr>\n" ;
	echo "<th>Trier par&nbsp;:</th>\n" ;
	echo "<td colspan='2'>" ;
	liste_tri("i_tri",
		( isset($_SESSION["filtres"]["imputations"]["tri"]) ? $_SESSION["filtres"]["imputations"]["tri"] : "" )
	) ;
	echo "</td>\n" ;

	echo "<tr>\n<td colspan='3'>" ;
	echo "<div class='c' style='padding: 3px 0;'>";
	echo "<div style='float: right'>\n" ;
	echo "<input type='submit' style='font-weight: bold; margin-right: 1em;' " ;
	echo " name='action' value='Exporter' />" ;
	echo "<label for='latin1'><input type='checkbox' id='latin1' name='latin1' value='latin1' /> &nbsp;Exporter en ISO-8859-1</div>" ;

	echo "<a class='reinitialiser' href='reinitialiser.php?redirect=".urlencode($_SERVER["SCRIPT_NAME"])."'>".LABEL_REINITIALISER."</a>"
	    . BOUTON_ACTUALISER ;

	echo "</div>\n" ;
	echo "</td>\n</tr>\n" ;

	echo "</tbody>\n" ;
	echo "</table>\n" ;
	echo "</form>" ;




	$req = "SELECT id_imputation, civilite, nom, prenom, lieu,
		imputations.etat, imputations.imputation, imputations.date_creation, date_mod, particulier, mod_imputation, imputations.commentaire,
		groupe, niveau, intitule, intit_ses
		FROM imputations, dossier, candidat, session, atelier
		WHERE dossier.id_candidat=candidat.id_candidat
		AND ref_dossier=id_dossier " ;
	if ( !empty($_SESSION["filtres"]["imputations"]["annee"]) ) {
		$req .= "AND annee_absolue='".$_SESSION["filtres"]["imputations"]["annee"]."' " ;
	}
	if ( !empty($_SESSION["filtres"]["imputations"]["lieu"]) ) {
		$req .= "AND lieu='".$_SESSION["filtres"]["imputations"]["lieu"]."' " ;
	}
	if ( !empty($_SESSION["filtres"]["imputations"]["promotion"]) ) {
		$req .= "AND dossier.id_session='".$_SESSION["filtres"]["imputations"]["promotion"]."' " ;
	}
	if ( !empty($_SESSION["filtres"]["imputations"]["etat"]) ) {
		$req .= "AND imputations.etat='".$_SESSION["filtres"]["imputations"]["etat"]."' " ;
	}
	if ( intval($_SESSION["id"]) > 3 ) {
		$req .= " AND session.id_session IN (".$_SESSION["liste_toutes_promotions"].") " ;
	}
	$req .= " AND dossier.id_session=session.id_session
		AND atelier.id_atelier=session.id_atelier
		ORDER BY annee_absolue DESC, groupe, niveau, intitule" ;
//		AND session.imputations='Oui'
	if ( !empty($_SESSION["filtres"]["imputations"]["tri"]) ) {
		$req .= ", ". $_SESSION["filtres"]["imputations"]["tri"] ;
	}
//	echo $req ;
	$res = mysqli_query($cnx, $req) ;
	$N = mysqli_num_rows($res);
	
	if ( $N != 0 )
	{
		if ( $N > 1 ) { $s = "s" ; }
		else { $s = "" ; }
		echo "<p class='c'><strong>$N</strong> imputations pour ces critères :</p>" ;
	
		echo "<table class='tableau'>\n" ;
		echo "<thead>\n" ;
		echo "<tr>\n" ;
		echo "<th class='help' title=\"Date d'encaissement\">Date</th>" ;
		echo "<th>Lieu</th>" ;
		echo "<th class='help' title=\"Allocataire ou Payant ou Allocataire SCAC\">?</th>" ;
		echo "<th>Civilité Nom Prénoms</th>" ;
		echo "<th class='help' title=\"Cas particulier ?\">?</th>" ;
		echo "<th>Imputation</th>" ;
		echo "</tr>\n" ;
		echo "</thead>\n" ;
		echo "<tbody>\n" ;
		$groupe = "" ;
		$formation = "" ;
		while ( $enr = mysqli_fetch_assoc($res) )
		{
			if ( $groupe != $enr["groupe"] ) {
				$groupe = $enr["groupe"] ;
				echo "<tr><td style='background: #ccc' class='r' colspan='6'>" ;
				echo "<b style='font-size: 120%;'>$groupe</b></td></tr>" ;
			}
			if ( $formation != $enr["intitule"] ) {
				$formation = $enr["intitule"] ;
				echo "<tr><td style='background: #ccc' class='r' colspan='6'>" ;
				echo "<b>$formation</b></td></tr>" ;
			}
			echo "<tr>\n" ;
			echo "<td>".mysql2datenum($enr["date_creation"])."</td>\n" ;
			echo "<td class='c'>".$enr["lieu"] ;
			// echo " ".$implantationBureau[$enr["lieu"]] ;
			echo "</td>\n" ;
			echo "<td" ;
				if ( $enr["etat"] == "Allocataire" ) {
					echo " class='c allocataire help' title='Allocataire'>A</td>\n" ;
				}
				else if ( $enr["etat"] == "Allocataire SCAC" ) {
					echo " class='c scac help' title='Allocataire SCAC'>AS</td>\n" ;
				}
				else {
					echo " class='c payant help' title='Payant'>P</td>\n" ;
				}
			echo "<td><a class='bl' " ;
				echo "href='attestation.php?id=".$enr["id_imputation"]."'>" ;
				echo $enr["civilite"] . " " ;
				echo "<strong>" .strtoupper($enr["nom"]) . "</strong> " 
				. ucwords(strtolower($enr["prenom"])) ;
				echo "</a></td>\n" ;
			if ( $enr["particulier"] == "Non" ) {
				echo "<td></td>\n" ;
			}
			else {
				echo "<td class='Non help' title='".$enr["commentaire"]."'>?</td>\n" ;
			}

			if ( $enr["mod_imputation"] == "Oui" ) {
				echo "<td class='particulier'>" ;
			}
			else {
				echo "<td>" ;
			}
			echo $enr["imputation"]."</td>\n" ;
			echo "</tr>\n" ;
		}
		echo "</tbody>\n" ;
		echo "</table>\n" ;
	}
	else {
		echo "<p class='c'>Aucune imputation pour ces critères.</p>" ;
	}
}





deconnecter($cnx) ;
echo $end ;
?>
