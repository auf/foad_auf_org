<?php
include("inc_session.php") ;

include("inc_html.php") ;
$titre = "Etat" ;
echo $dtd1 ;
echo "<title>$titre</title>\n" ;
echo $dtd2 ;
include("inc_menu.php") ;
echo $debut_chemin ;
echo "<a href='/bienvenue.php'>Accueil</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo "<a href='index.php'>Documentation</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo $titre;
echo $fin_chemin ;
?>


<div style='float: right'>
<p><select>
<option value='Non étudié' class='nonetudie'>Non étudié</option>
<option value='Allocataire' class='allocataire'>Allocataire</option>
<option value='Payant' class='payant'>Payant</option>
<option value='Allocataire SCAC' class='scac'>Allocataire SCAC</option>
<option value='En attente' class='enattente'>En attente</option>
<option value='Refusé' class='refuse'>Refusé</option>
<option value='Confirmé A' class='confirmea'>Confirmé A</option>
<option value='Désisté A' class='desistea'>Désisté A</option>
<option value='Reclassé A' class='reclassea'>Reclassé A</option>
<option value='A transférer' class='atransferer'>A transférer</option>
<option value='Cas 1' class='cas1'>Cas 1</option>
<option value='Cas 2' class='cas2'>Cas 2</option>
<option value='Cas 3' class='cas3'>Cas 3</option>
</select></p>
</div>



<table>
<thead>
<tr>
	<th>&Eacute;tat</th>
	<th>Description</th>
</tr>
</thead>
<tbody>
<tr>
	<td class='nonetudie'>Non étudié</td>
	<td>&Eacute;tat par défaut</td>
</tr><tr>
	<td class='allocataire'>Allocataire</td>
	<td>Candidat accepté, avec allocation</td>
</tr><tr>
	<td class='payant'>Payant</td>
	<td>Candidat accepté, sans allocation</td>
</tr><tr>
	<td class='scac'>Allocataire SCAC</td>
	<td>Candidat accepté, avec allocation</td>
</tr><tr>
	<td class='enattente'>En attente</td>
	<td>Candidat sur liste d'attente</td>
</tr><tr>
	<td class='refuse'>Refusé</td>
	<td>Candidat refusé</td>
</tr><tr>
	<td class='confirmea'>Confirmé A</td>
	<td>Allocataire qui a confirmé son inscription.</td>
</tr><tr>
	<td class='desistea'>Désisté A</td>
	<td>Allocataire qui s'est désisté.</td>
</tr><tr>
	<td class='reclassea'>Reclassé A</td>
	<td>Allocataire qui était auparavant sur liste d'attente</td>
</tr><tr>
	<td class='atransferer'>A transférer</td>
	<td>A transférer dans une autre formation</td>
</tr><tr>
	<td class='cas1'>Cas 1</td>
	<td rowspan='3' style='vertical-align: top'>Autres états</td>
</tr><tr>
	<td class='cas2'>Cas 2</td>
</tr><tr>
	<td class='cas3'>Cas 3</td>
</tr>
</tbody>
</table>



</body>
</html>

