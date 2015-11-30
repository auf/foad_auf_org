<?php
section_candidature("1") ;
affiche_si_non_vide( isset($erreur_saisie1) ? $erreur_saisie1 : "" ) ;
?>
<tr>
	<th><?php libelle("civilite") ?></th>
	<td><?php radio($tab_civilite, "civilite",
		( isset($T["civilite"]) ? $T["civilite"] : "" )
		) ; ?>
	</td>
</tr><tr>
	<th><?php libelle("nom") ; ?></th>
	<td><?php inputtxt("nom",
		( isset($T["nom"]) ? $T["nom"] : "" ),
		30, 60) ; ?></td>
</tr><tr>
	<th><?php libelle("nom_jf") ; ?></th>
	<td><?php inputtxt("nom_jf",
		( isset($T["nom_jf"]) ? $T["nom_jf"] : "" ),
		30, 50) ; ?></td>
</tr><tr>
	<th><?php libelle("prenom") ; ?></th>
	<td><?php inputtxt("prenom",
		( isset($T["prenom"]) ? $T["prenom"] : "" ),
		50, 100) ; ?></td>
</tr><tr>
	<th><?php libelle("naissance") ; ?></th>
	<td><?php liste_der1($jour, "jour_n", ( isset($T["jour_n"]) ? $T["jour_n"] : "" )) ;
		echo " / " ;
		liste_der2($mois, "mois_n", ( isset($T["mois_n"]) ? $T["mois_n"] : "" )) ;
		echo " / " ;
		liste_der1($annee_nai, "annee_n", ( isset($T["annee_n"]) ? $T["annee_n"] : "" )) ;
		?></td>
</tr><tr>
	<th><?php libelle("pays_naissance") ; ?></th>
	<td><?php echo selectPays($cnx, "pays_naissance",
		( isset($T["pays_naissance"]) ? $T["pays_naissance"] : "" )
		) ; ?></td>
</tr><tr>
	<th><?php libelle("genre") ; ?></th>
	<td><?php  radio($tab_genre, "genre", ( isset($T["genre"]) ? $T["genre"] : "" )); ?></td>
</tr><tr>
	<th><?php libelle("nationalite") ; ?></th>
	<td><?php inputtxt("nationalite",
		( isset($T["nationalite"]) ? $T["nationalite"] : "" ),
		30, 50); ?></td>
</tr><tr>
	<th><?php libelle("situation_actu") ; ?></th>
	<td><?php
		liste_der1($situation, "situation_actu", ( isset($T["situation_actu"]) ? $T["situation_actu"] : "" ));
		?><div><?php libelle("sit_autre") ; ?></div><?php
		textarea("sit_autre", ( isset($T["sit_autre"]) ? $T["sit_autre"] : "" ), 40, 1) ?></td>
</tr>
</table>


<?php 
section_candidature("2") ;
affiche_si_non_vide( isset($erreur_saisie2) ? $erreur_saisie2 : "" ) ;
?>
<tr>
	<th><?php libelle("adresse") ; ?></th>
	<td><?php inputtxt("adresse", ( isset($T["adresse"]) ? $T["adresse"] : "" ), 70, 100); ?></td>
</tr><tr>
	<th><?php libelle("code_postal") ; ?></th>
	<td><?php inputtxt("code_postal", ( isset($T["code_postal"]) ? $T["code_postal"] : "" ), 20, 20); ?></td>
</tr><tr>
	<th><?php libelle("ville") ; ?></th>
	<td><?php inputtxt("ville", ( isset($T["ville"]) ? $T["ville"] : "" ), 20, 100); ?></td>
</tr><tr>
	<th><?php libelle("pays") ; ?></th>
	<td><?php echo selectPays($cnx, "pays", ( isset($T["pays"]) ? $T["pays"] : "" )); ?></td>
</tr><tr>
	<th><?php libelle("tel") ; ?></th>
	<td><?php inputtxt("tel", ( isset($T["tel"]) ? $T["tel"] : "" ), 20, 30); ?></td>
