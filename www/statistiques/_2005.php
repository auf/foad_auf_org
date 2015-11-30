<?php

$etatsDossiers2005 = array(
	'Non étudié',
	'Refusé',
	'En attente',
	'Allocataire',
	'Payant',
) ;

$sessions2005 = sessionsAnnee($cnx, 2005) ;

echo "<table class='stats'>\n" ;
echo "<thead>\n" ;
echo "<tr>\n" ;
foreach($etatsDossiers2005 as $etat) {
	echo "<th>" ;
	echo "<img src='/img/etat/".$etat_dossier_img_class[$etat].".gif' " ;
	echo "height='120' width='18' alt='$etat' />" ;
	echo "</th>\n" ;
}
echo "<th>Total</th>\n" ;
echo "<th>Formation</th>\n" ;
echo "<th>Session</th>\n" ;
echo "</tr>\n" ;
echo "</thead>\n" ;
echo "<tbody>\n" ;
$groupe = "" ;
$total = 0 ;
$totalEtat = array() ;
foreach(array_keys($sessions2005) as $idSession)
{
	if ( ( intval($_SESSION["id"]) < 3 ) AND
		( $groupe != $sessions2005[$idSession][2] ) )
	{
		$groupe = $sessions2005[$idSession][2] ;
		echo "<tr><td style='background: #ccc;'" ;
		echo " colspan='10' class='r'>" ;
		echo "<b style='font-size: 100%;'>$groupe</b></td></tr>" ;
	}
	echo "<tr>\n" ;
	$req  = "SELECT etat_dossier, COUNT(etat_dossier) AS N
		FROM dossier, candidat
		WHERE id_session=$idSession
		AND dossier.id_candidat=candidat.id_candidat " ;
	if ( isset($_SESSION["filtres"]["statistiques"]["pays"]) AND ($_SESSION["filtres"]["statistiques"]["pays"]!="") )
	{
		$req .= "AND pays='".$_SESSION["filtres"]["statistiques"]["pays"]."' " ;
	}
	$req.=	"GROUP BY dossier.etat_dossier" ;
	$resultat = mysqli_query($cnx, $req) ;
	unset($etatDossier) ;
	while ( $ligne = mysqli_fetch_assoc($resultat) ) {
		$etatDossier[$ligne["etat_dossier"]] = $ligne["N"] ;
	}
	$sousTotal = 0 ;
	unset($accepte) ;
	foreach($etatsDossiers2005 as $etat)
	{
		if ( !isset($etatDossier[$etat]) ) {
			echo "<td class='".$etat_dossier_img_class[$etat]."'>0</td>\n" ;
		}
		else {
			echo "<td class='".$etat_dossier_img_class[$etat]."'>" ;
			echo $etatDossier[$etat]."</td>\n" ;
			$sousTotal += $etatDossier[$etat] ;
			@$totalEtat[$etat] += $etatDossier[$etat] ;
		}
		// Les stats des candidats acceptés
		if ( ( $etat == "" )
			OR ( $etat == "Allocataire" )
			OR ( $etat == "Payant" )
			OR ( $etat == "Confirmé A" )
			OR ( $etat == "Confirmé P" )
			 )
		{
			if ( !isset($etatDossier[$etat]) ) {
				$accepte[$etat] = 0 ;
			}
			else {
				$accepte[$etat] = $etatDossier[$etat] ;
			}
		}
	}
	$acceptes[$idSession] = $accepte ;
	echo "<th class='total'>$sousTotal</th>\n" ;
	echo "<th><a href='promotion.php?session=$idSession'>" ;
	echo $sessions2005[$idSession][0]."</a></th>\n " ;
	echo "<td>".$sessions2005[$idSession][1]."</td>\n" ;
	echo "</tr>\n" ;
	$total += $sousTotal ;
}
echo "</tbody>\n" ;

echo "<tbody>\n" ;
echo "<tr>\n" ;
foreach($etatsDossiers2005 as $etat) {
	echo "<th class='total'>".$totalEtat[$etat]."</th>" ;
}
	echo "<th class='total'>".$total."</th>" ;
echo "<th colspan='2' style='text-align: center'>Sous-total</th>\n" ;
echo "</tr>\n" ;
echo "</tbody>\n" ;
echo "<tbody>\n" ;

unset($nomformation) ;
$requete  = "SELECT DISTINCT nomformation FROM autrescandidats " ;
$requete .= "ORDER BY nomformation" ;
$resultat = mysqli_query($cnx, $requete) ;
while ( $ligne = mysqli_fetch_assoc($resultat) ) {
	$nomformation[] = $ligne["nomformation"] ;
}


echo "<tr><td style='background: #ccc;'" ;
echo " colspan='10' class='r'>" ;
echo "<b style='font-size: 100%;'>Promotions importées</b></td></tr>" ;


