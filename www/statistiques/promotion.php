<?php
include("inc_session.php") ;

include("inc_mysqli.php") ;
$cnx = connecter();

$req = "SELECT intitule, intit_ses, annee FROM atelier, session
	WHERE atelier.id_atelier=session.id_atelier
	AND id_session=" . $_GET["session"] ;
$res = mysqli_query($cnx, $req) ;
$enr = mysqli_fetch_assoc($res) ;

$titre = "Statistiques" ;
include("inc_html.php");
echo $dtd1 ;
echo "<title>Statistiques "
	. $enr['annee']
	. " "
	. $enr['intitule']
	." ("
	. $enr['intit_ses']
	. ")</title>" ;
echo $dtd2 ;
include("inc_menu.php") ;
echo $debut_chemin ;
echo "<a href='/bienvenue.php'>Accueil</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo "<a href='/statistiques/'>Statistiques</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo $enr['intitule'] ." (" . $enr['intit_ses'] . ")" ;
echo $fin_chemin ;

echo "<br class='noprint' />" ;

$requete = "SELECT etat_dossier, COUNT(etat_dossier) AS N
	FROM dossier, candidat
	WHERE dossier.id_session=".$_GET["session"]."
	AND dossier.id_candidat=candidat.id_candidat
	GROUP BY etat_dossier
	ORDER BY N DESC" ;
$resultat = mysqli_query($cnx, $requete) ;
$sousTotal = 0 ;
$etatDossier = array() ;
while ( $ligne = mysqli_fetch_assoc($resultat) ) {
	$etatDossier[$ligne["etat_dossier"]] = $ligne["N"] ;
}
include("inc_etat_dossier.php") ;
echo "<table class='stats'>" ;
echo "<caption>Etat du dossier</caption>\n" ;
echo "<tr>\n" ;
while ( list($key, $val) = each($etatDossier) ) {
	echo "\t<th>$key</th>\n" ;
}
echo "\t<th>Total</th>" ;
echo "</tr>\n" ;
echo "<tr>\n" ;
reset($etatDossier) ;
while ( list($key, $val) = each($etatDossier) ) {
	echo "\t<td class='".$etat_dossier_img_class[$key]."'>$val</td>\n" ;
	$sousTotal += $val ;
}
echo "\t<td><b>$sousTotal</b></td>\n" ;
echo "</tr>\n" ;
echo "</table>\n" ;

echo "<br/>\n" ;

include("inc_pays.php") ;
include("inc_statistiques.php") ;

afficheDetails($cnx, $_GET["session"]) ;

echo $end ;
deconnecter($cnx) ;
?>		
