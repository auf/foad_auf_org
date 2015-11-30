<?php

require_once("inc_mysqli.php");
require_once("inc_identite.php");
require_once("inc_date.php");
require_once("inc_annuaire.php");

$cnx = connecter() ;

$req = "SELECT anciens.*,
	intitule, intit_ses, annee
	FROM anciens, dossier_anciens, dossier, session, atelier
	WHERE id_ancien=".mysqli_real_escape_string($cnx, $_GET["id_ancien"])."
	AND anciens.id_ancien=dossier_anciens.ref_ancien
	AND dossier_anciens.ref_session=session.id_session
	AND dossier_anciens.ref_atelier=atelier.id_atelier
	AND session.id_atelier=atelier.id_atelier
	AND dossier.id_dossier=dossier_anciens.ref_dossier
	ORDER BY annee DESC" ;
$res = mysqli_query($cnx, $req) ;

$ancienTitre = "Inconnu" ;
$ancienIdentite = "Inconnu" ;
$ancienPage = "<p>Cet ancien n'existe pas.</p>" ;

if ( mysqli_num_rows($res) != 0 )
{
	$row = mysqli_fetch_assoc($res) ;

	$ancienTitre = $row["civilite"] . " " . $row["nom"] ;

	$ancienIdentite = identite($row) ;

	$ancienPage  = "" ;
	$ancienPage .= afficheAncienDiplomesSpip($cnx, $row["id_ancien"], TRUE) ;

	$ancienPage .= "<h3 class='spip'>" ;
	$ancienPage .= "Informations personnelles" ;
/*
	$ancienPage .= " mises à jour le " ;
	$ancienPage .= mysql2datealpha($row["date_maj"]) ;
*/
	$ancienPage .= "</h3>\n" ;
/*
*/
	$ancienPage .= "<div class='date'>\n" ;
	$ancienPage .= "Informations mises à jour le " ;
	$ancienPage .= mysql2datealpha($row["date_maj"]) ;
	$ancienPage .= "</div>\n" ;

	$ancienPage .= "<p>Pays de résidence : " . $row["pays"] . "</p>" ;
}

deconnecter($cnx) ;
?>
