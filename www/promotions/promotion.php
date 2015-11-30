<?php
include("inc_session.php") ;
include("inc_html.php") ;
include("inc_mysqli.php") ;
include("inc_formations.php");

$JOUR = array("01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31") ;
$annee_courante = intval(date("Y", time())) ;
$ANNEE = array() ;
for ( $i=$annee_courante+2 ; $i >= 2004 ; $i-- ) {
	$ANNEE[] = $i ;
} 
$mois = array("janvier", "février", "mars", "avril", "mai", "juin","juillet", "août", "septembre", "octobre", "novembre", "décembre") ;


function radio($nom, $checked)
{
	echo "<label class='bl Oui'>" ;
	echo "<input type='radio' id='$nom' name='$nom' value='Oui'" ;
	if ( $checked == 'Oui' ) {
		echo " checked='checked'" ;
	}
	echo "> Oui</label>\n" ;
	echo "<label class='bl Non'>" ;
	echo "<input type='radio' id='$nom' name='$nom' value='Non'" ;
	if ( $checked == 'Non' ) {
		echo " checked='checked'" ;
	}
	echo "> Non</label>\n" ;
}

function liste_der($tab, $nom_ld, $selected)
{
	echo "<select name=\"$nom_ld\">\n" ;
	while ( list($champ,$valeur) = each($tab) )
	{
		echo "<option value=\"$valeur\"" ;
		if ( $valeur == $selected ) {
			echo " selected='selected'" ;
		}
		echo ">$valeur</option>\n" ;
	}
	echo "</select>" ;
}

function date_from_mysql($mysql_date)
{
	$monthNameArray = array(
		1 => 'janvier', 'février', 'mars', 'avril',
		'mai', 'juin', 'juillet', 'août', 'septembre',
		'octobre', 'novembre', 'décembre') ;

	$d = explode( '-', $mysql_date );

	if ( $d[1][0] == '0' )
		$d[1] = substr( $d[1], 1, strlen($d[1]) );
	$d[1]=  $monthNameArray[$d[1]];
	return $d;
}

function date_to_mysql($jour, $mois, $annee)
{
	$monthNameArray = array(
		1 => 'janvier', 'février', 'mars', 'avril',
		'mai', 'juin', 'juillet', 'août', 'septembre',
		'octobre', 'novembre', 'décembre') ;
	while (list($cle, $valeur) = each($monthNameArray) )
	{
		if ( $valeur == $mois ) {
			$num_mois=$cle;
			if($num_mois<10)
				$num_mois="0" .$num_mois;
		}
	}
	return $annee ."-" .$num_mois ."-" .$jour;
}

$cnx = connecter() ;

// Formulaire envoye, traitement du formulaire : controle de validite
if ( isset($_POST["formulaire"]) )
{  
	include("controle_promotion.php") ;
}

// si formulaire envoye et aucune erreur detectee
if ( isset($_POST["formulaire"]) AND $verif_saisie=="ok" )
{  
	include("save_promotion.php");
}

