<?php
include("inc_session.php") ;
include_once("inc_etat_dossier.php") ;
include_once("inc_resultat.php") ;
$RESULTAT = tab_resultats() ;
$RESULTAT_IMG_CLASS = tab_resultats_img_class() ;

// Redirection vers l'unique promotion d'un selectionneur
/*
if ( count($_SESSION["tableau_promotions"]) == 1 ) {
	header("Location: /inscrits/inscrits.php?id_session="
		.$_SESSION["tableau_promotions"][0]) ;
	exit ;
}
*/

include("inc_html.php") ;

$titre = "Résultat des inscrits" ;
echo $dtd1 ;
echo "<title>$titre</title>" ;
echo $dtd2 ;
include("inc_menu.php") ;
echo $debut_chemin ;
echo "<a href='/bienvenue.php'>Accueil</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo $titre ;
echo $fin_chemin ;


include("inc_mysqli.php") ;
$cnx = connecter() ;                                                             

if ( intval($_SESSION["id"]) <= 3 )
{
	require_once("inc_groupe.php");

	echo "<form action='criteres.php' method='post'>\n" ;
	echo "<table class='formulaire'>\n" ;

    $req = "SELECT MAX(annee) FROM session" ;
    $res = mysqli_query($cnx, $req) ;
    $row = mysqli_fetch_row($res) ;
    $derniere_annee = $row[0] ;

    if ( !isset($_SESSION["filtres"]["inscrits"]["annee"]) ) {
        $_SESSION["filtres"]["inscrits"]["annee"] = $derniere_annee ;
    }

	echo "<tr>\n" ;
	echo "<th>Année&nbsp;: </th>\n" ;
	echo "<td><select name='inscrits_annee'>\n" ;
	$req = "SELECT DISTINCT(annee) FROM session ORDER BY annee DESC" ;
	$res = mysqli_query($cnx, $req) ;
	    while ( $enr = mysqli_fetch_assoc($res) ) {
	    echo "<option value='".$enr["annee"]."'" ;
	    if ( $_SESSION["filtres"]["inscrits"]["annee"] == $enr["annee"] ) {
	        echo " selected='selected'" ;
	    }
	    echo ">".$enr["annee"]."</option>" ;
	}
	echo "</select></td>\n" ;
	echo "</tr>\n" ;
	echo "<tr>\n" ;
	echo "<th>Domaine : </th>\n" ;
	echo "<td>" ;
	if ( isset($_SESSION["filtres"]["inscrits"]["groupe"]) ) {
		echo select_groupe($_SESSION["filtres"]["inscrits"]["groupe"]) ;
	}
	else {
		echo select_groupe("") ;
	}
	echo "</td>\n" ;
	echo "</tr>\n" ;



	if ( ($_SESSION["id"] == "02") OR ($_SESSION["id"] == "03") )
	{
		require_once("inc_pays.php") ;
		echo "<tr>\n" ;
		echo "<th>Pays de résidence&nbsp;:\n" ;
		echo "<div style='font-size: smaller'>Limiter aux formations pour lesquelles il y a au moins un candidat dans ce pays</div>" ;
		echo "</th>\n" ;
		echo "<td>" ;
		$req = "SELECT DISTINCT candidat.pays, ref_pays.code AS code, ref_pays.nom AS nom FROM candidat
			LEFT JOIN ref_pays ON candidat.pays=ref_pays.code
		    ORDER BY nom" ;
		echo selectPays($cnx, "inscrits_pays", 
			( isset($_SESSION["filtres"]["inscrits"]["pays"]) ? $_SESSION["filtres"]["inscrits"]["pays"] : "" ),
			$req) ;
		echo "</td>\n" ;
		echo "</tr>\n" ;
}
	echo "<tr>\n" ;
	echo "<td colspan='2'><div class='c'>" ;
	echo "<input class='b' type='submit' value='Actualiser' /></div></td>\n" ;
	echo "</tr>\n" ;
	echo "</table>\n" ;
	echo "</form>" ;
}


if ( ( ($_SESSION["id"] == "02") OR ($_SESSION["id"] == "03") ) AND ( !empty($_SESSION["filtres"]["inscrits"]["pays"]) ) ) {
	$requete = "SELECT DISTINCT annee, groupe, niveau, intitule, intit_ses, evaluations,
		session.id_session
		FROM session, atelier, dossier, candidat WHERE
		atelier.id_atelier=session.id_atelier
		AND session.id_session=dossier.id_session
		AND candidat.id_candidat=dossier.id_candidat
		AND candidat.pays='".$_SESSION["filtres"]["inscrits"]["pays"]."'
		AND annee='".$_SESSION["filtres"]["inscrits"]["annee"]."' " ;
	if ( $_SESSION["filtres"]["inscrits"]["groupe"] != "" ) {
		$requete .= " AND groupe='".mysqli_real_escape_string($cnx, $_SESSION["filtres"]["inscrits"]["groupe"])."' " ;
	}
	$requete .= " ORDER BY annee DESC, groupe, niveau, intitule" ;
		//AND (evaluations='Oui' OR imputations='Oui')
}
else if ( intval($_SESSION["id"]) < 4 ) {
	$requete = "SELECT * FROM session, atelier WHERE
		atelier.id_atelier=session.id_atelier
		AND annee='".$_SESSION["filtres"]["inscrits"]["annee"]."' " ;
	if ( isset($_SESSION["filtres"]["inscrits"]["groupe"]) AND ($_SESSION["filtres"]["inscrits"]["groupe"] != "") ) {
		$requete .= " AND groupe='".mysqli_real_escape_string($cnx, $_SESSION["filtres"]["inscrits"]["groupe"])."' " ;
	}
	$requete .= " ORDER BY annee DESC, groupe, niveau, intitule" ;
		//AND (evaluations='Oui' OR imputations='Oui')
}
else {
	$requete = "SELECT session.*, atelier.*
		FROM session , atelier , atxsel
		WHERE session.id_atelier=atelier.id_atelier
		AND atelier.id_atelier=atxsel.id_atelier
		AND atxsel.id_sel='".$_SESSION["id"]."'
		ORDER BY annee DESC, niveau, intitule" ; 
		//AND evaluations='Oui'
}
$resultat = mysqli_query($cnx, $requete) ;

