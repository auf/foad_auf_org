<?php
$etat_dossier = array(
	"Non étudié",
	"Allocataire",
	"Payant",
	"Allocataire SCAC",
	"Payant établissement",
	"Payant Nord",
	"En attente",
	"Refusé",
	"Désisté",
	"Reclassé A",
	"A transférer",
	"Cas 1",
	"Cas 2",
	"Cas 3",
) ;
$etat_dossier_inscrit = array(
	"Allocataire",
	"Payant",
	"Allocataire SCAC",
	"Payant établissement",
	"Payant Nord",
	"Externe",
);

$etat_dossier_img_class = array(
	"Non étudié" => "nonetudie",
	"Allocataire" => "allocataire",
	"Payant" => "payant",
	"Allocataire SCAC" => "scac",
	"Payant établissement" => "payantetab",
	"Payant Nord" => "payantnord",
	"En attente" => "enattente",
	"Refusé" => "refuse",
	"Désisté" => "desiste",
	"Reclassé A" => "reclassea",
	"A transférer" => "atransferer",
	"Cas 1" => "cas1",
	"Cas 2" => "cas2",
	"Cas 3" => "cas3",

	"Externe" => "",
	"" => "",
) ;

$listeEtatsImputables = "'Allocataire', 'Payant', 'Allocataire SCAC'" ;
$nomsEtatsImputables = "Imputable (Allocataire, Payant ou Allocataire SCAC)" ;

//$listeEtatsInscrits = "'Allocataire', 'Payant', 'Allocataire SCAC', 'Payant établissement', 'Payant Nord', 'Externe'" ;
//$nomsEtatsInscrits = "Inscrit (Imputable imputé ou Payant établissement ou Payant Nord ou Externe)" ;
$listeEtatsInscrits = "'Allocataire', 'Payant', 'Allocataire SCAC', 'Payant établissement'" ;
$nomsEtatsInscrits = "Allocataire ou Payant ou Allocataire SCAC ou Payant établissement" ;

//$listeEtatsInscritsImpute = "'Allocataire', 'Payant', 'Allocataire SCAC'" ;
$listeEtatsInscritsAutre = "'Payant établissement', 'Payant Nord', 'Externe'" ;


function liste_etats_inscrit($nom, $selected, $empty=FALSE)
{
	global $etat_dossier_inscrit ;
	global $etat_dossier_img_class ;
	echo "<select name=\"$nom\">\n" ;
	if ( $empty ) {
		echo "<option value=''></option>\n" ;
	}

	foreach ($etat_dossier_inscrit as $etat)
	{
		echo "<option value='$etat' " ;
		echo "class='".$etat_dossier_img_class[$etat]."'" ;
		if ( $etat == $selected ) {
			echo " selected='selected'" ;
		}
		echo ">$etat</option>\n" ;
	}
	echo "</select>" ;
}

function liste_etats($nom, $selected, $empty=FALSE, $imputable=FALSE, $inscrit=FALSE)
{
	global $etat_dossier ;
	global $etat_dossier_img_class ;
	global $nomsEtatsImputables ;
	global $nomsEtatsInscrits ;
	echo "<select name=\"$nom\">\n" ;
	if ( $empty ) {
		echo "<option value=''></option>\n" ;
	}

	foreach ($etat_dossier as $etat)
	{
		echo "<option value='$etat' " ;
		echo "class='".$etat_dossier_img_class[$etat]."'" ;
		if ( $etat == $selected ) {
			echo " selected='selected'" ;
		}
		echo ">$etat</option>\n" ;
	}

	if ( $imputable ) {
		echo "<option value='imputable'" ;
		if ( $selected == 'imputable' ) {
			echo " selected='selected'" ;
		}
		echo ">".$nomsEtatsImputables."</option>\n" ;
	}

	if ( $inscrit ) {
		echo "<option value='inscrit'" ;
		if ( $selected == 'inscrit' ) {
			echo " selected='selected'" ;
		}
		echo ">".$nomsEtatsInscrits."</option>\n" ;
	}

	echo "</select>" ;
}


/*
$etat_dossier_abrege = array(
	"Non étudié" => "<span class='aide' title='Non étudié'>NE</span>",
	"Allocataire" => "<span class='aide' title='Allocataire'>A</span>",
	"Payant" => "<span class='aide' title='Payant'>P</span>",
	"En attente" => "<span class='aide' title='En attente'>EA</span>",
	"Refusé" => "<span class='aide' title='Refusé'>R</span>",
	"Désisté A" => "<span class='aide' title='Désisté A'>DA</span>",
	"Reclassé A" => "<span class='aide' title='Reclassé A'>DA</span>",
	"A transférer" => "<span class='aide' title='A transférer'>T</span>",
	"Cas 1" => "<span class='aide' title='Cas 1'>1</span>",
	"Cas 2" => "<span class='aide' title='Cas 2'>2</span>",
	"Cas 3" => "<span class='aide' title='Cas 3'>3</span>",
) ;
*/
?>
