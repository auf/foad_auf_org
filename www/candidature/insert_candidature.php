<?php

while ( list($key, $val) = each($T) ) {
	$Ts[$key] = mysqli_real_escape_string($cnx, remets_guillemets($val)) ;
}

$date_n = $Ts["annee_n"] ."-". $Ts["mois_n"] ."-".  $Ts["jour_n"] ;
// Avant 2008 :
// acces_pc, appart_pc, connexion_int, autre_acces_internet,
$req = "INSERT INTO candidat (
	civilite, nom, nom_jf, prenom, naissance, pays_naissance, genre,
	nationalite, situation_actu, sit_autre,

	adresse, code_postal, ville, pays,
	tel, tlc_perso, email1, email2,

	emploi_actu, employeur, service, titre,
	adresse_emp, codepost_emp, ville_emp, pays_emp,
	tel_emp, fax_emp, email_pro1, email_pro2,
	duree_exp,

	niveau_dernier_dip, dernier_dip, info_dernier_dip,
	inscri_europe, code_ine,

	exp_dist, format_dist, exp_internet, exp_bureau, 

	projet_perso, lettre_motiv, cv,

	bourse_auf, financement_form, autre_pec,
	prix_sud, financement_sud, autre_sud,

	nbre_heures,
	ordipro, netpro, ordiperso, fixeportable, netperso,
	service_cnf, temps_dep, nbre_dep,

	signature, date_sign, ville_res )

	VALUES (
	'".$Ts["civilite"]."', '".$Ts["nom"]."', '".$Ts["nom_jf"]."',
	'".$Ts["prenom"]."', '".$date_n."', '".$Ts["pays_naissance"]."', '".$Ts["genre"]."',
	'".$Ts["nationalite"]."', '".$Ts["situation_actu"]."', '".$Ts["sit_autre"]."',

	'".$Ts["adresse"]."', '".$Ts["code_postal"]."', '".$Ts["ville"]."',
	'".$Ts["pays"]."', '".$Ts["tel"]."', '".$Ts["tlc_perso"]."',
	'".$Ts["email1"]."', '".$Ts["email2"]."',

	'".$Ts["emploi_actu"]."', '".$Ts["employeur"]."', '".$Ts["service"]."',
	'".$Ts["titre"]."', '".$Ts["adresse_emp"]."', '".$Ts["codepost_emp"]."',
	'".$Ts["ville_emp"]."', '".$Ts["pays_emp"]."', '".$Ts["tel_emp"]."',
	'".$Ts["fax_emp"]."', '".$Ts["email_pro1"]."', '".$Ts["email_pro2"]."',
	'".$Ts["duree_exp"]."',

	'".$Ts["niveau_dernier_dip"]."', '".$Ts["dernier_dip"]."',
	'".$Ts["info_dernier_dip"]."', '".$Ts["inscri_europe"]."',
	'".$Ts["code_ine"]."',

	'".$Ts["exp_dist"]."', '".$Ts["format_dist"]."',
	'".$Ts["exp_internet"]."', '".$Ts["exp_bureau"]."',

	'".$Ts["projet_perso"]."', '".$Ts["lettre_motiv"]."', '".$Ts["cv"]."',

	'".$Ts["bourse_auf"]."', '".$Ts["financement_form"]."', '".$Ts["autre_pec"]."',
	'".$Ts["prix_sud"]."', '".$Ts["financement_sud"]."', '".$Ts["autre_sud"]."',

	'".$Ts["nbre_heures"]."',
	'".$Ts["ordipro"]."', '".$Ts["netpro"]."',
	'".$Ts["ordiperso"]."', '".$Ts["fixeportable"]."', '".$Ts["netperso"]."',
	'".$Ts["service_cnf"]."', '".$Ts["temps_dep"]."', '".$Ts["nbre_dep"]."',

	'".$Ts["signature"]."', '".$Ts["date_sign"]."', '".$Ts["ville_res"]."' )" ; 

$res = mysqli_query($cnx, $req) ;
$id_candidat = mysqli_insert_id($cnx) ;

//Ajout des diplomes
$req = "INSERT INTO diplomes 
	(code_dip, id_candidat, annee_dip, titre_dip, mention_dip, etab_dip, pays_dip)
	VALUES ('','$id_candidat','".$Ts["annee_dip1"]."',
	'".$Ts["titre_dip1"]."','".$Ts["mention_dip1"]."',
	'".$Ts["etab_dip1"]."','".$Ts["pays_dip1"]."')";
mysqli_query($cnx, $req);
$req = "INSERT INTO diplomes
	(code_dip, id_candidat, annee_dip, titre_dip, mention_dip, etab_dip, pays_dip)
	VALUES ('','$id_candidat','".$Ts["annee_dip2"]."',
	'".$Ts["titre_dip2"]."','".$Ts["mention_dip2"]."',
	'".$Ts["etab_dip2"]."','".$Ts["pays_dip2"]."')";
