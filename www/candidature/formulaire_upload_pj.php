<?php
echo "<h1>Joindre un fichier</h1>\n" ;
echo "<form enctype='multipart/form-data' method='post' action='candidature.php' />\n";
echo "<input type='hidden' name='MAX_FILE_SIZE' value='2097152' />\n" ;
echo "<input type='hidden' name='formulaire' value='maj' />\n" ;
echo "<input type='hidden' name='id_dossier' value='$id_dossier' />\n" ;
echo "<input type='hidden' name='pwd' value='$pwd' />\n" ;
echo "<div style='margin: 0 auto; width: 50em;'><ul>\n" ;
echo "<li>Renommez éventuellement votre fichier avant de le joindre pour que son nom soit significatif.</li>\n" ;
echo "<li>Formats autorisés : <code>.pdf</code>, <code>.jpeg</code>, <code>.png</code>, <code>.doc</code>, <code>.rtf</code>.</li>\n" ;
echo "</ul></div>\n" ;
echo "<table class='formulaire'>\n" ;
echo "<tr>\n" ;
echo "<th>Fichier&nbsp;:</th>\n" ;
echo "<td colspan='2'><input class='upload' type='file' name='fichier' size='60' /></td>\n" ;
echo "</tr>\n" ;
echo "<tr>\n" ;
echo "<th></th>\n" ;
echo "<td><input type='checkbox' name='confirmation_pj' value='oui' /></td>\n" ;
echo "<td>En cochant cette case, vous certifiez l'exactitude des information contenues dans ce fichier.</td>\n" ;
echo "</tr>\n" ;
echo "</table>\n" ;
echo "<p class='c'><strong>" ;
echo "<input type='submit' name='submit' value='Joindre ce fichier' />" ;
echo " <input type='submit' value='Annuler' />" ;
echo "</p>\n" ;
echo "</form>\n" ;
?>
