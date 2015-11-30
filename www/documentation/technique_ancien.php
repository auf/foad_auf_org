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
echo $titre;
echo $fin_chemin ;
?>


<h2>Base de données</h2>

<div style='text-align: center; margin-top: 1em;'>
<img src='foad_ancien.png' width='700' height='505' alt='foad' title='foad' />
</div>

<p>Les tables <code>dossier</code> et <code>candidat</code> ne devraient pas
être disjointes, mais elles l'étaient, et les fusionner aurait nécessité trop
de travail. Pour les candidatures de l'année 2007-2008, on pourra utiliser
une même valeur de clé primaire dans les deux tables.</p>

<p>Les noms des tables et des champs et l'ordre des champs ne sont pas
toujours pertinents, mais les modifier nécessiterait trop de travail.</p>

<p>La gestion des questions est à revoir.</p>


<h2>Code et configuration</h2>

<p>Tout fonctionne avec <code>register_globals</code> à <code>Off</code>.
La migration sur <code>programmes.refer.org</code> est donc possible.</p>

<p><code>magic_quotes_gpc</code> doit être à <code>On</code> et
<code>magic_quotes_runtime</code> à <code>Off</code>.
C'est indispensable pour qu'une majorité (mais pas la totalité) du code
fonctionne correctement.</p>


<h2>A faire</h2>

<ul>
<li>Migration sur <code>programmes.refer.org</code> et transformation
	de <code>http://www.foad.refer.org</code>
	en <code>https://foad.refer.org</code>.</li>
<li><strong>Base de données :</strong>
	<ul>
	<li>Supprimer les données non liées aux autres
		(candidat sans dossier&nbsp;; dossier sans candidat&nbsp;;
		stages, diplômes et réponses aux questions sans candidat&nbsp;;
		commentaire sans responsable&nbsp;; ... non trivial).</li>
	<li>Revoir la gestion des questions
		(remplacer <code>mod_dossier</code> et <code>question</code> par
		une seule table, probablement liée aux promotions (<code>session</code>)
		plutôt qu'aux formations (<code>atelier</code>)...</li>
	<li>Faire une sauvegarde des tables inutiles (et non vides) avant de
		les supprimer.</li>
	<li>Supprimer les champs inutiles de la table <code>candidat</code></li>
	</ul>
</li>
<li><strong>Code :</strong>
	<ul>
	<li>Refaire la gestion des questions et des réponses.</li>
	<li>Améliorer le formulaire de candidature&nbsp;:
		<ul>
		<li>Refaire la gestion des fichiers joints</li>
		<li>Affichage du dossier de candidature
			(identique à celui des responsables,
			éventuellement avec les autre candidatures)
			avant signature du dossier.</li>
		<li>Faire une vérification plus poussée de l'adresse électronique
			de contact des candidats (ajouter une interrogation du DNS à
			l'expression régulière).
			Eventuellement, demander confirmation du candidat une fois
			qu'il aura rempli un préformulaire avec son adresse électronique.</li>
		</ul>
	</li>
	<li>Robustesse vis à vis des données manquantes (anciens dossiers
		de candidature).</li>
	<li>Harmoniser les connexions à la base de données.</li>
	<li>Supprimer les répertoires vides (ne contenant pas de fichiers joints
		à une candidature)<br />
		(et supprimer les anciens fichiers d'une année sur l'autre ?)</li>
	<li>Factoriser certaines fonctions relatives à l'affichage des formulaires.</li>
	</ul>
</li>
</ul>





<?php
echo $end ;
?>


