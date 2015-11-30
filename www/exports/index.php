<?php
include("inc_session.php") ;
ini_set("memory_limit", "600M") ;
include("inc_mysqli.php") ;
$cnx = connecter() ;


//
// Export
//
if	(
		//( isset($_POST["operation"]) AND ($_POST["operation"] == "exporter") )
		// Décommenter et Commenter pour éliminer le choix obligatoire d'une année ou d'une promo
		(
			( isset($_POST["operation"]) AND ($_POST["operation"] == "exporter") )
			AND (
					( isset($_POST["annee"]) AND ($_POST["annee"] != "") )
					OR ( isset($_POST["promotion"]) AND ($_POST["promotion"] != "0") )
				)
		)
	)
{
	require_once("inc_pays.php") ;
	$statiquePays = statiquePays($cnx) ;

	require_once("inc_resultat.php") ;
	$RESULTAT = tab_resultats() ;

	//
	// Nom du fichier contenant l'export
	//
	require_once("inc_traitements_caracteres.php") ;
	$datecourante = date("Y-m-d", time()) ;
	$fichier = $datecourante ;

	// Pas de promotion => export d'une année
	// NB : l'annee est connue, cf test initial
	if ( ($_POST["promotion"] == "0") )
	{
		$fichier .= "__" . $_POST["annee"] ;
	}
	// Export d'une promotion
	else
	{
		$requete = "SELECT intitule, intit_ses FROM atelier, session
			WHERE session.id_atelier=atelier.id_atelier
			AND session.id_session=".$_POST["promotion"] ;
		$resultat = mysqli_query($cnx, $requete) ;
		$ligne = mysqli_fetch_assoc($resultat) ;
		$promo = $ligne["intitule"] . "_" . $ligne["intit_ses"] ;
		$fichier .= "__" . traitementNomFichier($promo) ;
	}

	if ( isset($_POST["uniquement"]) ) {
		if ( $_POST["uniquement"] == "imputes" ) {
			$fichier .= "__Imputes" ;
		}
		if ( $_POST["uniquement"] == "diplomes" ) {
			$fichier .= "__Diplomes" ;
		}
	}
	if ( isset($_POST["etat"]) ) {
		if ( $_POST["etat"] != "" ) {
			$fichier .= "__" . traitementNomFichier($_POST["etat"]) ;
		}
		if ( $_POST["pays"] != "" ) {
			$fichier .= "__" . traitementNomFichier(refPays($_POST["pays"], $statiquePays)) ;
		}
	}
	$fichier .= ".xls" ;
	//echo $fichier ."<br />" ;


	// Champs à exporter sous une forme plus exploitable qu'une simple liste
	include_once("inc_exports.php") ;
	// On limite l'export aux champs communs si on exporte une annee
	if ( ($_POST["promotion"] == "0") )
	{
		$enlever = array(
			"" => "",
			"diplomes" => "diplomes",
			"stages" => "stages",
			"questions" => "questions",
			"commentaires" => "commentaires",
		) ;
		$exporter = array_diff_key($_SESSION["filtres"]["exporter"], $enlever) ;
		$champs = exporter2champs($exporter) ;
	}
	// Export d'une promotion
	else
	{
		$champs = exporter2champs($_SESSION["filtres"]["exporter"]) ;
	}

	// Résultat de la requête principale
	$res = requete_principale($champs, $_POST, $cnx) ;
//	print_r($res) ;
	$nombre_candidatures = count($res) ;

	// Diplômes
	if ( isset($champs["diplomes"]) AND ($champs["diplomes"] == "diplomes") )
	{
		for ( $i=0 ; $i < $nombre_candidatures ; $i++ ) {
			$req_diplomes = "SELECT annee_dip, titre_dip, mention_dip, etab_dip, pays_dip
				FROM diplomes
				WHERE id_candidat=".$res[$i]["id_candidat"]."
				ORDER BY code_dip" ;
			$res_diplomes = mysqli_query($cnx, $req_diplomes) ;
			$k = 1 ;
			for ( $j=1 ; $j<=4 ; $j++ )
			{
				$ligne = mysqli_fetch_assoc($res_diplomes) ;
				$res[$i]["diplomes$k"] = $ligne["annee_dip"] ;
				$k++ ;
				$res[$i]["diplomes$k"] = $ligne["titre_dip"] ;
				$k++ ;
				$res[$i]["diplomes$k"] = $ligne["mention_dip"] ;
				$k++ ;
				$res[$i]["diplomes$k"] = $ligne["etab_dip"] ;
				$k++ ;
				$res[$i]["diplomes$k"] = $ligne["pays_dip"] ;
				$k++ ;
			} 
		}
	}
	// Stages
	if ( isset($champs["stages"]) AND ($champs["stages"] == "stages") ) {
		for ( $i=0 ; $i < $nombre_candidatures ; $i++ ) {
			$req_stage = "SELECT annee_stage, titre_stage, org_stage
				FROM stage
				WHERE id_candidat=".$res[$i]["id_candidat"]."
				ORDER BY code_stage" ;
			$res_stage = mysqli_query($cnx, $req_stage) ;
			$k = 1 ;
			for ( $j=1 ; $j<=4 ; $j++ ) {
				$ligne = mysqli_fetch_assoc($res_stage) ;
				$res[$i]["stage$k"] = $ligne["annee_stage"] ;
				$k++ ;
				$res[$i]["stage$k"] = $ligne["titre_stage"] ;
				$k++ ;
				$res[$i]["stage$k"] = $ligne["org_stage"] ;
				$k++ ;
			} 
		}
	}
	// Questions
	if ( isset($champs["questions"]) AND ($champs["questions"] == "questions") ) {
		$req_questions = "SELECT texte_quest
			FROM question, session, mod_dossier
			WHERE session.id_session=".$_POST["promotion"]."
			AND session.id_atelier=mod_dossier.id_atelier
			AND mod_dossier.id_modos=question.id_modos
			ORDER BY id_question" ;
		$res_questions = mysqli_query($cnx, $req_questions) ;
		$nombre_questions = @mysqli_num_rows($res_questions) ;

		$les_questions = array() ;
		while ( $ligne = mysqli_fetch_assoc($res_questions) ) {
			$texte_quest[] = $ligne["texte_quest"] ;
		}

		if ( $nombre_questions != 0 ) {
			for ( $i=0 ; $i < $nombre_candidatures ; $i++ ) {
				$req_reponses = "SELECT texte_rep
					FROM reponse
					WHERE id_dossier=".$res[$i]["id_dossier"]."
					ORDER BY id_question" ;
				$res_reponses = mysqli_query($cnx, $req_reponses) ;
				for ( $k=1 ; $k<=$nombre_questions ; $k++ ) {
					$ligne = mysqli_fetch_assoc($res_reponses) ;
					$res[$i]["reponse$k"] = $ligne["texte_rep"] ;
				}
			}
		}
	}
	// Évaluations
	if ( isset($champs["commentaires"]) AND ($champs["commentaires"] == "commentaires") ) {
		$req_sel = "SELECT codesel, nomsel, prenomsel
			FROM selecteurs, atxsel, atelier, session
			WHERE selecteurs.codesel=atxsel.id_sel
			AND atxsel.id_atelier=atelier.id_atelier
			AND atelier.id_atelier=session.id_atelier
			AND session.id_session=".$_POST["promotion"]."
			ORDER BY codesel" ;
		$res_sel = mysqli_query($cnx, $req_sel) ;
		$selecteurs = array() ;
		while ( $enr = mysqli_fetch_assoc($res_sel) ) {
			$selecteurs[] = $enr ;
		}
		$nombre_selecteurs = count($selecteurs) ;
	}
	//
	// Génération du fichier
	//
	$flux = entete_sylk() ;
	// nb lignes * nb colonnes :  B;Yligmax;Xcolmax
	$nb_colonnes = count($res[0]) ;
	if ( $champs["commentaires"] == "commentaires" ) {
		// Commentaire et etat pour chaque selectionneur + commentaire AUF
		if ( $nombre_selecteurs > 1 ) {
			$nb_colonnes += ( ($nombre_selecteurs * 2) + 1 ) ;
		}
		// Commentaire selectionneur + commentaire AUF
		else {
			$nb_colonnes += ( $nombre_selecteurs + 1 ) ;
		}
	}

	// 7 colonnes de plus pour
	// groupe, intitule, intit_ses, universite, lieu, bureau, age
	// 8 avec ref_ancien
	if ( ($_POST["promotion"] == "0") ) {
		$nb_colonnes = $nb_colonnes + 7 ;
	}

	$nb_lignes = $nombre_candidatures + 1 ;
	$flux .= "B;Y".$nb_lignes ;
	$flux .= ";X".$nb_colonnes."\n";
	$flux .= "\n";
	// largeur des colonnes
	include("inc_formulaire_candidature.php") ;
	$i = 1 ;

	// 7 colonnes de plus pour
	// groupe, intitule, intit_ses, universite, lieu, bureau, age
	// 8 avec ref_ancien
	if ( ($_POST["promotion"] == "0") ) {
		$flux .= "F;W".$i." ".$i." 20\n" ;
		$i++ ;
		$flux .= "F;W".$i." ".$i." 20\n" ;
		$i++ ;
		$flux .= "F;W".$i." ".$i." 10\n" ;
		$i++ ;
		$flux .= "F;W".$i." ".$i." 20\n" ;
		$i++ ;
		$flux .= "F;W".$i." ".$i." 9\n" ;
		$i++ ;
		$flux .= "F;W".$i." ".$i." 7\n" ;
		$i++ ;
	/*
		$flux .= "F;W".$i." ".$i." 5\n" ;
		$i++ ;
	*/
		$flux .= "F;W".$i." ".$i." 5\n" ;
		$i++ ;
	}

	if ( isset($champs["candidat1"]) ) {
		foreach( $champs["candidat1"] as $col ) {
			$flux .= "F;W".$i." ".$i." ".longueurc($col)."\n";
			$i++ ;
		}
	}
	if ( $champs["diplomes"] == "diplomes" ) {
		reset($DIPLOMES) ;
		while( list($key, $val) = each($DIPLOMES) ) {
			$flux .= "F;W".$i." ".$i." ".$val."\n";
			$i++ ;
		}
	}
	if ( $champs["stages"] == "stages" ) {
		reset($STAGES) ;
		while( list($key, $val) = each($STAGES) ) {
			$flux .= "F;W".$i." ".$i." ".$val."\n";
			$i++ ;
		}
	}
	if ( isset($champs["candidat2"]) ) {
		foreach( $champs["candidat2"] as $col ) {
			$flux .= "F;W".$i." ".$i." ".longueurc($col)."\n";
			$i++ ;
		}
	}
	if ( $champs["questions"] == "questions" ) {
		if ( $nombre_questions != 0 ) {
			for ( $j=1 ; $j<=$nombre_questions ; $j++ ) {
				$flux .= "F;W".$i." ".$i." 50\n";
				$i++ ;
			}
		}
	}
	if ( isset($champs["dossier"]) ) {
		foreach( $champs["dossier"] as $col ) {
			$flux .= "F;W".$i." ".$i." ".longueurd($col)."\n";
			$i++ ;
		}
	}
	if ( $champs["commentaires"] == "commentaires" ) {
		// AUF
		$flux .= "F;W".$i." ".$i." 21\n";
		$i++ ;
		// Sélectionneurs
		if ( $nombre_selecteurs > 1 ) {
			for ( $j=1 ; $j<=$nombre_selecteurs ; $j++ ) {
				// Commentaire
				$flux .= "F;W".$i." ".$i." 30\n";
				$i++ ;
				// Etat
				$flux .= "F;W".$i." ".$i." 13\n";
				$i++ ;
			}
		}
		else {
			for ( $j=1 ; $j<=$nombre_selecteurs ; $j++ ) {
				$flux .= "F;W".$i." ".$i." 30\n";
				$i++ ;
			}
		}
	}
	$flux .= "F;W".$i." 256 8\n";
	$flux .= "\n";
	// en-tête des colonnes (en gras --> SDM4)
	@reset($champs["candidat1"]) ;
	@reset($champs["candidat2"]) ;
	@reset($champs["dossier"]) ;
	$i = 1 ;

	// 7 colonnes de plus pour
	// groupe, intitule, intit_ses, universite, lieu, bureau, age
	if ( ($_POST["promotion"] == "0") ) {
		$flux .= "F;SDM4;FG0C;".($i == 1 ? "Y1;" : "")."X".$i."\n";
		$flux .= "C;N;K\""."Domaine"."\"\n";
		$i++ ;
		$flux .= "F;SDM4;FG0C;".($i == 1 ? "Y1;" : "")."X".$i."\n";
		$flux .= "C;N;K\""."Formation"."\"\n";
		$i++ ;
		$flux .= "F;SDM4;FG0C;".($i == 1 ? "Y1;" : "")."X".$i."\n";
		$flux .= "C;N;K\""."Promotion"."\"\n";
		$i++ ;
		$flux .= "F;SDM4;FG0C;".($i == 1 ? "Y1;" : "")."X".$i."\n";
		$flux .= "C;N;K\""."Université"."\"\n";
		$i++ ;
		$flux .= "F;SDM4;FG0C;".($i == 1 ? "Y1;" : "")."X".$i."\n";
		$flux .= "C;N;K\""."Lieu"."\"\n";
		$i++ ;
		$flux .= "F;SDM4;FG0C;".($i == 1 ? "Y1;" : "")."X".$i."\n";
		$flux .= "C;N;K\""."Bureau"."\"\n";
		$i++ ;
		/*
		$flux .= "F;SDM4;FG0C;".($i == 1 ? "Y1;" : "")."X".$i."\n";
		$flux .= "C;N;K\""."Diplômé"."\"\n";
		$i++ ;
		*/
		$flux .= "F;SDM4;FG0C;".($i == 1 ? "Y1;" : "")."X".$i."\n";
		$flux .= "C;N;K\""."Age"."\"\n";
		$i++ ;
	}

	if ( isset($champs["candidat1"]) ) {
		foreach( $champs["candidat1"] as $col ) {
			$flux .= "F;SDM4;FG0C;".($i == 1 ? "Y1;" : "")."X".$i."\n";
			$flux .= "C;N;K\"".libelc($col)."\"\n";
			$i++ ;
		}
	}
	if ( isset($champs["diplomes"]) AND ($champs["diplomes"] == "diplomes") ) {
		reset($DIPLOMES) ;
		while( list($key, $val) = each($DIPLOMES) ) {
			$flux .= "F;SDM4;FG0C;".($i == 1 ? "Y1;" : "")."X".$i."\n";
			$flux .= "C;N;K\"".$key."\"\n";
			$i++ ;
		}
	}
	if ( isset($champs["stages"]) AND ($champs["stages"] == "stages") ) {
		reset($STAGES) ;
		while( list($key, $val) = each($STAGES) ) {
			$flux .= "F;SDM4;FG0C;".($i == 1 ? "Y1;" : "")."X".$i."\n";
			$flux .= "C;N;K\"".$key."\"\n";
			$i++ ;
		}
	}
	if ( isset($champs["candidat2"]) ) {
		foreach( $champs["candidat2"] as $col ) {
			$flux .= "F;SDM4;FG0C;".($i == 1 ? "Y1;" : "")."X".$i."\n";
			$flux .= "C;N;K\"".libelc($col)."\"\n";
			$i++ ;
		}
	}
	if ( isset($champs["questions"]) AND ($champs["questions"] == "questions") ) {
		if ( $nombre_questions != 0 ) {
			reset($texte_quest) ;
			foreach($texte_quest as $question) {
				$flux .= "F;SDM4;FG0C;".($i == 1 ? "Y1;" : "")."X".$i."\n";
				$flux .= "C;N;K\"".txtexp($question)."\"\n";
				$i++ ;
			}
		}
	}
	if ( isset($champs["dossier"]) ) {
		foreach( $champs["dossier"] as $col ) {
			$flux .= "F;SDM4;FG0C;".($i == 1 ? "Y1;" : "")."X".$i."\n";
			$flux .= "C;N;K\"".libeld($col)."\"\n";
			$i++ ;
		}
	}
	if ( isset($champs["commentaires"]) AND ($champs["commentaires"] == "commentaires") ) {
		$flux .= "F;SDM4;FG0C;".($i == 1 ? "Y1;" : "")."X".$i."\n";
		$flux .= "C;N;K\"Évaluation de l'AUF\"\n";
		$i++ ;
		if ( $nombre_selecteurs > 1 ) {
			for ( $j=0 ; $j<$nombre_selecteurs ; $j++ ) {
				$sel = "Évaluation de " . $selecteurs[$j]["prenomsel"] . " "
					. strtoupper($selecteurs[$j]["nomsel"]) ;
				$flux .= "F;SDM4;FG0C;".($i == 1 ? "Y1;" : "")."X".$i."\n";
				$flux .= "C;N;K\"".$sel."\"\n";
				$i++ ;
				$sel = $selecteurs[$j]["prenomsel"] . " "
					. strtoupper($selecteurs[$j]["nomsel"]) ;
				$flux .= "F;SDM4;FG0C;".($i == 1 ? "Y1;" : "")."X".$i."\n";
				$flux .= "C;N;K\"".$sel."\"\n";
				$i++ ;
			}
		}
		else {
			for ( $j=0 ; $j<$nombre_selecteurs ; $j++ ) {
				$sel = "Évaluation de " . $selecteurs[$j]["prenomsel"] . " "
					. strtoupper($selecteurs[$j]["nomsel"]) ;
				$flux .= "F;SDM4;FG0C;".($i == 1 ? "Y1;" : "")."X".$i."\n";
				$flux .= "C;N;K\"".$sel."\"\n";
				$i++ ;
			}
		}
	}
	$flux .= "\n";
	//
	// Données
	//
	include_once("inc_date.php") ;
	include_once("inc_cnf.php") ;
	@reset($champs["candidat1"]) ;
	@reset($champs["candidat2"]) ;
	@reset($champs["dossier"]) ;
	$ligne = 2 ;
	reset($res) ;
	foreach ($res as $enr)
	{
		$i = 1 ;

		// 7 colonnes de plus pour
		// groupe, intitule, intit_ses, universite, lieu, bureau, age
		if ( ($_POST["promotion"] == "0") ) {
			$flux .= "F;P3;FG0L" ;
			$flux .= ( $i == 1 ? ";Y".$ligne : "").";X".$i."\n";
			$flux .= "C;N;K\"" ;
			$flux .= txtexp($enr["groupe"]) ;
			$flux .= "\"\n";
			$i++ ;
			$flux .= "F;P3;FG0L" ;
			$flux .= ( $i == 1 ? ";Y".$ligne : "").";X".$i."\n";
			$flux .= "C;N;K\"" ;
			$flux .= txtexp($enr["intitule"]) ;
			$flux .= "\"\n";
			$i++ ;
			$flux .= "F;P3;FG0L" ;
			$flux .= ( $i == 1 ? ";Y".$ligne : "").";X".$i."\n";
			$flux .= "C;N;K\"" ;
			$flux .= txtexp($enr["intit_ses"]) ;
			$flux .= "\"\n";
			$i++ ;
			$flux .= "F;P3;FG0L" ;
			$flux .= ( $i == 1 ? ";Y".$ligne : "").";X".$i."\n";
			$flux .= "C;N;K\"" ;
			$flux .= txtexp($enr["universite"]) ;
			$flux .= "\"\n";
			$i++ ;
			$flux .= "F;P3;FG0L" ;
			$flux .= ( $i == 1 ? ";Y".$ligne : "").";X".$i."\n";
			$flux .= "C;N;K\"" ;
			$flux .= txtexp(( isset($enr["lieu"]) ? $enr["lieu"] : "" )) ;
			$flux .= "\"\n";
			$i++ ;
			$flux .= "F;P3;FG0L" ;
			$flux .= ( $i == 1 ? ";Y".$ligne : "").";X".$i."\n";
			$flux .= "C;N;K\"" ;
			$flux .= txtexp($implantationBureau[( isset($enr["lieu"]) ? $enr["lieu"] : "" )]) ;
			$flux .= "\"\n";
			$i++ ;
			/*
			$flux .= "F;P3;FG0L" ;
			$flux .= ( $i == 1 ? ";Y".$ligne : "").";X".$i."\n";
			$flux .= "C;N;K\"" ;
			if ( intval($enr["ref_ancien"]) != 0 ) {
				$flux .= txtexp("Diplômé") ;
			}
			else {
				$flux .= txtexp("") ;
			}
			$flux .= "\"\n";
			$i++ ;
			*/
			$flux .= "F;P3;FG0L" ;
			$flux .= ( $i == 1 ? ";Y".$ligne : "").";X".$i."\n";
			$flux .= "C;N;K\"" ;
			$flux .= txtexp($enr["age"]) ;
			$flux .= "\"\n";
			$i++ ;
		}

		if ( isset($champs["candidat1"]) ) {
			foreach( $champs["candidat1"] as $col ) {
				$flux .= "F;P3;FG0L" ;
				$flux .= ( $i == 1 ? ";Y".$ligne : "").";X".$i."\n";
				$flux .= "C;N;K\"" ;
				if ( $col == "naissance" ) {
					$flux .= txtexp(mysql2date($enr[$col])) ;
				}
				else if ( $col == "nom" ) {
					$flux .= txtexp(strtoupper($enr[$col])) ;
				}
				else if ( $col == "nom_jf" ) {
					$flux .= txtexp(strtoupper($enr[$col])) ;
				}
				else if ( $col == "prenom" ) {
					$flux .= txtexp(ucwords(strtolower($enr[$col]))) ;
				}
				else if ( ($col == "pays") OR ($col == "pays_naissance") OR ($col == "pays_emp") ) {
					$flux .= txtexp(ucwords(strtolower(refPays($enr[$col], $statiquePays)))) ;
				}
				else {
					$flux .= txtexp($enr[$col]) ;
				}
				$flux .= "\"\n";
				//$flux .= "C;N;K\"".txtexp($enr[$col])."\"\n";
				$i++ ;
			}
		}
		if ( isset($champs["diplomes"]) AND ($champs["diplomes"] == "diplomes") ) {
			for ( $k=1 ; $k<=20 ; $k++) {
				$flux .= "F;P3;FG0L" ;
				$flux .= ( $i == 1 ? ";Y".$ligne : "").";X".$i."\n";
				if ( ($k==5) OR ($k==10) OR ($k==15) OR ($k==20) ) {
					$flux .= "C;N;K\"".txtexp(refPays($enr["diplomes$k"], $statiquePays))."\"\n";
				}
				else {
					$flux .= "C;N;K\"".txtexp(refPays($enr["diplomes$k"], $statiquePays))."\"\n";
					$flux .= "C;N;K\"".txtexp($enr["diplomes$k"])."\"\n";
				}
				$i++ ;
			}
		}
		if ( isset($champs["stages"]) AND ($champs["stages"] == "stages") ) {
			for ( $k=1 ; $k<=12 ; $k++) {
				$flux .= "F;P3;FG0L" ;
				$flux .= ( $i == 1 ? ";Y".$ligne : "").";X".$i."\n";
				$flux .= "C;N;K\"".txtexp($enr["stage$k"])."\"\n";
				$i++ ;
			}
		}
		if ( isset($champs["candidat2"]) ) {
			foreach( $champs["candidat2"] as $col ) {
				$flux .= "F;P3;FG0L" ;
				$flux .= ( $i == 1 ? ";Y".$ligne : "").";X".$i."\n";
				$flux .= "C;N;K\"".txtexp($enr[$col])."\"\n";
				$i++ ;
			}
		}
		if ( isset($champs["questions"]) AND ($champs["questions"] == "questions") ) {
			if ( $nombre_questions != 0 ) {
				for ( $j=1 ; $j<=$nombre_questions ; $j++ ) {
					$flux .= "F;P3;FG0L" ;
					$flux .= ( $i == 1 ? ";Y".$ligne : "").";X".$i."\n";
					$flux .= "C;N;K\"".txtexp($enr["reponse$j"])."\"\n";
					$i++ ;
				}
			}
		}
		if ( isset($champs["dossier"]) ) {
			foreach( $champs["dossier"] as $col ) {
				$flux .= "F;P3;FG0L" ;
				$flux .= ( $i == 1 ? ";Y".$ligne : "").";X".$i."\n";
				$flux .= "C;N;K\"" ;
				if ( ($col == "date_inscrip") OR ($col == "date_maj") OR ($col == "date_maj_etat") OR ($col == "date_maj_resultat") ) {
					if ( $enr[$col] == "0000-00-00" ) {
						$flux .= txtexp("") ;
					}
					else {
						$flux .= txtexp(mysql2date($enr[$col])) ;
					}
				}
				else if ($col == "resultat") {
					if ( $enr[$col] == "0" ) {
						$flux .= txtexp("") ;
					}
					else {
						$flux .= txtexp($RESULTAT[$enr[$col]]) ;
					}
				}
				else {
					$flux .= txtexp($enr[$col]) ;
				}
				$flux .= "\"\n";
				$i++ ;
			}
		}
		if ( isset($champs["commentaires"]) AND ($champs["commentaires"] == "commentaires") ) {
			// AUF
			$req_auf = "SELECT commentaire FROM comment_auf
				WHERE ref_candidat=".$enr["id_candidat"] ;
			$res_auf = mysqli_query($cnx, $req_auf) ;
			$com = mysqli_fetch_assoc($res_auf) ;
			$commentaire = $com["commentaire"] ;
			$flux .= "F;P3;FG0L" ;
			$flux .= ( $i == 1 ? ";Y".$ligne : "").";X".$i."\n";
			$flux .= "C;N;K\"".txtexp($commentaire)."\"\n";
			$i++ ;
			// Sélectionneurs
			if ( $nombre_selecteurs > 1 ) {
				for ( $j=0 ; $j<$nombre_selecteurs ; $j++ ) {
					$req_sel = "SELECT commentaire, etat_sel FROM comment_sel
						WHERE ref_candidat=".$enr["id_candidat"]."
						AND ref_selecteur=".$selecteurs[$j]["codesel"] ;
					$res_sel = mysqli_query($cnx, $req_sel) ;
					$com = mysqli_fetch_assoc($res_sel) ;
					$commentaire = $com["commentaire"] ;
					$etat_sel = $com["etat_sel"] ;
					$flux .= "F;P3;FG0L" ;
					$flux .= ( $i == 1 ? ";Y".$ligne : "").";X".$i."\n";
					$flux .= "C;N;K\"".txtexp("$commentaire")."\"\n";
					$i++ ;
					$flux .= "F;P3;FG0L" ;
					$flux .= ( $i == 1 ? ";Y".$ligne : "").";X".$i."\n";
					$flux .= "C;N;K\"".txtexp("$etat_sel")."\"\n";
					$i++ ;
				}
			}
			else {
				for ( $j=0 ; $j<$nombre_selecteurs ; $j++ ) {
					$req_sel = "SELECT commentaire FROM comment_sel
						WHERE ref_candidat=".$enr["id_candidat"]."
						AND ref_selecteur=".$selecteurs[$j]["codesel"] ;
					$res_sel = mysqli_query($cnx, $req_sel) ;
					$com = mysqli_fetch_assoc($res_sel) ;
					$commentaire = $com["commentaire"] ;
					$flux .= "F;P3;FG0L" ;
					$flux .= ( $i == 1 ? ";Y".$ligne : "").";X".$i."\n";
					$flux .= "C;N;K\"".txtexp("$commentaire")."\"\n";
					$i++ ;
				}
			}
		}
		$flux .= "\n";
		$ligne++ ;
	}

	// fin du fichier SYLK
	$flux .= "E\n";

	// ISO-8859-1
	if ( isset($_POST["latin1"]) AND ($_POST["latin1"] == "latin1") ) {
		$flux = utf8_decode($flux) ;
	}
	$taille = strlen($flux) ;

	//echo "\xEF\xBB\xBF"; // UTF-8 BOM
	
	if ( isset($_POST["latin1"]) AND ($_POST["latin1"] == "latin1") ) {
		header('Content-type: application/vnd.ms-excel; charset=iso-8859-1') ;
		header("Content-Disposition: attachment; filename=\"".$fichier."\";" );
		//header("Content-Disposition: filename=\"".$fichier."\";" );
	}
	else {
		header('Content-type: application/vnd.ms-excel; charset=UTF-8') ;
		header("Content-Disposition: attachment; filename=\"".$fichier."\";" );
	}
/*
	//header("Content-type: text/csv; charset=utf-8");
	//header("Content-Type: application/octet-stream");

	header("Pragma: public");
	header("Pragma: no-cache") ;
	header("Expires: 0") ;
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);

	header("Content-Disposition: attachment; filename=$fichier") ;
	header("Content-type: application/octet-stream") ;

	header("Content-Transfer-Encoding: binary"); 
*/
	echo $flux ;

}









