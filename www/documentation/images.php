<?php
include("inc_session.php") ;

include("inc_html.php") ;
$titre = "Images (texte vertical)" ;
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

<style type="text/css" media="all">
/*
#titreVertical {
	-moz-transform:rotate(90deg);
	rotation: 180deg;
	
	transform: rotate(-90deg);
	transform-origin: left top 0;
	transform-origin: left bottom 0;
	transform-origin: right top 0;
	transform-origin: right middle 0;
}
#titreVertical div {
	font-size: 1.3em;
	font-weight: bold;
	font-family: 'Liberation Sans narrow', sans-serif;
}
	background: #999;
	color: #fff;
*/
table th {
	font-family: 'Liberation Sans narrow', sans-serif;
	font-family: arial, 'Liberation Sans narrow', sans-serif;
	font-family: 'Bitstream Vera sans', 'arial narrow', arial, 'Liberation Sans narrow', sans-serif;
	font-family: 'arial narrow', arial, 'Liberation Sans narrow', sans-serif;
	font-family: 'Bitstream Vera Sans Bold', 'arial narrow', arial, 'Liberation Sans narrow', sans-serif;
	font-family: arial, 'Liberation Sans narrow', sans-serif;
	font-size: 1.4em;
	font-size: larger;
	font-size: 1em;
	font-size: 1.3em;
	font-size: 1.1em;
	text-align: center;
	vertical-align: middle;
	font-weight: bold;
	padding: 1px 3px;
	word-spacing: 5px;
}
table#v th {
	transform: rotate(-90deg);
}
</style>

<div id='titreVertical'>
<div>Sélectionneurs</div>
<div>Bourses</div>
<div>AUF</div>
<div>Responsables</div>
<div>Gestionnaires AUF</div>
<div>Administrateur</div>
</div>

<table>
<tr>
<th>Responsables</th>
<th>Gestionnaires AUF</th>
<th>Administrateur</th>
<th>Payant Nord</th>
</tr>
</table>

<br /><br /><br /><br /><br />

<table id="v">
<tr>
<th>Responsables</th>
<th>Gestionnaires AUF</th>
<th>Administrateur</th>
<th>Payant Nord</th>
</tr>
</table>


<?php
echo $end ;
?>


