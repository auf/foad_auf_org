<?
/*
echo "<pre>" ;
print_r($_POST) ;
echo "</pre>" ;
*/

$date_debut = date_to_mysql($_POST["jour_debut"],
	$_POST["mois_debut"], $_POST["annee_debut"]) ;
$date_fin = date_to_mysql($_POST["jour_fin"],
	$_POST["mois_fin"], $_POST["annee_fin"]) ;
$v_intitule = mysqli_real_escape_string($cnx, $_POST["v_intitule"]) ;
$annee = $_POST["annee"] ;
$imputation = $_POST["imputation"] ;
$imputation2 = $_POST["imputation2"] ;
$tarifP = floatval(str_replace(",", ".", $_POST["tarifP"])) ;
$tarif2P = floatval(str_replace(",", ".", $_POST["tarif2P"])) ;
$tarifA = floatval(str_replace(",", ".", $_POST["tarifA"])) ;
$tarif2A = floatval(str_replace(",", ".", $_POST["tarif2A"])) ;
$candidatures = $_POST["candidatures"] ;
$evaluations = $_POST["evaluations"] ;
$imputations = $_POST["imputations"] ;
$imputations2 = $_POST["imputations2"] ;
// Pour les cursus en 2 ans, imputation2, tarif2P et tarif2A peuvent être vides
// mais imputations2 doit valoir Non.
if ( $imputations2 == "" ) {
	$imputations2 = "Non" ;
}
$id_atelier = $_POST["id_atelier"] ;
$session = $_POST["session"] ;

if ( $_POST["operation"] == "addBase" )
{
	$req = "INSERT INTO session
		(date_deb, date_fin, intit_ses,
		candidatures, evaluations, imputations, imputations2,
		id_atelier, annee,
		imputation, tarifP, tarifA,
		imputation2, tarif2P, tarif2A)
		VALUES(
		'$date_debut',
		'$date_fin',
		'$v_intitule',
		'$candidatures',
		'$evaluations',
		'$imputations',
		'$imputations2',
		'$id_atelier',
		'$annee',
		'$imputation',
		'$tarifP',
		'$tarifA',
		'$imputation2',
		'$tarif2P',
		'$tarif2A'
	)";
//	echo $req ;
	$res = mysqli_query($cnx, $req) ;
	if ( !($res) ) {
		echo "<p class='erreur c'>La promotion n'a pas été créée&nbsp;:<br />"
		. mysqli_error($cnx) . "</p>\n" ;
	}
	else {
		$_SESSION["filtres"]["promotions"]["annee"] = $annee ;
		header("Location: /promotions/index.php") ;
	}
}

if ( $_POST["operation"] == "modifBase" )
{
	$req = "UPDATE session SET
		date_deb='$date_debut',
		date_fin='$date_fin',
		intit_ses ='$v_intitule',
		candidatures='$candidatures',
		evaluations='$evaluations',
		imputations='$imputations',
		imputations2='$imputations2',
		id_atelier='$id_atelier',
		annee='$annee',
		imputation='$imputation',
		tarifP='$tarifP',
		tarifA='$tarifA',
		imputation2='$imputation2',
		tarif2P='$tarif2P',
		tarif2A='$tarif2A'
		WHERE id_session=$session" ;
//	echo $req ;
	$res = mysqli_query($cnx, $req) ;
	if ( !($res) ) {
		echo "<p class='erreur c'>La promotion n'a pas été modifiée&nbsp;:<br />"
		. mysqli_error($cnx) . "</p>\n" ;
	}
	else {
		header("Location: /promotions/index.php#p".$session) ;
	}
}
?>