//
// Affichage du formulaire
// + gestion du message d'erreur 
//
else
{
	include("inc_html.php") ;
	$titre = "Exports (tableur)" ;
	echo $dtd1 ;
	echo "<title>$titre</title>\n" ;
	echo $htmlJquery ;
	echo $htmlMakeSublist ;
	echo $dtd2 ;
	include("inc_menu.php") ;
	echo $debut_chemin ;
	echo "<a href='/bienvenue.php'>Accueil</a>" ;
	echo " <span class='arr'>&rarr;</span> " ;
	echo $titre ;
	echo $fin_chemin ;

	if ( !isset($_SESSION["filtres"]["exporter"]) OR (count($_SESSION["filtres"]["exporter"]) == 0) ) {
		echo "<p class='c'>Vous devez commencer par <strong><a href='/exports/champs.php'>choisir quels champs vous voulez exporter</a></strong>.</p>" ;
	}
	else {
		echo "<p class='c'><strong><a href='/exports/champs.php'>" ;
		echo "Choix des champs à exporter</a></strong></p>\n" ;

		echo "<form action='index.php' method='post'>\n" ;

		include("inc_promotions.php") ;
		echo "<table class='formulaire'>\n" ;

		if ( intval($_SESSION["id"]) <= 3 ) {
			echo "<tr><td colspan='3'>" ;
			echo "Sélectionner une année <strong>OU</strong> une promotion à exporter :" ;
			echo "</td></tr>\n" ;

			echo "<tr><th><label for='annee'><strong>Année :</strong></label></th><td colspan='2'>" ;
			$req = "SELECT DISTINCT(annee) FROM session " ;
			if ( intval($_SESSION["id"]) > 3 ) {
				$req .= " WHERE id_session IN (".$_SESSION["liste_toutes_promotions"].")" ;
			}
			$req .= " ORDER BY annee DESC" ;
			$res = mysqli_query($cnx, $req) ;
			echo "<select id='annee' name='annee'>\n" ;
			echo "<option value=''></option>\n" ;
			while ( $enr = mysqli_fetch_assoc($res) ) {
				echo "<option value='".$enr["annee"]."'" ;
				echo ">".$enr["annee"]."</option>\n" ;
			}
			echo "</select>\n" ;
			echo " &nbsp; <span class=''>L'année sera ignorée si une promotion est sélectionnée.</span>" ;
			echo "</td></tr>\n" ;
		}

		echo "<tr><th><label for='promotion'><strong>Promotion :</strong></label></th><td colspan='2'> " ;
		if ( intval($_SESSION["id"]) > 3 ) {
			$tab_promotions = liste_promotions("promotion", "", $cnx) ;
		}
		else {
			// $tab_promotions = liste_promotions("promotion", "", $cnx, TRUE) ;
			$req = "SELECT id_session, annee, groupe, niveau, intitule, intit_ses
				FROM atelier, session
				WHERE session.id_atelier=atelier.id_atelier
				ORDER BY annee DESC, groupe, niveau, intitule" ;
			$formPromo = chaine_liste_promotions("promotion", "", $req, $cnx) ;
			echo $formPromo["form"] ;
			echo $formPromo["script"] ;
		}
		echo "</td></tr>\n" ;

		echo "<tr><td style='padding: 1px; background: #777; height: 1px;' colspan='3'></td></tr>\n" ;

		// Pays
		include_once("inc_pays.php") ;
		echo "<tr><th rowspan='4'>Limiter l'export à&nbsp;:</th>" ;
		echo "<th>Pays de résidence&nbsp;:</th><td>" ;
		echo selectPays($cnx, "pays", "") ;
		echo "</td></tr>\n" ;

		// Etat
		include_once("inc_etat_dossier.php") ;
		echo "<tr><th>&Eacute;tat de la candidature&nbsp;:</th><td>" ;
		liste_etats("etat", "", TRUE, FALSE, TRUE) ;
		echo "</td></tr>\n" ;

		// Imputes seulement
		echo "<tr><th>Imputés&nbsp;:</th><td>" ;
		echo "<label class='bl'><input type='checkbox' name='uniquement' value='imputes' /></label>" ;
		echo "</td></tr>\n" ;

		// Imputes seulement
		echo "<tr><th>Diplômés&nbsp;:</th><td>" ;
		echo "<input type='checkbox' name='uniquement' value='diplomes' />" ;
		echo "</td></tr>\n" ;

		// Tri
		function liste_tri($name, $value)
		{
			$TRI = array(
			    "id_dossier" => "Ordre d'arrivée des candidatures",
			    "date_maj" => "Date de mise à jour par le candidat (ordre chronologique inverse)",
			    "civilite" => "Civilité",
			    "nom" => "Nom",
			    "naissance" => "Date de naissance",
			    "pays" => "Pays de résidence",
			    "etat_dossier" => "&Eacute;tat du dossier",
				"classement" => "Ordre de classement des candidatures en attente"
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
		echo "<th>Trier par :</th>\n" ;
		echo "<td colspan='2'>" ;
		liste_tri("tri", "date_maj") ;
		if ( intval($_SESSION["id"]) <= 3 ) {
			echo " <span style='padding: 0 4em 0 2em'>(Tri pour chaque promotion)</span></td>\n" ;
		}
		/*
		else {
			echo " <span style='padding: 0 10em;'>&nbsp;</span></td>\n" ;
		}
		*/
		echo "</tr>\n" ;
		echo "<tr><td colspan='3'>\n" ;
		echo "<input type='hidden' name='operation' value='exporter' />\n" ;
		echo "<p style='float: right'><label for='latin1'><input type='checkbox' id='latin1' name='latin1' value='latin1' /> &nbsp;Exporter en ISO-8859-1</p>\n" ;
		echo "<p class='c'><input type='submit' value='Exporter' style='font-weight: bold;' /></p>\n" ;
		echo "</td></tr></table>\n" ;
		echo "</form>\n" ;
	}
/*
// Probleme:l'affichage du message d'erreur demeure sur la page, il ne faut ps l'afficher.
	if ( ($_POST["annee"] == "") AND ($_POST["promotion"] == "0") ) {
		echo "<p class='c erreur'>Veuillez sélectionner une année ou une promotion.</p>\n" ;
	}
*/
	echo "<p class='c'>Après avoir cliqué sur le bouton « Exporter », il faut enregistrer le fichier avant de pouvoir l'ouvrir.</p>\n" ;
	echo "<br />\n" ;
	echo "<p>Ces exports des candidatures sont directement exploitables dans Microsoft Excel, OpenOffice.org Calc, ou la plupart des tableurs.&nbsp;[1]<br />
Les notifications d'erreur de Microsoft Excel devraient être sans importance (il suffit de cliquer sur «&nbsp;OK&nbsp;»).</p>\n" ;
	if ( intval($_SESSION["id"]) > 3 ) {
		echo "<p>Le nom des fichiers générés est de la forme :\n" ;
		echo "<code>Date__Nom_de_la_promotion[__Imputes][__Diplomes][__Etat][__Pays].xls</code>.</p>\n" ;
	}
	else {
		echo "<p>Le nom des fichiers générés est de la forme :</p>\n" ;
		echo "<ul>\n" ;
		echo "<li><code>Date__Nom_de_la_promotion[__Imputes][__Diplomes][__Etat][__Pays].xls</code> &nbsp; pour un export d'une promotion.</li>\n" ;
		echo "<li><code>Date__Annee[__Imputes][__Diplomes][__Etat][__Pays].xls</code> &nbsp; pour un export d'une année.</li>\n" ;
		echo "</ul>\n" ;
	}
echo "<p>Les parties <code>[</code>entre crochets<code>]</code> ne sont présentes que si l'export est limité à certaines candidatures.<br />
La date est au format <code>AAAA-MM-JJ</code> pour permettre un tri chronologique sur le nom du fichier." ;
	echo "</p>\n" ;
	echo "<br />\n" ;
	echo "<p style='font-size:smaller'>[1] &nbsp; Les fichiers générés ont une extension <code>.xls</code> et un type MIME <code>application/vnd.ms-excel</code> afin d'être reconnus par les navigateurs, mais sont en fait au format SYLK.</p>" ;

/*
Test d'affichage
	include_once("inc_exports.php") ;
	$champs = exporter2champs($_SESSION["filtres"]["exporter"], TRUE) ;
	echo "<pre>" ;
	print_r($_SESSION["filtres"]["exporter"]) ;
	print_r($champs) ;
	echo "</pre>" ;
*/

	echo $end ;
}


deconnecter($cnx) ;
?>
