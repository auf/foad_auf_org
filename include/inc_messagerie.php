<?php
include_once("inc_etat_dossier.php") ;

function formulaire_courriel($messagerie, $courriel, $cnx)
{
	$req = "SELECT dossier.id_candidat, civilite, candidat.nom, prenom, pays,
		transferts,
		ref_pays.nom AS nom_pays
		FROM dossier, candidat
		LEFT JOIN ref_pays ON candidat.pays=ref_pays.code
		WHERE dossier.id_session=".$messagerie["promotion"]."
		and dossier.id_candidat=candidat.id_candidat " ;
	if ( !empty($messagerie["etat"]) ) {
		$req .= "AND etat_dossier='".$messagerie["etat"]."' " ;
	}
	$req .= "ORDER BY candidat.nom" ;
	$res = mysqli_query($cnx, $req) ;
	$nbDestinataires = mysqli_num_rows($res) ;

	echo "<form enctype='multipart/form-data' action='session.php' method='post'>\n" ;
	echo "<input type='hidden' name='ok' value='ok' />\n" ;
	echo "<input type='hidden' name='promotion' " ;
		echo "value='".$messagerie["promotion"]."' />\n" ;

	echo "<table class='formulaire' style='margin-bottom: 1em;'>\n<tr>\n" ;
	echo "<th>&Eacute;tat de la candidature&nbsp;:</th>\n<td>" ;
	liste_etats("etat", $messagerie["etat"], TRUE) ;
	echo "</td><td><input type='submit' style='font-weight: bold' " ;
	echo "name='dest' value='Modifier les destinataires' /></td>" ;
	echo "</tr>\n</table>\n" ;

	// Pour les fonctions strtoupper etc
	echo "<table class='formulaire'>\n" ;
	// Destinataires
	echo "<tr>\n" ;
	if ( $nbDestinataires == 0 ) {
		echo "<th>Destinataires ($nbDestinataires)&nbsp;:</th>" ;
		echo "<td colspan='3'>Il n'y a aucun destinataire potentiel" ;
		if ( !empty($messagerie["etat"]) ) {
			echo " pour un état de candidature de ".$messagerie["etat"] ;
		}
		else {
			echo "." ;
		}
		echo "</td></tr>\n" ;
	}
	else {
		echo "<th rowspan='$nbDestinataires'>" ;
		echo "Destinataires ($nbDestinataires)&nbsp;:<br />" ;
		echo "<a href='javascript:checkAll()'>Tout cocher</a> - " ;
		echo "<a href='javascript:unCheck()'>Tout décocher</a>" ;
		echo "</th>\n" ;
		$i = 0 ;
		while ( $enr = mysqli_fetch_assoc($res) )
		{   
			if ( $i != 0 ) {
				echo "<tr>\n" ;
			}
			if ( @in_array($enr["id_candidat"], $messagerie["destinataires"]) ) 
			{
				echo "<td style='width: 1em' class='aex'>" ;
				echo "<input type='checkbox' " ;
				echo "name='destinataires[]' id='".$enr["id_candidat"]."' " ;
				echo "checked='checked' " ;
				echo "value='".$enr["id_candidat"]."' />" ;
				echo "</td>\n<td class='aex'>" ;
				echo "<label class='bl' for='".$enr["id_candidat"]."'>" ;
				echo $enr["civilite"] ;
				echo " <em>". strtoupper($enr["nom"])."</em> " ;
				echo ucwords(strtolower($enr["prenom"])) ;
				if ( $enr["transferts"] != "" ) {
					echo " <span class='transferts help'" ;
					echo " title=\"".$enr["transferts"]."\"" ;
					echo ">Transféré</span>" ;
				}
				echo "</label></td>\n" ;
				echo "<td class='aex'>".$enr["nom_pays"]."</td>\n" ;
			}
			else {
				echo "<td style='width: 1em'>" ;
				echo "<input type='checkbox' " ;
				echo "name='destinataires[]' id='".$enr["id_candidat"]."' " ;
				echo "value='".$enr["id_candidat"]."' /></td>\n" ;
				echo "<td><label class='bl' for='".$enr["id_candidat"]."'>" ;
				echo $enr["civilite"] ;
				echo " <em>". strtoupper($enr["nom"])."</em> " ;
				echo ucwords(strtolower($enr["prenom"])) ;
				if ( $enr["transferts"] != "" ) {
					echo " (<span class='transferts help'" ;
					echo " title=\"".$enr["transferts"]."\"" ;
					echo ">Transféré</span>)" ;
				}
				echo "</label></td>\n" ;
				echo "<td>".$enr["nom_pays"]."</td>\n" ;
			}
			echo "</tr>\n" ;
			$i++ ;
		}
	}
	// from
	echo "<tr>\n" ;
	echo "<th>Expéditeur&nbsp;:</th>\n" ;
	echo "<td colspan='3'>";
	if ( intval($_SESSION["id"]) < 3 ) {
		echo "Agence universitaire de la Francophonie" ;
	}
	else {
		echo "FOAD" ;
	}
	echo " &lt;" ;
	if ( EMAIL_FROM_TOUJOURS ) {
		echo "<del class='barre'>".$courriel."</del> " ;
		echo EMAIL_FROM ;
	}
	else {
		echo $courriel ;
	}
	echo "&gt;</td>\n" ;
	echo "</tr>\n" ;
	// sender
	echo "<tr>\n" ;
	echo "<th>Adresse de retour&nbsp;:</th>\n" ;
	echo "<td colspan='3'>";
	if ( EMAIL_SENDER_TOUJOURS ) {
		echo "<del class='barre'>".$courriel."</del> " ;
		echo EMAIL_SENDER ;
	}
	else {
		echo $courriel ;
	}
	echo "</td>\n" ;
	echo "</tr>\n" ;
	// reply to
	echo "<tr>\n" ;
	echo "<th>Réponse à&nbsp;:</th>\n" ;
	echo "<td colspan='3'>";
	echo $courriel ;
	echo "</td>\n" ;
	echo "</tr>\n" ;
	// cc
	echo "<tr>\n" ;
	echo "<th><label for='cc'>Copie à&nbsp;:</label><br />" ;
//	echo "<a href='#' onClick='document.forms[0].cc.value=\"".$courriel."\"'>Copie à l'expéditeur</a></th>\n" ;
	echo "<a href='javascript:copie()'>Copie à l'expéditeur</a></th>\n" ;
	echo "<td colspan='3'><input type='text' id='cc' name='cc' size='80' maxlength='250' " ;
	echo "value=\""
		. ( isset($messagerie["cc"]) ? $messagerie["cc"] : "" )
		. "\" " ;
	echo "/></td>\n" ;
	echo "</tr>\n" ;
	// commentaire
	echo "<tr>\n" ;
	echo "<th><label for='commentaire'>Commentaire&nbsp;:<br />" ;
	echo "<span class='normal'>Affiché seulement dans la<br /> liste des courriels envoyés</span></label></th>\n" ;
	echo "<td colspan='3'><textarea name='commentaire' id='commentaire' " ;
	echo "rows='2' cols='90'>" ;
	echo ( isset($messagerie["commentaire"]) ? $messagerie["commentaire"] : "" ) ;
	echo "</textarea></td>\n" ;
	echo "</tr>\n" ;
	// subject
	echo "<tr>\n" ;
	echo "<th><label for='subject'>Sujet&nbsp;:</label></th>\n" ;
	echo "<td colspan='3'><input type='text' id='subject' name='subject' " ;
	echo "size='80' " ;
	echo "value=\""
		. ( isset($messagerie["subject"]) ? $messagerie["subject"] : "" )
		. "\" " ;
	echo "/></td>\n" ;
	echo "</tr>\n" ;
	// body
	echo "<tr>\n" ;
	echo "<th><label for='body'>Message&nbsp;:</label></th>\n" ;
	echo "<td colspan='3'><textarea name='body' id='body' " ;
	echo "rows='20' cols='90'>" ;
	echo ( isset($messagerie["body"]) ? $messagerie["body"] : "" ) ;
	echo "</textarea></td>\n" ;
	echo "</tr>\n" ;
	// attach
	echo "<tr>\n" ;
	echo "<th rowspan='3'>Fichiers joints&nbsp;:</th>\n" ;
	echo "<td colspan='3'>";

	$chemin = "/attachements/".$messagerie["promotion"]."/" ;

	if ( isset($messagerie["nbAttachements"]) AND (count($messagerie["nbAttachements"]) > 0) )
	{
		$i = 0 ;
		foreach($messagerie["attachements"] as $attach) {
			if ( $i == 0 ) {
				$liste_attachements = "$attach" ;
			}
			else {
				$liste_attachements .= ", " . "$attach" ;
			}
			$i++ ;
		}
	
		$req = "SELECT * FROM attachements
			WHERE ref_courriel=0
			AND ref_session=".$messagerie["promotion"] ;
		if ( $liste_attachements != "" ) {
			$req .= " AND id_attachement IN ($liste_attachements)" ;
		}
		$req .= " ORDER BY id_attachement" ;
		$res = mysqli_query($cnx, $req) ;
		if ( mysqli_num_rows($res) != 0 ) {
			echo "<ul>\n" ;
			while ( $enr = mysqli_fetch_assoc($res) ) {
				echo "<li>" ;
				echo "<strong>" ;
				echo "<a href='".$chemin.$enr["nom"]."'>".$enr["nom"]."</a>" ;
				echo "</strong>" ;
				echo " (".intval($enr["taille"]/1024.0)."ko) " ;
				echo "<a href='supprimer.php?id=".$enr["id_attachement"]."'>Supprimer</a>" ;
				echo "</li\n>" ;
			}
			echo "</ul>\n" ;
		}
	}
	echo "<input type='submit' style='font-weight:bold;' " ;
	if ( isset($messagerie["nbAttachements"]) AND (count($messagerie["nbAttachements"]) > 0) ) {
		echo "name='attachement' value='Joindre un autre fichier' /></p>\n" ;
	}
	else {
		echo "name='attachement' value='Joindre un fichier' /></p>\n" ;
	}
	echo "</td>" ;
	echo "</tr>\n" ;
	echo "</table>\n" ;
	
	echo "<p class='c'>" ;
	echo "<input type='submit' style='font-weight:bold;' " ;
	echo "name='envoi' value='Envoyer ' /></p>\n" ;
	echo "</form>\n" ;
}

