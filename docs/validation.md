# Vérifications réalisées le 6 octobre 2026

La copie publique indépendante a été vérifiée avec des données fictives.

| Vérification | Résultat |
| --- | --- |
| Fichiers JSON, permissions et règles d’hygiène des sources | Réussi |
| Syntaxe PHP et shell, espaces du diff | Réussi |
| Liens de la documentation | Réussi |
| Validation des deux configurations Docker Compose | Réussi |
| Installation du site avec Node 16 et le verrouillage Yarn | Réussi |
| Construction du Dockerfile depuis les sources | Réussi |
| Démarrage de la copie publique avec Docker Compose | Réussi |
| Tests Chromium de la démonstration | 11 tests réussis |
| Analyse des sources et du nouvel historique avec Gitleaks 8.30.1 | Aucun secret détecté |

Les tests navigateur couvrent les pages principales françaises, l’accueil et une fiche en anglais, le filtre de budget, le refus des envois, l’accueil sur petit écran et les illustrations de biens fictifs. Ils sont disponibles dans `tests/demo`.

La vérification Docker a utilisé un projet isolé et un port local dédié. Le site a réellement été compilé et rendu dans Chromium. Aucun service de production n’a été contacté.

## Limites

L’administration Symfony, la base MySQL, les commandes d’export, les traductions externes et les PDF ne sont pas validés de bout en bout. La syntaxe PHP a été vérifiée avec l’interpréteur local PHP 8.4 ; cela ne constitue pas un test d’exécution du backend PHP 7.4.

La suite GitHub Actions est fournie, mais n’a pas encore été exécutée sur GitHub. Les dépendances historiques émettent des avertissements de compatibilité et n’ont pas fait l’objet d’un audit exhaustif de vulnérabilités.

Gitleaks analyse des motifs connus de secrets ; l’absence de détection ne garantit pas l’absence de toute information sensible. Les anciens commits du dépôt original restent conservés séparément et contiennent les informations exposées. Les identifiants concernés doivent être remplacés sur les services externes.
