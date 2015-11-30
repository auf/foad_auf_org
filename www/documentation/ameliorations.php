<?php
include("inc_session.php") ;

include("inc_html.php") ;
$titre = "Idées d'amélioration (priorités ?)" ;
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

<h3>Améliorations fonctionnelles</h3>

<ul>

<li>Améliorer les contrôles des noms et date de naissance dans les
	formulaires de candidature :
	<ul>
	<li>Utiliser la "signature" en bas des formulaires de candidature
		pour faire un contrôle d'égalité des noms et de la date de
		naissance.</li>
	<li>Eliminer systématiquement tous les caractères spéciaux, pas
		seulement les . _ / mais aussi les ; ce qui pose un problèmes
		pour les entités présentes dans quelques noms asiatiques.</li>
	<li>Message demandant la sélection du premier jour du mois de naissance
		ou du premier janvier de l'année de naissance pour ceux qui
		ne connaissent pas précisément leur date de naissance&nbsp;?</li>
	<li>Imposer que le noms de jeune fille soit différent
		du nom&nbsp;? ... ne contienne ni le nom ni le prénom.</li>
	<li>Ajouter dans les formulaires de candidature, une consigne
		« (sans aucune abbréviation) » pour les champs nom&nbsp;?</li>
	</ul>
</li>

<li>Ajouter « Pays de naissance » (aux formulaires de candidatures et
	à l'annuaire).</li>

<li>
	Stocker pays, bureaux, et leur association dans la base de données,
	ce qui permettrait notamment  de généraliser un filtre par bureau&nbsp;:
	<ul>
	<li>Utiliser les données de SQI (avec une page qui récapitule
		les pays de chaque bureau, mais pas d'interface d'édition).</li>
	<li>Traiter les DOM TOM de manière cohérente :
		Mayotte n'est pas un pays.
		(Les autres DOM TOM ne font partie de la liste des pays.)</li>
	<li>Nettoyage pour 2004 et 2005, et probablement quelques autres
		ajustement à la liste des pays utilisés.</li>
	</ul>
</li>

<li>Pagination dans la gestion des candidatures, et ajout d'un critère
	nombre de candidatures par page.<br />
	NB : à défaut, la recherche, dont les résultats sont paginés, permet
	aussi de gérer les candidatures.</li>

<li>Amélioration de la gestion des pièces jointes&nbsp;:
	<ul>
	<li>gestion du type MIME, n'accepter que certains formats.
		Les candidats font n'mporte quoi, il leur faut des consignes
		et des interdictions : pas d'exécutables Windows, pas d'archives,
		pas fichiers tiff, plutôt des images PNG...<br />
		PNG, JPEG et PDF seulement ?</li>
	<li>ajouter un champ titre (et éventuellement une description) pour
	éviter aux candidats de donner des noms à rallonge à leurs fichiers.</li>
	</ul>
</li>

<li>Permettre un tri dans la page Responsables.</li>

<li>Nettoyage dans les anciennes candidatures
	<ul>
	<li>Supprimer les doublons dans les candidatures 2004 et 2005.</li>
	<li>Récupérer les candidatures importées de 2005 au format des candidatures
		actuelles ?</li>
	<li>Récupérer les candidats allocataires de 2004 et 2005,
		(sans leur imputations ?)</li>
	</ul>
</li>

<li>Scripts de nettoyage des données inutiles des années passées, et
	qui permettraient d'alléger la base de données ou d'économiser
	de l'espace disque.
	<ul>
	<li>Pièces jointes (quelques Go).</li>
	<li>Dates d'examens (allègerait l'affichage des anciennes promotions).</li>
	<li>Même s'il faut conserver toutes les données utiles
		(servant à générer des statistiques,
		affichées ailleurs que dans les dossiers complets,
		toutes les adresses électroniques, ...)
		la suppression des autres données accélérerait certains traitements
		du fait de la réduction de l'espace disque utilisé par la base
		de données (quelques centaines de Mo).</li>
	</ul>
	NB : les problématiques liées aux lois comme la loi informatique et libertés
	française ne se sont pas posées jusqu'à présent (sauf avant 2006 du fait
	de l'indexation par Google, impossible depuis 2006).
</li>

<li>Recherche de sélections multiples pour deux années consécutives
	(pas seulement pour une ou toutes).<br />
	Ou bien sélection multiples sur toutes les années, mais en affichant
	seulement les candidats pour lesquels il y a une sélection pour
	une certaine année (actuellement l'année courante est tout de même
	signalée par du gras).</li>

<li>Filtre par année pour la messagerie, qui doit toujours être accessible
	en consultation, mais pas forcément pour l'envoi de messages.
	Ou modifier le fonctionnement d'ouverture/fermeture pour les
	promotions.</li>

</ul>



<h3>Améliorations uniquement techniques</h3>

<ul>
<li>Remplacer phpmailer par swiftmailer.</li>
</ul>








<h3>Idées abandonnées</h3>

<ul>
<li>Améliorer la gestion des candidatures lorsqu'il y a plusieurs
	responsables pour une même promotion&nbsp;:
	<br />Ajouter une liste déroulante pour filtrer sur l'état du
	dossier spécifique au responsable identifié, aussi bien pour
	l'affichage que pour les actions.</li>

</ul>



<?php
echo $end ;
?>


