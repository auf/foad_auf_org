<?php
include("inc_session.php") ;
include("inc_html.php") ;
$titre = "Choix des champs à exporter" ;
echo $dtd1 ;
echo "<title>$titre</title>\n" ;
?>
<script language="JavaScript">
function selec(debut, fin) {
	for ( i=debut;i<=fin;i++) {
		if (document.forms[0].elements[i].type=="checkbox") {
			document.forms[0].elements[i].checked=true;
		}
	}
}
function unselec(debut, fin) {
	for ( i=debut;i<=fin;i++) {
		if (document.forms[0].elements[i].type=="checkbox") {
			document.forms[0].elements[i].checked=false;
		}
	}
}
</script>
<?php
echo $dtd2 ;
include("inc_menu.php") ;
echo $debut_chemin ;
echo "<a href='/bienvenue.php'>Accueil</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo "<a href='/exports/index.php'>Exports (tableur)</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo $titre ;
echo $fin_chemin ;


include("inc_formulaire_candidature.php") ;

while (list($key, $val) = each($_POST)) {
   echo "$key => $val<br />" ;
}

function unchoix($champ, $libelle)
{
	if ( isset($_SESSION["filtres"]["exporter"][$champ]) AND ($_SESSION["filtres"]["exporter"][$champ] == $champ) ) {
		$checked = " checked='checked'" ;
		$class = " class='aex'" ;
	}
	else {
		$checked = "" ;
		$class = "" ;
	}
	echo "<tr" . $class . ">" ;
	echo "<th><input type='checkbox' " ;
	echo "id='$champ' name='$champ' value='$champ'" ;
	echo $checked . " />" ;
	echo "</th><td width='100%'>" ;
	echo " <label class='bl' for='$champ'>" ;
	echo $libelle ;
	echo "</label>\n" ;
	echo "</td></tr>\n" ;
}
function choix($champ, $flag=FALSE)
{
	global $CANDIDATURE;
	if ( isset($_SESSION["filtres"]["exporter"][$champ]) AND ($_SESSION["filtres"]["exporter"][$champ] == $champ) ) {
		$checked = " checked='checked'" ;
		$class = " class='aex'" ;
	}
	else {
		$checked = "" ;
		$class = "" ;
	}
	echo "<tr" . $class . ">" ;
	echo "<th><input type='checkbox' " ;
	echo "id='$champ' name='$champ' value='$champ'" ;
	echo $checked . " />" ;
	echo "</th><td width='100%'>" ;
	echo " <label class='bl' for='$champ'>" ;
	if ( ($CANDIDATURE[$champ][1] != "")  AND ( $flag!=TRUE ) ) {
		echo $CANDIDATURE[$champ][1] ;
	}
	else {
		echo $CANDIDATURE[$champ][0] ;
	}
	echo "</label>\n" ;
	echo "</td></tr>\n" ;
}

echo "<p>Sélectionnez les champs que vous souhaitez exporter en cochant la case
correspondante (il suffit de cliquer sur la ligne), ou en utilisant les liens
 &nbsp;cocher&nbsp;» et «&nbsp;décocher&nbsp;»&nbsp;;<br />
puis cliquez sur le bouton «&nbsp;Enregistrer&nbsp;» en bas de la page, ou un des liens «&nbsp;Enregistrer&nbsp;» à droite.<br />" ;
if ( intval($_SESSION["id"]) <= 3 ) {
	echo "Certaines cases, avec un libellé en caractères gras, correspondent à plusieurs champs à exporter (mais ne sont exportables que promotion par promotion&nbsp;: ils ne seront pas pris en compte dans un export par année)." ;
}
else {
	echo "Certaines cases, avec un libellé en caractères gras, correspondent à plusieurs champs à exporter." ;
}
if ( isset($_SESSION["filtres"]["exporter"]) AND (count($_SESSION["filtres"]["exporter"]) > 0) ) {
	echo "<br /><span class='aex'>Les champs effectivement enregistrés comme à exporter sont sur fond vert.</span>" ;
}
echo "</p>\n" ;

if ( isset($_SESSION["modif_champs_export"]) AND ($_SESSION["modif_champs_export"] == "ok") ) {
	echo "<p class='msgok'>Les champs que vous voulez exporter ont été enregistrés.<br />
Vous pouvez les modifier, ou revenir à la page précédente en cliquant sur <a href='index.php'>Exports (tableur)</a> ici, ou dans le fil d'ariane, ou dans la navigation permanente.</p>" ;
	unset($_SESSION["modif_champs_export"]) ;
}

function titre_selec($titre, $debut, $fin)
{
	echo "<div><h3>$titre " ;
	echo "<span style='font-size:0.8em; font-weight:normal; padding-left:2em'>" ;
	echo "<span class='fright'><a href='#' onclick='document.getElementById(\"formChamps\").submit();'>Enregistrer</a></span>" ;
	echo "<a href='javascript:selec($debut, $fin)'>Cocher</a> - " ;
	echo "<a href='javascript:unselec($debut, $fin)'>Décocher</a>" ;
	echo "</span></h3>" ;
}


echo "<form id='formChamps' method='post' action='choix_champs.php'>\n" ;

echo "<p><strong><a href=\"javascript:selec(0, 73)\">Tout cocher</a> -
<a href=\"javascript:unselec(0, 73)\">Tout décocher</a></strong></p>\n" ;

