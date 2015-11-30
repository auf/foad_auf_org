<?php
include("inc_session.php") ;
if ( intval($_SESSION["id"]) != 0 ) {
	header("Location: /bienvenue.php") ;
	exit() ;
}

$titre = "Promotions" ;
include("inc_html.php");
echo $dtd1 ;
echo "<title>$titre</title>" ;
echo $dtd2 ;
include("inc_menu.php");
echo $debut_chemin ;
echo "<a href='/bienvenue.php'>Accueil</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo $titre ;
echo $fin_chemin ;

?>
<p class='c'><strong><a
	href="promotion.php?operation=add">Nouvelle promotion</a></strong></p>

<?php

include_once("inc_date.php");
include_once("inc_guillemets.php");
require_once("inc_groupe.php");

include_once("inc_mysqli.php");
$cnx = connecter() ;

if ( !isset($_SESSION["filtres"]["promotions"]["annee"]) ) {
	$_SESSION["filtres"]["promotions"]["annee"] = $_SESSION["derniere_annee"] ;
}



echo "<form method='post' action='criteres.php'>\n" ;
echo "<table class='formulaire'>\n<tbody>\n" ;
// On ne peut pas compter sur $_SESSION["derniere_annee"], car quand on ajoute
// une nouvelle promotion pour une année ultérieure, on ne peut pas y accéder
// sans se déconnecter et se reconnecter.
echo "<tr>\n" ;
echo "<th rowspan='2'>Limiter à&nbsp;: </th>\n" ;
echo "<th>Année&nbsp;: </th>\n" ;
echo "<td><select name='p_annee'>\n" ;
$req = "SELECT DISTINCT(annee) FROM session ORDER BY annee DESC" ;
$res = mysqli_query($cnx, $req) ;
while ( $enr = mysqli_fetch_assoc($res) ) {
	echo "<option value='".$enr["annee"]."'" ;
	if ( $_SESSION["filtres"]["promotions"]["annee"] == $enr["annee"] ) {
		echo " selected='selected'" ;
	}
	echo ">".$enr["annee"]."</option>" ;
}
echo "</select></td>\n" ;
echo "</tr>\n" ;

echo "<tr>\n" ;
echo "<th>Domaine : </th>\n" ;
echo "<td>" ;
echo select_groupe(
	( isset($_SESSION["filtres"]["promotions"]["groupe"]) ? $_SESSION["filtres"]["promotions"]["groupe"] : "" )
	) ;
echo "</td>\n" ;
echo "</tr>\n" ;

echo "<tr>\n" ;
echo "<th>Afficher : </th>\n" ;
echo "<td colspan='2'><label><input name='p_exam' type='checkbox' value='p_exam' " ;
if ( isset($_SESSION["filtres"]["promotions"]["exam"]) AND ($_SESSION["filtres"]["promotions"]["exam"] == "p_exam") )
{
	echo " checked='checked'" ;
}
echo " /> Dates d'examen</label></td>\n" ;
echo "</tr>\n" ;

echo "<tr>\n<td colspan='3'>" . FILTRE_BOUTON_LIEN . "</td>\n</tr>\n" ;
echo "</tbody>\n</table>\n" ;
echo "</form>" ;
echo "<br />" ;

$requete = "SELECT COUNT(*) FROM session
	WHERE annee=".$_SESSION["filtres"]["promotions"]["annee"] ;
$result = mysqli_query($cnx, $requete);
$row = mysqli_fetch_row($result) ;
$nombre_annee = $row[0] ;


$requete = "SELECT intitule, groupe, nb_annees, session.*
	FROM session, atelier
	WHERE atelier.id_atelier=session.id_atelier
	AND annee=".$_SESSION["filtres"]["promotions"]["annee"]." " ;
if ( isset($_SESSION["filtres"]["promotions"]["groupe"]) AND ($_SESSION["filtres"]["promotions"]["groupe"] != "") ) {
	$requete .= " AND groupe='".mysqli_real_escape_string($cnx, $_SESSION["filtres"]["promotions"]["groupe"])."' " ;
}
$requete .= " ORDER BY  groupe, niveau, intitule" ;
$result = mysqli_query($cnx, $requete);
$nombre_groupe = mysqli_num_rows($result) ;
$promos = array() ;
while ( $row = mysqli_fetch_assoc($result) ) {
	$promos[] = $row ;
}

echo "<table class='stats'>\n";
echo "<thead>\n" ;
echo "<tr>\n" ;
echo "\t<th class='invisible'></th>\n";
if ( isset($_SESSION["filtres"]["promotions"]["exam"]) AND ($_SESSION["filtres"]["promotions"]["exam"] == "p_exam") )
{
	echo "<th>Dates d'examens</th>" ;
}
echo "\t<th><div style='font-size: 1.5em'>" ;
echo "$nombre_annee promotions en ".$_SESSION["filtres"]["promotions"]["annee"] ;
echo "</div>" ;
if ( isset($_SESSION["filtres"]["promotions"]["groupe"]) AND ($_SESSION["filtres"]["promotions"]["groupe"] != "") ) {
	echo "<div style='font-size: 1.2em;'>" ;
	echo "$nombre_groupe promotions en ".$_SESSION["filtres"]["promotions"]["groupe"] ;
	echo "</div>" ;
}
echo "</th>\n";
echo "\t<th><img src='/img/promotions/candidatures.png' width='22' height='126' alt='Candidatures' title='Candidatures' /></th>\n";
echo "\t<th><img src='/img/promotions/evaluations.png' width='22' height='126' alt='Évaluations' title='Évaluations' /></th>\n";
echo "\t<th><img src='/img/promotions/imputations.png' width='22' height='126' alt='Imputations' title='Imputations' /></th>\n";
echo "\t<th><img src='/img/promotions/imputations2.png' width='25' height='126' alt='Imputations' title='Imputations' /></th>\n";
echo "</tr>\n" ;
echo "</thead>\n" ;
echo "<tbody>\n" ;

