<?php
include("inc_session.php") ;
include("inc_mysqli.php") ;
$cnx = connecter() ;

include("inc_html.php") ;
$titre = "Messagerie <span>(CNF)</span>" ;
echo $dtd1 ;
echo "<title>".strip_tags($titre)."</title>\n" ;
echo $dtd2 ;
include("inc_menu.php") ;
echo $debut_chemin ;
echo "<a href='/bienvenue.php'>Accueil</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo $titre ;
echo $fin_chemin ;

if ( intval($_SESSION["id"]) < 2 ) {
	echo "<div style='float: right; text-align: right; width: 20em;'>" ;
	echo "<strong><a href='/individus/index.php'>Individus <span style='font-weight: normal'>(CNF)</span> destinataires potentiels</a></strong></div>\n" ;

	echo "<p class='c' style='margin-left: 20em;'>" ;
	echo "<strong><a href='/messagerie_cnf/fil_nouveau.php'>Nouveau fil de messages</a></strong></p>\n" ;
}

require_once("inc_date.php") ;

if ( !isset($_SESSION["filtres"]["fils"]["annee"]) ) {
	$req = "SELECT MAX(annee) AS N FROM fils" ;
	$res = mysqli_query($cnx, $req) ;
	$enr = mysqli_fetch_assoc($res) ;
	$_SESSION["filtres"]["fils"]["annee"] = $enr["N"] ;
}

echo "<form method='post' action='criteres.php'>\n" ;
echo "<table class='formulaire'>\n<tbody>\n" ;
echo "<tr>\n" ;
echo "<th>Année&nbsp;: </th>\n" ;
echo "<td><select name='fils_annee'>\n" ;
$req = "SELECT DISTINCT(annee) FROM fils ORDER BY annee DESC" ;
$res = mysqli_query($cnx, $req) ;
while ( $enr = mysqli_fetch_assoc($res) ) {
	echo "<option value='".$enr["annee"]."'" ;
	if ( $_SESSION["filtres"]["fils"]["annee"] == $enr["annee"] ) {
		echo " selected='selected'" ;
	}
	echo ">".$enr["annee"]."</option>" ;
}
echo "</select></td>\n" ;
echo "</tr>\n" ;

echo "<tr>\n" ;
echo "<th>Tri par&nbsp;: </th>\n" ;
echo "<td>" ;
echo "<select name='fils_tri'>\n" ;
$fils_tri = array(
	"titre" => "Titre",
	"id_max DESC" => "Date du dernier message envoyé",
) ;
while ( list($key, $val) = each($fils_tri) ) {

	echo "<option value='".$key."'" ;
	if ( isset($_SESSION["filtres"]["fils"]["tri"]) AND ($_SESSION["filtres"]["fils"]["tri"] == $key) ) {
		echo " selected='selected'" ;
	}
	echo ">".$val."</option>" ;
}
echo "</select>" ;
echo "</td>\n" ;
echo "</tr>\n" ;

echo "<tr>\n" ;
echo "<td colspan='2'><div class='c'><input class='b' type='submit' value='Actualiser' /></div></td>\n" ;
echo "</tr>\n" ;
echo "</tbody>\n</table>\n" ;
echo "</form>" ;

if ( !isset($_SESSION["filtres"]["fils"]["tri"]) ) {
	$_SESSION["filtres"]["fils"]["tri"] = "titre" ;
}

$req = "SELECT fils.*,
	(SELECT COUNT(ref_session) FROM fils_sessions WHERE ref_fil=id_fil) AS nb_sessions,
	(SELECT COUNT(ref_individu) FROM fils_individus WHERE ref_fil=id_fil) AS nb_individus,
	(SELECT COUNT(ref_fil) FROM messages WHERE ref_fil=id_fil) AS nb_messages,
	(SELECT MAX(`date`) FROM messages WHERE ref_fil=id_fil) AS date_max,
	(SELECT MAX(id_message) FROM messages WHERE ref_fil=id_fil) AS id_max
	FROM fils
	WHERE fils.annee='".$_SESSION["filtres"]["fils"]["annee"]."'
	ORDER BY ".$_SESSION["filtres"]["fils"]["tri"] ;
$res = mysqli_query($cnx, $req) ;
$N = mysqli_num_rows($res) ;
if ( $N != 0 )
{
	if ( $N > 1 ) { $s = "s" ; } else { $s = "" ; }
	echo "<p class='c'><strong>".$N." fil".$s." de messages</strong></p>\n" ;

	echo "<table class='tableau'>\n" ;
	echo "<thead>\n" ;
	echo "<tr>\n" ;
	echo "<th colspan='2'>Paramétrage actuel</th>\n" ;
	echo "<th rowspan='2'>Titre<br /><span class='normal'>Commentaire</span></th>\n" ;
	echo "<th colspan='2'>Messages<br />envoyés</th>\n" ;
	echo "</tr>\n" ;
	echo "<tr>\n" ;
	echo "<th>Promotions</th>\n" ;
	echo "<th>Destinataires</th>\n" ;
	echo "<th class='help' title='nombre de messages envoyés'>N</th>\n" ;
	echo "<th class='help' title='Date du dernier message envoyé'>Dernier</th>\n" ;
	echo "</tr>\n" ;
	echo "</thead>\n" ;
	echo "<tbody>\n" ;
	while ( $enr = mysqli_fetch_assoc($res) )
	{
		echo "<tr>\n" ;
		echo "<td class='r'>" .$enr["nb_sessions"]. "</td>\n" ;
		echo "<td class='r'>" .$enr["nb_individus"]. "</td>\n" ;
		echo "<td>" ;
		echo "<strong><a class='bl' href='fil.php?id_fil=".$enr["id_fil"]."'>" ;
		echo $enr["titre"]."</a></strong>" ;
		if ( trim($enr["commentaire"]) != "" ) {
			echo "" . $enr["commentaire"] ;
		}
		echo "</td>\n" ;
		echo "<td class='r'>" .$enr["nb_messages"]. "</td>\n" ;
		echo "<td class='c'>" .mysql2datenum($enr["date_max"]). "</td>\n" ;
		echo "</tr>\n" ;
	}
	echo "</tbody>\n" ;
	echo "</table>\n" ;
}


deconnecter($cnx) ;
echo $end ;
?>
