<?
include("inc_session.php") ;

// Redirection vers l'unique promotion d'un selectionneur
/*
if ( count($_SESSION["tableau_promotions"]) == 1 ) {
	header("Location: /candidatures/candidatures.php?id_session="
		.$_SESSION["tableau_promotions"][0]) ;
	exit ;
}
*/

include("inc_html.php") ;

$titre = "Gestion des candidatures" ;
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

echo "<p class='c'><strong><a href='selections_multiples.php'>Sélections multiples</a></strong></p>\n" ;


if ( intval($_SESSION["id"]) <= 3 )
{
	require_once("inc_groupe.php");

	echo "<form action='criteres.php' method='post'>\n" ;
	echo "<table class='formulaire'>\n" ;

    $req = "SELECT MAX(annee) FROM session" ;
    $res = mysqli_query($cnx, $req) ;
    $row = mysqli_fetch_row($res) ;
    $derniere_annee = $row[0] ;

    if ( !isset($_SESSION["filtres"]["candidatures"]["annee"]) ) {
        $_SESSION["filtres"]["candidatures"]["annee"] = $derniere_annee ;
    }

	echo "<tr>\n" ;
	echo "<th>Année&nbsp;: </th>\n" ;
	echo "<td><select name='c_annee'>\n" ;
	$req = "SELECT DISTINCT(annee) FROM session ORDER BY annee DESC" ;
	$res = mysqli_query($cnx, $req) ;
	    while ( $enr = mysqli_fetch_assoc($res) ) {
	    echo "<option value='".$enr["annee"]."'" ;
	    if ( $_SESSION["filtres"]["candidatures"]["annee"] == $enr["annee"] ) {
	        echo " selected='selected'" ;
	    }
	    echo ">".$enr["annee"]."</option>" ;
	}
	echo "</select></td>\n" ;
	echo "</tr>\n" ;
	echo "<tr>\n" ;
	echo "<th>Domaine : </th>\n" ;
	echo "<td>" ;
	if ( isset($_SESSION["filtres"]["candidatures"]["groupe"]) ) {
		echo select_groupe($_SESSION["filtres"]["candidatures"]["groupe"]) ;
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
		echo selectPays($cnx, "c_pays", 
			( isset($_SESSION["filtres"]["candidatures"]["pays"]) ? $_SESSION["filtres"]["candidatures"]["pays"] : "" ),
			$req) ;
	/*
		echo "<select name='c_pays'>\n" ;
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
		echo "<select name='c_pays'>\n" ;
		foreach($PAYS as $pays) {
			echo "<option value=\"$pays\"" ;
		    if ( isset($_SESSION["filtres"]["candidatures"]["pays"]) AND ($_SESSION["filtres"]["candidatures"]["pays"] == $pays) ) {
				echo " selected='selected'" ;
			}
			echo ">$pays</option>\n" ;
		}
		echo "</select>" ;
	*/
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


if ( ( ($_SESSION["id"] == "02") OR ($_SESSION["id"] == "03") ) AND ( !empty($_SESSION["filtres"]["candidatures"]["pays"]) ) ) {
	$requete = "SELECT DISTINCT annee, groupe, niveau, intitule, intit_ses, evaluations,
		session.id_session
		FROM session, atelier, dossier, candidat WHERE
		atelier.id_atelier=session.id_atelier
		AND session.id_session=dossier.id_session
		AND candidat.id_candidat=dossier.id_candidat
		AND candidat.pays='".$_SESSION["filtres"]["candidatures"]["pays"]."'
		AND annee='".$_SESSION["filtres"]["candidatures"]["annee"]."' " ;
	if ( $_SESSION["filtres"]["candidatures"]["groupe"] != "" ) {
		$requete .= " AND groupe='".mysqli_real_escape_string($cnx, $_SESSION["filtres"]["candidatures"]["groupe"])."' " ;
	}
	$requete .= " ORDER BY annee DESC, groupe, niveau, intitule" ;
		//AND (evaluations='Oui' OR imputations='Oui')
}
else if ( intval($_SESSION["id"]) < 4 ) {
	$requete = "SELECT * FROM session, atelier WHERE
		atelier.id_atelier=session.id_atelier
		AND annee='".$_SESSION["filtres"]["candidatures"]["annee"]."' " ;
	if ( isset($_SESSION["filtres"]["candidatures"]["groupe"]) AND ($_SESSION["filtres"]["candidatures"]["groupe"] != "") ) {
		$requete .= " AND groupe='".mysqli_real_escape_string($cnx, $_SESSION["filtres"]["candidatures"]["groupe"])."' " ;
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

function debut_tableau($annee, $id)
{
	echo "<table class='tableau'>\n" ;
//	if ( $id < 4 ) {
		echo "<caption style='font-size: 1.5em;'>Année $annee</caption>\n" ;
//	}
	echo "<thead>\n<tr>
	<th>Formation <span style='font-weight: normal'>(promotion)</span></th>
	</tr>\n</thead>\n<tbody>
" ;
} 
$fin_tableau = "</tbody>\n</table>\n" ;

if ( mysqli_num_rows($resultat) == 0 )
	if ( !empty($_SESSION["filtres"]["candidatures"]["pays"]) ) {
		echo "<p class='c'>Aucun candidat pour ce pays (de résidence).</p>" ;
	}
	else {
		echo "<p class='erreur c'>Aucune promotion avec des candidatures à évaluer actuellement !</p>" ;
	}
else
{
	$i = 1 ;
	$etat0 = TRUE ;
	$annee_precedente = "aucune" ;
	$groupe = "" ;
	while ( $ligne = mysqli_fetch_assoc($resultat) )
	{
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
		echo "<td><a href='/candidatures/candidatures.php?id_session=" ;
		echo $ligne["id_session"] ;
		echo "' class='bl'>" ;
		if ( $ligne["evaluations"] == "Oui" ) {
			echo "<strong>" ;
		}
		echo $ligne["intitule"] ;
		if ( $ligne["evaluations"] == "Oui" ) {
			echo "</strong>" ;
		}
		echo " (".$ligne["intit_ses"].")</a></td>\n" ;
		echo "</tr>\n" ;
		$i++ ;
	}
	echo "</tbody>\n</table>\n" ;
}

echo $end ;
deconnecter($cnx) ;

?>
