# Contribution

Créer une branche dédiée à une modification cohérente (`docs/`, `fix/` ou `feat/`). Écrire un commit qui décrit le problème et la modification ; séparer les changements applicatifs des opérations sur les données.

Avant de proposer une modification :

```bash
bash scripts/check.sh
git diff --check
```

Pour un changement applicatif, vérifier aussi les parcours concernés dans les conteneurs et préciser les résultats. Pour le site, exécuter aussi les tests navigateur décrits dans `docs/demo.md`. Les contrôles statiques ne constituent pas des tests métier.

Conserver les fichiers de verrouillage des dépendances. Ne pas ajouter de données de production, sauvegardes, clés privées ou mots de passe. Documenter les nouvelles variables dans les fichiers `.env.example` avec des valeurs fictives.

Les scripts shell et points d’entrée peuvent être exécutables. Les images, styles, polices et traductions ne doivent pas l’être.
