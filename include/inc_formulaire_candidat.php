<?php
// Code semblable au formulaire de candidature
// pour le formulaire d'ajout d'un ancien par ajout minimal d'un candidat

$CANDIDATURE = array(
	"promotion" => array(
		"Promotion",
		),
	"civilite" => array(
		"Civilité",
		),
	"nom" => array(
		"Nom de famille",
		),
	"nom_jf" => array(
		"Nom de jeune fille",
		),
	"prenom" => array(
		"Prénoms",
		),
	"naissance" => array(
		"Date de naissance",
		),
	"nationalite" => array(
		"Nationalité",
		),
	"pays" => array(
		"Pays de résidence",
		),
// candidat.email1 / ancien.courriel
	"courriel" => array(
		"Courrier électronique",
		),
// candidat.emploi_actu / ancien.profession
	"profession" => array(
		"Profession",
		),
// candidat.employeur / ancien.employeur
	"employeur" => array(
		"Employeur",
		),
) ;

function libelc($champ)
{
	global $CANDIDATURE ;
	if ( $CANDIDATURE[$champ][1] != "" ) {
		return str_replace("&nbsp;", " ", strip_tags($CANDIDATURE[$champ][1])) ;
	}
	else {
		return str_replace("&nbsp;", " ", strip_tags($CANDIDATURE[$champ][0])) ;
	}
}
function longueurc($champ)
{
	global $CANDIDATURE ;
	return $CANDIDATURE[$champ][2] ;
}

$tab_civilite = array(
	"Madame",
	"Mademoiselle",
	"Monsieur"
) ;

$jour = array(
	"",
	"01","02","03","04","05","06","07","08","09","10",
	"11","12","13","14","15","16","17","18","19","20",
	"21","22","23","24","25","26","27","28","29","30",
	"31") ;

$mois = array(
	array("", ""),
	array("01", "janvier"),
	array("02", "février"),
	array("03", "mars"),
	array("04", "avril"),
	array("05", "mai"),
	array("06", "juin"),
	array("07", "juillet"),
	array("08", "août"),
	array("09", "septembre"),
	array("10", "octobre"),
	array("11", "novembre"),
	array("12", "décembre")
) ;

$annee_courante = intval(date("Y", time())) ;

unset($annee_nai) ;
$annee_nai[] = "" ;
for ( $i = ($annee_courante - 70) ; $i <= ($annee_courante - 15) ; $i++ ) {
	$annee_nai[] = $i ;
}

unset($tab_annee_dip_sta) ;
$tab_annee_dip_sta[] = "" ;
for ( $i = $annee_courante ; $i >= ($annee_courante - 55) ; $i-- ) {
	$tab_annee_dip_sta[] = $i ;
}

?>