mysqli_query($cnx, $req);
$req = "INSERT INTO diplomes
	(code_dip, id_candidat, annee_dip, titre_dip, mention_dip, etab_dip, pays_dip)
	VALUES ('','$id_candidat','".$Ts["annee_dip3"]."',
	'".$Ts["titre_dip3"]."','".$Ts["mention_dip3"]."',
	'".$Ts["etab_dip3"]."','".$Ts["pays_dip3"]."')";
mysqli_query($cnx, $req);
$req = "INSERT INTO diplomes
	(code_dip, id_candidat, annee_dip, titre_dip, mention_dip, etab_dip, pays_dip)
	VALUES ('','$id_candidat','".$Ts["annee_dip4"]."',
	'".$Ts["titre_dip4"]."','".$Ts["mention_dip4"]."',
	'".$Ts["etab_dip4"]."','".$Ts["pays_dip4"]."')";
mysqli_query($cnx, $req);

//Ajout des stages
$req = "INSERT INTO stage
	(code_stage,id_candidat,annee_stage,titre_stage,org_stage)
	VALUES ('','$id_candidat','".$Ts["annee_stage1"]."',
	'".$Ts["titre_stage1"]."','".$Ts["org_stage1"]."')";
mysqli_query($cnx, $req);
$req = "INSERT INTO stage
	(code_stage,id_candidat,annee_stage,titre_stage,org_stage)
	VALUES ('','$id_candidat','".$Ts["annee_stage2"]."',
	'".$Ts["titre_stage2"]."','".$Ts["org_stage2"]."')";
mysqli_query($cnx, $req);
$req = "INSERT INTO stage
	(code_stage,id_candidat,annee_stage,titre_stage,org_stage)
	VALUES ('','$id_candidat','".$Ts["annee_stage3"]."',
	'".$Ts["titre_stage3"]."','".$Ts["org_stage3"]."')";
mysqli_query($cnx, $req);
$req = "INSERT INTO stage
	(code_stage,id_candidat,annee_stage,titre_stage,org_stage)
	VALUES ('','$id_candidat','".$Ts["annee_stage4"]."',
	'".$Ts["titre_stage4"]."','".$Ts["org_stage4"]."')";
mysqli_query($cnx, $req);



$pwd = key_generator(8) ;
$req = "INSERT INTO dossier
	(id_dossier, id_candidat, id_session,
	date_inscrip, etat_dossier, pwd, date_maj, diplome)
	VALUES ('', '$id_candidat', '$id_session',
	CURRENT_DATE, 'Non étudié','$pwd', CURRENT_DATE, 'non')" ;
mysqli_query($cnx, $req) ;
$id_dossier = mysqli_insert_id($cnx) ;


if ( $nombre_questions > 0 )
{
	for ( $i=1 ; $i <= $nombre_questions ; $i++ )
	{
		$req = "INSERT INTO reponse VALUES ('',
			'".$Ts["id_question$i"]."',
			'".$Ts["question$i"]."',
			$id_dossier)" ;
		mysqli_query($cnx, $req) ;
	}
}

/*envoi d'email de confirmation*/
$req = "SELECT nom, prenom, email1 FROM candidat WHERE id_candidat=$id_candidat";
//drapeau($req);
$res = mysqli_query($cnx, $req);
$enr = mysqli_fetch_assoc($res);
$nom = $enr["nom"];
$prenom = $enr["prenom"];
$email = $enr["email1"];

// FIXME phpMailer pour l'encodage...

require_once("inc_config.php") ;
require_once("inc_aufPhpmailer.php") ;
$mail = new aufPhpmailer() ;
$mail->From = EMAIL_FROM ;
$mail->FromName = NOM_CONTACT ;
$mail->AddReplyTo(EMAIL_CONTACT, "") ;
$mail->Sender = EMAIL_SENDER ;
$mail->Subject = "Votre candidature sur le site FOAD de l'AUF" ;
$mail->Body = "Bonjour $prenom $nom".",

Votre candidature à la formation :
$intitule
(Promotion $intit_ses)
a été enregistrée.

Vous pouvez la modifier jusqu'à la date de clôture des candidatures sur
https://" . URL_DOMAINE . "/candidature/
en utilisant les paramètres suivants :

  Numéro de dossier : $id_dossier
  Mot de passe      : $pwd

L'enregistrement de votre candidature ne constitue pas une sélection
pour suivre la formation. Le collège pédagogique de la formation
procédera à la sélection qui sera  publiée sur le site FOAD de l'AUF
(http://" . URL_DOMAINE_PUBLIC . ").

Cordialement,

Agence universitaire de la Francophonie
http://" . URL_DOMAINE_PUBLIC . "

" . MESSAGE_AUTOMATIQUE ;

$mail->AddAddress($email) ;
if ( !$mail->Send() ) {
	echo "<p class='erreur'>L'envoi du mail a échoué.</p>" ;
}
$mail->ClearAddresses() ;

?>
