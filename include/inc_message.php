<?php
require_once("inc_erreurs.php") ;
require_once("inc_bool.php") ;
require_once("inc_date.php") ;
require_once("inc_guillemets.php") ;
function formulaire_message_nouveau($cnx, $fil, $post)
{
	$form = "" ;
	echo "<form method='post' action='message_nouveau.php?".$_SERVER["QUERY_STRING"]."'>\n" ;
	$form .= "<table class='formulaire hovertd'>\n" ;

	// Promotions
	$nb_sessions = intval($fil["nb_sessions"]) ;
	$sessions = $fil["sessions"] ;
	if ( $nb_sessions == 0 ) {
		$form .= "<tr><th>Promotions (0)&nbsp;:</th>\n" ;
		$form .= "<td colspan='2'>(Aucune promotion)</td>\n</tr>\n" ;
	}
	else {
		$form .= "<tr>\n<th rowspan='$nb_sessions'>Promotions ($nb_sessions)&nbsp;:</th>\n" ;
		$i = 0 ;
		if ( !is_array($post["promos"]) ) { $post["promos"] = array() ; }
		while ( list($key, $val) = each($sessions) )
		{
			if ( in_array($key, $post["promos"]) ) {
				$checked = " checked='checked'" ;
				$class = " class='aex ho'" ;
			}
			else {
				$checked = "" ;
				$class = " class='ho'" ;
			}

			if ( $i != 0 )  {
				$form .= "<tr>\n" ;
			}
			$form .= "<td".$class." style='padding: 0;' colspan='2'>" ;
			$form .= "<label class='bl' for='p".$key."'>" ;
			$form .= "<input type='checkbox' name='promos[]' " ;
			$form .= "id='p".$key."' value='$key'".$checked." />\n" ;
			$form .= $val["intitule"]."</label>" ;
			$form .= "</td>\n" ;
			$form .= "</tr>\n" ;
			$i++ ;
		}
	}
/*
*/
	// from
	$form .= "<tr>\n" ;
		$form .= "<th>Expéditeur&nbsp;:<br />" ;
		$form .= "<span class='normal'>(et adresse de retour)</th>\n" ;
		$form .= "<td colspan='2'>FOAD &lt;".$_SESSION["courriel"]."&gt;\n" ;
		$form .= "</td>\n" ;
	$form .= "</tr>\n" ;
/*
*/
	// Destinataires
	$nb_individus = intval($fil["nb_individus"]) ;
	$individus = $fil["individus"] ;
	if ( $nb_individus == 0 ) {
		$form .= "<tr><th>Destinataires (0)&nbsp;:</th>\n" ;
		$form .= "<td colspan='2'>(Aucun destinataire)</td>\n</tr>\n" ;
	}
	else {
		$form .= "<tr>\n<th rowspan='$nb_individus'>Destinataires ($nb_individus)&nbsp;:</th>\n" ;
		$i = 0 ;
		if ( !is_array($post["destin"]) ) { $post["destin"] = array() ; }
		while ( list($key, $val) = each($individus) )
		{
			if ( in_array($key, $post["destin"]) ) {
				$checked = " checked='checked'" ;
				$class = " class='aex ho'" ;
			}
			else {
				$checked = "" ;
				$class = " class='ho'" ;
			}

			if ( $i != 0 )  {
				$form .= "<tr>\n" ;
			}
			$form .= "<td".$class." colspan='2'>" ;
			$form .= "<label class='bl' for='i".$key."'>" ;
			$form .= "<input type='checkbox' name='destin[]' " ;
			$form .= "id='i".$key."' value='$key' ".$checked." />\n" ;
			$form .= $val["individu"]."</label>" ;
			$form .= "</td>\n" ;
			$form .= "</tr>\n" ;
			$i++ ;
		}
	}

	$form .= "<tr>\n" ;
		$form .= "<th><label for='cc'>Copie à&nbsp;:</label><br />" ;
		$form .= "<a class='normal' href='javascript:copie()'>Copie à l'expéditeur</a></th>\n" ;
		$form .= "<td colspan='2'><input type='text' id='cc' name='cc' size='50' " ;
		$form .= "value='".( isset($post["cc"]) ? $post["cc"] : "" )."'" ;
		$form .= "/></td>\n" ;
	$form .= "</tr>\n" ;

	$form .= "<tr>\n" ;
		$form .= "<th><label for='commentaire'>Commentaire&nbsp;:</label></th>\n" ;
		$form .= "<td colspan='2'><textarea name='commentaire' id='commentaire' rows='2' cols='90'>" ;
		$form .= ( isset($post["commentaire"]) ? $post["commentaire"] : "" ) ;
		$form .= "</textarea>" ;
		$form .= "</td>\n" ;
	$form .= "</tr>\n" ;

	$form .= "<tr>\n" ;
		$form .= "<th><label for='secret'>Archive consultable<br /> par les CNF&nbsp;:</label></th>\n" ;
		$form .= "<td colspan='2'>\n" ;
		$form .= radioSecret("secret", ( isset($post["secret"]) ? $post["secret"] : "" )) ;
		$form .= "</td>\n" ;
	$form .= "</tr>\n" ;

	$form .= "<tr>\n" ;
		$form .= "<th><label for='subject'>Sujet&nbsp;:</label></th>\n" ;
		$form .= "<td colspan='2'><input type='text' name='subject' id='subject' size='90' " ;
		$form .= "value='".( isset($post["subject"]) ? $post["subject"] : "" )."'" ;
		$form .= " /></td>\n" ;
	$form .= "</tr>\n" ;

	$form .= "<tr>\n" ;
		$form .= "<th><label for='body'>Message&nbsp;:</label></th>\n" ;
		$form .= "<td colspan='2'><textarea name='body' id='body' rows='20' cols='90'>" ;
		$form .= ( isset($post["body"]) ? $post["body"] : "" ) ;
		$form .= "</textarea>" ;
		$form .= "</td>\n" ;
	$form .= "</tr>\n" ;

	$form .= "<tr>\n<td colspan='3'>" ;
	$form .= "<p class='c'><input type='submit' name='submit' " ;
	$form .= "value='Envoyer' style='font-weight: bold;' /></p>\n" ;
	$form .= "</td>\n</tr>\n" ;

	$form .= "</table>\n" ;
	$form .= "</form>\n" ;
	echo $form ;
}


