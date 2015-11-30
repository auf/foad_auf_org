<?php
include("inc_session.php") ;

include("inc_html.php") ;
$titre = "Dossier de candidature" ;
echo $dtd1 ;
echo "<title>$titre</title>" ;
echo $dtd2 ;

include("inc_mysqli.php") ;
$cnx = connecter() ;

$req = "SELECT dossier.*, candidat.*, anneed,
	(SELECT id_imputation FROM imputations WHERE ref_dossier=id_dossier AND annee_relative=1)
	AS id_imputation1,
	(SELECT id_imputation FROM imputations WHERE ref_dossier=id_dossier AND annee_relative=2)
	AS id_imputation2
	FROM candidat, dossier
	LEFT JOIN imputations ON dossier.id_dossier=imputations.ref_dossier
	LEFT JOIN dossier_anciens ON dossier.id_dossier=dossier_anciens.ref_dossier
	WHERE dossier.id_dossier=".$_GET["id_dossier"]."
	AND dossier.id_candidat=candidat.id_candidat" ;
$res = mysqli_query($cnx, $req) ;
$T = mysqli_fetch_assoc($res) ;

// Chemin
$req = "SELECT intitule, intit_ses, ref_institution FROM atelier, session
    WHERE atelier.id_atelier=session.id_atelier
    AND session.id_session=".$T["id_session"] ;
$res = mysqli_query($cnx, $req) ;
$enr = mysqli_fetch_assoc($res) ;

echo $debut_chemin ;
echo "<a id='haut' accesskey='H'></a>" ;
echo "Candidatures" ;
echo " <span class='arr'>&rarr;</span> " ;
echo $enr["intitule"]." <span class='normal'>(".$enr["intit_ses"].")</span>" ;
echo $fin_chemin ;

//Ajout de l'institution au tableau
$T["ref_institution"] = $enr["ref_institution"] ;

// Dossier
include("inc_formulaire_candidature.php") ;
include("inc_date.php");
include("inc_etat_dossier.php") ;
include("inc_dossier.php");
affiche_dossier($T, $_SESSION, $cnx, FALSE, TRUE) ;

echo $end;
deconnecter($cnx) ;
?>
