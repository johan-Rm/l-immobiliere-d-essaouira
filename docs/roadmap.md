# Limites et travaux à prévoir

## Démonstration autonome

- Créer une base fictive couvrant les biens, locations, contenus et traductions.
- Relier une démonstration de l’administration aux exports fictifs du site déjà disponibles.
- Étendre la couverture des tests navigateur à tous les filtres, menus et traductions.

## Fiabilité

- Vérifier l’installation depuis un clone neuf avec les versions des conteneurs.
- Ajouter des tests sur les parcours métier et les commandes d’export.
- Vérifier l’alignement entre le schéma MySQL 8 et la configuration Doctrine actuellement déclarée pour MySQL 5.7.
- Documenter et vérifier les dépendances externes : PDF, traduction et cartographie.

## Modernisation

- Planifier la migration de PHP/Symfony et Node/Nuxt, avec validation fonctionnelle à chaque étape.
- Réduire progressivement les plugins et ressources tierces copiés dans les sources.
- Clarifier les licences : le dépôt est GPL-3.0, alors que les manifestes applicatifs indiquent des licences propriétaires ou non déclarées.

## Publication

Des sauvegardes et identifiants figuraient dans les anciens commits. Le nettoyage des fichiers courants ne les supprime pas de l’historique. Avant de présenter publiquement le dépôt, suivre les étapes de `SECURITY.md`.

Les contrôles statiques sont complétés par des tests navigateur de la démonstration Nuxt. Le fonctionnement de bout en bout avec Symfony et MySQL reste à vérifier.
