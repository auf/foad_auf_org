<?php

function historiqueResultatAdd($cnx, $ref_dossier, $resultat_hist)
{
	$req = "INSERT INTO resultat_hist
		(ref_dossier, resultat_hist, date_resultat_hist, resultat_par)
		VALUES(
			" . $ref_dossier . ",
			'". $resultat_hist ."',
			CURDATE(),
			'". mysqli_real_escape_string($cnx, $_SESSION["utilisateur"]) ."'
		)" ;
	mysqli_query($cnx, $req) ;
}

require_once("inc_date.php") ;
require_once("inc_resultat.php") ;
/*
var_dump($RESULTAT) ;
$resultat = $RESULTAT ;
var_dump($resultat) ;
*/

function historiqueResultatShow($cnx, $ref_dossier)
{
	$RESULTAT = tab_resultats() ;
	$RESULTAT_IMG_CLASS = tab_resultats_img_class() ;

	$req = "SELECT * FROM resultat_hist WHERE ref_dossier=".$ref_dossier."
		ORDER BY id_resultat_hist" ;
	$res = mysqli_query($cnx, $req) ;

	$tab = "" ;

	if ( mysqli_num_rows($res) == 0 ) {
		return $tab ;
	}

	$tab .= "<div class='historique'><div>" ;

	$tab .= "<table class='petit' style='margin: 0'>\n" ;
	$tab .= "<caption>Historique du résultat</caption>\n" ;
	while ( $enr = mysqli_fetch_assoc($res) )
	{
		$tab .= "<tr>\n" ;
		$tab .= "<td>".mysql2date($enr["date_resultat_hist"])."</td>\n" ;
		$tab .= "<td><span class='".$RESULTAT_IMG_CLASS[$enr["resultat_hist"]]."'>"
			. $RESULTAT[$enr["resultat_hist"]]
			. "</span></td>\n" ;
		$tab .= "<td>".$enr["resultat_par"]."</td>\n" ;
		$tab .= "</tr>\n" ;
	}
	$tab .= "</table>\n" ;
	$tab .= "</div></div>\n" ;
	return $tab ;
}


function historiqueResultatTitle($cnx, $ref_dossier)
{
	$RESULTAT = tab_resultats() ;

	$req = "SELECT * FROM resultat_hist WHERE ref_dossier=".$ref_dossier."
		ORDER BY id_resultat_hist" ;
	$res = mysqli_query($cnx, $req) ;

	$str = "" ;

	if ( mysqli_num_rows($res) == 0 ) {
		return $str ;
	}

	$str .= "<span class='help' title=\"" ;
	while ( $enr = mysqli_fetch_assoc($res) )
	{
		$str .= $RESULTAT[$enr["resultat_hist"]] ;
		$str .= " le " ;
		$str .= mysql2date($enr["date_resultat_hist"]) ;
		$str .= " par " ;
		$str .= $enr["resultat_par"] ;
		$str .= "\n" ;
	}
	$str .= "\">" ;

	return $str ;
}
?>
