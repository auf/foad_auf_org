<?php
include("inc_session.php") ;

include("inc_html.php") ;
$titre = "Suggestions de Pascal Boulerie (ENSG)" ;
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

<style type="text/css">
hr {
	clear: both;
}
.reponse {
	float: right;
	width: 30%;
	white-space: normal;
	margin-top: 1em;
	font-family: sans-serif;
}
.fait {
	font-weight: bold;
	color: green;
}
.afaire {
	color: red;
}
</style>

<!--
<hr /><div class="reponse"></div><strong>
</strong>
-->

<p>+ Suggestion relative au site SPIP et à l'affichage de la date de publication/mise à jour d'un article, transmise le 04/03/2010 par l'AUF, réponse le 23/03/2010.</p>

<pre>

<hr /><div class="reponse">
Répondu le 26/10/2010
<div class="fait">Correction de 26/10/2010 des sélections multiples des responsables.</div>
<div class="afaire">La suggestion est intéressante pour les responsables consciencieux, mais nécessiterait probablement quelques options pour être pertinente (considérer les En attente et les Refusés...)</div>
</div><strong>
Mon, 25 Oct 2010 12:59:27 +0200
Tue, 26 Oct 2010 12:42:54 +0200
améliorer la requête Sélections multiples dans la Gestion des candidatures ?
</strong>
En plus, pour revenir à mon besoin initial *, j'aimerais aussi une requête
qui m'indiquerait que le candidat suivant, refusé en 2009 ici :
https://foad.refer.org/candidatures/autre.php?id_dossier=35865
a été sélectionné en 2010 et a payé sur un autre Master :
https://foad.refer.org/candidatures/autre.php?id_dossier=46456

* que je n'avais pas assez bien précisé : savoir si parmi tous les
candidats à la formation (tous : sélectionnés et aussi les non
sélectionnés - donc sans examiner la valeur du champ table1.etat dans la
requête), certains ont été sélectionnés depuis dans d'autres formations ?
(c'est une information intéressante, par exemple, pour proposer des
réorientations éventuelles à des candidats qui n'ont pas le profil requis
pour la formation, et qui pourront tenter leur chance ailleurs)

<hr /><div class="reponse">
<div class="afaire">Il y a déjà des statistiques par pays pour les candidats imputés, qui avec les statistiques par pays des candidats, permettraient de dégager rapidement des %ages de réussite, mais il ne me semble pas souhaitable de proposer explicitement un tel classement.</div>
</div><strong>
Fri, 08 Oct 2010 19:08:29 +0200
statistiques FOAD - efficacité relative de chaque pays dans les candidatures ?
</strong>
Dans les statistiques FOAD, vous pourriez rajouter un tri des candidatures
par pays en fonction du ratio de réussite :

- nombre de candidats acceptés / nombre total de dossiers
- puis en triant ensuite - au sein d'une même tranche - par le nombre de
candidats acceptés.

<hr /><div class="reponse">
<div class="afaire">Une telle automatisation des listes de candidats admis et en attente serait intéressante, mais pose la question de la restriction de l'accès à ces informations avant le résultat officiel des sélections.</div>
</div><strong>
Tue, 05 Oct 2010 18:18:50 +0200
améliorer la gestion de la liste d'attente FOAD ?
</strong>
Ayant suivi avec attention la préparation de la rentrée 2010 du Master IASIG
de l'université de Douala, et suite à plusieurs désistements en cascade, je
me suis aperçu qu'il serait possible d'améliorer la liste d'attente :

1) il faudrait fortement conseiller aux candidats de renseigner la 2e adresse
de courriel, en expliquant que leur 1e boîte peut être détournée par un pirate,
ou être fermée par leur FAI ou leur hébergeur, ou se retrouver inopinément en
panne, et leur empêcher ainsi de recevoir toutes les informations utiles pour
préparer leur dossier d'inscription. De plus, je me suis aperçu que certains
messages peuvent se perdre dans les dossiers de courriels indésirables, ou via
le réseau (des messages jamais parvenus à destination, et perdus quelque part
dans un élément du réseau, c'est possible, surtout en Afrique, avec les coupures
d'électricité et les pannes de disques durs)

2) il serait utile de publier - dans un souci de transparence publique et
d'information aux candidats - au fur et à mesure la liste mise à jour des
allocataires, en rappelant pour mémoire les noms des candidats désistés
2bis) accessoirement, de publier aussi la mise à jour des candidats restés
en liste d'attente, tout en indiquant le rang du dernier candidat en attente
déjà appelé en tant qu'allocataire suite à un désistement (il pourra être
intéressant de gérer aussi l'information sur les dates des désistements)

