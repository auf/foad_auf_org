<?php
include("inc_session.php") ;

include("inc_html.php") ;
$titre = "Imputations en 1<sup>ère</sup> et 2<sup>ème</sup> année" ;
echo $dtd1 ;
echo "<title>".strip_tags($titre)."</title>\n" ;
echo $dtd2 ;
include("inc_menu.php") ;
echo $debut_chemin ;
echo "<a href='/bienvenue.php'>Accueil</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo "<a href='index.php'>Documentation</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo $titre;
echo $fin_chemin ;

echo "<h2>$titre</h2>\n" ;
?>

<p>Pour gérer deux imputations, en 1<sup>ère</sup> et 2<sup>ème</sup> année, il faut&nbsp;:</p>
<ol>
<li>Dans la gestion des <a href="/formations/index.php">formations</a>,
	choisir «&nbsp;<code>Nombre&nbsp;d'années&nbsp;(d'imputations)</code>&nbsp;»&nbsp;<code>=</code>&nbsp;«&nbsp;<code>2</code>&nbsp;».</li>
<li>Dans la gestion des <a href="/promotions/promotions.php">promotions</a>,
	renseigner les champs «&nbsp;<code>Code d'imputation 2ème année</code>&nbsp;»,
	«&nbsp;<code>Tarif Payant 2ème année</code>&nbsp;» et
	«&nbsp;<code>Tarif Allocataire 2ème année</code>&nbsp;»</li>
</ol>


<p>Une candidature peut être imputée en 2<sup>ème</sup> année sans avoir été imputée
en 1<sup>ère</sup> année.<br />
Il faut donc en général veiller, pour les formations concernées, dans la gestion des
<a href="/promotions/promotions.php">promotions</a>, à ce que les imputations ne soient
pas ouvertes en même temps pour les deux années
(«&nbsp;<code>Imputations</code>&nbsp;»&nbsp;<code>=</code>&nbsp;«&nbsp;<code>Oui</code>&nbsp;» et
«&nbsp;<code>Imputations 2<sup>ème</sup> année</code>&nbsp;»&nbsp;<code>=</code>&nbsp;«&nbsp;<code>Oui</code>&nbsp;»).</p>

<h3>Année d'imputation</h3>

<ul>
<li>L'année d'imputation en 1<sup>ère</sup> année est égale à l'année <code>A</code> de la promotion
	(renseignée dans la gestion des <a href="/promotions/promotions.php">promotions</a>).
<li>L'année d'imputation en 2<sup>ème</sup> année est égale à l'année suivant l'année de la promotion,
	<code>A</code>&nbsp;<code>+</code>&nbsp;<code>1</code>.</li>
</ul>

<p>Dans les <a href="/imputations/statistiques.php">statistiques des imputations</a>,
les imputations en 1<sup>ère</sup> et 2<sup>ème</sup> année sont donc comptabilisées
dans deux années successives (<code>A</code> et <code>A</code>&nbsp;<code>+</code>&nbsp;<code>1</code>)
quand on restreint les statistiques à une année.<br />
Mais quand on ne restreint pas les statistiques à une année, les imputations en 2<sup>ème</sup>
année sont comptabilisées dans l'année de leur promotion
(donc en 1<sup>ère</sup> année, <code>A</code>).</p>

<p>Dans les <a href="/imputations/index.php">imputations</a> et leurs exports&nbsp;:</p>
<ul>
<li>On sélectionne toutes les imputations d'une promotion en restreignant la sélection à une
	promotion, mais pas à une année.</li>
<li>On sélectionne seulement les imputations en 1<sup>ère</sup> ou 2<sup>ème</sup> année en
	restreignant la sélection à une promotion et à une année.<br />
	(Attention à sélectionner une année correspondant à la promotion
	(la sélection d'une année et d'une promotion était impossible avant la possibilité d'imputer deux fois).)</li>
</ul>


<h3>Différence entre imputation en 1<sup>ère</sup> et 2<sup>ème</sup> année</h3>

<p>Le compte <code>foad-scp</code> a la possibilité de transformer une candidature
«&nbsp;<span class='payant'>Payant</span>&nbsp;» en
«&nbsp;<span class='allocataire'>Allocataire</span>&nbsp;»
(dans la page de l'attestation de paiement)&nbsp;:</p>

<ul>
<li>Pour une imputation en 1<sup>ère</sup> année,
	l'état de l'imputation, et celui de la candidature deviennent
	«&nbsp;<span class='allocataire'>Allocataire</span>&nbsp;».</li>
<li>Pour une imputation en 2<sup>ème</sup> année, l'état de l'imputation devient
	«&nbsp;<span class='allocataire'>Allocataire</span>&nbsp;» mais l'état de la candidature
	(et de l'imputation en 1<sup>ère</sup> année) demeure
	«&nbsp;<span class='payant'>Payant</span>&nbsp;».
	<p>N.B. : Cet état <span class='allocataire'>Allocataire</span> n'est alors pas pris
	en compte dans la recherche d'imputations multiple&nbsp;; où c'est l'état
	<span class='payant'>Payant</span> de la candidature qui est pris en compte.</p>
	</li>
</ul>


<h3>Fonctionnement du critère « <code>Imputation</code> » de la <a href="/recherche/">recherche</a></h3>

<ul>
<li>« <code>Imputé</code> » : recherche les candidatures imputées au moins une fois.</li>
<li>« <code>A imputer</code> » : recherche les candidatures imputables
(«&nbsp;<span class='allocataire'>Allocataire</span>&nbsp;»,
«&nbsp;<span class='payant'>Payant</span>&nbsp;» et
«&nbsp;<span class='scac'>Allocataire SCAC</span>&nbsp;»)
mais non imputées, ni en 1<sup>ère</sup> année ni en 2<sup>ème</sup> année.</li>
</ul>

<p>Le choix « <code>A imputer</code> » ne permet donc pas de trouver les candidatures
imputées en 1<sup>ère</sup> année et à imputer en 2<sup>ème</sup> année.<br />
Pour rechercher ces candidatures, le critère qui convient est :
«&nbsp;<code>&Eacute;tat</code>&nbsp;» = «&nbsp;<code>Allocataire ou Payant ou Allocataire SCAC</code>&nbsp;».</p>

<p>N.B. : On peut aussi imputer une candidature à partir de la <a href="/candidatures/index.php"
>gestion des candidatures</a>.</p>


<?php
echo $end ;
?>


