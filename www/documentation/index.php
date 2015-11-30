<?php
include_once("inc_session.php") ;

require_once("inc_html.php");
$titre = "Documentation" ;
echo $dtd1 ;
echo "<title>".$titre."</title>" ;
echo $dtd2 ;
require_once("inc_menu.php");
echo $debut_chemin ;
echo "<a href='/bienvenue.php'>Accueil</a>" ;
echo " <span class='arr'>&rarr;</span> " ;
echo $titre ;
echo $fin_chemin ;

echo "<h2>$titre</h2>\n" ;
?>


<div class='fright'>
<h3>Questions, réflexions</h3>
<ul>
<li><a href='anciens.php'>Réflexions et questions à propos de l'annuaire des anciens</a></li>
<li><a href='Boulerie_Pascal_ENSG.php'>Suggestions de Pascal Boulerie (ENSG)</a></li>
<li><a href='ameliorations.php'>Idées d'améliorations (priorités ?)</a></li>
<li><a href='navigation.php'>Quid de la navigation ?</a></li>
</ul>
</div>


<h3>Ébauche de documentation sur le fonctionnement de la plateforme</h3>
<ul>
<li><a href='utilisateur.php'>Documentation pour utilisateurs</a></li>
<li><a href='ajout_diplome_ancien.php'>Ajouter un diplôme/ancien</a></li>
<li><a href='imputations.php'>Imputations en 1<sup>ère</sup> et 2<sup>ème</sup> année</a></li>
</ul>

<h3>Ébauche de documentation technique</h3>

<ul>
<li><a href='technique_general.php'>Généralités, configuration</a></li>
<li><a href='technique_bdd.php'>Base de données</a></li>
<li><a href='technique_session.php'>Sessions</a></li>
</ul>


<?php
echo $end ;
?>	
