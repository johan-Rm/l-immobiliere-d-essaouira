# Démonstration avec données fictives

La démonstration utilise le site Nuxt 2 existant et des exports JSON créés sans données de production. Elle contient trois biens fictifs, une agence fictive, des contacts réservés au domaine `example.invalid` et des illustrations SVG créées pour ce dépôt.

## Démarrage depuis un clone propre

```bash
python3 scripts/prepare-demo.py
docker compose -f docker-compose.demo.yml up -d --build
```

Ouvrir http://localhost:3000. Pour un autre port :

```bash
DEMO_PORT=3100 docker compose -f docker-compose.demo.yml up -d --build
```

Le script refuse d’écraser des dossiers `data/`, `lang/` ou un fichier `.env` existants. Utiliser un clone neuf si ces ressources existent déjà. La compilation initiale peut prendre quelques dizaines de secondes après le démarrage du serveur.

La démonstration ne démarre ni Symfony ni MySQL. Elle bloque les appels à l’API et aux confirmations email, désactive la soumission des formulaires et le suivi Analytics. Le catalogue et les filtres de budget utilisent les exports locaux. Les illustrations remplacent les médias métier.

## Vérifications dans un navigateur

Avec Node 22 ou une version compatible avec la dépendance Playwright verrouillée :

```bash
npm ci --prefix tests/demo
cd tests/demo
npx playwright install --with-deps chromium
npm test
```

Pour un autre port, définir `DEMO_BASE_URL`, par exemple `http://localhost:3100`. Adapter aussi `URL_WEBSITE` dans la configuration locale du site pour ses liens canoniques.

Les tests couvrent les pages principales françaises, l’accueil et une fiche en anglais, le filtre de budget, le blocage des envois et l’affichage de l’accueil sur un petit écran. Ils ne couvrent pas l’administration Symfony, les exports métier, les PDF ni l’ensemble des traductions.

## Arrêt

```bash
docker compose -f docker-compose.demo.yml down
```

Les fixtures publiques sont dans `demo/`. Leur génération se fait avec `python3 scripts/create-demo-fixtures.py`. Les exports préparés dans le site restent ignorés par Git.