3) à chaque désistement, il serait bien de prévenir aussi les candidats
encore en liste d'attente après, qu'ils ont avancé d'1 rang dans la liste
d'attente (ceci afin de mieux préparer leur réponse éventuelle ultérieure,
au cas où un nouveau désistement les ferait passer allocataire).

<hr /><div class="reponse">
Réponse le 23/06/2010
</div><strong>
Wed, 16 Jun 2010 17:35:27 +0200
copie de vos codes sources PHP utilisés pour le site FOAD ?
</strong>
Si ce n'est pas trop abuser de ma part, est-ce que vous avez mis quelque
part, par exemple sur Sourceforge, les source PHP afin que des bénévoles
puissent vous aider à faire évoluer l'interface ?

<hr /><div class="reponse">
<div class="afaire">Améliorerait beaucoup la gestion des candidatures.</div>
<div class="fait">Fait (dans des bulles d'aide) le 19/11/2010</div>
</div><strong>
Fri, 11 Jun 2010 11:26:24 +0200
aperçu résumé de toutes les candidatures d'un seul coup d'oeil ?
</strong>
Dans la liste résumée ./candidatures/candidatures.php , il serait bon que
le commentateur puisse voir son propre commentaire d'Évaluation
à la fin de l'écran (vision synthétique), après la colonne "État".

Dans le cas où la personne n'aurait pas donné d'évaluation, il serait
intéressant de donner le nombre d'évaluations déjà données, et si celui
est égal à 1, de montrer cette unique évaluation.

PS A l'heure actuelle, mes collègues préfèrent faire un export et
travailler en dehors de l'interface, ce qui les oblige ensuite à
"rapatrier" leurs évaluations...

<hr /><div class="reponse">
<div class="afaire">Information non disponible. Il s'agirait plutôt de la date de dernière édition de l'état ou du commentaire d'un candidat, les deux étant faits en même temps. Nécessiterait l'ajout d'un datetime, et de l'id_session à comment_sel.</div>
</div><strong>
Date: Thu, 10 Jun 2010 15:12:53 +0200
trier les dossiers dans l'ordre de leur consultation ?
</strong>
Je pensais encore à une autre fonctionnalité : trier la liste par date
d'enregistrement du dernier commentaire ?

<hr /><div class="reponse">
<div class="afaire">L'idée me semble bonne, mais les Cas 1-3 peuvent aussi être utilisés pour cela.</div>
</div><strong>
04 Jun 2010 17:56:22 +0200
état Incomplet à une date donnée pour les dossiers de candidature FOAD ?
</strong>
A tout hasard, je trouve personnellement que j'aimerais avoir une valeur
d'état "Incomplet" pour pouvoir indiquer que j'ai consulté un dossier que
le candidat n'a pas rempli entièrement (par exemple, quand un candidat
indique "Lettre de motivation : à transmettre plus tard").

<hr /><div class="reponse">
<div class="fait">Fait le 23/06/2010</div>
</div><strong>
Fri, 04 Jun 2010 17:54:07 +0200
Warning: mysql_fetch_assoc() ?
</strong>
J'ai un message d'erreur / avertissement dans l'interface
https://foad.refer.org/recherche/
Warning: mysql_fetch_assoc(): supplied argument is not a valid MySQL result
resource in /var/www/foad.refer.org/ssl/www/recherche/index.php on line 626
et ensuite le bas de la page ne donne pas de résultats affichés.

<hr /><div class="reponse">
<div class="afaire">La nationalité et un champ texte libre, et peut être multiple. Rapport avec l'ajout d'un pays de naissance.</div>
</div><strong>
Thu, 03 Jun 2010 12:38:49 +0200
affichage et choix du champ Nationalité ?
</strong>
Je serais aussi intéressé d'avoir un affichage de la nationalité dans la
page résumé des candidatures.

