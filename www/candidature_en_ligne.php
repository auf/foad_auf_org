<?php
include("inc_session.php") ;
include("inc_html.php") ;
include("inc_mysqli.php") ;

$titre = "Candidature en ligne" ;
echo $dtd1 ;
echo "<title>$titre</title>\n" ;
echo $dtd2 ;
include("inc_menu.php") ;
echo $debut_chemin ;
echo "<a href='/bienvenue.php'>Accueil</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo $titre ;
echo $fin_chemin ;


echo "<p class='c'>Page de <strong> <a target='_blank' href='/candidature/'
>mise à jour d'un dossier de candidature</a></strong>.</p>\n" ;

$cnx = connecter() ;



require_once("inc_groupe.php");
echo "<form action='criteres_en_ligne.php' method='post'>\n" ;
echo "<table class='formulaire'>\n" ;
echo "<tr>\n" ;
echo "<th>Limiter à un domaine : </th>\n" ;
echo "<td>" ;
echo select_groupe(
	( isset($_SESSION["e_groupe"]) ? $_SESSION["e_groupe"] : "" )
	) ;
echo "</td>\n" ;
echo "<td>" ;
echo "<input class='b' type='submit' value='Actualiser' /></td>\n" ;
echo "</tr>\n" ;
echo "</table>\n" ;
echo "</form>" ;


$req = "SELECT COUNT(*) FROM session WHERE candidatures='Oui'" ;
$res = mysqli_query($cnx, $req) ;
$row = mysqli_fetch_row($res) ;
$nombre = $row[0] ;


$req = "SELECT groupe, intitule, session.*
	FROM session, atelier 
	WHERE atelier.id_atelier=session.id_atelier
	AND session.candidatures='Oui' " ;
if ( isset($_SESSION["e_groupe"]) AND ($_SESSION["e_groupe"] != "") ) {
	$req .= " AND groupe='".addslashes($_SESSION["e_groupe"])."' " ;
}
$req .= " ORDER BY annee DESC, groupe, niveau, intitule" ;
$res = mysqli_query($cnx, $req) ;
$N = mysqli_num_rows($res) ;

echo "<p class='c'>" ;
echo "<strong>$nombre promotions</strong>
pour lesquelles les candidatures sont ouvertes.<br />
<span style='font-size: smaller'>(Chaque lien mène au formulaire de candidature corrrespondant.)</span> " ;
if ( isset($_SESSION["e_groupe"]) AND ($_SESSION["e_groupe"] != "") ) {
	echo "<br /><strong>$N promotions</strong> en " . $_SESSION["e_groupe"] ;
}
echo "</p>" ;

echo "<table class='tableau'>
<tbody>" ;

$i = 1 ;
$groupe = "" ;
while ( $ligne = mysqli_fetch_assoc($res) ) 
{
    if ( $groupe != $ligne["groupe"] ) {
        $groupe = $ligne["groupe"] ;
        echo "<tr><td style='background: #ccc' colspan='2' class='r'>" ;
        echo "<b style='font-size: 100%;'>$groupe</b></td></tr>" ;
    }
	echo "<tr>" ;
	echo "<td><b>".$ligne["intitule"]."</b> " ;
	echo "(".$ligne["intit_ses"].")" ;
        echo "<div style='font-size: 70%; padding: 2px 1em;'>" ;
        echo "<a href='/candidature/candidature.php" ;
        echo "?id_session=".$ligne["id_session"]."' target='_blank'>" ;
        echo "&lt;a href='https://" . URL_DOMAINE . "/candidature/" ;
        echo "candidature.php?id_session=".$ligne["id_session"] ;
        echo "' target='_blank'&gt;Candidater en ligne&lt;/a&gt;" ;
        echo "</div>" ;
	echo "</td>" ;
	echo "</tr>" ;
}
$i++ ;
echo "</tbody></table>";

deconnecter($cnx) ;
echo $end;
?>
