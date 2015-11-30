<table class='formulaire'>
<tr>
	<th><?php libelle("promotion") ?></th>
	<td style='width: 50em;'><?php
		$formPromo = chaine_liste_promotions("promotion",
			( isset($T["promotion"]) ? $T["promotion"] : "" ),
			"", $cnx) ;
		echo  $formPromo["form"] ;
		echo $formPromo["script"] ;
	?></td>
<?php /*
</tr><tr>
	<th><label>État :</label></th>
	<td><span class='payant'>Payant</span></td>
*/ ?>
</tr><tr>
	<td colspan='2' style='height: 1px; background: #777; padding: 1px;'></td>
</tr><tr>
	<th><?php libelle("civilite") ?></th>
	<td><?php radio($tab_civilite, "civilite",
		( isset($T["civilite"]) ? $T["civilite"] : "" )
		) ; ?></td>
</tr><tr>
	<th><?php libelle("nom") ; ?></th>
	<td><?php inputtxt("nom",
		( isset($T["nom"]) ? $T["nom"] : "" ),
		30, 50) ; ?></td>
</tr><tr>
	<th><?php libelle("nom_jf") ; ?></th>
	<td><?php inputtxt("nom_jf",
		( isset($T["nom_jf"]) ? $T["nom_jf"] : "" ),
		30, 50) ; ?></td>
</tr><tr>
	<th><?php libelle("prenom") ; ?></th>
	<td><?php inputtxt("prenom",
		( isset($T["prenom"]) ? $T["prenom"] : "" ),
		40, 100) ; ?></td>
</tr><tr>
	<th><?php libelle("naissance") ; ?></th>
	<td><?php liste_der1($jour, "jour_n",
			( isset($T["jour_n"]) ? $T["jour_n"] : "" )
			) ;
		echo " / " ;
		liste_der2($mois, "mois_n",
			( isset($T["mois_n"]) ? $T["mois_n"] : "" )
			) ;
		echo " / " ;
		liste_der1($annee_nai, "annee_n",
			( isset($T["annee_n"]) ? $T["annee_n"] : "" )
			) ;
		?></td>
</tr><tr>
	<th><?php libelle("nationalite") ; ?></th>
	<td><?php inputtxt("nationalite",
		( isset($T["nationalite"]) ? $T["nationalite"] : "" ),
		30, 50); ?></td>
</tr><tr>
	<th><?php libelle("pays") ; ?></th>
	<td><?php liste_der1($PAYS, "pays",
		( isset($T["pays"]) ? $T["pays"] : "" )
		); ?></td>
</tr><tr>
	<th><?php libelle("courriel") ; ?></th>
	<td><?php inputtxt("courriel",
		( isset($T["courriel"]) ? $T["courriel"] : "" ),
		50, 100); ?></td>
</tr><tr>
	<th><?php libelle("profession") ; ?></th>
	<td><?php textarea("profession",
		( isset($T["profession"]) ? $T["profession"] : "" ),
		80, 1); ?></td>
</tr><tr>
    <th><?php libelle("employeur") ; ?></th>
    <td><?php textarea("employeur",
		( isset($T["employeur"]) ? $T["employeur"] : "" ),
		80, 1); ?></td>
</tr>
<?php
if ( $erreurDoublon ) {
	echo "<tr><td colspan='2'>\n" ;
	echo "<label><input type='checkbox' name='confirmation' value='confirmation'" ;
	if ( $T["confirmation"] == "confirmation" ) {
		echo " checked='checked'" ;
	}
	echo "/> Confirmer l'ajout de cette nouvelle candidature</label>\n" ;
	echo "</td></tr>\n" ;
}
?>
</table>

<p class='c'><input type='submit' value='Ajouter' /></p>