</tr><tr>
	<th><?php libelle("tlc_perso") ; ?></th>
	<td><?php inputtxt("tlc_perso",( isset($T["tlc_perso"]) ? $T["tlc_perso"] : "" ), 20, 20); ?></td>
</tr><tr>
	<th><?php libelle("email1") ; ?></th>
	<td><?php inputtxt("email1", ( isset($T["email1"]) ? $T["email1"] : "" ), 50, 100); ?>
<div class="erreur">Attention !
C'est à cette adresse que vous recevrez le résultat de votre éventuelle sélection.<br />
C'est aussi à cette adresse que vous recevrez le numéro de dossier et le mot de passe<br />qui vous permettront de modifier votre candidature.</div>
<div>Saisissez à nouveau cette adresse, pour vérification&nbsp;:</div>
	<?php inputtxt("verif_email1", ( isset($T["verif_email1"]) ? $T["verif_email1"] : "" ), 50, 100); ?>
</td>
</tr><tr>
	<th><?php libelle("email2") ; ?></th>
	<td><?php inputtxt("email2", ( isset($T["email2"]) ? $T["email2"] : "" ), 50, 100); ?></td>
</tr>
</table>


<?php
section_candidature("3") ;
affiche_si_non_vide( isset($erreur_saisie3) ? $erreur_saisie3 : "" ) ;
?>
<tr>
	<th><?php libelle("emploi_actu") ; ?></th>
	<td><?php inputtxt("emploi_actu",
		( isset($T["emploi_actu"]) ? $T["emploi_actu"] : "" ),
		50, 100); ?></td>
</tr><tr>
	<th><?php libelle("employeur") ; ?></th>
	<td><?php inputtxt("employeur",
		( isset($T["employeur"]) ? $T["employeur"] : "" ),
		50, 100); ?></td>
</tr><tr>
	<th><?php libelle("service") ; ?></th>
	<td><?php inputtxt("service",
		( isset($T["service"]) ? $T["service"] : "" ),
		50, 100); ?></td>
</tr><tr>
	<th><?php libelle("titre") ; ?></th>
	<td><?php inputtxt("titre",
		( isset($T["titre"]) ? $T["titre"] : "" ),
		50, 100); ?></td>
</tr><tr>
	<th><?php libelle("adresse_emp") ; ?></th>
	<td><?php inputtxt("adresse_emp", ( isset($T["adresse_emp"]) ? $T["adresse_emp"] : "" ), 70, 100); ?></td>
</tr><tr>
	<th><?php libelle("codepost_emp") ; ?></th>
	<td><?php inputtxt("codepost_emp", ( isset($T["codepost_emp"]) ? $T["codepost_emp"] : "" ), 20, 20); ?></td>
</tr><tr>
	<th><?php libelle("ville_emp") ; ?></th>
	<td><?php inputtxt("ville_emp",( isset($T["ville_emp"]) ? $T["ville_emp"] : "" ), 20, 100); ?></td>
</tr><tr>
	<th><?php libelle("pays_emp") ; ?></th>
	<td><?php echo selectPays($cnx, "pays_emp", ( isset($T["pays_emp"]) ? $T["pays_emp"] : "" )); ?></td>
</tr><tr>
	<th><?php libelle("tel_emp") ; ?></th>
	<td><?php inputtxt("tel_emp", ( isset($T["tel_emp"]) ? $T["tel_emp"] : "" ), 20, 30); ?></td>
</tr><tr>
	<th><?php libelle("fax_emp") ; ?></th>
	<td><?php inputtxt("fax_emp", ( isset($T["fax_emp"]) ? $T["fax_emp"] : "" ), 20, 30); ?></td>
</tr><tr>
	<th><?php libelle("email_pro1") ; ?></th>
	<td><?php inputtxt("email_pro1", ( isset($T["email_pro1"]) ? $T["email_pro1"] : "" ), 50, 100); ?></td>
</tr><tr>
	<th><?php libelle("email_pro2") ; ?></th>
	<td><?php inputtxt("email_pro2", ( isset($T["email_pro2"]) ? $T["email_pro2"] : "" ), 50, 100); ?></td>
