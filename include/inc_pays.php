<?php
// Génère un tableau de correspondance code nom de pays
// Permet d'éviter une requête par pays, ou une jointure dans les requêtes existantes avant intégration des données externes
// Permet aussi d'afficher simplement un code de pays qui ne se trouverait plus dans les données externes.
//$statiquePAYS = statiquePays($cnx) ;
function statiquePays($cnx)
{
	$statiquePAYS = array() ;
	// Cas des champs vides (anciens dossiers...)
	$statiquePAYS[""] = "" ;
	$req = "SELECT code, nom FROM ref_pays WHERE actif=1 ORDER BY nom" ;
	$res = mysqli_query($cnx, $req) ;
	while ( $enr = mysqli_fetch_assoc($res) ) {
		$statiquePAYS[$enr["code"]] = $enr["nom"] ;
	}
	return $statiquePAYS ;
}

// FIXME 
// Affiche le pays correspondant au code, ou le code
function refPays($code, $statiquePAYS)
{
	if ( array_key_exists($code, $statiquePAYS) ) {
		return $statiquePAYS[$code] ;
	}
	else {
		return $code ;
	}
}

function selectPays($cnx, $name, $value="", $req="SELECT code, nom FROM ref_pays WHERE actif=1 ORDER BY nom")
{
	$str = "" ;
	$res = mysqli_query($cnx, $req) ;
	$str .= "<select name='$name'>\n" ;
	$str .= "<option value=''></option>" ;
	while ( $enr = mysqli_fetch_assoc($res) )
	{
	    $str .= "<option value='".$enr["code"]."'" ;
	    if ( $enr["code"] == $value ) {
	        $str .= " selected='selected'" ;
	    }
		$str .= ">".$enr["nom"]."</option>\n" ;
	}
	$str .= "</select>\n" ;
	return $str ;
}
function selectRegion($cnx, $name, $value="", $req="SELECT id, nom FROM ref_region WHERE actif=1 ORDER BY nom")
{
	$str = "" ;
	$res = mysqli_query($cnx, $req) ;
	$str .= "<select name='$name'>\n" ;
	$str .= "<option value=''></option>" ;
	while ( $enr = mysqli_fetch_assoc($res) )
	{
	    $str .= "<option value='".$enr["id"]."'" ;
	    if ( $enr["id"] == $value ) {
	        $str .= " selected='selected'" ;
	    }
		$str .= ">".$enr["nom"]."</option>\n" ;
	}
	$str .= "</select>\n" ;
	return $str ;
}