(mon idée est que je m'intéresse à l'impact pour un pays donné de la
candidature de ses ressortissants, mais qu'un Togolais au Gabon n'aura pas
le même impact qu'un Gabonais du Gabon.

carte de l'indice du développement humain du PNUD :
http://upload.wikimedia.org/wikipedia/commons/d/d2/UN_Human_Development_Report_2009.PNG
le Togo - en zone "rouge" - est moins développé que le Gabon en zone "verte")

<hr /><div class="reponse">Répondu le 26/10/2010
<div class="fait">Fait partiellement</div>
<div class="afaire">L'âge à ajouter dans les dossiers de candidature et dans les exports.</div>
</div><strong>
Thu, 03 Jun 2010 11:46:15 +0200
limiter l'affichage selon des critères supplémentaires (Civilité, âge)
</strong>
Dans ./candidatures/candidatures.php
Limiter l'affichage :

je pense qu'il serait possible de rajouter le champ "Civilité", sachant que
l'AUF a une politique de genre pour privilégier les candidatures féminines.

De même, dans le tableau résumé, un calcul de l'âge pourrait être plus
utile à afficher que la date de naissance, d'autant que l'AUF utilise
ouvertement le critère de 35 ans pour donner priorité aux jeunes.

Et ce champ pourrait être utilisé pour limiter l'affichage pareillement
(et avec un affichage du texte en orange, quand l'âge est supérieur à 35
ans, et rouge quand l'âge est supérieur à 40 ans, d'autant que l'AUF fait
ce genre de statistiques, par exemple dans la page :
./statistiques/promotion.php ).

(C'est de la discrimination positive, ça. :-) )

PS Le critère de l'âge, c'est une vision utilitariste opposée à la morale de Kant. :-)
http://fr.wikipedia.org/wiki/Philosophie_morale_de_Kant#La_doctrine_de_la_bonne_volont.C3.A9
http://fr.wikipedia.org/wiki/Utilitarisme

<hr /><div class="reponse">
<div class="afaire">La liste des autres candidatures dans le dossier pourrait être améliorée.
Actuellement, sont en gras les promotions pour lesquelles les évaluations sont ouvertes, les promotions du responsable pourraient être surlignées...</div
</div><strong>
Wed, 02 Jun 2010 16:12:54 +0200
Mon, 14 Jun 2010 14:59:17 +0200
Wed, 16 Jun 2010 09:55:03 +0200
compter les autres candidatures précédentes au même diplôme
</strong>
Dans l'interface FOAD, puisque vous avez une requête qui cherche les
autres candidatures,
je pense que vous pourriez rajouter une 2e requête qui chercherait dans
ces autres candidatures celles concernant le même diplôme.

Leur compte donnerait le nombre de tentatives totales, une information
pouvant être donnée dans la page candidature.php
quand ce compte est supérieur à 1
voir aussi dans la page candidatures.php

Je pense que c'est une information qui peut être utile à tous les
utilisateurs appelés à sélectionner des dossiers de candidats
(j'ai une solution de contournement pour moi : j'exporte en CSV, j'importe
dans MySQL, j'ai ma propre requête, mais ça m'oblige à faire l'opération
qui n'est pas immédiate, et personne d'autre ne peut en profiter)

<hr /><div class="reponse">
<div class="afaire"></div>
</div><strong>
Tue, 01 Jun 2010 14:34:31 +0200
premier aperçu "résumé" d'une autre candidature
</strong>
Dans une page https://foad.refer.org/candidatures/autre.php?id_dossier=*
je trouve personnellement qu'il me serait plus rapide de voir la section
"Évaluations" juste après la section "État du dossier"
(cela permet en effet d'avoir directement un résumé du dossier, sans avoir
à appuyer sur la touche "Fin" du clavier)

Peut-être faut-il adopter la même présentation pour la page ./candidatures.php

PS Cela aurait d'ailleurs l'avantage de rendre inutile la manipulation
décrite en page d'accueil ./bienvenue.php : "Raccourcis clavier...

<hr /><div class="reponse">
<div class="fait">Fait le 31/05/2010</div>
</div><strong>
Sat, 29 May 2010 10:05:20 +0200
abréviation de Mademoiselle
</strong>
L'abréviation de Mademoiselle est "Mlle" et non "Melle", et sans point à la fin.
http://fr.wiktionary.org/wiki/Mlle

Pour "Mme" aussi sans point, contrairement à "M." avec un point.
</pre>


<?php
echo $end ;
?>