</tr><tr>
	<th><?php libelle("duree_exp") ; ?></th>
	<td><?php liste_der1($tab_duree, "duree_exp", ( isset($T["duree_exp"]) ? $T["duree_exp"] : "" )); ?></td>
</tr>
</table>


<?php
section_candidature("4") ;
affiche_si_non_vide( ( isset($erreur_saisie4) ? $erreur_saisie4 : "" ), 3) ;
?>
<tr class='sous' style='border-top: 3px solid #fff;'>
	<th rowspan='3'><?php
		libelle("dernier_diplome") ; /* champ inexistant */ ?></th>
	<th><?php libelle("niveau_dernier_dip") ; ?></th>
	<td><?php liste_der1($tab_dernier_dip,
		"niveau_dernier_dip", ( isset($T["niveau_dernier_dip"]) ? $T["niveau_dernier_dip"] : "" )); ?></td>
</tr><tr class='sous'>
	<th><?php libelle("dernier_dip") ; ?></th>
	<td><?php inputtxt("dernier_dip", ( isset($T["dernier_dip"]) ? $T["dernier_dip"] : "" ), 40, 100); ?></td>
</tr><tr class='sous'>
	<th><?php libelle("info_dernier_dip") ; ?></th>
	<td><?php textarea("info_dernier_dip", ( isset($T["info_dernier_dip"]) ? $T["info_dernier_dip"] : "" ), 40, 2) ; ?></td>
</tr><tr>
<?php
// Aussi dans include/inc_dossier.php
if ( $ref_institution == "27" )
{
?>
	<th colspan='2'><?php libelle("inscri_ouaga") ; ?></th>
	<td><?php textarea("inscri_europe", ( isset($T["inscri_europe"]) ? $T["inscri_europe"] : "" ), 40, 3) ; ?></td>
</tr><tr>
	<th colspan='2'><?php libelle("code_ouaga") ; ?></th>
	<td><?php inputtxt("code_ine", ( isset($T["code_ine"]) ? $T["code_ine"] : "" ), 20, 20) ; ?></td>
<?php
}
else
{
?>
	<th colspan='2'><?php libelle("inscri_europe") ; ?></th>
	<td><?php textarea("inscri_europe", ( isset($T["inscri_europe"]) ? $T["inscri_europe"] : "" ), 40, 3) ; ?></td>
</tr><tr>
	<th colspan='2'><?php libelle("code_ine") ; ?></th>
	<td><?php inputtxt("code_ine", ( isset($T["code_ine"]) ? $T["code_ine"] : "" ), 20, 20) ; ?></td>
<?php
}
?>
</tr>
</table>
<table class='formulaire' width='100%'>
<tr>
	<td><p><strong><?php libelle("diplomes") ; ?></strong></p>
	<table align="right">
	<tr>
		<th>Année</th>
		<th>Titre du diplôme </th>
		<th>Mention obtenue </th>
		<th>&Eacute;tablissement</th>
		<th>Pays</th>
	</tr><tr>
		<td><? liste_der1($tab_annee_dip_sta, "annee_dip1", ( isset($T["annee_dip1"]) ? $T["annee_dip1"] : "" )); ?></td>
		<td><? inputtxt("titre_dip1", ( isset($T["titre_dip1"]) ? $T["titre_dip1"] : "" ), 45, 200); ?></td>
		<td><? inputtxt("mention_dip1", ( isset($T["mention_dip1"]) ? $T["mention_dip1"] : "" ),  10, 200); ?></td>
		<td><? inputtxt("etab_dip1", ( isset($T["etab_dip1"]) ? $T["etab_dip1"] : "" ),  45, 200); ?></td>
		<td><?php echo selectPays($cnx, "pays_dip1", ( isset($T["pays_dip1"]) ? $T["pays_dip1"] : "" )); ?></td>
	</tr><tr>
		<td><? liste_der1($tab_annee_dip_sta, "annee_dip2", ( isset($T["annee_dip2"]) ? $T["annee_dip2"] : "" )); ?></td>
		<td><? inputtxt("titre_dip2", ( isset($T["titre_dip2"]) ? $T["titre_dip2"] : "" ), 45, 200); ?></td>
		<td><? inputtxt("mention_dip2", ( isset($T["mention_dip2"]) ? $T["mention_dip2"] : "" ),  10, 200); ?></td>
		<td><? inputtxt("etab_dip2", ( isset($T["etab_dip2"]) ? $T["etab_dip2"] : "" ),  45, 200); ?></td>
		<td><?php echo selectPays($cnx, "pays_dip2", ( isset($T["pays_dip2"]) ? $T["pays_dip2"] : "" )); ?></td>
	</tr><tr>
		<td><? liste_der1($tab_annee_dip_sta, "annee_dip3", ( isset($T["annee_dip3"]) ? $T["annee_dip3"] : "" )); ?></td>
		<td><? inputtxt("titre_dip3", ( isset($T["titre_dip3"]) ? $T["titre_dip3"] : "" ), 45, 200); ?></td>
		<td><? inputtxt("mention_dip3", ( isset($T["mention_dip3"]) ? $T["mention_dip3"] : "" ),  10, 200); ?></td>
		<td><? inputtxt("etab_dip3", ( isset($T["etab_dip3"]) ? $T["etab_dip3"] : "" ),  45, 200); ?></td>
		<td><?php echo selectPays($cnx, "pays_dip3", ( isset($T["pays_dip3"]) ? $T["pays_dip3"] : "" )); ?></td>
	</tr><tr>
		<td><? liste_der1($tab_annee_dip_sta, "annee_dip4", ( isset($T["annee_dip4"]) ? $T["annee_dip4"] : "" )); ?></td>
		<td><? inputtxt("titre_dip4", ( isset($T["titre_dip4"]) ? $T["titre_dip4"] : "" ), 45, 200); ?></td>
		<td><? inputtxt("mention_dip4", ( isset($T["mention_dip4"]) ? $T["mention_dip4"] : "" ),  10, 200); ?></td>
		<td><? inputtxt("etab_dip4", ( isset($T["etab_dip4"]) ? $T["etab_dip4"] : "" ),  45, 200); ?></td>
		<td><?php echo selectPays($cnx, "pays_dip4", ( isset($T["pays_dip4"]) ? $T["pays_dip4"] : "" )); ?></td>
	</tr>
	</table></td>
