<?php
setlocale(LC_ALL, 'fr_FR.UTF-8') ;

define("URL_DOMAINE_PUBLIC", "foad-mooc.auf.org") ;
define("URL_DOMAINE", "foad.refer.org") ;

// Contact et expéditeur des messages
define("NOM_CONTACT", "Agence universitaire de la Francophonie") ;
define("EMAIL_CONTACT", "foad@auf.org") ;
define("EMAIL_FROMNAME", NOM_CONTACT) ;
define("EMAIL_REPLYTO", EMAIL_CONTACT) ;
define("EMAIL_FROM", "automate@refer.org") ;
define("EMAIL_SENDER", "foad@refer.org") ;

// Pour les deux messageries
define("EMAIL_FROM_TOUJOURS", TRUE) ;
define("EMAIL_SENDER_TOUJOURS", TRUE) ;

// Pour la maintenance
define("SITE_EN_LECTURE_SEULE", FALSE) ;
// Message affiché auc candidats et aux gestionnaires et reponsables
define("EN_MAINTENANCE", "Le site est actuellement en maintenance.") ;
define("EN_MAINTENANCE_INFO", "Il demeure consultable, mais toute tentative de mise à jour des données sera un échec.<br />
(Échec avec ou sans message d'erreur technique.)") ;

// Libellés des liens de l'interface
define("LIEN_IMPUTER", "&nbsp;<strong>&rArr;</strong>&nbsp;&nbsp;Imputer") ;
define("LIEN_IMPUTATION", "Imputation") ;
define("LIEN_IMPUTER_2", "&nbsp;<strong>&rArr;</strong>&nbsp;&nbsp;Imputer 2<sup>ème</sup> année") ;
define("LIEN_IMPUTATION_2", "Imputation 2<sup>ème</sup> année") ;
define("LIEN_DIPLOMER", "&nbsp;<strong>&rArr;</strong>&nbsp;&nbsp;Diplômer") ;
define("LIEN_ANCIEN", "Ancien") ;
define("LIEN_CANDIDATURE", "Candidature") ;
define("LIEN_DOSSIER", "Voir dossier") ;
// Libellés des pictogrammes (en texte) de l'interface
define("LABEL_DIPLOME", "Diplôme") ;
define("LABEL_INSCRIT", "a payé") ;
define("LABEL_INSCRIT_2", "a payé 2<sup>ème</sup> année") ;

define("MESSAGE_AUTOMATIQUE", "Ce message a été envoyé automatiquement par un robot, merci de ne pas y répondre.") ;

// Filtres
define("LABEL_REINITIALISER", "Réinitialiser") ;
define("LABEL_ACTUALISER", "Actualiser") ;
?>
