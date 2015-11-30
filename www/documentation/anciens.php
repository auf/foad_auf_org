<?php
include("inc_session.php") ;

include("inc_html.php") ;
$titre = "Réflexions et questions à propos de l'annuaire des anciens" ;
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

<h3>Objectifs ?</h3>

<ul>
<li>Publication des résultats, transparence.</li>
<li>Statistiques
	<ul>
	<li>Nombre d'inscrits, nombre de diplomés,
		pourcentage de diplomés (par rapport aux inscrits).
		<ul>
		<li>Tous, allocataires, payants.</li>
		<li>Globales, par groupe de formations, par formation.</li>
		</ul>
	</li>
	<li>Autre chose ?</li>
	</ul>
</li>
<li>Favoriser la constitution de réseau(x) d'anciens ?<br />
	Il faudrait pour cela ajouter la ville au pays de résidence,
	et vraissemblablement enrichir encore la fiche de chaque ancien.</li>
<li>?</li>
</ul>

<p>NB : il existe des diplômés parmi les non imputés.
(Comment ont-ils pu bénéficier de la messagerie ??
Comment ont-ils payés ??)<br />
Il existe donc vraissemblablement aussi des non diplômés, mais inscrits,
parmi les non imputés (mais qui auraient dû l'être).<br />
Il n'est sans doute pas envisageable de rajouter des imputations après coup,
ce qui veut dire que le nombre d'inscrits ne serait pas calculé à partir du
nombre d'imputés, mais à partir du nombre d'allocataires et de payants.</p>


<h3>Annuaire public/privé, année d'obtention</h3>

<p>Les informations affichés par la recherche d'anciens sont légèrement
différentes dans la plateforme, et sur le site SPIP.</p>

<p>Annuaire privé, via la plateforme :</p>

<ul>
<li>Les anciens diplomés de la formation de test sont affichés
	(pour administrateur, service des bourses, CNF et SCAC&nbsp;;
	les responsable ne voyant que les diplomés de leurs formations).</li>
<li>Pour chaque diplôme, sont affichés, en plus de l'intitulé de la formation,
	l'année et l'intitulé de la promotion.</li>
</ul>

<p>Annuaire public, présenté sur le site SPIP :</p>

<ul>
<li>Les anciens diplomés de la formation de test sont exclus de l'annuaire
présenté sur le site SPIP.</li>
<li>Pour chaque diplôme, est affichée, en plus de l'intitulé de la formation,
	l'université diplômante, et l'année d'obtention,
	c'est à dire l'année de la promotion&nbsp;+&nbsp;1.</li>
</ul>


<h3>Edition des informations personnelles d'un ancien</h3>

<p>L'annuaire est consultable sur le site SPIP, mais doit aussi permettre
aux anciens de s'identifier pour mettre à jour leurs informations personnelles,
et/ou consulter les informations personnelles des autres anciens.</p>

<p>L'identité de l'ancien
(civilité, nom, prénom, nom de jeune fille, date de naissance)
n'a à priori des raisons légitimes de changer que pour les femmes
(est-ce généralisable à tous les pays ?).
Par ailleurs, permettre à un ancien de modifier son identité pourrait
être problématique dans la mesure où l'AUF pourrait certifier à son insue
un faux diplomé.</p>

<p>Interdire l'édition des informations personnelles n'étant pas une option,
il faudrait que la plateforme intégre un dispositif permettant de vérifier
la concordance entre identité d'un ancien, et identité dans la candidature
correspondante.</p>


<h3>Nom de domaine et HTTPS</h3>

<p>L'usage du même nom de domaine, mais de HTTP ou HTTPS pour le site
SPIP ou la plateforme est (un peu, en théorie plus qu'en pratique)
problématique :</p>

<p>Pourquoi les informations données lors des candidatures seraient-elles
cryptées (HTTPS), et pas les informations mises à jour dans l'annuaire ?</p>

<p>(Le préremplissage d'un formulaire de candidature (sur la plateforme)
après identification dans l'annuaire (sur le site SPIP) est possible.)</p>

<p></p>

<h3>Courriel(s) aux anciens</h3>

<p>L'ajout d'un individu à l'annuaire des anciens doit déclencher l'envoi
d'un courriel contenant l'identifiant (adresse électronique) et le mot de
passe permettant à cet individu de se connecter à l'annuaire.</p>

<p>L'envoi de ce courriel n'est pas immédiat,
mais groupé pour deux raisons&nbsp;:</p>

<ul>
<li>Une fois un mail envoyé aux anciens, il devrait ne plus
	être possible de leur enlever un diplôme (sauf éventuellement s'ils en ont
	plusieurs) et donc de les éliminer de l'annuaire.
	Il est préférable que l'attribution d'un diplôme soit réversible,
	au moins le temps de que tous les diplômes de la même promotions
	soient attribués.</li>
<li>Permet que les premiers anciens ne soient pas prévenus avant que leurs
	camarades de promotion diplomés n'y figurent aussi.</li>
</ul>

<p>Il convient de grouper l'envoi de ces courriels, au moins par promotion,
ou par année.</p>

<p>Quid des anciens qui obtiennent un autre diplôme :
faut-il leut envoyer systématiquement un nouveau courriel,
ou seulement lorsque la candidature associée au nouveau diplôme
contient des informations postérieures à la dernière modification&nbsp;?<br />
(Ce n'est actuellement pas prévu techniquement, mais il suffirait
d'ajouter un champ à la table <code>dossier_anciens</code>.)</p>


<?php
echo $end ;
?>