</tr><tr>
	<td><p><strong><?php libelle("stages") ; ?></strong></p>
	<table align="right">
	<tr>
		<th>Année</th>
		<th>Titre du stage ou de la certification</th>
		<th>Organisateur</th>
	</tr><tr>
		<td><? liste_der1($tab_annee_dip_sta, "annee_stage1",
			( isset($T["annee_stage1"]) ? $T["annee_stage1"] : "" )) ; ?></td>
		<td><? inputtxt("titre_stage1", ( isset($T["titre_stage1"]) ? $T["titre_stage1"] : "" ), 50, 200) ; ?></td>
		<td><? inputtxt("org_stage1",   ( isset($T["org_stage1"]) ? $T["org_stage1"] : "" ),   50, 200) ; ?></td>
	</tr><tr>
		<td><? liste_der1($tab_annee_dip_sta, "annee_stage2",
			( isset($T["annee_stage2"]) ? $T["annee_stage2"] : "" )) ; ?></td>
		<td><? inputtxt("titre_stage2", ( isset($T["titre_stage2"]) ? $T["titre_stage2"] : "" ), 50, 200) ; ?></td>
		<td><? inputtxt("org_stage2",   ( isset($T["org_stage2"]) ? $T["org_stage2"] : "" ),   50, 200) ; ?></td>
	</tr><tr>
		<td><? liste_der1($tab_annee_dip_sta, "annee_stage3",
			( isset($T["annee_stage3"]) ? $T["annee_stage3"] : "" )) ; ?></td>
		<td><? inputtxt("titre_stage3", ( isset($T["titre_stage3"]) ? $T["titre_stage3"] : "" ), 50, 200) ; ?></td>
		<td><? inputtxt("org_stage3",   ( isset($T["org_stage3"]) ? $T["org_stage3"] : "" ),   50, 200) ; ?></td>
	</tr><tr>
		<td><? liste_der1($tab_annee_dip_sta, "annee_stage4",
			( isset($T["annee_stage4"]) ? $T["annee_stage4"] : "" )) ; ?></td>
		<td><? inputtxt("titre_stage4", ( isset($T["titre_stage4"]) ? $T["titre_stage4"] : "" ), 50, 200) ; ?></td>
		<td><? inputtxt("org_stage4",   ( isset($T["org_stage4"]) ? $T["org_stage4"] : "" ),   50, 200) ; ?></td>
	</tr>
	</table></td>
