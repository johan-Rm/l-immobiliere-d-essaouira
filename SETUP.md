# Installation locale

Cet environnement sert au développement. Il nécessite Docker et Docker Compose v2. Le code existant utilise PHP 7.4, Symfony 4.2, Node 16, Yarn 1 et Nuxt 2.

Pour découvrir uniquement le site avec des données fictives, suivre [le guide de démonstration](docs/demo.md). Les étapes ci-dessous concernent l’environnement complet avec Symfony et MySQL.

## 1. Configuration

Depuis la racine du dépôt :

```bash
cp -n .env.example .env
cp -n digital-management-system/.env.example digital-management-system/.env
cp -n nuxt-modern-website/.env.example nuxt-modern-website/.env
```

Modifier les mots de passe MySQL dans `.env`. Utiliser des valeurs compatibles avec une URL de connexion. Le service backend reçoit sa connexion MySQL depuis Docker Compose. Générer des valeurs aléatoires distinctes pour `APP_SECRET` et `JWT_PASSPHRASE` dans la configuration Symfony.

```bash
openssl rand -hex 32
```

La clé Google Maps est facultative et se configure dans le fichier d’environnement du site. Une clé utilisée dans le navigateur doit être restreinte aux domaines et API autorisés.

## 2. Préparer les services

```bash
docker compose config --quiet
docker compose up -d --build db backend nginx mailhog
docker compose exec backend composer install
docker compose exec backend yarn install --frozen-lockfile
docker compose exec backend yarn dev
```

Le backend n’installe pas automatiquement ses dépendances au démarrage. La compilation et l’installation doivent réussir avant de poursuivre. Les dépendances historiques peuvent nécessiter des adaptations ; ne pas remplacer les fichiers de verrouillage pour contourner une erreur sans analyser sa cause.

Pour les fonctions d’authentification JWT, générer une paire de clés locale :

```bash
mkdir -p digital-management-system/config/jwt
openssl genrsa -aes256 -out digital-management-system/config/jwt/private.pem 4096
openssl rsa -pubout -in digital-management-system/config/jwt/private.pem -out digital-management-system/config/jwt/public.pem
```

Utiliser la même passphrase que `JWT_PASSPHRASE`. Les clés sont ignorées par Git.

## 3. Données et exports

Une base métier et des exports locaux sont nécessaires. Aucune sauvegarde contenant des données personnelles n’est fournie. La démonstration publique du site utilise des exports fictifs sans base MySQL. Une base fictive complète pour tester l’administration reste à créer.

Avec une base locale autorisée et compatible, les commandes présentes dans le backend permettent de produire les ressources :

```bash
docker compose exec backend php bin/console app:generate-json-api --full
docker compose exec backend php bin/console app:generate-translation
docker compose exec backend php bin/console app:generate-nuxtjs-routes full
```

Vérifier les options avec `--help` et le résultat des exports. Le site attend notamment les dossiers `data/` et `lang/`. Le backend partage `/app` avec le site pour y écrire les ressources. La configuration Symfony doit utiliser `DIRECTORY_VIEW=/app`.

Le script [de synchronisation](scripts/sync-private.sh) est réservé aux environnements privés autorisés. Il exige une configuration SSH explicite. Son import SQL remplace potentiellement les données locales ; il ne fait pas partie du démarrage d’une démonstration publique.

## 4. Démarrer le site

Une fois ses exports disponibles :

```bash
docker compose up -d --build frontend
```

| Service | Adresse locale |
| --- | --- |
| Site public | http://localhost:3000 |
| Backend | http://localhost:8080 |
| API | http://localhost:8080/api |
| Capture des emails | http://localhost:8025 |
| MySQL | `127.0.0.1:3306` |

La disponibilité des services ne garantit pas celle des fonctionnalités métier. Vérifier les pages de vente/location, les langues, le contact et l’administration avec une base de démonstration.

## 5. Vérifier et arrêter

```bash
bash scripts/check.sh
docker compose logs --tail=100 backend frontend nginx
docker compose down
```

`docker compose down` conserve les données MySQL. Ne supprimer le volume qu’en connaissance de cause. Les tests navigateur couvrent la démonstration du site ; l’administration ne dispose pas encore de tests automatisés.
