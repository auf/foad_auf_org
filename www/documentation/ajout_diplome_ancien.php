<?php
include("inc_session.php") ;

include("inc_html.php") ;
$titre = "Ajouter un diplôme/ancien" ;
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


<p>L'ajout d'un individu à l'annuaire des anciens se fait en associant un
diplôme à une candidature de cet individu,<br />
 en cliquant sur un lien
«&nbsp;<?php echo LIEN_DIPLOMER ; ?>&nbsp;» à droite des résultats de la
<strong><a href='/recherche/'>recherche</a></strong>.</p>

<p>Un même individu peut être diplômé plusieurs fois, pour des diplômes
correspondants à des candidatures à des formations différentes.<br />
Le système est censé détecter ces cas (à partir soit de l'adresse électronique,
soit du nom et de la date de naissance)
afin qu'un même individu figure dans l'annuaire des anciens une seule fois
avec tous ses diplômes plutôt qu'autant de fois qu'il a de diplômes.</p>

<!--
<p>
<div style='margin: 0 auto; text-align: center; font-size: smaller; font-weight: bold;'>
Exemple d'ajout d'un diplôme qui ne doit pas donner lieu à l'ajout d'un ancien,<br />
mais à l'ajout d'un diplôme à un ancien existant
</div>
<div style='margin: 0 auto; text-align: center; font-size: smaller;'>
Après avoir comparé les informations des deux encadrés, il faut cliquer sur le second bouton.
</div>
<div style='margin: 0 auto; text-align: center;'>
<img style='border: 1px solid #777;' src='KAMA.png' width='669' height='400' />
</div>
</p>
-->

<p>Sont donc présentés, dans l'ordre :</p>

<ol>
<li>Ajout du diplôme à l'ancien ancien existant qui a la même adresse électronique.</li>
<li>Ajout du diplôme à l'ancien ancien existant qui n'a pas la même adresse électronique,
	mais un nom et une date de naissance identiques.</li>
<li>Ajout d'un nouvel ancien.
	Possible seulement s'il n'en existe pas déjà un avec la même adresse électronique, mais on peut passer outre.</li>
</ol>


<!--
<p><div style='margin: 0 auto; text-align: center; font-weight: bold;'>
Exemple : cas 1
</div>
<div style='margin: 0 auto; text-align: center; font-size: smaller;'>
</div>
<div style='margin: 0 auto; text-align: center;'>
<img style='border: 1px solid #777;' src='cas1e.png' width='641' height='523' />
</div></p>

<p><div style='margin: 0 auto; text-align: center; font-weight: bold;'>
Exemple : cas 2 et 3 (sans illustration du fait que l'on peut passer outre).
</div>
<div style='margin: 0 auto; text-align: center; font-size: smaller;'>
</div>
<div style='margin: 0 auto; text-align: center;'>
<img style='border: 1px solid #777;' src='cas1nn.png' width='580' height='709' />
</div></p>
-->

<p><div style='margin: 0 auto; text-align: center; font-weight: bold;'>
Exemple 1 : il existe un ancien avec la même adresse électronique, on ne peut pas créer un nouvel ancien, mais on peut passer outre.
</div>
<div style='margin: 0 auto; text-align: center; font-size: smaller;'>
</div>
<div style='margin: 0 auto; text-align: center;'>
<img style='border: 1px solid #777;' src='cas1eo.png' width='658' height='456' />
</div></p>

<p><div style='margin: 0 auto; text-align: center; font-weight: bold;'>
Exemple 1 bis : il existe un ancien avec la même adresse électronique, mais on a cliqué sur le lien pour passer outre et créer un nouvel ancien.
</div>
<div style='margin: 0 auto; text-align: center; font-size: smaller;'>
</div>
<div style='margin: 0 auto; text-align: center;'>
<img style='border: 1px solid #777;' src='cas1eoo.png' width='658' height='612' />
</div></p>

<p><div style='margin: 0 auto; text-align: center; font-weight: bold;'>
Exemple 2 : Il existe un ancien avec le même nom et la même date de naissance.
</div>
<div style='margin: 0 auto; text-align: center; font-size: smaller;'>
</div>
<div style='margin: 0 auto; text-align: center;'>
<img style='border: 1px solid #777;' src='cas2nd.png' width='658' height='587' />
</div></p>





<p>Lorsqu'un diplôme est ajouté à un ancien existant (qui a déjà un autre
diplôme puisque c'est déjà un ancien), sa fiche est mise à jour avec les
informations contenues dans le dossier de candidature correspondant au
nouveau diplôme si la date de mise à jour de ce dossier est postérieure
à la date de la mise à jour de la fiche.</p>



<p>La <strong>date de mise à jour</strong> de la fiche d'un ancien
(« Informations mises à jour le ... ») est donc la date la plus récente
entre la date de mise à jour des dossiers de candidature correspondant
à ses diplômes

<?php
echo $end ;
?>