function verification_courriel($messagerie)
{
	$erreurs = array() ;
	// Aucun destinataire
	if ( count($messagerie["destinataires"]) == 0 ) {
		$erreurs[] = "Votre courriel doit avoir au moins un destinataire&nbsp;!";
	}
	// subject vide
	if ( trim($messagerie["subject"]) == "" ) {
		$erreurs[] = "Le champ «&nbsp;Sujet&nbsp;» est obligatoire." ;
	}
	// body vide
	if ( trim($messagerie["body"]) == "" ) {
		$erreurs[] = "Le champ «&nbsp;Message&nbsp;» est obligatoire." ;
	}
	if ( trim($messagerie["cc"]) != "" ) {
		$CC = trim($messagerie["cc"]) ;
		$tabCC = explode(",", $CC) ;
		if ( count($tabCC)>1 ) {
			foreach($tabCC AS $cc) {
				if (filter_var(trim($cc), FILTER_VALIDATE_EMAIL)) {
				}
				else {
					$erreurs[] = "Le champ «&nbsp;Copie à&nbsp;» est invalide : $cc" ;
				}
			}
		}
		else {
			if (filter_var($CC, FILTER_VALIDATE_EMAIL)) {
			}
			else {
				if ( substr_count($CC, "@") > 1 ) {
					$erreurs[] = "Le champ «&nbsp;Copie à&nbsp;» est invalide. Plusieurs adresses doivent être séparées par une virgule." ;
				}
				else {
					$erreurs[] = "Le champ «&nbsp;Copie à&nbsp;» est invalide." ;
				}
			}
		}
	}
	return $erreurs ;
}

?>