titre_selec($SECTION_CANDIDATURE["1"], 0, 9) ;
echo "<table class='formulaire hover' width='100%'>\n" ;
	choix("civilite") ;
	choix("nom") ;
	choix("nom_jf") ;
	choix("prenom") ;
	choix("naissance") ;
	choix("pays_naissance") ;
	choix("genre") ;
	choix("nationalite") ;
	choix("situation_actu") ;
	choix("sit_autre") ;
echo "</table>\n" ;

titre_selec($SECTION_CANDIDATURE["2"], 10, 17) ;
echo "<table class='formulaire hover' width='100%'>\n" ;
	choix("adresse") ;
	choix("code_postal") ;
	choix("ville") ;
	choix("pays") ;
	choix("tel") ;
	choix("tlc_perso") ;
	choix("email1") ;
	choix("email2") ;
echo "</table>\n" ;

titre_selec($SECTION_CANDIDATURE["3"], 18, 30) ;
echo "<table class='formulaire hover' width='100%'>\n" ;
	choix("emploi_actu") ;
	choix("employeur") ;
	choix("service") ;
	choix("titre") ;
	choix("adresse_emp") ;
	choix("codepost_emp") ;
	choix("ville_emp") ;
	choix("pays_emp") ;
	choix("tel_emp") ;
	choix("fax_emp") ;
	choix("email_pro1") ;
	choix("email_pro2") ;
	choix("duree_exp") ;
echo "</table>\n" ;

titre_selec($SECTION_CANDIDATURE["4"], 31, 37) ;
echo "<table class='formulaire hover' width='100%'>\n" ;
	choix("niveau_dernier_dip") ;
	choix("dernier_dip") ;
	choix("info_dernier_dip", TRUE) ;
	choix("inscri_europe") ;
	choix("code_ine") ;
	unchoix("diplomes", "<b>".$CANDIDATURE["diplomes"][1]."</b>"
		." (20 champs : 4 fois «&nbsp;Année&nbsp;», «&nbsp;Titre du diplôme&nbsp;», «&nbsp;Mention&nbsp;», «&nbsp;&Eacute;tablissement&nbsp;» et «&nbsp;Pays&nbsp;»)") ;
	unchoix("stages", "<b>".$CANDIDATURE["stages"][0]."</b>"
		." (12 champs : 4 fois «&nbsp;Année&nbsp;», «&nbsp;Titre du stage ou de la certification&nbsp;» et «&nbsp;Organisateur&nbsp;»)") ;
echo "</table>\n" ;

titre_selec($SECTION_CANDIDATURE["5"], 38, 41) ;
echo "<table class='formulaire hover' width='100%'>\n" ;
	choix("exp_dist") ;
	choix("format_dist") ;
	choix("exp_internet") ;
	choix("exp_bureau") ;
echo "</table>\n" ;

titre_selec($SECTION_CANDIDATURE["6"], 42, 44) ;
echo "<table class='formulaire hover' width='100%'>\n" ;
	choix("projet_perso") ;
	choix("lettre_motiv") ;
	choix("cv") ;
echo "</table>\n" ;

titre_selec($SECTION_CANDIDATURE["7"], 45, 50) ;
echo "<table class='formulaire hover' width='100%'>\n" ;
	choix("bourse_auf") ;
	choix("financement_form") ;
	choix("autre_pec") ;
	choix("prix_sud") ;
	choix("financement_sud") ;
	choix("autre_sud") ;
echo "</table>\n" ;

titre_selec($SECTION_CANDIDATURE["8"], 51, 63) ;
echo "<table class='formulaire hover' width='100%'>\n" ;
	choix("nbre_heures") ;
	// Jusqu'en 2007
	choix("acces_pc") ;
	choix("appart_pc") ;
	choix("connexion_int") ;
	choix("autre_acces_internet") ;
	// Depuis 2008
	choix("ordipro") ;
	choix("netpro") ;
	choix("ordiperso") ;
	choix("fixeportable") ;
	choix("netperso") ;
	choix("service_cnf") ;
	choix("temps_dep") ;
	choix("nbre_dep") ;
echo "</table>\n" ;

titre_selec($SECTION_CANDIDATURE["9"], 64, 64) ;
echo "<table class='formulaire hover' width='100%'>\n" ;
	unchoix("questions",
		"<em>Questions</em> (de 0 à n champs, 1 par question spécifique à la formation, s'il y en a)") ;
echo "</table>\n" ;

titre_selec("Autres informations relatives à la candidature", 65, 73) ;
echo "<table class='formulaire hover' width='100%'>\n" ;
	unchoix("date_inscrip", "Date de dépôt de la candidature") ;
	unchoix("date_maj", "Date de mise à jour de la candidature") ;
if (
	( intval($_SESSION["id"]) < 4 ) OR
	( ( intval($_SESSION["id"]) >= 4 ) AND ( $_SESSION["transfert"] == "Oui" ) )
	)
{
	unchoix("transferts", "Transfert du dossier") ;
}
	unchoix("etat_dossier", "&Eacute;tat du dossier") ;
	unchoix("date_maj_etat", "Date de mise à jour de l'état du dossier") ;
	unchoix("classement", "Classement des candidatures en attente") ;
	unchoix("commentaires", "<em>Évaluations</em> du (ou des) sélectionneur(s) et de l'AUF") ;
//	unchoix("diplome", "Diplôme (Oui/non)") ;
	unchoix("resultat", "Résultat") ;
	unchoix("date_maj_resultat", "Date de mise à jour du résultat") ;
echo "</table>\n" ;

echo "<p class='c'><input type='submit' style='font-weight: bold'
	value='Enregistrer' /></p>\n" ;

echo "</form>\n" ;


echo $end ;


?>