function selectPaysRegion($cnx, $name, $value="", $req="SELECT ref_pays.code AS code_pays, ref_pays.nom AS nom_pays, ref_region.nom AS nom_region
	FROM ref_pays LEFT JOIN ref_region ON ref_pays.region=ref_region.id
	WHERE ref_pays.actif=1 ORDER BY nom_region, nom_pays")
{
	$str = "" ;
	$res = mysqli_query($cnx, $req) ;
	$str .= "<select name='$name'>\n" ;
	$str .= "<option value=''></option>" ;
	$region_precedente = "aucune" ;
	$nbRegions = 0 ;
	while ( $enr = mysqli_fetch_assoc($res) )
	{
		if ( $enr["nom_region"] != $region_precedente ) {
			if ( $nbRegions != 0 ) {
				$str .= "</optgroup>\n" ;
			}
			$str .= "<optgroup label=\"".$enr["nom_region"]."\">\n" ;
			$region_precedente = $enr["nom_region"] ;
			$nbRegions++ ;
		}
	    $str .= "<option value='".$enr["code_pays"]."'" ;
	    if ( $enr["code_pays"] == $value ) {
	        $str .= " selected='selected'" ;
	    }
		$str .= ">".$enr["nom_pays"]."</option>\n" ;
		
	}
	$str .= "</optgroup>\n" ;
	$str .= "</select>\n" ;
	return $str ;
}


$PAYS_MIGRATION = array(
	"Afghanistan", "Afrique du Sud", "Albanie", "Algérie", "Allemagne", "Andorre", "Angola",
//	"Anguilla",
	"Antigua-et-Barbuda", // "Antigua-Et-Barbuda"
//	"Antilles néerlandaises",
	"Arabie saoudite", // "Arabie Saoudite"
	"Argentine", "Arménie", "Australie", "Autriche", "Azerbaïdjan", "Bahamas", "Bahreïn", "Bangladesh", "Barbade",
	"Biélorussie", // "Bélarus"
	"Belgique", "Belize", "Bénin",
//	"Bermudes",
	"Bhoutan", "Bolivie", "Bosnie-Herzégovine", "Botswana", "Brésil",
	"Brunei", // "Brunei Darussalam"
	"Bulgarie", "Burkina Faso", "Burundi", "Cambodge", "Cameroun", "Canada", "Cap-Vert", "Chili", "Chine", "Chypre", "Colombie", "Comores", "Congo",
	"Costa Rica", "Côte d'Ivoire", "Croatie", "Cuba",
	"Danemark", "Djibouti",
	"Dominique", // FIXME NOUVEAU
	"Egypte",
	"Salvador",
	"Emirats arabes unis", "Equateur",
	"Erythrée", "Espagne", "Estonie", "Etats Unis d'Amérique", "Ethiopie",
	"Russie",
	"Fidji", "Finlande", "France", "Gabon", "Gambie",
	"Géorgie",
	"Ghana",
//	"Groënland",
	"Grèce",
	"Grenade",
//	"Guam",
	"Guatemala", "Guinée",
	"Guinée-Bissao",
	"Guinée équatoriale",
	"Guyana",
	"Haïti", "Honduras", "Hongrie",
	"Îles Marshall", // FIXME NOUVEAU
//	"Îles Cook",
	"Îles Salomon", "Inde", "Indonésie",
	"Iran",
	"Iraq", "Irlande", "Islande", "Israël", "Italie",
	"Jamaïque", // FIXME NOUVEAU
	"Japon", "Jordanie", "Kazakhstan", "Kenya", "Kirghistan",
	"Kiribati", // FIXME NOUVEAU
	"Koweït",
	"Lesotho", "Lettonie", "Liban", "Libéria", "Libye", "Liechtenstein",
	"Lituanie", "Luxembourg",
	"Macédoine", "Madagascar", "Malaisie", "Malawi", "Maldives", "Mali",
	"Malte", // FIXME NOUVEAU
	"Maroc",
	"Maurice", "Mauritanie",
	"Mexique", // FIXME NOUVEAU
//	"Mayotte",
	"Micronésie (états fédérés de)", // FIXME NOUVEAU
	"Monaco", "Mongolie",
	"Monténégro", // FIXME NOUVEAU
	"Mozambique",
	"Myanmar", // FIXME NOUVEAU
	"Namibie",
	"Nauru", // FIXME NOUVEAU
	"Népal",
	"Nicaragua", "Niger", "Nigeria", "Norvège", "Nouvelle-Zélande",
	"Oman", "Ouganda", "Ousbékistan",
	"Pakistan",
	"Palaos", // FIXME NOUVEAU
	"Territoires Palestiniens",
	"Panama", "Papouasie-Nouvelle-Guinée", "Paraguay", "Pays-Bas", "Pérou",
	"Philippines", // FIXME NOUVEAU (est dans les bureaux...)
	"Pologne", "Portugal", "Qatar",
	"Syrie",
	"République Centrafricaine",
	"Corée du Sud",
	"Moldavie",
	"Congo (R.D)",
	"Laos",
	"République Dominicaine",
	"Corée du Nord",
	"République Tchèque",
	"Tanzanie",
	"Roumanie", "Royaume-Uni", "Rwanda",
	"Saint-Kitts-et-Nevis", // FIXME NOUVEAU
	"Saint-Marin",
	"Saint-Siège (État de la Cité du Vatican)", // FIXME NOUVEAU
	"Saint-Vincent-et-les Grenadines", "Sainte-Lucie",
	"Samoa occidentales", "Sao Tomé-et-Principe", "Sénégal",
	"Serbie", // FIXME NOUVEAU
	"Seychelles",
	"Sierra Leone", "Singapour", "Slovaquie", "Slovénie", "Somalie", "Soudan",
	"Soudan du Sud", // FIXME NOUVEAU
	"Sri Lanka", "Suède", "Suisse", "Suriname", "Swaziland",
	"Tadjikistan", // FIXME NOUVEAU
//	"Taiwan",
	"Tchad",
//	"Tchétchénie",
	"Thaïlande",
	"Timor-Leste", // FIXME NOUVEAU
	"Togo", "Tonga", "Trinité-et-Tobago", "Tunisie", "Turkménistan", "Turquie",
	"Tuvalu", // FIXME NOUVEAU
	"Ukraine", "Uruguay", "Vanuatu",
	"Vénézuela",
	"Viêt-Nam",
//	"Yougoslavie",
	"Yémen", "Zambie", "Zimbabwe"
) ;

// ==========================
// Ancienne version
// ==========================
$PAYS = array(
	"",
	"Afghanistan", "Afrique du Sud", "Albanie", "Algérie", "Allemagne", "Andorre", "Angola",
	"Anguilla",
	"Antigua-et-Barbuda",
	"Antilles néerlandaises",
	"Arabie saoudite", "Argentine", "Arménie",
	"Australie", "Autriche", "Azerbaïdjan",
	"Bahamas", "Bahreïn", "Bangladesh", "Barbade", "Belgique", "Belize",
	"Bénin", "Bermudes", "Bhoutan", "Biélorussie", "Bolivie",
	"Bosnie-Herzégovine", "Botswana", "Brunei", "Brésil",
	"Bulgarie", "Burkina Faso", "Burundi", "Cambodge",
	"Cameroun", "Canada", "Cap-Vert", "Chili", "Chine", "Chypre", "Colombie",
	"Comores", "Congo", "Congo (R.D)", "Corée du Nord", "Corée du Sud",
	"Costa Rica", "Côte d'Ivoire", "Croatie", "Cuba",
	"Danemark", "Djibouti", "Egypte", "Emirats arabes unis", "Equateur",
	"Erythrée", "Espagne", "Estonie", "Etats Unis d'Amérique", "Ethiopie",
	"Fidji", "Finlande", "France", "Gabon", "Gambie", "Ghana",
	"Grenade", "Groënland", "Grèce", "Guam", "Guatemala", "Guinée",
	"Guinée équatoriale", "Guinée-Bissao", "Guyana", "Géorgie",
	"Haïti", "Honduras", "Hongrie",
	"Îles Cook", "Îles Salomon",
	"Inde", "Indonésie", "Iran", "Iraq", "Irlande", "Islande", "Israël",
	"Italie",
	"Japon", "Jordanie", "Kazakhstan", "Kenya", "Kirghistan", "Koweït",
	"Laos", "Lesotho", "Lettonie", "Liban", "Libéria", "Libye", "Liechtenstein",
	"Lituanie", "Luxembourg",
	"Macédoine", "Madagascar", "Malaisie", "Malawi", "Maldives", "Mali", "Maroc",
	"Maurice", "Mauritanie", "Mayotte", "Moldavie", "Monaco", "Mongolie",
	"Mozambique",
	"Namibie", "Népal",
	"Nicaragua", "Niger", "Nigeria", "Norvège", "Nouvelle-Zélande",
	"Oman", "Ouganda", "Ousbékistan",
	"Pakistan", "Panama", "Papouasie-Nouvelle-Guinée", "Paraguay", "Pays-Bas",
	"Pérou",
	"Pologne", "Portugal",
	"Qatar",
	"Roumanie", "Royaume-Uni", "Rwanda", "République Centrafricaine",
	"République Dominicaine", "République Tchèque", "Russie",
	"Saint-Marin", "Saint-Vincent-et-les Grenadines", "Sainte-Lucie", "Salvador",
	"Samoa occidentales", "Sao Tomé-et-Principe", "Sénégal", "Seychelles",
	"Sierra Leone", "Singapour", "Slovaquie", "Slovénie", "Somalie", "Soudan",
	"Sri Lanka", "Suède", "Suisse", "Suriname", "Swaziland", "Syrie",
	"Taiwan", "Tanzanie", "Tchad", "Tchétchénie", "Territoires Palestiniens",
	"Thaïlande",
	"Togo", "Tonga", "Trinité-et-Tobago", "Tunisie", "Turkménistan", "Turquie",
	"Ukraine", "Uruguay", "Vanuatu", "Viêt-Nam",
	"Vénézuela", "Yougoslavie", "Yémen", "Zambie", "Zimbabwe"
) ;
$TAB_BUREAUX = array( // copie
	"Bureau Afrique Centrale",
	"Bureau Afrique de l'Ouest",
	"Bureau Asie Pacifique",
	"Bureau Caraibe",
	"Bureau Europe centrale et orientale",
	"Europe occidentale",
	"Maghreb",
	"Bureau Moyen Orient",
	"Bureau Océan Indien",
	"Autres Sud",
	"Autres Nord",
) ;
$TAB_PAYS = array( // copie
    array(
		"Cameroun",
		"Burundi",
		"Congo (R.D)",
		"Congo",
		"Gabon",
		"Rwanda",
		"République Centrafricaine",
		"Tchad"
	),
    array(
		"Mali",
		"Bénin",
		"Burkina Faso",
		"Cap-Vert",
		"Côte d'Ivoire",
		"Guinée",
		"Guinée équatoriale",
		"Mauritanie",
		"Niger",
		"Sénégal",
		"Togo"
	),
	array(
		"Cambodge","Laos", "Vanuatu","Viêt-Nam"
	),
	array(
		"Haïti"
	),
	array(
		"Albanie",
		"Bulgarie",
		"Géorgie",
		"Hongrie",
		"Macédoine",
		"Moldavie",
		"Roumanie"
	),
	array(
		"Andorre","Autriche","Belgique","Chypre","Danemark",
		"Espagne","Finlande","France","Grèce",
		"Irlande","Islande","Italie",
		"Lettonie","Liechtenstein","Lituanie","Luxembourg",
		"Monaco","Norvège","Pays-Bas","Pologne","Portugal",
		"Royaume-Uni","République Tchèque",
		"Slovaquie","Slovénie","Suède","Suisse","Yougoslavie"
	),
	array(
		"Algérie", "Maroc", "Tunisie"
	),
	array(
		"Djibouti", "Egypte", "Jordanie", "Liban", "Syrie",
		"Territoires Palestiniens"
	),
	array(
		"Madagascar", "Maurice", "Comores"
	),
	array(
		"Afghanistan","Afrique du Sud","Angola","Anguilla",
		"Antigua-et-Barbuda","Arabie saoudite","Argentine",
		"Arménie","Azerbaïdjan",
		"Bahamas","Bahreïn","Bangladesh","Barbade","Belize","Bermudes",
		"Bhoutan","Biélorussie","Bolivie","Bosnie-Herzégovine",
		"Botswana","Brunei","Brésil",
		"Chili","Chine","Colombie","Corée du Nord","Corée du Sud",
		"Costa Rica","Croatie","Cuba",
		"Emirats arabes unis","Equateur","Erythrée","Estonie","Ethiopie",
		"Fidji","Gambie","Ghana","Grenade","Guam","Guatemala","Guinée-Bissao",
		"Guyana",
		"Honduras","Îles Cook","Îles Salomon",
		"Inde","Indonésie","Iran",
		"Iraq","Israël","Kazakhstan","Kenya","Kirghistan","Koweït",
		"Lesotho","Libye","Libéria",
		"Malaisie","Malawi","Maldives","Mayotte","Mongolie","Mozambique",
		"Namibie","Nicaragua","Nigeria","Népal",
		"Oman","Ouganda","Ousbékistan",
		"Pakistan","Panama","Papouasie-Nouvelle-Guinée","Paraguay",
		"Pérou", "Philippines",
		"Qatar",
		"République Dominicaine",
		"Saint-Marin","Saint-Vincent-et-les Grenadines","Sainte-Lucie",
		"Salvador","Samoa occidentales","Sao Tomé-et-Principe","Seychelles",
		"Sierra Leone","Singapour","Somalie","Soudan","Sri Lanka",
		"Suriname","Swaziland",
		"Taiwan","Tanzanie","Tchétchénie","Thaïlande",
		"Tonga","Trinité-et-Tobago","Turkménistan","Turquie",
		"Ukraine","Uruguay","Vénézuela","Yémen","Zambie","Zimbabwe"
	),
	array(
		"Allemagne","Australie","Antilles néerlandaises",
		"Canada","Groënland","Nouvelle-Zélande","Russie",
		"Japon","Etats Unis d'Amérique"
	),
) ;


function liste_pays($name, $selected, $empty=FALSE)
{
	global $PAYS ;
	echo "<select name='$name'>\n" ;
	foreach($PAYS as $pay) {
	    echo "<option value=\"$pay\"" ;
	    if ( $selected == $pay ) {
	        echo " selected='selected'" ;
	    }
	    echo ">$pay</option>\n" ;
	}
	echo "</select>" ;
}
function listePays($name, $selected)
{
	global $PAYS ;
	$form = "<select name='$name'>\n" ;
	foreach($PAYS as $pay) {
	    $form .= "<option value=\"$pay\"" ;
	    if ( $selected == $pay ) {
	        $form .= " selected='selected'" ;
	    }
	    $form .= ">$pay</option>\n" ;
	}
	$form .= "</select>" ;
	return $form;
}
/*
"Samoa occidentales" ou "Samoa Américaines"
Aruba appartient aux Pays-Bas
*/

?>
