<div id="navigation">

<div id="identification">
<div style="float: right"><a href="/logout.php">Déconnexion</a></div>
<div style="text-align: left"><?php
	echo $_SESSION["utilisateur"] ;
	?>
<a id='haut' accesskey='H'></a>
</div>
</div>

<?php
// Administrateur
if ( $_SESSION["id"] == "00" )
{
	//<a href="/responsables/index.php" title="Responsables de formations">Responsables</a> |
	?>
	<a href="/institutions/index.php">Institutions</a> |
	<a href="/formations/index.php">Formations</a> |
	<a href="/promotions/index.php">Promotions</a> |
	<a href="/questions/index.php">Questions</a> |
	<a href="/responsables/index.php">Sélectionneurs</a> |
	<a href="/individus/index.php">Individus <span>(CNF)</span></a> |
	<a href="/documentation/index.php">Documentation</a>
	<br />
	<a href="/recherche/">Recherche</a> |
	<a href="/candidature_en_ligne.php">Candidature en ligne</a> |
	<a href="/statistiques/">Statistiques</a> |
	<a href="/candidatures/index.php">Gestion des candidatures</a>
	| <a href="/exports/index.php">Exports <span>(tableur)</span></a>
	| <a href="/messagerie/index.php">Messagerie <span>(candidats)</span></a>
	| <a href="/imputations/statistiques.php">Imputations</a>
	| <a href="/inscrits/index.php">Résultat des inscrits</a>
	| <a href="/examens/">Examens</a>
	| <a href="/agendaCNF/" title="Agenda des CNF">Agenda</a>
	| <a href="/messagerie_cnf/index.php">Messagerie <span>(CNF)</span></a>
	<?php
	/*
	| <a href="/anciens/">Anciens</a>
	*/
}
// Service des bourses
else if ( $_SESSION["id"] == "01" )
{
	?>
	<a href="/recherche/">Recherche</a> |
	<a href="/candidature_en_ligne.php">Candidature en ligne</a> |
	<a href="/statistiques/">Statistiques</a> |
	<a href="/candidatures/index.php">Gestion des candidatures</a>
	| <a href="/exports/index.php">Exports <span>(tableur)</span></a>
	| <a href="/messagerie/index.php">Messagerie <span>(candidats)</span></a>
	| <a href="/imputations/statistiques.php">Imputations</a>
	| <a href="/inscrits/index.php">Résultat des inscrits</a>
	| <a href="/examens/">Examens</a>
	| <a href="/agendaCNF/" title="Agenda des CNF">Agenda</a>
	| <a href="/messagerie_cnf/index.php">Messagerie <span>(CNF)</span></a>
	<?php
	/*
	| <a href="/anciens/">Anciens</a>
	*/
}
// CNF
else if ( $_SESSION["id"] == "02")
{
	?>
	<a href="/recherche/">Recherche</a> |
	<a href="/candidature_en_ligne.php">Candidature en ligne</a> |
	<a href="/statistiques/">Statistiques</a> |
	<a href="/candidatures/index.php">Gestion des candidatures</a> 
	| <a href="/exports/index.php">Exports <span>(tableur)</span></a>
	| <a href="/imputations/statistiques.php">Imputations</a>
	| <a href="/examens/">Examens</a>
	| <a href="/agendaCNF/" title="Agenda des CNF">Agenda</a>
	| <a href="/messagerie_cnf/index.php">Messagerie <span>(CNF)</span></a>
	<?
	/*
	| <a href="/anciens/">Anciens</a>
	*/
}		   
// SCAC
else if ( $_SESSION["id"] == "03")
{
	?>
	<a href="/recherche/">Recherche</a> |
	<a href="/statistiques/">Statistiques</a> |
	<a href="/candidatures/index.php">Consultation des candidatures</a> 
	| <a href="/exports/index.php">Exports <span>(tableur)</span></a>
	<?
	/*
	*/
}
else
{ 
	?>
	<a href="/recherche/">Recherche</a> |
	<a href="/statistiques/">Statistiques</a>
	| <a href="/candidatures/index.php">Gestion des candidatures</a> 
	| <a href="/exports/index.php">Exports <span>(tableur)</span></a>
	| <a href="/messagerie/index.php">Messagerie</a>
	| <a href="/imputations/statistiques.php">Imputations</a>
	| <a href="/inscrits/index.php">Résultat des inscrits</a>
	| <a href="/examens/">Examens</a>
	| <a href="/agendaCNF/" title="Agenda des CNF">Agenda</a>
	<?
	/*
	| <a href="/anciens/">Anciens</a>
	*/
}
?>
</div>
