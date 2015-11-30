<?php
include("inc_session.php") ;

include("inc_html.php") ;
$titre = "Imputations" ;
echo $dtd1 ;
echo "<title>$titre</title>\n" ;
echo $dtd2 ;
include("inc_menu.php") ;
echo "<div class='noprint'>" ;
echo $debut_chemin ;
echo "<a href='/bienvenue.php'>Accueil</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo "<a href='/imputations/index.php'>" ;
echo $titre ;
echo "</a>" ;
echo "</div>" ;
echo $fin_chemin ;

include("inc_mysqli.php") ;
$cnx = connecter() ;

$req = "SELECT intitule, universite, nb_annees,
	intit_ses, annee, tarifa, tarifp, imputations, tarif2a, tarif2p, imputations2,
	civilite, nom, prenom, nom_jf, naissance, nationalite, imputations.*
	FROM atelier, session, dossier, candidat, imputations
	WHERE id_imputation=".$_GET["id"]."
	AND ref_dossier=id_dossier
	AND dossier.id_candidat=candidat.id_candidat
	AND dossier.id_session=session.id_session
	AND session.id_atelier=atelier.id_atelier" ;
$res = mysqli_query($cnx, $req) ;
$enr = mysqli_fetch_assoc($res) ;


if
	(
		( intval($_SESSION["id"]) < 3 )
		AND (
			( ($enr["imputations"] == 'Oui') AND ($enr["annee_relative"] == '1') )
			OR
			( ($enr["imputations2"] == 'Oui') AND ($enr["annee_relative"] == '2') )
		)
	)
{
	echo "<div class='noprint' style='margin: 1em 0;'>" ;
	echo "<p class='c navig'>\n" ;

	if ( isset($_GET["action"]) AND ($_GET["action"] == "supprimer") ) {
		echo "Confirmer la suppression de cette attestation&nbsp; ?" ;
		echo "<br />\n" ;
		echo "<br />\n" ;
		echo "<a href='supprimer.php?id=".$enr["id_imputation"]."'>Supprimer</a>\n" ;
		echo "<a href='attestation.php?id=" ;
		echo $enr["id_imputation"]."'>Annuler</a></strong>" ;
	}
	else {
		echo "<a href='attestation.php?id=".$enr["id_imputation"]."&amp;action=supprimer'>Supprimer</a>\n" ;
		echo "<a href='modifier.php?id_imputation=".$enr["id_imputation"]."'>Modifier</a>\n" ;
		echo "<a href='javascript.php' onclick='window.print(); return false;'>Imprimer</a>" ;
	}

	echo "</p>" ;

	// Payant -> Allocataire pour foad-scp
	if	(
			( $_SESSION["utilisateur"] == "Service des bourses (SCP)" )
			AND ( $enr["etat"] == "Payant" )
		)
	{
		echo "<p class='c'>foad-scp uniquement : " ;
		if ( $enr["annee_relative"] == '1' ) {
			echo "Changer l'état du dossier et de l'attestation en allocataire : " ;
		}
		else {
			echo "Changer l'état de l'attestation en allocataire : " ;
		}
		echo "<strong><a href='allocataire.php?id_imputation=".$enr["id_imputation"]."&amp;ref_dossier=".$enr["ref_dossier"]."'>Allocataire</a></strong></p>\n" ;
	}

	echo "<br />\n" ;
	echo "</div>" ;
}


if ( 
	($enr["particulier"] == 'Oui')
	OR ($enr["commentaire"]!='')
	OR ($enr["mod_imputation"]=='Oui')
)
{
	echo "<div class='noprint' style='float: right; width: 30%; height: 300px;'>\n" ;
	echo "<div style='border: 1px solid #777; padding: 0 15px;'>" ;
	
	echo "<p style='border-bottom: 1px solid #777;'><strong>Notes du service des bourses (SCP)</strong></p>\n" ;


	if ($enr["particulier"] == 'Oui') {
		echo "<p class='Non'>Cas particulier</p>\n" ;
	}
	if ($enr["mod_imputation"] == 'Oui') {
		echo "<p class='particulier'>Modification du code d'imputation</p>\n" ;
	}

	if ($enr["commentaire"]!='') {
		echo "<p><strong>Commentaire :</strong><br />" ;
		echo nl2br($enr["commentaire"]) ;
		echo "</p>" ;
	}

	echo "</div>\n\n" ;
	echo "</div>\n\n" ;
}








include("inc_date.php") ;

echo "<div class='c'><img src='/img/AUF.png' width='130' height='93' alt='Agence universitaire de la Francophonie' /></div>\n" ;
echo "<h2 style='font-weight: normal;' class='c'>" ;
echo "Agence Universitaire de la Francophonie<br />\n" ;
echo "Formations ouvertes et à distance<br />\n" ;
echo "Année universitaire ". $enr["annee_absolue"] . "-" . ($enr["annee_absolue"]+1) ;
echo "</h2>\n" ;

echo "<table class='imput'>\n" ;

echo "<tbody>\n" ;
echo "<tr>\n" ;
	echo "\t<th>Formation&nbsp;:</th>\n" ;
	echo "\t<td>".$enr["intitule"] ;
	if ( $enr["nb_annees"] != "1" ) {
		echo "<br />\n" ;
		if ( $enr["annee_relative"] == "1" ) {
			echo "1<sup>ère</sup> année" ;
		}
		if ( $enr["annee_relative"] == "2" ) {
			echo "2<sup>ème</sup> année" ;
		}
	}
	echo "</td>\n" ;