// si erreur trouvee ou 1ere execution
if	(
		( isset($verif_saisie) AND ($verif_saisie!="ok") )
		OR !isset($_POST["formulaire"])
	)
{
	if ( ( $_GET["operation"] == "add") OR ( $_GET["operation"] == "addBase") )
	{
		$intit_ses = "" ;
		$date_deb = "" ;
		$date_fin = "" ;
		$id_atelier = "" ;
		$annee = "" ;
		$imputation = "" ;
		$tarifP = "" ;
		$tarifA = "" ;
		$imputation2 = "" ;
		$tarif2P = "" ;
		$tarif2A = "" ;
		$j_debut = "" ;
		$m_debut = "" ;
		$a_debut = "" ;
		$j_fin = "" ;
		$m_fin = "" ;
		$a_fin = "" ;
		$candidatures = "Non" ;
		$evaluations = "Non" ;
		$imputations = "Non" ;
		$imputations2 = "Non" ;
		//$etat="";
		$hidden = "<input type='hidden' name='operation' value='addBase' />" ;
		$titre = "Nouvelle promotion" ;
	}

	if ( ($_GET["operation"] == "modif") OR ($_GET["operation"] == 'modifBase') )
	{
		$req = "SELECT intitule, nb_annees, session.* FROM atelier, session
			WHERE id_session=".$_GET["session"]."
			AND session.id_atelier=atelier.id_atelier" ;
		$res = mysqli_query($cnx, $req) ;
		$row = mysqli_fetch_assoc($res) ;
		$nb_annees = $row["nb_annees"] ;
		$session = $row["id_session"] ;
		$intit_ses = $row["intit_ses"] ;
		$candidatures = $row["candidatures"] ;
		$evaluations = $row["evaluations"] ;
		$imputations = $row["imputations"] ;
		$imputations2 = $row["imputations2"] ;
		$id_atelier = $row["id_atelier"] ;
		$annee = intval($row["annee"]) ;
		$imputation = $row["imputation"] ;
		$imputation2 = $row["imputation2"] ;
		$tarifP = $row["tarifP"] ;
		$tarif2P = $row["tarif2P"] ;
		$tarifA = $row["tarifA"] ;
		$tarif2A = $row["tarif2A"] ;
		$date_deb = date_from_mysql($row["date_deb"]) ;
		$date_fin = date_from_mysql($row["date_fin"]) ;
		$j_debut = $date_deb[2] ;
		$m_debut = $date_deb[1] ;
		$a_debut = $date_deb[0] ;
		$j_fin = $date_fin[2] ;
		$m_fin = $date_fin[1] ;
		$a_fin = $date_fin[0] ;
		$hidden = "<input type='hidden' name='operation' value='modifBase' />" ;
		$titre = "Modification d'une promotion" ;
	}

	echo $dtd1 ;
	echo "<title>$titre</title>\n" ;
	echo $htmlJquery ;
	echo $htmlMakeSublist ;
	echo $dtd2 ;
	include("inc_menu.php") ;
	echo $debut_chemin ;
	echo "<a href='/bienvenue.php'>Accueil</a>" ;
	echo " <span class='arr'>&rarr;</span> " ;
	echo "<a href='/promotions/index.php" ;
	if ( isset($_GET["session"]) ) {
		echo "#".$_GET["session"] ;
	}
	echo "'>Promotions</a>" ;
	echo " <span class='arr'>&rarr;</span> " ;
	echo $titre ;
	echo $fin_chemin ;
?>
<form id="formpromo" action='promotion.php' method='post'>

<table class='formulaire'>
<?php
if ( isset($verif_saisie) AND ($verif_saisie != "ok") ) {
		echo "<tr><td colspan='3' style='background: #fff;'>" ;
		echo $verif_saisie."</td></tr>\n" ;
}
?>
<tr>
	<th>Formation : </th>
	<td colspan='3'><?php
//liste_formations($cnx, "id_atelier", $id_atelier) ;
$formForma = chaine_liste_formations("id_atelier", $id_atelier, "", $cnx) ;
echo $formForma["form"] ;
echo $formForma["script"] ;
	?></td>
</tr><tr>
	<th>Intitulé de la promotion : </th>
	<td colspan='3'><input type="text" name="v_intitule" size=60 maxlength=100 value="<?php
		if ($intit_ses) 
			echo "$intit_ses";
		else if ( isset($v_intitule) )
			echo $v_intitule;
		?>" /></td>
</tr><tr>
	<th>Année : </th>
	<td colspan='3'><?php liste_der($ANNEE, "annee", $annee) ; ?></td>
</td>
</tr><tr>
	<th>Date Début : </th>
	<td colspan='3'><?php if (isset($jour_debut)) $j_debut = $jour_debut ;
		liste_der($JOUR, "jour_debut", $j_debut) ; ?> /
		<?php if (isset($mois_debut)) $m_debut = $mois_debut ;
		liste_der($mois, "mois_debut", $m_debut) ; ?> /
		<?php if (isset($annee_debut)) $a_debut = $annee_debut;
		liste_der($ANNEE, "annee_debut", $a_debut) ; ?></td>
</tr><tr>
	<th>Date Fin : </th>
	<td colspan='3'><?php if (isset($jour_fin)) $j_fin = $jour_fin ;
		liste_der($JOUR, "jour_fin", $j_fin) ; ?> /
		<?php if (isset($mois_fin)) $m_fin = $mois_fin;
		liste_der($mois, "mois_fin", $m_fin) ; ?> /
		<?php if (isset($annee_fin)) $a_fin = $annee_fin ;
		liste_der($ANNEE, "annee_fin", $a_fin) ; ?></td>
</tr><tr>
	<th>Code d'imputation : </th>
	<td colspan='3'><input type="text" name="imputation" size="10" value="<?php
		echo $imputation ;
		?>" /></td>
</tr><tr>
	<th>Tarif Payant : </th>
	<td colspan='3'><input type="text" name="tarifP" size="10" value="<?php
		echo $tarifP ;
		?>" class='r' /> &euro;</td>
</tr><tr>
	<th>Tarif Allocataire : </th>
	<td colspan='3'><input type="text" name="tarifA" size="10" value="<?php
		echo $tarifA ;
		?>" class='r' /> &euro;</td>

<?php
if ( isset($nb_annees ) AND ($nb_annees != "1") ) {
?>
</tr><tr>
	<th>Code d'imputation 2<sup>ème</sup> année : </th>
	<td colspan='3'><input type="text" name="imputation2" size="10" value="<?php
		echo $imputation2 ;
		?>" /></td>
</tr><tr>
	<th>Tarif Payant 2<sup>ème</sup> année : </th>
	<td colspan='3'><input type="text" name="tarif2P" size="10" value="<?php
		echo $tarif2P ;
		?>" class='r' /> &euro;</td>
</tr><tr>
	<th>Tarif Allocataire 2<sup>ème</sup> année : </th>
	<td colspan='3'><input type="text" name="tarif2A" size="10" value="<?php
		echo $tarif2A ;
		?>" class='r' /> &euro;</td>
<?php
}
?>

</tr><tr>
	<th>Candidatures : </th>
	<td style='width: 3.4em;'><?php radio("candidatures", $candidatures) ; ?></td>
	<td colspan='2' style='font-size: smaller;'>«&nbsp;Oui&nbsp;» pour que les candidats puissent postuler ou mettre à jour leur candidature.</td>
</tr><tr>
	<th>&Eacute;valuations : </th>
	<td><?php radio("evaluations", $evaluations) ; ?></td>
	<td style='font-size: smaller;' colspan='2'>«&nbsp;Oui&nbsp;» pour autoriser l'évaluation (ajout de commentaire, changement d'état) des candidatures<br />
	Concerne responsables, services des bourses, et CNF. L'administrateur peut toujours le faire.</td>