$i=0 ;
$annee_precedente = "aucune" ;
$groupe = "" ;
foreach ($promos as $promo)
{
	$lien = "?session=".$promo["id_session"] ;

    if ( $groupe != $promo["groupe"] ) {
        $groupe = $promo["groupe"] ;
        echo "<tr class='groupe'>" ;
		echo "<td class='invisible'></td>" ;
		echo "<td style='background: #333; color: #fff; font-size: larger;' colspan='5'>" ;
        echo "<strong>$groupe</strong></td>" ;
		echo "</tr>" ;
    }

	echo "<tr id='p".$promo["id_session"]."'>\n" ;

	// Suppression
	$req = "SELECT COUNT(id_dossier) FROM dossier, candidat
		WHERE id_session=".$promo["id_session"] ;
	$req .= " AND dossier.id_candidat=candidat.id_candidat" ;
	$res = mysqli_query($cnx, $req) ;
	$ligne = mysqli_fetch_row($res) ;
	$N = $ligne[0] ;
	if ( intval($N) == 0 ) 
	{
		echo "<td><a href='supprimer_promotion.php".$lien."'>Supprimer</a></td>\n" ;
	}
	else {
		echo "<td class='invisible'></td>\n" ;
	}

	if ( isset($_SESSION["filtres"]["promotions"]["exam"]) AND ($_SESSION["filtres"]["promotions"]["exam"] == "p_exam") )
	{
		echo "\t<td class='r'>" ;
/*
		echo "<div style='font-size: smaller;'>" ;
		echo "<a href='/statistiques/promotion.php".$lien."'>Statistiques</a>" ;
		echo "</div>\n" ;
		if ( $promo["evaluations"] == "Oui" ) {
			echo "<a href='/candidatures/candidatures.php?id_session=" ;
			echo $promo["id_session"] . "'>Candidatures</a></div>\n" ;
		}
*/
		//echo "<span class='addexam'>" ;
		echo "<div style='text-align: left'>" ;
		echo "<a href='/examens/ajout.php" ;
		echo  "?promotion=" ;
		echo $promo["id_session"] ;
		echo "'>Ajouter</a>" ;
		echo "</div>" ;

		$req = "SELECT * FROM examens WHERE ref_session=".$promo["id_session"]
			. " ORDER BY date_examen" ;
		$res = mysqli_query($cnx, $req);
		while ( $row = mysqli_fetch_assoc($res) ) {
			echo "<div><a id='e".$row["id_examen"]."' href='/examens/examen.php?action=maj&amp;id_examen="
				. $row["id_examen"]
				."' title='".enleve_guillemets($row["commentaire"])."'>" ;
			echo dateComplete($row["date_examen"]) . "</a></div>" ;
		}

		echo "</td>\n" ;
	}


	echo "\t<td class='l'><strong style='font-size: 100%'>" ;
	echo "<a class='bl' href='promotion.php".$lien."&amp;operation=modif'>" ;
	echo $promo["intitule"]."</a></strong>\n" ;
	echo "<div style='font-size: 90%'>" ;
	echo "Promotion <strong>".$promo["intit_ses"]."</strong>" ;
	echo " du ". mysql2datealpha($promo["date_deb"]) ;
	echo " au ". mysql2datealpha($promo["date_fin"])."</div>\n" ;
	echo "<div>" ;
	echo $promo["imputation"] ;
	echo " <span class='sep'>-</span> " ;
	echo $promo["tarifP"] . "&euro;" ;
	echo " <span class='sep'>-</span> " ;
	echo $promo["tarifA"] . "&euro;" ;
	echo "</div>\n" ;
	if ( $promo["nb_annees"] != "1" ) {
		echo "<div>" ;
		echo $promo["imputation2"] ;
		echo " <span class='sep'>-</span> " ;
		echo $promo["tarif2P"] . "&euro;" ;
		echo " <span class='sep'>-</span> " ;
		echo $promo["tarif2A"] . "&euro;" ;
		echo "</div>\n" ;
	}
	echo "</td>\n" ;


	echo "\t<td class='c ".$promo["candidatures"]."'>".$promo["candidatures"]."</td>\n" ;
	echo "\t<td class='c ".$promo["evaluations"]."'>".$promo["evaluations"]."</td>\n" ;
	echo "\t<td class='c ".$promo["imputations"]."'>".$promo["imputations"]."</td>\n" ;
	if ( $promo["nb_annees"] != "1" ) {
		echo "\t<td class='c ".$promo["imputations2"]."'>".$promo["imputations2"]."</td>\n" ;
	}
	else {
		echo "<td class='invisible'></td>" ;
	}

	echo "</tr>\n" ;
}
echo "</tbody>\n" ;
echo "</table>\n" ;


deconnecter($cnx) ;
echo $end ;

