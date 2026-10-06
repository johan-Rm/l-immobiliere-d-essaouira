#!/bin/bash
set -e

# # PHP - Composer
# if [ ! -d "vendor" ]; then
#   echo "==> vendor/ absent, lancement de composer install"
#   composer install --prefer-dist --no-dev --optimize-autoloader --no-scripts
# fi

# # JS - Yarn install
# if [ -f "package.json" ] && [ ! -d "node_modules" ]; then
#   echo "==> node_modules/ absent, lancement de yarn install"
#   yarn install --ignore-engines
# fi

# if [ -f "composer.json" ]; then
#   echo "==> Exécution des scripts post-install Symfony"
#   composer run-script post-install-cmd
# fi

# 🛠 Crée les chemins nécessaires s'ils n'existent pas (uploads & cache LiipImagine)
mkdir -p /var/www/html/var
mkdir -p /var/www/html/public/uploads
mkdir -p /var/www/html/public/media

# 🛠 Crée le dossier partagé avec le frontend si besoin (Nuxt routes)
mkdir -p /app/update 
mkdir -p /app/lang

# 🔐 Fixe les permissions pour Symfony + Nuxt routes partagées
chown -R www-data:www-data \
        /var/www/html/var \
        /var/www/html/translations \
        /var/www/html/public/uploads \
        /var/www/html/public/media \
        /app/update \
        /app/lang

chmod -R u+rwX,g+rwX,o+rX /app/update
chmod -R u+rwX,g+rwX,o+rX /app/lang
chmod -R u+rwX,g+rwX,o+rX /var/www/html/translations

# 🚀 (optionnel) Warmup cache Symfony
# su www-data -s /bin/bash -c "php bin/console cache:warmup"

exec "$@"
