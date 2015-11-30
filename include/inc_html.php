<?php
define("LIEN_REINITIALISER", "<a class='reinitialiser' href='reinitialiser.php'>".LABEL_REINITIALISER."</a>") ;
define("BOUTON_ACTUALISER", "<input class='b' type='submit' value='".LABEL_ACTUALISER."' />") ;
define("FILTRE_BOUTON_LIEN", "<div class='c'>" . LIEN_REINITIALISER . BOUTON_ACTUALISER . "</div>") ;

$dtd1 = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">
<head>
' ;

$htmlJquery = '<script type="text/javascript" src="/js/jquery-1.3.2.min.js"></script>
' ;
$htmlJquery172 = '<script type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
' ;
$htmlMakeSublist = '<script type="text/javascript" src="/js/makeSublist.js"></script>
' ;
$htmlDatePick = '<script type="text/javascript" src="/js/jquery.datepick.js"></script>
<script type="text/javascript" src="/js/jquery.datepick-fr.js"></script>
<link rel="stylesheet" type="text/css" href="/js/jquery.datepick.css" />
' ;
/*
$htmlCombo = '
<script type="text/javascript" src="~/js/jquery.simpleCombo.js"></script>
';

$htmlChecked = '
<script type="text/javascript" src="~/js/checked.js"></script>
';
*/

$dtd2sansBody= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/css/foad.css" rel="stylesheet" type="text/css" media="screen, print" />
<link href="/css/screen.css" rel="stylesheet" type="text/css" media="screen" />
<link href="/css/impression.css" rel="stylesheet" type="text/css" media="print" />
<link rel="icon" href="/favicon.ico" type="image/x-icon" />
</head>
' ;

$dtd2 = $dtd2sansBody . '
<body>
' ;

$debut_chemin = "<div id='chemin'>\n" ;
$fin_chemin = "</div>\n
<div id='page'>" ;

$end = "<a id='bas' accesskey='B'></a></div></body>\n</html>" ;

$logoAUF = "<p class='c' style='margin-top: 2em;'>
	<img src='/img/AUF.png' border='0' width='130' height='93'
	alt='Agence universitaire de la Francophonie' /></p>\n" ;

$titreFOAD = "<h1 title='Formations ouvertes et à distance'><a style='font-weight: normal;' href='http://www.foad-mooc.auf.org/FOAD'>FOAD</a></h1>\n" ;

$logoAufFoad = "<div class='c accueil'>
	<img src='/img/AUF.png' border='0' width='130' height='93'
	alt='Agence universitaire de la Francophonie'
	title='Agence universitaire de la Francophonie' />
<h1 class='foad'><a href='http://www.foad-mooc.auf.org/FOAD'>Formations ouvertes et à distance</a></h1>
</div>\n" ;


?>
