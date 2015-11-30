<?php

function historiqueAdd($cnx, $ref_dossier, $etat_hist, $evaluations)
{
	$req = "INSERT INTO etat_hist
		(ref_dossier, etat_hist, date_hist, evaluations, etat_par)
		VALUES(
			" . $ref_dossier . ",
			'". $etat_hist ."',
			CURDATE(),
			'". $evaluations ."',
			'". mysqli_real_escape_string($cnx, $_SESSION["utilisateur"]) ."'
		)" ;
	mysqli_query($cnx, $req) ;
}

require_once("inc_date.php") ;
require_once("inc_etat_dossier.php") ;
function historiqueShow($cnx, $ref_dossier)
{
	global $etat_dossier_img_class ;

	$req = "SELECT * FROM etat_hist WHERE ref_dossier=".$ref_dossier."
		ORDER BY id_etat_hist" ;
	$res = mysqli_query($cnx, $req) ;

	$tab = "" ;

	if ( mysqli_num_rows($res) == 0 ) {
		return $tab ;
	}

	$tab .= "<div class='historique'><div>" ;
	$tab .= "<table class='petit' style='margin: 0'>\n" ;
	$tab .= "<caption>Historique de l'état du dossier</caption>\n" ;
	while ( $enr = mysqli_fetch_assoc($res) )
	{
		$tab .= "<tr>\n" ;
		$tab .= "<td>".mysql2date($enr["date_hist"])."</td>\n" ;
		$tab .= "<td><span class='".$etat_dossier_img_class[$enr["etat_hist"]]."'>".$enr["etat_hist"]."</span></td>\n" ;
		$tab .= "<td>".$enr["etat_par"]."</td>\n" ;
		$tab .= "</tr>\n" ;
	}
	$tab .= "</table>\n" ;
	$tab .= "</div></div>\n" ;
	return $tab ;
}


function historiqueTitle($cnx, $ref_dossier)
{
	$req = "SELECT * FROM etat_hist WHERE ref_dossier=".$ref_dossier."
		AND evaluations='Non'
		ORDER BY id_etat_hist" ;
	$res = mysqli_query($cnx, $req) ;

	$str = "" ;

	if ( mysqli_num_rows($res) == 0 ) {
		return $str ;
	}

	$str .= "<span class='help' title=\"" ;
	while ( $enr = mysqli_fetch_assoc($res) )
	{
		$str .= $enr["etat_hist"] ;
		$str .= " le " ;
		$str .= mysql2date($enr["date_hist"]) ;
		$str .= " par " ;
		$str .= $enr["etat_par"] ;
		$str .= "\n" ;
	}
	$str .= "\">" ;

	return $str ;
}

?>
