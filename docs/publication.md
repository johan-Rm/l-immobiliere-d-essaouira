# Préparer une publication sans ancien historique

L’historique original contient des données et identifiants exposés. Le conserver sur un dépôt public rendrait ces informations accessibles même après suppression dans un nouveau commit.

## Copie indépendante

Le script d’export copie les sources suivies présentes et les nouveaux fichiers non ignorés. Il exclut l’historique Git, les configurations locales, les dépendances, les sauvegardes et les exports privés. Il refuse les archives, les fichiers SQL et les clés privées.

```bash
python3 scripts/export-public.py /tmp/immobiliere-public
cd /tmp/immobiliere-public
git init -b main
bash scripts/check.sh
git add .
git commit -m "Prepare public project showcase with fictitious demo"
```

Cette copie forme un nouveau dépôt avec un premier commit unique. Elle ne réécrit pas le dépôt original et ne publie rien automatiquement. Le README précise que l’application est historique ; ce nouveau commit représente la préparation de sa présentation publique, pas la date de sa réalisation initiale.

Avant la publication, analyser les sources et le nouvel historique avec Gitleaks, vérifier la démonstration et les droits sur les médias tiers conservés. Les identifiants exposés dans le dépôt original doivent être remplacés, même si la nouvelle copie ne les contient plus.

## Choix du dépôt GitHub

Un nouveau dépôt permet de conserver les anciens commits dans une sauvegarde privée. Remplacer l’historique du dépôt GitHub existant nécessite une mise à jour forcée et la coordination des autres clones. Cette opération ne garantit pas la suppression des copies, forks ou caches déjà existants.

La publication doit donc être décidée explicitement après revue de la copie, sans inclure le dossier `.local-backup/` du dépôt original.
