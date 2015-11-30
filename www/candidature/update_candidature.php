<?php

while ( list($key, $val) = each($T) ) {
	$Ts[$key] = mysqli_real_escape_string($cnx, remets_guillemets($val)) ;
}

$date_n = $Ts["annee_n"] ."-". $Ts["mois_n"] ."-".  $Ts["jour_n"] ;

$req = "UPDATE candidat SET
	civilite='".$Ts["civilite"]."',
	nom='".$Ts["nom"]."',
	nom_jf='".$Ts["nom_jf"]."',
	prenom='".$Ts["prenom"]."',
	naissance='".$date_n."',
	pays_naissance='".$Ts["pays_naissance"]."',
	genre='".$Ts["genre"]."',
	nationalite='".$Ts["nationalite"]."',
	situation_actu='".$Ts["situation_actu"]."',
	sit_autre='".$Ts["sit_autre"]."',

	adresse='".$Ts["adresse"]."',
	code_postal='".$Ts["code_postal"]."',
	ville='".$Ts["ville"]."',
	pays='".$Ts["pays"]."',
	tel='".$Ts["tel"]."',
	tlc_perso='".$Ts["tlc_perso"]."',
	email1='".$Ts["email1"]."',
	email2='".$Ts["email2"]."',

	emploi_actu='".$Ts["emploi_actu"]."',
	employeur='".$Ts["employeur"]."',
	service='".$Ts["service"]."',
	titre='".$Ts["titre"]."',
	adresse_emp='".$Ts["adresse_emp"]."',
	codepost_emp='".$Ts["codepost_emp"]."',
	ville_emp='".$Ts["ville_emp"]."',
	pays_emp='".$Ts["pays_emp"]."',
	tel_emp='".$Ts["tel_emp"]."',
	fax_emp='".$Ts["fax_emp"]."',
	email_pro1='".$Ts["email_pro1"]."',
	email_pro2='".$Ts["email_pro2"]."',
	duree_exp='".$Ts["duree_exp"]."',

	niveau_dernier_dip='".$Ts["niveau_dernier_dip"]."',
	dernier_dip='".$Ts["dernier_dip"]."',
	info_dernier_dip='".$Ts["info_dernier_dip"]."',
	inscri_europe='".$Ts["inscri_europe"]."',
	code_ine='".$Ts["code_ine"]."',

	exp_dist='".$Ts["exp_dist"]."',
	format_dist='".$Ts["format_dist"]."',
	exp_internet='".$Ts["exp_internet"]."',
	exp_bureau='".$Ts["exp_bureau"]."',

	projet_perso='".$Ts["projet_perso"]."',
	lettre_motiv='".$Ts["lettre_motiv"]."',
	cv='".$Ts["cv"]."',

	bourse_auf='".$Ts["bourse_auf"]."',
	financement_form='".$Ts["financement_form"]."',
	autre_pec='".$Ts["autre_pec"]."',
	prix_sud='".$Ts["prix_sud"]."',
	financement_sud='".$Ts["financement_sud"]."',
	autre_sud='".$Ts["autre_sud"]."',

	nbre_heures='".$Ts["nbre_heures"]."',

	ordipro='".$Ts["ordipro"]."',
	netpro='".$Ts["netpro"]."',
	ordiperso='".$Ts["ordiperso"]."',
	fixeportable='".$Ts["fixeportable"]."',
	netperso='".$Ts["netperso"]."',

	service_cnf='".$Ts["service_cnf"]."',
	temps_dep='".$Ts["temps_dep"]."',
	nbre_dep='".$Ts["nbre_dep"]."',

	signature='".$Ts["signature"]."',
	date_sign='".$Ts["date_sign"]."',
	ville_res='".$Ts["ville_res"]."'

	WHERE id_candidat=".$Ts["id_candidat"] ;
	/* Avant 2008
	acces_pc='".$Ts["acces_pc"]."',
	appart_pc='".$Ts["appart_pc"]."',
	connexion_int='".$Ts["connexion_int"]."',
	autre_acces_internet='".$Ts["autre_acces_internet"]."',
	*/
$res = mysqli_query($cnx, $req) ;


// Diplomes
for ( $i=1 ; $i<=4 ; $i++ )
{
	$req = "UPDATE diplomes SET
		annee_dip='".$Ts["annee_dip$i"]."',
		titre_dip='".$Ts["titre_dip$i"]."',
		mention_dip='".$Ts["mention_dip$i"]."',
		etab_dip='".$Ts["etab_dip$i"]."',
		pays_dip='".$Ts["pays_dip$i"]."'
		WHERE code_dip=".$Ts["code_dip$i"]."
		AND id_candidat=".$Ts["id_candidat"] ;
	$res = mysqli_query($cnx, $req) ;
}

// Stages
for ( $i=1 ; $i<=4 ; $i++ )
{
	$req = "UPDATE stage SET
		annee_stage='".$Ts["annee_stage$i"]."',
		titre_stage='".$Ts["titre_stage$i"]."',
		org_stage='".$Ts["org_stage$i"]."'
		WHERE code_stage=".$Ts["code_stage$i"]."
		AND id_candidat=".$Ts["id_candidat"] ;
	$res = mysqli_query($cnx, $req) ;
}

// Questions
if ( isset($Ts["nombre_questions"]) AND ($Ts["nombre_questions"] != 0) ) {
	for ( $i=1 ; $i<=$Ts["nombre_questions"] ; $i++ )
	{
		$req = "UPDATE reponse SET texte_rep='".$Ts["question$i"]."'
			WHERE id_reponse='".$Ts["id_reponse$i"]."'
			AND id_question='".$Ts["id_question$i"]."'" ;
		$res = mysqli_query($cnx, $req) ;
	}
}

// Date de mise à jour du dossier
$req = "UPDATE dossier SET date_maj=CURRENT_DATE
	WHERE id_dossier=".$Ts["id_dossier"]."
	AND id_candidat=".$Ts["id_candidat"]  ;
$res = mysqli_query($cnx, $req) ;

?>
