<?php

$SECTION_CANDIDATURE = array(
	"1" => "Informations personnelles",
	"2" => "Adresse personnelle",
	"3" => "Informations professionnelles",
	"4" => "Diplômes et certifications",
	"5" => "Expérience de la <acronym class='help' title='Formation ouverte et A Distance'>FOAD</acronym> et de l'informatique",
	"6" => "Motivations, curriculum vitae",
	"7" => "Financement de la formation",
	"8" => "Informations complémentaires",
	"9" => "Questions",
	"signature" => "Signature",
	"fichiers" => "Fichiers joints",
	"commentaires" => "Évaluations<span class='noprint'>, état du dossier</span>",
) ;

$CANDIDATURE = array(
// 1.
	"civilite" => array(
		"Civilité",
		"",
		9),
	"nom" => array(
		"Nom de famille",
		"",
		15),
	"nom_jf" => array(
		"Nom de jeune fille (pour les femmes mariées uniquement)",
		"Nom de jeune fille",
		15),
	"prenom" => array(
		"Prénoms",
		"",
		15),
	"naissance" => array(
		"Date de naissance",
		"",
		15),
	"pays_naissance" => array(
		"Pays de naissance",
		"",
		15),
	"genre" => array(
		"Genre",
		"",
		7),
	"nationalite" => array(
		"Nationalité",
		"",
		15),
	"situation_actu" => array(
		"Situation Actuelle",
		"",
		20),
	"sit_autre" => array(
		"Si «&nbsp;Autre&nbsp;», précisez",
		"",
		20),
// 2
	"adresse" => array(
		"Adresse",
		"",
		25),
	"code_postal" => array(
		"Boîte ou code postal",
		"",
		10),
	"ville" => array(
		"Ville",
		"",
		12),
	"pays" => array(
		"Pays de résidence",
		"",
		15),
	"tel" => array(
		"Téléphone",
		"",
		15),
	"tlc_perso" => array(
		"Télécopie",
		"",
		15),
	"email1" => array(
		"Courrier électronique 1",
		"",
		20),
	"email2" => array(
		"Courrier électronique 2",
		"",
		20),
// 3
	"emploi_actu" => array(
		"Emploi actuel",
		"",
		20),
	"employeur" => array(
		"Organisme employeur",
		"",
		20),
	"service" => array(
		"Département, service",
		"",
		20),
	"titre" => array(
		"Fonction ou titre",
		"",
		20),
	"adresse_emp" => array(
		"Adresse",
		"",
		25),
	"codepost_emp" => array(
		"Boîte ou code postal",
		"",
		10),
	"ville_emp" => array(
		"Ville",
		"",
		12),
	"pays_emp" => array(
		"Pays",
		"",
		15),
	"tel_emp" => array(
		"Téléphone",
		"",
		15),
	"fax_emp" => array(
		"Télécopie",
		"",
		15),
	"email_pro1" => array(
		"Courrier électronique professionnel 1",
		"",
		20),
	"email_pro2" => array(
		"Courrier électronique professionnel 2",
		"",
		20),
	"duree_exp" => array(
		"Durée de votre expérience professionnelle",
		"Durée de l'expérience professionnelle",
		14),
// 4.
// Ce champ n'existe pas, c'est un intitulé pour les 3 suivants.
	"dernier_diplome" => array(
		"Diplôme obtenu le plus élevé",
		""),
	"niveau_dernier_dip" => array(
		"Niveau du diplôme obtenu le plus élevé",
		"",
		12),
	"dernier_dip" => array(
		"Intitulé du diplôme obtenu le plus élevé",
		"",
		20),
	"info_dernier_dip" => array(
		"Date et lieu d'obtention du diplôme obtenu le plus élevé",
		"Date et lieu d'obtention",
		20),
	"inscri_europe" => array(
		"Si vous avez déjà été inscrit dans un établissement d'enseignement supérieur en Europe ou au Canada, précisez dans quel établissement et pour quel diplôme",
		"",
		30),
	"code_ine" => array(
		"Si vous avez été inscrit dans un établissement d'enseignement supérieur français, précisez ici votre code&nbsp;<acronym title='Identifiant National Etudiant'>INE</acronym>",
		"Code INE",
		12),
	// Personnalisation des deux questions precedentes pour les formations concernées
	"inscri_ouaga" => array(
		"Si vous avez déjà été inscrit à l'université Ouaga 2 ou à l'université de Ouagadougou, précisez dans quel établissement et pour quel diplôme",
		"",
		30),
	"code_ouaga" => array(
		"Si vous avez été inscrit à l'université Ouaga 2 ou à l'université de Ouagadougou, précisez ici votre n° matricule",
		"N° matricule",
		12),

// Ce champ n'existe pas.
	"diplomes" => array(
		"Liste de tous les derniers diplômes académiques (y compris le plus élevé) en cours et obtenus",
		"Derniers diplômes académiques en cours et obtenus"),
// Ce champ n'existe pas.
	"stages" => array(
		"Liste des stages de formation suivis ou des certifications professionnelles obtenues",
		""),
// 5.
	"exp_dist" => array(
		"Avez vous déjà eu une expérience en matière de formation à distance&nbsp;?",
		"Expérience en matière de formation à distance&nbsp;?",
		5),
	"format_dist" => array(
		"Si «&nbsp;Oui&nbsp;», précisez",
		"",
		20),
	"exp_internet" => array(
		"Précisez votre expérience en matière d'usage de l'Internet",
		"",
		30),
	"exp_bureau" => array(
		"Nommez les logiciels, bureautiques et professionnels, que vous estimez maîtriser correctement",
		"",
		30),
// 6.
	"projet_perso" => array(
		"Décrivez le projet personnel ou le projet pédagogique que vous souhaiteriez mener pendant votre formation",
		"",
		40),
	"lettre_motiv" => array(
		"Lettre de motivation (expliquez en 20 lignes environ les raisons qui vous incitent à choisir cette formation)",
		"",
		40),
	"cv" => array(
		"Inscrivez ici votre curriculum vitae",
		"",
		40),
//
// 7.
//
	"bourse_auf" => array(
		"Sollicitez-vous une allocation d'étude à distance de l'<acronym title='Agence universitaire de la Francophonie'>AUF</acronym>&nbsp;?",
		"",
		5),
	"financement_form" => array(
		"Qui prend en charge la part personnelle de financement qui vous est demandée pour cette formation, même en cas d'allocation de l'<acronym title='Agence universitaire de la Francophonie'>AUF</acronym>&nbsp;?",
		"",
		12),
	"autre_pec" => array(
		"Si «&nbsp;Autre&nbsp;», précisez",
		"",
		15),
	"prix_sud" => array(
		"Si vous n'obtenez pas une allocation d'étude à distance de l'<acronym title='Agence universitaire de la Francophonie'>AUF</acronym>, pensez-vous quand même pouvoir trouver le budget nécessaire à la formation&nbsp;?",
		"",
		5),
	"financement_sud" => array(
		"Si «&nbsp;Oui&nbsp;», qui prendra en charge le financement&nbsp;?",
		"",
		12),
	"autre_sud" => array(
		"Si «&nbsp;Autre&nbsp;», précisez",
		"",
		15),

//
// 8.
//
	"nbre_heures" => array(
		"De combien d'heures par semaine pensez-vous pouvoir disposer pour suivre votre formation&nbsp;?",
		"Temps pour suivre la formation, par semaine",
		15),
	// Nouveaux champs en 2008
	"ordipro" => array(
		"Disposez vous d'un accès réservé à un ordinateur sur votre lieu de travail&nbsp;?",
		"Accès réservé à un ordinateur sur le lieu de travail&nbsp;?",
		),
	"netpro" => array(
		"Disposez vous d'une connexion Internet à votre travail&nbsp;?",
		"Connexion Internet sur le lieu de travail&nbsp;?",
		),
	"ordiperso" => array(
		"Disposez vous d'un ordinateur à votre domicile&nbsp;?",
		"Ordinateur à domicile&nbsp;?",
		),
	"fixeportable" => array(
		"Si «&nbsp;Oui&nbsp;», cet ordinateur est il&nbsp;?",
		"",
		),
	"netperso" => array(
		"Disposez vous d'une connexion Internet à votre domicile&nbsp;?",
		"Connexion Internet à domicile&nbsp;?",
		),
	// Fin nouveaux champs 2008
	// Champs avant 2008
	"acces_pc" => array(
		"Avez-vous facilement accès à un ordinateur sur lequel vous pourrez travailler&nbsp;?",
		"Accès facile à un ordinateur pour travailler",
		5),
	"appart_pc" => array(
		"Si «&nbsp;Oui&nbsp;», cet ordinateur vous appartient-il&nbsp;?",
		"Cet ordinateur vous appartient-il&nbsp;?",
		5),
	"connexion_int" => array(
		"Disposez-vous d'une connexion à Internet chez vous ou à votre travail que vous pourrez utiliser pour votre formation&nbsp;?",
		"Connexion Internet pour suivre la formation&nbsp;?",
		5),
	"autre_acces_internet" => array(
		"Si «&nbsp;Non&nbsp;», comment comptez vous pouvoir accéder réguliérement à Internet&nbsp;?",
		"Comment comptez vous accéder à Internet&nbsp;?",
		20),
	// Fin champs avant 2008
	"service_cnf" => array(
		"Avez-vous besoin des services d'un <acronym title='Campus Numérique Francophone'>CNF</acronym> de l'<acronym title='Agence universitaire de la Francophonie'>AUF</acronym>&nbsp;?",
		"Besoin des services d'un <acronym title='Campus Numérique Francophone'>CNF</acronym> de l'<acronym title='Agence universitaire de la Francophonie'>AUF</acronym>&nbsp;?",
		5),
	"temps_dep" => array(
		"Si «&nbsp;Oui&nbsp;», combien de temps de déplacement vous faut-il pour rejoindre un CNF&nbsp;?",
		"Temps de déplacement vers un CNF&nbsp;?",
		15),
	"nbre_dep" => array(
		"Si «&nbsp;Oui&nbsp;», combien de fois par semaine pensez-vous pouvoir vous rendre dans un CNF&nbsp;?",
		"Présence prévue dans un CNF",
		15),
//
// signature
//
/*
	"" => array(
		"",
		""),
	"" => array(
		"",
		"")
*/
// "sexe"
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

$tab_duree = array(
	"-------",
	"1 an",
	"entre 1 et 3 ans",
	"entre 3 et 5 ans",
	"entre 5 et 10 ans",
	"plus de 10 ans"
) ;
$tab_dernier_dip = array(
	"-------",
	"Baccalauréat",
	"Bac+1",
	"Bac+2",
	"Bac+3",
	"Bac+4",
	"Bac+5",
	"Supérieur à Bac+5"
) ;
$tab_nbre_heures = array(
	"-------",
	"De 5 à 10 heures",
	"De 10 à 15 heures",
	"De 15 à 20 heures",
	"Plus de 20 heures"
) ;
$tab_temps_dep = array(
	"-------",
	"Moins d'une demi_heure",
	"Moins d'une heure",
	"Plus qu'une heure"
) ;
$tab_nbre_dep = array(
	"-------",
	"Tous les jours",
	"2 ou 3 fois par semaine",
	"1 fois par semaine",
	"Au moins 2 ou 3 fois par mois"
) ;
$tab_genre = array(
	"Homme",
	"Femme"
) ;
$tab_civilite = array(
	"Madame",
	"Mademoiselle",
	"Monsieur"
) ;
$situation = array(
	"-------",
	"Etudiant(e)",
	"Enseignant(e)",
	"Fonctionnaire",
	"Salarié(e) du secteur privé",
	"Employé(e) d'une ONG ou d'une coopération",
	"Sans emploi",
	"Autre"
) ;
$financement = array(
	"-------",
	"Moi même",
	"Ma famille",
	"Mon employeur",
	"Autre"
) ;
$oui_non = array(
	"-------",
	"Oui",
	"Non"
) ;
$fixe_portable = array(
	"-------",
	"un portable",
	"un poste fixe"
) ;
/*
$reglement = array("Chèque","Virement","Autre");
$financement = array("-------","Vous même","Votre employeur","Autre");
$situation = array("-------","Etudiant(e)","Salarié(e)ou Travailleur indépendant","En recherche d'emploi","Autre");
$niveau_etude = array("1er cycle","2ème cycle","3ème cycle");
*/


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

$CONSIGNES = "
<p>Complétez le formulaire de candidature ci-dessous, et cliquez sur le bouton «&nbsp;<strong>Valider</strong>&nbsp;» en bas de page.<br />
Vous pourrez, le cas échéant, joindre un ou plusieurs fichiers à votre candidature dans la page suivante.</p>
";
/*
	Muni<a href='/candidature/mise_a_jour.php' target='_blank'>Confirmer ou modifier</a></li>
*/
?>
