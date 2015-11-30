<?php
include("inc_session.php") ;

include("inc_html.php") ;
$titre = "Quid de la navigation ?" ;
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

echo "<h2>$titre</h2>\n" ;
?>


<p>La <strong>navigation transversale</strong> (d'une page à une autre) peut vraissemblablement être améliorée (enrichie),<br />
mais ce n'est pas forcément pertinent... car cela ajoute des liens à des pages
qui en contiennent parfois déjà beaucoup.</p>

<p>La <strong>navigation principale</strong> devient moins appropriée à mesure que s'y ajoutent d'autres élements.<br />
Un menu déroulant serait approprié, mais la meilleure solution n'est pas évidente...</p>

<p>Tout ce qui est réservé à l'administrateur peut être rassemblé.

<div>
	<div style='float: left; border: 1px solid #777;; margin-right: 2px; padding: 2px 2px 0 2px;'>
		<strong>Administration</strong>
		<ul>
		<li>Formations</li>
		<li>Promotions</li>
		<li>Questions</li>
		<li>Responsables</li>
		<li>Ajouter un examen</li>
		</ul>
	<div style='clear: both;'></div>
</div>
<div style='clear: both;'></div>



<p style='clear: both;'>Ensuite, on peut considérer que les anciens font partie des imputés, qui eux mêmes font partie des candidats&nbsp;:</p>

<div>
	<div style='float: left; border: 1px solid #777;; margin-right: 2px; padding: 2px 2px 0 2px;'>
		<strong>Candidatures</strong>
		<ul>
		<li>Candidatures ouvertes (lien vers les formulaires)</li>
		<li>Gestion des candidatures</li>
		<li>Statistiques</li>
		<li>Exports (tableur)</li>
		<li>Recherche</li>
		<li>Messagerie</li>
		<li>Sélections multiples</li>
		</ul>
	</div>
	<div style='float: left; border: 1px solid #777;; margin-right: 2px; padding: 2px 2px 0 2px;'>
		<strong>Imputations</strong>
		<ul>
		<li></li>
		<li></li>
		</ul>
	</div>
	<div style='float: left; border: 1px solid #777;; margin-right: 2px; padding: 2px 2px 0 2px;'>
		<strong>Anciens</strong>
		<ul>
		<li></li>
		<li></li>
		</ul>
	</div>
	<div style='clear: both;'></div>
</div>

<p>Ou bien on peut faire des groupements par fonctionnalités&nbsp;:</p>

<div>
	<div style='float: left; border: 1px solid #777;; margin-right: 2px; padding: 2px 2px 0 2px;'>
		<strong>Statistiques</strong>
		<ul>
		<li>Candidatures</li>
		<li>Réponses des candidats</li>
		<li>Imputations</li>
		<li>Réponses des candidats imputés</li>
		</ul>
	</div>
	<div style='float: left; border: 1px solid #777;; margin-right: 2px; padding: 2px 2px 0 2px;'>
		<strong>Exports</strong>
		<ul>
		<li>Candidatures</li>
		<li>imputations</li>
		</ul>
	</div>
	<div style='float: left; border: 1px solid #777;; margin-right: 2px; padding: 2px 2px 0 2px;'>
		<strong>Recherche</strong>
		<ul>
		<li>Candidats</li>
		<li>Imputations</li>
		<li>Anciens</li>
		</ul>
	</div>
	<div style='float: left; border: 1px solid #777;; margin-right: 2px; padding: 2px 2px 0 2px;'>
		<strong></strong>
		<ul>
		<li></li>
		<li></li>
		</ul>
	</div>
	<div style='clear: both;'></div>
</div>





<?php
echo $end ;
?>


