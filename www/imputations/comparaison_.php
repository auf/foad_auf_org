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
?>




<p class='c'><strong>11 différences</strong></p><table class='tableau'>
<thead>
<tr>
<th>Nom</th><th>Candidature</th><th>Imputation</th></tr>
</thead>
<tbody>
<tr>
<td>2006 <a href='/imputations/promotion.php?promotion=142'>142</a> Monsieur <strong>BANDELEN BIOMB</strong> Alain</td>

<td class='desistea'><a title='Désisté A' href='/candidatures/candidature.php?id_dossier=12709'><strong>Candidature</strong></a></td>
<td class='payant'><a title='Payant' href='/imputations/attestation.php?id=747'><strong>Imputation</strong></a></td>
</tr>
<tr>
<td>2006 <a href='/imputations/promotion.php?promotion=159'>159</a> Monsieur <strong>KAKA</strong> Assanti</td>
<td class='desistea'><a title='Désisté A' href='/candidatures/candidature.php?id_dossier=10627'><strong>Candidature</strong></a></td>
<td class='payant'><a title='Payant' href='/imputations/attestation.php?id=601'><strong>Imputation</strong></a></td>

</tr>
<tr>
<td>2007 <a href='/imputations/promotion.php?promotion=202'>202</a> Monsieur <strong>ASSOHOUN</strong> Vangah Innocent</td>
<td class='nonetudie'><a title='Non étudié' href='/candidatures/candidature.php?id_dossier=19277'><strong>Candidature</strong></a></td>
<td class='payant'><a title='Payant' href='/imputations/attestation.php?id=1708'><strong>Imputation</strong></a></td>
</tr>
<tr>
<td>2007 <a href='/imputations/promotion.php?promotion=202'>202</a> Mademoiselle <strong>BAKKAR</strong> Yasmine</td>

<td class='nonetudie'><a title='Non étudié' href='/candidatures/candidature.php?id_dossier=25114'><strong>Candidature</strong></a></td>
<td class='allocataire'><a title='Allocataire' href='/imputations/attestation.php?id=1721'><strong>Imputation</strong></a></td>
</tr>
<tr>
<td>2007 <a href='/imputations/promotion.php?promotion=202'>202</a> Mademoiselle <strong>BANGAGNE</strong> Mariam</td>
<td class='nonetudie'><a title='Non étudié' href='/candidatures/candidature.php?id_dossier=25307'><strong>Candidature</strong></a></td>
<td class='allocataire'><a title='Allocataire' href='/imputations/attestation.php?id=1715'><strong>Imputation</strong></a></td>

</tr>
<tr>
<td>2007 <a href='/imputations/promotion.php?promotion=202'>202</a> Monsieur <strong>DENIS</strong> Daniel</td>
<td class='nonetudie'><a title='Non étudié' href='/candidatures/candidature.php?id_dossier=19212'><strong>Candidature</strong></a></td>
<td class='allocataire'><a title='Allocataire' href='/imputations/attestation.php?id=1719'><strong>Imputation</strong></a></td>
</tr>
<tr>
<td>2007 <a href='/imputations/promotion.php?promotion=202'>202</a> Monsieur <strong>ESONO NZE OYANA</strong> Leoncio-feliciano</td>

<td class='nonetudie'><a title='Non étudié' href='/candidatures/candidature.php?id_dossier=25398'><strong>Candidature</strong></a></td>
<td class='payant'><a title='Payant' href='/imputations/attestation.php?id=1732'><strong>Imputation</strong></a></td>
</tr>
<tr>
<td>2007 <a href='/imputations/promotion.php?promotion=202'>202</a> Monsieur <strong>HOUNKPE</strong> Coovi Gaudens Jonas</td>
<td class='nonetudie'><a title='Non étudié' href='/candidatures/candidature.php?id_dossier=22174'><strong>Candidature</strong></a></td>
<td class='payant'><a title='Payant' href='/imputations/attestation.php?id=1705'><strong>Imputation</strong></a></td>

</tr>
<tr>
<td>2007 <a href='/imputations/promotion.php?promotion=202'>202</a> Monsieur <strong>KODA</strong> Romuald Iréné</td>
<td class='nonetudie'><a title='Non étudié' href='/candidatures/candidature.php?id_dossier=25080'><strong>Candidature</strong></a></td>
<td class='allocataire'><a title='Allocataire' href='/imputations/attestation.php?id=1666'><strong>Imputation</strong></a></td>
</tr>
<tr>
<td>2007 <a href='/imputations/promotion.php?promotion=202'>202</a> Monsieur <strong>OUERAOGO</strong> Abdoulaye Clotaire</td>

<td class='nonetudie'><a title='Non étudié' href='/candidatures/candidature.php?id_dossier=25792'><strong>Candidature</strong></a></td>
<td class='allocataire'><a title='Allocataire' href='/imputations/attestation.php?id=1731'><strong>Imputation</strong></a></td>
</tr>
<tr>
<td>2007 <a href='/imputations/promotion.php?promotion=202'>202</a> Mademoiselle <strong>PINCHINAT</strong> Adelyne</td>
<td class='nonetudie'><a title='Non étudié' href='/candidatures/candidature.php?id_dossier=25660'><strong>Candidature</strong></a></td>
<td class='payant'><a title='Payant' href='/imputations/attestation.php?id=1725'><strong>Imputation</strong></a></td>

</tr>
</tbody>
</table>





<?php
echo $end ;
?>
