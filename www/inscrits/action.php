<?php
include("inc_session.php") ;
require_once("inc_mysqli.php") ;

/*
echo "<pre>" ;
print_r($_POST) ;
echo "</pre>" ;
*/

//
// Changement de résultat
//
if ( $_POST["changement"] == "OK" )
{
	// Aucun résultat à changer
	// Le tableau de chexboxes s'appelle imprimer, raison historique
	if ( count($_POST["imprimer"]) == 0 )
	{
		header("Location: /inscrits/inscrits.php?id_session="
			.$_POST["promotion"]."&erreur=zero") ;
	}
	else
	{
		$cnx = connecter() ;

		$liste_imp = "" ;
		foreach($_POST["imprimer"] as $imp) {
			$liste_imp .= $imp . ", " ;
		}
		$liste_imp = substr($liste_imp, 0, -2) ;

		// Historique
		// Parmi les etats a modifier, quels sont ceux qui sont differents ?
		$req = "SELECT id_dossier FROM dossier
			WHERE resultat!='".$_POST["nouveau_resultat"]."'
			AND id_dossier IN (".$liste_imp.")" ;
		$res = mysqli_query($cnx, $req) ;

		$tab_hist = array() ;		
		while ( $enr = mysqli_fetch_assoc($res) ) {
			$tab_hist[] = $enr["id_dossier"] ;
		}
		require_once("inc_historique_resultat.php") ;
		foreach ($tab_hist as $id)
		{
			historiqueResultatAdd($cnx, $id, $_POST["nouveau_resultat"]) ;
		}


		$req = "UPDATE dossier SET resultat='".$_POST["nouveau_resultat"]."',
			date_maj_resultat=CURDATE()
			WHERE id_dossier IN (".$liste_imp.")
			AND resultat!='".$_POST["nouveau_resultat"]."'
			AND id_session=".$_POST["promotion"] ;
		mysqli_query($cnx, $req) ;
		deconnecter($cnx) ;
		header("Location: /inscrits/inscrits.php?id_session="
			. $_POST["promotion"] ) ;
	}
}
// Erreur
else {
	if ( !empty($_POST["promotion"]) ) {
		header("Location: /inscrits/inscrits.php?id_session="
			. $_POST["promotion"] ) ;
	}
}

/*
*/
?>