</tr>
</table>


<?php
section_candidature("5") ;
affiche_si_non_vide( isset($erreur_saisie5) ? $erreur_saisie5 : "" ) ;
?>
<tr>
	<th><?php libelle("exp_dist", FALSE) ; ?></th>
	<td><? liste_der1($oui_non, "exp_dist", ( isset($T["exp_dist"]) ? $T["exp_dist"] : "" )) ; ?>
		<div><?php libelle("format_dist") ; ?></div>
		<?php textarea("format_dist", ( isset($T["format_dist"]) ? $T["format_dist"] : "" ), 70, 3) ; ?>
	</td>
</tr><tr>
	<th><?php libelle("exp_internet") ; ?></th>
	<td><?php textarea("exp_internet", ( isset($T["exp_internet"]) ? $T["exp_internet"] : "" ), 70, 5) ; ?></td>
</tr><tr>
	<th><?php libelle("exp_bureau") ; ?></th>
	<td><?php textarea("exp_bureau", ( isset($T["exp_bureau"]) ? $T["exp_bureau"] : "" ), 70, 4) ; ?></td>
</tr>
</table>


<?php
section_candidature("6") ;
affiche_si_non_vide( isset($erreur_saisie6) ? $erreur_saisie6 : "" ) ;
?>
<tr>
	<td><div><strong><?php libelle("projet_perso") ; ?></strong></div>
	<?php textarea("projet_perso", ( isset($T["projet_perso"]) ? $T["projet_perso"] : "" ), 90, 7) ; ?></td>
</tr><tr>
	<td><div><strong><?php libelle("lettre_motiv") ; ?></strong></div>
	<?php textarea("lettre_motiv", ( isset($T["lettre_motiv"]) ? $T["lettre_motiv"] : "" ), 90, 15) ; ?></td>
</tr><tr>
	<td><div><strong><?php libelle("cv") ; ?></strong></div>
	<?php textarea("cv", ( isset($T["cv"]) ? $T["cv"] : "" ), 100, 20) ; ?></td>
</tr>
</table>   		


<?php
section_candidature("7") ;
affiche_si_non_vide( isset($erreur_saisie7) ? $erreur_saisie7 : "" ) ;
?>
<tr>
	<th><?php libelle("bourse_auf", FALSE) ; ?></th>
	<td><? $bourse_auf1 = $oui_non ;
		liste_der1($bourse_auf1, "bourse_auf", ( isset($T["bourse_auf"]) ? $T["bourse_auf"] : "" )) ; ?></td>
</tr><tr>
	<th><?php libelle("financement_form", FALSE) ; ?></th>
	<td><? liste_der1($financement, "financement_form",
		( isset($T["financement_form"]) ? $T["financement_form"] : "" )) ; ?>
		<div><?php libelle("autre_pec") ; ?></div>
		<?php textarea("autre_pec", ( isset($T["autre_pec"]) ? $T["autre_pec"] : "" ), 40, 2) ; ?></td>
</tr><tr>
	<th><?php libelle("prix_sud", FALSE) ; ?></th>
	<td><? liste_der1($oui_non, "prix_sud", ( isset($T["prix_sud"]) ? $T["prix_sud"] : "" )) ; ?>
		<div><?php libelle("financement_sud", FALSE) ; ?></div>
		<? liste_der1($financement, "financement_sud",
			( isset($T["financement_sud"]) ? $T["financement_sud"] : "" )); ?><br />
		<div><?php libelle("autre_sud") ; ?></div>
		<?php textarea("autre_sud", ( isset($T["autre_sud"]) ? $T["autre_sud"] : "" ), 40, 2) ; ?></td>
