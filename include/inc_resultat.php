<?php
function tab_resultats()
{
	$RESULTAT = array(
		"0" => "Inconnu",
		"1" => "Diplômé",
		"2" => "Ajourné",
	) ;
	return $RESULTAT ;
}

function tab_resultats_img_class()
{
	$RESULTAT_IMG_CLASS = array(
		"0" => "res_inconnu",
		"1" => "res_diplome",
		"2" => "res_ajourne",
	) ;
	return $RESULTAT_IMG_CLASS ;
}

function liste_resultats($nom, $selected, $empty=FALSE)
{
	$RESULTAT = tab_resultats() ;
	$RESULTAT_IMG_CLASS = tab_resultats_img_class() ;

	echo "<select name=\"$nom\">\n" ;
	if ( $empty ) {
		echo "<option value=''></option>\n" ;
	}
	while ( list($key, $val) = each($RESULTAT) )
	{
		echo "<option value='$key' " ;
		echo "class='".$RESULTAT_IMG_CLASS[$key]."'" ;
		if ( strval($key) === strval($selected) ) {
			echo " selected='selected'" ;
		}
		echo ">$val</option>\n" ;
	}
	echo "</select>" ;
}
?>