<!--
	<td rowspan='2' style='font-size: smaller;'
>L'un des deux au moins<br />
doit être à «&nbsp;Oui&nbsp;»<br />
pour pouvoir envoyer<br />
un nouveau courriel.</td>
-->
</tr><tr>
	<th>Imputations : </th>
	<td><?php radio("imputations", $imputations) ; ?></td>
	<td style='font-size: smaller;' colspan='2'>«&nbsp;Oui&nbsp;» pour permettre l'imputation des paiements des candidats.</td>
<?php
if ( isset($nb_annees ) AND ($nb_annees != "1") ) {
?>
</tr><tr>
	<th>Imputations : <br />2<sup>ème</sup> année</th>
	<td><?php radio("imputations2", $imputations2) ; ?></td>
	<td style='font-size: smaller;' colspan='2'>«&nbsp;Oui&nbsp;» pour permettre l'imputation des paiements des candidats la 2<sup>ème</sup> année.</td>
<?php
}
?>
</tr>
</table>
<?php
echo $hidden ;
if ( isset($session) ) {
	?><input type="hidden" name="session" value=<?php echo $session ; ?> /><?php
}
?>
<input type="hidden" name="formulaire" value="OK" />

<p class='c'>
<input type="submit" value="Enregistrer" />
</p>

</form>

<?
	echo $end;
}
deconnecter($cnx) ;
?>