</tr>
</table>


<?php
section_candidature("8") ;
affiche_si_non_vide( isset($erreur_saisie8) ? $erreur_saisie8 : "" ) ;
?>
<tr>
	<th><?php libelle("nbre_heures", FALSE) ; ?></th>
	<td><?php liste_der1($tab_nbre_heures, "nbre_heures",
		( isset($T["nbre_heures"]) ? $T["nbre_heures"] : "" )) ; ?></td>

</tr><tr>
	<th><?php libelle("ordipro", FALSE) ; ?></th>
	<td><?php $ordipro1 = $oui_non ;
		liste_der1($ordipro1, "ordipro", ( isset($T["ordipro"]) ? $T["ordipro"] : "" )) ; ?></td>
</tr><tr>
	<th><?php libelle("netpro", FALSE) ; ?></th>
	<td><?php $netpro1 = $oui_non ;
		liste_der1($netpro1, "netpro", ( isset($T["netpro"]) ? $T["netpro"] : "" )) ; ?></td>
</tr><tr>
	<th><?php libelle("ordiperso", FALSE) ; ?></th>
	<td><?php $ordiperso1 = $oui_non ;
		liste_der1($ordiperso1, "ordiperso", ( isset($T["ordiperso"]) ? $T["ordiperso"] : "" )) ; ?>
		<div><?php libelle("fixeportable", FALSE) ; ?></div>
		<?php liste_der1($fixe_portable, "fixeportable", ( isset($T["fixeportable"]) ? $T["fixeportable"] : "" )) ;
		?></td>
</tr><tr>
	<th><?php libelle("netperso", FALSE) ; ?></th>
	<td><?php $netperso1 = $oui_non ;
		liste_der1($netperso1, "netperso", ( isset($T["netperso"]) ? $T["netperso"] : "" )) ; ?></td>

<?php /*
</tr><tr>
	<th><?php libelle("acces_pc", FALSE) ; ?></th>
	<td><?php $acces_pc1 = $oui_non ;
		liste_der1($acces_pc1, "acces_pc", ( isset($T["acces_pc"]) ? $T["acces_pc"] : "" )) ; ?>
		<div><?php libelle("appart_pc", FALSE) ; ?></div>
		<?php $appart_pc1 = $oui_non ;
		liste_der1($appart_pc1, "appart_pc", ( isset($T["appart_pc"]) ? $T["appart_pc"] : "" )) ; ?></td>
</tr><tr>
	<th><?php libelle("connexion_int", FALSE) ; ?></th>
	<td><?php $connexion_int1 = $oui_non ;
		liste_der1($connexion_int1, "connexion_int", ( isset($T["connexion_int"]) ? $T["connexion_int"] : "" )) ; ?>
		<div><?php libelle("autre_acces_internet", FALSE) ; ?></div>
		<?php textarea("autre_acces_internet",
			( isset($T["autre_acces_internet"]) ? $T["autre_acces_internet"] : "" ), 40, 2) ; ?>
	</td>
*/ ?>
</tr><tr>
	<th><?php libelle("service_cnf", FALSE) ; ?></th>
	<td><?php $service_cnf1 = $oui_non ;
		liste_der1($service_cnf1, "service_cnf", ( isset($T["service_cnf"]) ? $T["service_cnf"] : "" )) ; ?>
		<div><?php libelle("temps_dep", FALSE) ; ?></div>
		<? liste_der1($tab_temps_dep, "temps_dep", ( isset($T["temps_dep"]) ? $T["temps_dep"] : "" )) ; ?>
		<div><?php libelle("nbre_dep", FALSE) ; ?></div>
		<? liste_der1($tab_nbre_dep, "nbre_dep", ( isset($T["nbre_dep"]) ? $T["nbre_dep"] : "" )) ; ?></td>
</tr>
</table>