foreach($nomformation as $formation) {
	echo "<tr>\n" ;
	$requete  = "SELECT etatd, COUNT(etatd) AS N FROM autrescandidats " ;
	$requete .= "WHERE nomformation='$formation' " ;
	if ( $_SESSION["filtres"]["statistiques"]["pays"] != "" ) {
		$requete .= " AND pays='".$_SESSION["filtres"]["statistiques"]["pays"]."' " ;
	}
	$requete .= " GROUP BY etatd" ;
	$resultat = mysqli_query($cnx, $requete) ;
	unset($etatDossier) ;
	while ( $ligne = mysqli_fetch_assoc($resultat) ) {
		$etatDossier[$ligne["etatd"]] = $ligne["N"] ;
	}
	$sousTotal = 0 ;
	foreach($etatsDossiers2005 as $etat) {
		if ( !isset($etatDossier[$etat]) ) {
			echo "<td class='".$etat_dossier_img_class[$etat]."'>0</td>\n" ;
		}
		else {
			echo "<td class='".$etat_dossier_img_class[$etat]."'>" ;
			echo $etatDossier[$etat]."</td>\n" ;
			$sousTotal += $etatDossier[$etat] ;
			$totalEtat[$etat] += $etatDossier[$etat] ;
		}
	}
	echo "<th class='total'>$sousTotal</th>\n" ;
	echo "<th colspan='2'>$formation</th>\n " ;
	echo "</tr>\n" ;
	$total += $sousTotal ;
}

echo "</tbody>\n" ;
echo "<tfoot>\n" ;
echo "<tr>\n" ;
foreach($etatsDossiers2005 as $etat) {
	echo "<th class='total'>".$totalEtat[$etat]."</th>" ;
}
echo "<th class='total'>".$total."</th>" ;
echo "<th colspan='2'>Total</th>\n" ;
echo "</tr>\n" ;
echo "</tfoot>\n" ;

echo "</table>" ;

//
//
echo "<hr class='clear' />\n" ;
//
//

?>
<table class='stats'>
<thead>
<tr>
	<th colspan="2">Payants</th>
	<th colspan="2">Allocataires</th>
	<th rowspan="2">Formation</th>
	<th rowspan="2">Session</th>
</tr>
<tr>
	<th><span title="Payant">P</span></th>
	<th>% <span title="Payant">P</span></th>
	<th><span title="Allocataire">A</span></th>
	<th>% <span title="Allocataire">A</span></th>
</tr>
</thead>
<tbody>
<?php
$totauxAllocataire = 0 ;
$totauxPayant = 0 ;
$totauxConfirmeA = 0 ;
$totauxConfirmeP = 0 ;
foreach(array_keys($sessions2005) as $idSession)
{
	$Allocataire = $acceptes[$idSession]["Allocataire"] ; 
	$totauxAllocataire += $Allocataire ;

	$Payant = $acceptes[$idSession]["Payant"] ;
	$totauxPayant += $Payant ;

	$allocataires = $Allocataire ;
	$payants = $Payant ;

	$t = $allocataires + $payants ;

	echo "<tr>\n" ;
	echo "<td class='payant'>$payants</td>\n" ;
	echo "<td class='payant'>" ;
	if ( $t != 0 ) {
		printf("%.2f", (($payants/$t)*100)) ;
		echo "&nbsp;%" ;
	}
	echo "</td>\n" ;
	echo "<td class='allocataire'>$allocataires</td>\n" ;
	echo "<td class='allocataire'>" ;
	if ( $t != 0 ) {
		printf("%.2f", (($allocataires/$t)*100)) ;
		echo "&nbsp;%" ;
	}
	echo "</td>\n" ;
	echo "<th>".$sessions2005[$idSession][0]."</th>\n " ;
	echo "<td>".$sessions2005[$idSession][1]."</td>\n" ;
	echo "</tr>\n" ;
}
echo "</tbody>\n" ;
$totauxAllocataires = $totauxAllocataire + $totauxConfirmeA ;
$totauxPayants = $totauxPayant + $totauxConfirmeP ;
$totaux = $totauxAllocataires + $totauxPayants ;
echo "<tfoot>\n" ;
echo "<tr>\n" ;
	echo "<th>$totauxPayants</th>" ;
	echo "<th>" ;
		if ( $totaux != 0 ) {
			printf("%.2f", (($totauxPayants/$totaux)*100)) ;
		}
		else {
			echo "0" ;
		}
		echo "&nbsp;%" ;
	echo "</th>" ;
	echo "<th>$totauxAllocataires</th>" ;
	echo "<th>" ;
		if ( $totaux != 0 ) {
			printf("%.2f", (($totauxAllocataires/$totaux)*100)) ;
		}
		else {
			echo "0" ;
		}
		echo "&nbsp;%" ;
	echo "</th>" ;
	echo "<th colspan='2'>Total</th>\n" ;
echo "</tr>\n" ;
echo "</tfoot>\n" ;

echo "</table>" ;
?>
