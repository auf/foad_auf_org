<?php
include("inc_session.php") ;

include("inc_html.php") ;
$titre = "Comparaison Imputations Candidatures" ;
echo $dtd1 ;
echo "<title>$titre</title>\n" ;
echo $dtd2 ;
include("inc_menu.php") ;
echo "<div class='noprint'>" ;
echo $debut_chemin ;
echo "<a href='/bienvenue.php'>Accueil</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo "<a href='/imputations/'>Imputations</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo $titre ;
echo "</div>" ;
echo $fin_chemin ;


include("inc_date.php") ;
include("inc_etat_dossier.php") ;
include("inc_mysqli.php") ;
$cnx = connecter() ;

$req = "SELECT id_imputation, ref_dossier, dossier.id_candidat,
	civilite, nom, prenom, nom_jf, nationalite, naissance,
	etat, etat_dossier, dossier.id_session, annee
	FROM imputations, dossier, candidat, session
	WHERE imputations.ref_dossier=dossier.id_dossier
	AND dossier.id_candidat=candidat.id_candidat
	AND dossier.id_session=session.id_session
	AND etat_dossier != etat
	ORDER BY id_session, nom" ;
//echo $req ;
$res = mysqli_query($cnx, $req) ;

echo "<p class='c'><strong>".mysqli_num_rows($res)." différences</strong></p>" ;


echo "<table class='tableau'>\n" ;
echo "<thead>\n" ;
echo "<tr>\n" ;
echo "<th>Nom</th>" ;
echo "<th>Candidature</th>" ;
echo "<th>Imputation</th>" ;
echo "</tr>\n" ;
echo "</thead>\n" ;
echo "<tbody>\n" ;
while ( $enr = mysqli_fetch_assoc($res) )
{
	$to_update = array() ;

	echo "<tr>\n" ;
	echo "<td>" ;
	echo $enr["annee"] ." " ;
	echo "<a href='/imputations/promotion.php?promotion=".$enr["id_session"]."'>" ;
	echo $enr["id_session"] ."</a> " ;
	echo $enr["civilite"] ." " ;
	echo "<strong>" . strtoupper($enr["nom"]) ."</strong> " ;
	echo ucwords(strtolower($enr["prenom"])) ;
	echo "</td>\n" ;

	echo "<td class='".$etat_dossier_img_class[$enr["etat_dossier"]]."'>" ;
	$id = $enr["ref_dossier"] ;
	echo "<a title='".$enr["etat_dossier"]."' " ;
	echo "href='/candidatures/candidature.php?id_dossier=$id'>" ;
	echo "<strong>Candidature</strong></a>" ;
	echo "</td>\n" ;

	echo "<td class='".$etat_dossier_img_class[$enr["etat"]]."'>" ;
	$id = $enr["id_imputation"] ;
	echo "<a title='".$enr["etat"]."' " ;
	echo "href='/imputations/attestation.php?id=$id'>" ;
	echo "<strong>Imputation</strong></a>" ;
	echo "</td>\n" ;

	echo "</tr>\n" ;
}
echo "</tbody>\n" ;
echo "</table>\n" ;

deconnecter($cnx) ;
echo $end ;
?>