/*
$req_inscrits = "SELECT id_dossier FROM dossier
    LEFT JOIN imputations ON dossier.id_dossier=imputations.ref_dossier
    WHERE id_session=".$_GET["id_session"]."
    AND (
        etat_dossier IN ($listeEtatsInscritsAutre)
        OR  (
                etat_dossier IN ($listeEtatsImputables)
                AND (id_imputation IS NOT NULL)
            )
        )
    ORDER BY id_dossier" ;
*/


function debut_tableau($annee, $id)
{
	$RESULTAT = tab_resultats() ;
	$RESULTAT_IMG_CLASS = tab_resultats_img_class() ;
	echo "<table class='tableau'>\n" ;
//	if ( $id < 4 ) {
		echo "<caption style='font-size: 1.5em;'>Année $annee</caption>\n" ;
//	}
	echo "<thead>\n<tr>
	<th rowspan='2'>Formation <span style='font-weight: normal'>(promotion)</span></th>
	<th rowspan='2'>Inscrits</th>
	<th colspan='3'>Résultat</th>
	</tr>
	<tr>\n" ;
	while ( list($key, $val) = each($RESULTAT) ) {
		echo "<td class='".$RESULTAT_IMG_CLASS["$key"]."'>".$val."</td>" ;
	}
	echo "</tr>\n</thead>\n<tbody>
" ;
} 
$fin_tableau = "</tbody>\n</table>\n" ;

if ( mysqli_num_rows($resultat) == 0 )
	if ( !empty($_SESSION["filtres"]["inscrits"]["pays"]) ) {
		echo "<p class='c'>Aucun candidat pour ce pays (de résidence).</p>" ;
	}
	else {
		echo "<p class='erreur c'>Aucune promotion avec des inscrits à évaluer actuellement !</p>" ;
	}
else
{
	$i = 1 ;
	$etat0 = TRUE ;
	$annee_precedente = "aucune" ;
	$groupe = "" ;
	while ( $ligne = mysqli_fetch_assoc($resultat) )
	{
		$criteresInscrits = "LEFT JOIN imputations ON dossier.id_dossier=imputations.ref_dossier
			WHERE id_session=".$ligne["id_session"]."
			AND (
				etat_dossier IN ($listeEtatsInscritsAutre)
				OR  (
					etat_dossier IN ($listeEtatsImputables)
					AND (id_imputation IS NOT NULL)
					)
				)" ;

		$req2 = "SELECT COUNT(*) AS N FROM dossier " . $criteresInscrits ;
		$res2 = mysqli_query($cnx, $req2) ;
		$enr2 = mysqli_fetch_assoc($res2) ;
		$nbInscrits = $enr2["N"] ;

		$nbDiplome = $nbAjourne = $nbInconnu = 0 ;
		$req3 = "SELECT resultat, COUNT(resultat) AS N FROM dossier " . $criteresInscrits . " GROUP BY resultat ORDER BY resultat" ;
		$res3 = mysqli_query($cnx, $req3) ;
		while ( $enr3 = mysqli_fetch_assoc($res3) ) {
			if ( $enr3["resultat"] == "0" ) {
				$nbInconnu = $enr3["N"] ;
			}
			else if ( $enr3["resultat"] == "1" ) {
				$nbDiplome = $enr3["N"] ;
			}
			else if ( $enr3["resultat"] == "2" ) {
				$nbAjourne = $enr3["N"] ;
			}
		}


		if ( $ligne["annee"] != $annee_precedente ) {
			if ( !$etat0 ) {
				echo $fin_tableau ;
				$etat0 = FALSE ;
			}
			debut_tableau($ligne["annee"], intval($_SESSION["id"]) ) ;
			$annee_precedente = $ligne["annee"] ;
		}

		if ( intval($_SESSION["id"]) < 4 ) {
			if ( $groupe != $ligne["groupe"] ) {
				$groupe = $ligne["groupe"] ;
				echo "<tr class='groupe'><td>" ;
				echo "$groupe</td></tr>" ;
			}
		}
		echo "<tr>" ;
		echo "<td><a href='/inscrits/inscrits.php?id_session=" ;
		echo $ligne["id_session"] ;
		echo "' class='bl'>" ;
		if ( $ligne["evaluations"] == "Non" ) {
			echo "<strong>" ;
		}
		echo $ligne["intitule"] ;
		if ( $ligne["evaluations"] == "Non" ) {
			echo "</strong>" ;
		}
		echo " (".$ligne["intit_ses"].")</a></td>\n" ;

		echo "<td class='r'><strong>".$nbInscrits."</strong></td>\n" ;
		echo "<td class='r'>".$nbInconnu."</td>\n" ;
		echo "<td class='r'>".$nbDiplome."</td>\n" ;
		echo "<td class='r'>".$nbAjourne."</td>\n" ;

		echo "</tr>\n" ;
		$i++ ;
	}
	echo "</tbody>\n</table>\n" ;
}

echo $end ;
deconnecter($cnx) ;

?>
