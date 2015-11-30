<?php

if ( $_SERVER["SCRIPT_NAME"] == "/anciens.html" ) {
	echo "<li><div><a href='/anciens.html' class='on'>Recherche</a></div></li>\n" ;
}
else {
	echo "<li><div><a href='/anciens.html'>Recherche</a></div></li>\n" ;
}

/*
// Identifie
if	( 
		isset($_SESSION["annuaireAncien"]["id"]) 
		AND ( $_SESSION["annuaireAncien"]["id"] == "id" )
	)
{
}
// Non identifie
else
{
	if ( $_SERVER["SCRIPT_NAME"] == "/membre.html" ) {
		echo "<li><div><a href='/membre.html' class='on'>Accès membre</a></div></li>\n" ;
	}
	else {
		echo "<li><div><a href='/membre.html'>Accès membre</a></div></li>\n" ;
	}
}
*/
?>
