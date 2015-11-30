<?php
include("inc_session.php") ;
require_once("inc_mysqli.php") ;

/*
echo "<pre>" ;
print_r($_POST) ;
echo "</pre>" ;
*/

//
// CLassement des candidatures en attente
//
if ( isset($_POST["classement"]) AND ($_POST["classement"] == "Enregistrer l'ordre de classement") )
{
	$cnx = connecter() ;

	while ( list($dossier, $classement) = each($_POST["ordre"]) )
	{
		$req = "UPDATE dossier SET " ;
		if ( intval($classement) == 0 ) {
			$req .= "classement=NULL WHERE id_dossier=$dossier" ;
		}
		else {
			$req .= "classement=$classement WHERE id_dossier=$dossier" ;
		}
//		echo $req . "<br />" ;
		mysqli_query($cnx, $req) ;
	}

	deconnecter($cnx) ;
	header("Location: /candidatures/candidatures.php?id_session="
		. $_POST["promotion"] ) ;
}
//
// Impression
//
else if ( isset($_POST["impression"]) AND ($_POST["impression"] == "Imprimer") )
{
	// Aucune candidature à imprimer
	if ( count($_POST["imprimer"]) == 0 )
	{
		header("Location: /candidatures/candidatures.php?id_session="
			.$_POST["promotion"]."&erreur=zero") ;
	}
	// Impression
	else
	{
		// Candidatures
		$i = 0 ;
		foreach($_POST["imprimer"] as $imp) {
			if ( $i == 0 ) {
				$liste_imp = "$imp" ;
			}
			else {
				$liste_imp .= ", $imp" ;
			}
			$i++ ;
		}
		
		include("inc_html.php") ;
		$titre = "Dossier de candidature" ;
		echo $dtd1 ;
		echo "<title>$titre</title>" ;
		echo $dtd2 ;
		include("inc_menu.php") ;
		
		$cnx = connecter() ;
		
		// Chemin
		$req = "SELECT intitule, intit_ses
			FROM atelier, session
		    WHERE atelier.id_atelier=session.id_atelier
		    AND session.id_session=".$_POST["promotion"] ;
		$res = mysqli_query($cnx, $req) ;
		$enr = mysqli_fetch_assoc($res) ;
		
		echo $debut_chemin ;
		echo "<a href='/bienvenue.php'>Accueil</a>" ;
		echo " <span class='arr'>&rarr;</span> " ;
		echo "<a href='index.php'>Gestion des candidatures</a>" ;
		echo " <span class='arr'>&rarr;</span> " ;
		echo "<a href='candidatures.php?id_session=".$_POST["promotion"]."'>".$enr["intitule"]." <span class='normal'>(".$enr["intit_ses"].")</span></a>" ;
		echo " <span class='arr'>&rarr;</span> " ;
		echo "Impression de $i dossiers de candidature le ".date("d/m/Y") ;
		if ( !empty($_SESSION["filtres"]["candidatures"]["etat"]) ) {
			echo " <span class='normal'>(".$_SESSION["filtres"]["candidatures"]["etat"].")</span>" ;
		}
		echo $fin_chemin ;
		
		
		$req = "SELECT * FROM candidat, dossier
			WHERE dossier.id_dossier IN ($liste_imp)
			AND dossier.id_candidat=candidat.id_candidat" ;
		$res = mysqli_query($cnx, $req) ;
	
		include("inc_formulaire_candidature.php") ;
		include("inc_date.php");
		include("inc_etat_dossier.php");
		include("inc_dossier.php");
	
		$i = 0 ;
		while( $T = mysqli_fetch_assoc($res) ) {
			if ( $i != 0 ) {
				echo "<hr class='saut'/>\n" ;
			}
			affiche_dossier($T, $_SESSION, $cnx, FALSE) ;
			$i++ ;
		}
		
		echo $end;
		deconnecter($cnx) ;
	}
}
//
// Changement d'état
//
else if ( $_POST["changement"] == "OK" )
{
	// Aucune candidature à changer d'état
	// Le tableau de chexboxes s'appelle imprimer, raison historique
	if ( count($_POST["imprimer"]) == 0 )
	{
		header("Location: /candidatures/candidatures.php?id_session="
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
			WHERE etat_dossier!='".$_POST["nouvel_etat"]."'
			AND id_dossier IN (".$liste_imp.")" ;
		$res = mysqli_query($cnx, $req) ;

		$tab_hist = array() ;		
		while ( $enr = mysqli_fetch_assoc($res) ) {
			$tab_hist[] = $enr["id_dossier"] ;
		}
		require_once("inc_historique.php") ;
		foreach ($tab_hist as $id)
		{
			historiqueAdd($cnx, $id, $_POST["nouvel_etat"], $_POST["evaluations"]) ;
		}


		$req = "UPDATE dossier SET etat_dossier='".$_POST["nouvel_etat"]."',
			date_maj_etat=CURDATE()
			WHERE id_dossier IN (".$liste_imp.")
			AND id_session=".$_POST["promotion"] ;
		mysqli_query($cnx, $req) ;
		deconnecter($cnx) ;
		header("Location: /candidatures/candidatures.php?id_session="
			. $_POST["promotion"] ) ;
	}
}
// Erreur
else {
	if ( !empty($_POST["promotion"]) ) {
		header("Location: /candidatures/candidatures.php?id_session="
			. $_POST["promotion"] ) ;
	}
}

/*
*/
?>