function formulaire_message($cnx, $fil, $post)
{
	$form = "" ;
	echo "<form method='post' action='message.php?".$_SERVER["QUERY_STRING"]."'>\n" ;
	$form .= "<table class='formulaire hovertd'>\n" ;

	// Promotions
	$nb_sessions = intval($fil["nb_sessions"]) ;
	$sessions = $fil["sessions"] ;
	if ( $nb_sessions == 0 ) {
		$form .= "<tr><th>Promotions (0)&nbsp;:</th>\n" ;
		$form .= "<td colspan='2'>(Aucune promotion)</td>\n</tr>\n" ;
	}
	else {
		$form .= "<tr>\n<th rowspan='$nb_sessions'>Promotions ($nb_sessions)&nbsp;:</th>\n" ;
		$i = 0 ;
		if ( !is_array($post["promos"]) ) { $post["promos"] = array() ; }
		while ( list($key, $val) = each($sessions) )
		{
			if ( in_array($key, $post["promos"]) ) {
				$checked = " checked='checked'" ;
				$class = " class='aex ho'" ;
			}
			else {
				$checked = "" ;
				$class = " class='ho'" ;
			}

			if ( $i != 0 )  {
				$form .= "<tr>\n" ;
			}
			$form .= "<td".$class." style='padding: 0;' colspan='2'>" ;
			$form .= "<label class='bl' for='p".$key."'>" ;
			$form .= "<input type='checkbox' name='promos[]' " ;
			$form .= "id='p".$key."' value='$key'".$checked." />\n" ;
			$form .= $val["intitule"]."</label>" ;
			$form .= "</td>\n" ;
			$form .= "</tr>\n" ;
			$i++ ;
		}
	}
	$form .= "<tr>\n" ;
		$form .= "<th><label for='commentaire'>Commentaire&nbsp;:</label></th>\n" ;
		$form .= "<td colspan='2'><textarea name='commentaire' id='commentaire' rows='2' cols='90'>" ;
		$form .= $post["commentaire"] ;
		$form .= "</textarea>" ;
		$form .= "</td>\n" ;
	$form .= "</tr>\n" ;

	$form .= "<tr>\n" ;
		$form .= "<th><label for='secret'>Archive consultable<br /> par les CNF&nbsp;:</label></th>\n" ;
		$form .= "<td colspan='2'>\n" ;
		$form .= radioSecret("secret", $post["secret"]) ;
		$form .= "</td>\n" ;
	$form .= "</tr>\n" ;

	$form .= "<tr>\n<td colspan='3'>" ;
	$form .= "<p class='c'><input type='submit' name='submit' " ;
	$form .= "value='Envoyer' style='font-weight: bold;' /></p>\n" ;
	$form .= "</td>\n</tr>\n" ;

	$form .= "</table>\n" ;
	$form .= "</form>\n" ;
	echo $form ;
}

function controle_message($T)
{
	$erreurs = array() ;

	if ( !is_array($T["destin"]) ) {
		$erreurs[] = "Il faut au moins un destinataire." ;
	}
	if ( trim($T["subject"]) == "" ) {
		$erreurs[] = "Le sujet est obligatoire." ;
	}
	if ( trim($T["body"]) == "" ) {
		$erreurs[] = "Le message est obligatoire." ;
	}

	return erreurs_string($erreurs) ;
}

