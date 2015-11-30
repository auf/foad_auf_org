<?php
include("inc_session.php") ;

include("inc_html.php") ;
$titre = "Documentation technique" ;
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



<h2>Base de données</h2>

<div style='float: right; margin-left: 1em;'>
<img src='foad2014.png' width='966' height='950' alt='foad' title='foad' />
</div>

<ul>
<li>La structure et le nommage ne sont pas particulièrement cohérents
	pour des raisons historiques...</li>
<li>A partir des candidatures 2007, <code>id_dossier</code> est égal à
	<code>id_candidat</code>.<br />
	(Les défauts d'intégrité ont été corrigés au prix de la suppression
	de quelques données antérieures à 2006.)</li>
</ul>

<?php
echo $end ;
?>


