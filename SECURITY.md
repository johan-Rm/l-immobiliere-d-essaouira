# Données et identifiants

Ne pas versionner les configurations locales, clés privées, exports SQL, sauvegardes ou données personnelles. Les exemples d’environnement contiennent uniquement des valeurs de remplacement.

## Exposition historique

Les anciens commits contiennent une sauvegarde SQL avec des données nominatives, un mot de passe de production dans l’ancien script de synchronisation, une clé Google Maps et des valeurs d’authentification Symfony.

Le nettoyage courant ne retire pas ces informations des anciens commits. Considérer les identifiants exposés comme compromis jusqu’à leur remplacement ou vérification par leur propriétaire.

Avant une publication destinée aux recruteurs :

1. Remplacer les mots de passe encore utilisés et les secrets d’application concernés ; vérifier les restrictions de la clé Google Maps.
2. Conserver une sauvegarde privée du dépôt pour pouvoir vérifier la réécriture.
3. Décider entre un nouveau dépôt de présentation avec un historique assaini et une réécriture de l’historique existant.
4. Retirer les données et secrets de l’ensemble de l’historique retenu, puis contrôler le résultat avec un outil de détection dédié et une revue humaine.
5. Coordonner la publication et, en cas de réécriture, la mise à jour des clones des collaborateurs.

La sauvegarde `.local-backup/` reste privée et ignorée par Git. Elle contient les fichiers retirés lors du nettoyage local. Ne jamais la joindre à une archive publique.

## Synchronisation privée

Le script de synchronisation utilise une connexion SSH configurée par l’utilisateur. L’accès MySQL distant doit être configuré sur le serveur, sans mot de passe écrit dans le script ni envoyé dans une commande SSH. L’import SQL exige une option explicite et ne doit viser qu’une base locale autorisée.