echo "</tr>\n" ;
echo "<tr>\n" ;
	echo "\t<th>Université&nbsp;:</th>\n" ;
	echo "\t<td>".$enr["universite"]."</td>\n" ;
echo "</tr>\n" ;
echo "<tr>\n" ;
	echo "\t<th>Promotion&nbsp;:</th>\n" ;
	echo "\t<td>".$enr["annee"]." (".$enr["intit_ses"].")</td>\n" ;
echo "</tr>\n" ;
echo "<tr>\n" ;
	echo "\t<th>Tarif « Payant »&nbsp;:</th>\n" ;
	echo "\t<td>" ;
	if ( ( $enr["nb_annees"] != "1" ) AND ( $enr["annee_relative"] == "2" ) ) {
		echo $enr["tarif2p"] ;
	}
	else {
		echo $enr["tarifp"] ;
	}
	echo " EUR</td>\n" ;
echo "</tr>\n" ;
echo "<tr>\n" ;
	echo "\t<th>Tarif « Allocataire »&nbsp;:</th>\n" ;
	echo "\t<td>" ;
	if ( ( $enr["nb_annees"] != "1" ) AND ( $enr["annee_relative"] == "2" ) ) {
		echo $enr["tarif2a"] ;
	}
	else {
		echo $enr["tarifa"] ;
	}
	echo " EUR
	<br /><br /></td>\n" ;
echo "<tr>\n" ;
echo "</tbody>\n" ;

echo "<tbody>\n" ;
echo "<tr>\n" ;
	echo "\t<th>Civilité&nbsp;:</th>\n" ;
	echo "\t<td>".$enr["civilite"]."</td>\n" ;
echo "</tr>\n" ;
echo "<tr>\n" ;
	echo "\t<th>Nom de famille&nbsp;:</th>\n" ;
	echo "\t<td>".strtoupper($enr["nom"])."</td>\n" ;
echo "</tr>\n" ;
if ( $enr["nom_jf"] != "" ) {
	echo "<tr>\n" ;
		echo "\t<th>Nom de jeune fille&nbsp;:</th>\n" ;
		echo "\t<td>".strtoupper($enr["nom_jf"])."</td>\n" ;
	echo "</tr>\n" ;
}
echo "<tr>\n" ;
	echo "\t<th>Prénoms&nbsp;:</th>\n" ;
	echo "\t<td>".ucwords(strtolower($enr["prenom"]))."</td>\n" ;
echo "</tr>\n" ;
echo "<tr>\n" ;
	echo "\t<th>Date de naissance&nbsp;:</th>\n" ;
	echo "\t<td>".mysql2datealpha($enr["naissance"])."</td>\n" ;
echo "</tr>\n" ;
echo "<tr>\n" ;
	echo "\t<th>Nationalité&nbsp;:</th>\n" ;
	echo "\t<td>".$enr["nationalite"]."
	<br /><br /></td>\n" ;
echo "</tr>\n" ;
echo "</tbody>\n" ;


echo "<tbody>\n" ;
echo "<tr>\n" ;
	echo "\t<th>Imputation comptable&nbsp;:</th>\n" ;
	echo "\t<td>".$enr["imputation"]."</td>\n" ;
echo "</tr>\n" ;
echo "<tr>\n" ;
	echo "\t<th>&Eacute;tat&nbsp;:</th>\n" ;
	echo "\t<td>« ".$enr["etat"]." »
	<br /><br /></td>\n" ;
echo "</tr>\n" ;
echo "</tbody>\n" ;

echo "<tbody>\n" ;
echo "<tr>\n" ;
	echo "\t<th>Date de création&nbsp;:</th>\n" ;
	echo "\t<td>".mysql2datealpha($enr["date_creation"])."</td>\n" ;
echo "</tr>\n" ;
if ( $enr["date_creation"] != $enr["date_mod"] )
{
	echo "<tr>\n" ;
		echo "\t<th>Date de modification&nbsp;:</th>\n" ;
		echo "\t<td>".mysql2datealpha($enr["date_mod"])."</td>\n" ;
	echo "</tr>\n" ;
}
echo "<tr>\n" ;
	echo "\t<th>Lieu d'enregistrement&nbsp;:</th>\n" ;
	echo "\t<td>".$enr["lieu"]."</td>\n" ;
echo "</tr>\n" ;
echo "<tr>\n" ;
	echo "\t<th>Frais&nbsp;de&nbsp;dossier&nbsp;acquittés&nbsp;:</th>\n" ;
	echo "\t<td>".floatval($enr["montant_frais"])." ".$enr["monnaie_frais"]."</td>\n" ;
echo "</tr>\n" ;
echo "</tr>\n" ;
	echo "\t<th>Montant acquitté&nbsp;:<br />
	<span style='font-size: smaller'>(hors frais de dossier) &nbsp;</span></th>\n" ;
	echo "\t<td>".floatval($enr["montant"]). " ".$enr["monnaie"]."</td>\n" ;
echo "</tr>\n" ;
echo "</tbody>\n" ;
echo "</table>\n" ;


deconnecter($cnx) ;
echo $end ;
?>