function affiche_message($cnx, $message)
{
	// Edition par administrateur et service des bourses/pôle FOAS seulement
	// Ou seulement par administrateurt et expediteur
	//if ( intval($_SESSION["id"] < 2 ) {
	// Attention, courriel identique pour administrateur et CNF
	if	(
			(intval($_SESSION["id"]) == 0)
			OR ( (intval($_SESSION["id"])==1) AND ($_SESSION["courriel"]==$message["from"]) )
		)
	{
		$autorisation = TRUE ;
	}
	else {
		$autorisation = FALSE ;
	}

	$msg = "" ;
	$msg .= "<table class='formulaire'>\n" ;

	$msg .= "<tr>\n<th>Date&nbsp;:</th>\n" ;
	$msg .= "<td colspan='2'>".mysql2datenum($message["date"])."</td>\n</tr>\n" ;

	$msg .= "<tr>\n" ;
		$msg .= "<th>Expéditeur&nbsp;:</th>\n" ;
		$msg .= "<td colspan='2'>FOAD &lt;".$message["from"]."&gt;</td>\n" ;
	$msg .= "</tr>\n" ;

	if ( $message["cc"] != "" ) {
		$msg .= "<tr>\n<th>Copie à&nbsp;:</th>\n" ;
		$msg .= "<td colspan='2'>".$message["cc"]."</td>\n</tr>\n" ;
	}

	$msg .= "<tr><td colspan='3' class='separation'></td></tr>\n" ;

	//
	$req = "SELECT ref_session, intit_ses, annee, intitule FROM messages_sessions
		LEFT JOIN session ON messages_sessions.ref_session=session.id_session
		LEFT JOIN atelier ON session.id_atelier=atelier.id_atelier
		WHERE messages_sessions.ref_message=".$message["id_message"]."
		ORDER BY niveau, intitule" ;
	$res = mysqli_query($cnx, $req) ;
	$nb_sessions = mysqli_num_rows($res) ;
	$msg .= "<tr>\n<th>Promotions(".$nb_sessions.")&nbsp;:</th>\n" ;
	if ( $autorisation ) {
		$msg .= "<td>" ;
	}
	else {
		$msg .= "<td colspan='2'>" ;
	}
	while ( $enr = mysqli_fetch_assoc($res) ) {
		$msg .= $enr["intitule"] . "<br />" ;
	}
	$msg .= "</td>\n" ;
	if ( $autorisation ) {
		$msg .= "<td rowspan='3'><strong>" ;
		$msg .= "<a href='message.php?id_message=".$message["id_message"]."&action=change'>" ;
		$msg .= "Modifier</a></strong></td>\n" ;
	}
	else {
	}
	$msg .= "</tr>\n" ;

	//
	$msg .= "<tr>\n<th>Archive consultable par les CNF&nbsp;:</th>\n" ;
	if ( $autorisation ) {
		$msg .= "<td>" ;
	}
	else {
		$msg .= "<td colspan='2'>" ;
	}
	if ( strval($message["secret"]) == "0" ) {
		$msg .= "oui" ;
	}
	else {
		$msg .= "<span class='Non'>non</span>" ;
	}
	$msg .= "</td>\n</tr>\n" ;

	$msg .= "<tr>\n<th>Commentaire&nbsp;:</th>\n" ;
	if ( $autorisation ) {
		$msg .= "<td class='invisible'>" ;
	}
	else {
		$msg .= "<td colspan='2' class='invisible'>" ;
	}
	$msg .= $message["commentaire"]."</td>\n</tr>\n" ;

	$msg .= "<tr><td colspan='3' class='separation'></td></tr>\n" ;

	$msg .= "<tr>\n<th>Sujet&nbsp;:</th>\n" ;
	$msg .= "<td colspan='2' class='invisible'><strong>".$message["subject"]."</strong></td>\n</tr>\n" ;

	$msg .= "<tr>\n<th>Message&nbsp;:</th>\n" ;
	$msg .= "<td colspan='2' class='invisible'>" ;
	if  ( (intval($_SESSION["id"]) == 2) AND (strval($message["secret"])=="1") ) {
		$msg .= "<span class='Non'>Le contenu de ce message n'est pas consultable par les CNF.</span>" ;
	}
	else {
		$msg .= nl2br($message["body"]) ;
	}
	$msg .= "</td>\n</tr>\n" ;

	$msg .= "<tr><td colspan='3' class='separation'></td></tr>\n" ;

	//
	$req = "SELECT ref_individu, nom, prenom, cnf, actif FROM messages_individus
		LEFT JOIN individus ON messages_individus.ref_individu=individus.id_individu
		WHERE messages_individus.ref_message='".$message["id_message"]."'
		ORDER BY nom" ;
	$res = mysqli_query($cnx, $req) ;
	$nb_individus = mysqli_num_rows($res) ;
	$msg .= "<tr>\n<th>Destinataires (".$nb_individus.")&nbsp;:</th>\n" ;
	$msg .= "<td colspan='2'>" ;
	while ( $enr = mysqli_fetch_assoc($res) ) {
		if ( $enr["actif"] == 1 ) {
			$inactif = "" ;
		}
		else {
			$inactif = " <span class='actif0'>inactif</span>" ;
		}
		$msg .= strtoupper($enr["nom"]) . " " . $enr["prenom"]
			. " (" . $enr["cnf"] . ")" .$inactif . "<br />" ;
	}
	$msg .= "</td>\n</tr>\n" ;

	$msg .= "</table>\n" ;
	echo $msg ;
}




/*
é
*/
?>
