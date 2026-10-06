#!/bin/bash

# Liste des formats d'image à rechercher
formats=(
    "original"
    "mini_thumbnail"
    "grid"
    "grid_nostamp"
    "grid_small"
    "grid_small_nostamp"
    "vertical"
    "vertical_large"
    "vertical_large_nostamp"
    "large_nostamp"
    "team_square"
    "team_square_small"
    "blog_horizontal"
    "blog_horizontal_small"
    "blog"
    "blog_vertical"
    "carousel"
    "carousel_medium"
    "carousel_small"
)

# Définir les couleurs
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Afficher l'en-tête du tableau
printf "+----------------------+------------+\n"
printf "| %-20s | %-10s |\n" "Format" "Utilisé"
printf "+----------------------+------------+\n"

# Rechercher chaque format
for format in "${formats[@]}"; do
    # Recherche dans les fichiers Vue.js (patterns spécifiques)
    vue_pattern1=$(grep -r "var format = '$format'" ./nuxt-modern-website --include="*.vue" 2>/dev/null | wc -l)
    vue_pattern2=$(grep -r "var format = \"$format\"" ./nuxt-modern-website --include="*.vue" 2>/dev/null | wc -l)
    vue_pattern3=$(grep -r "format = '$format'" ./nuxt-modern-website --include="*.vue" 2>/dev/null | wc -l)
    vue_pattern4=$(grep -r "format = \"$format\"" ./nuxt-modern-website --include="*.vue" 2>/dev/null | wc -l)
    vue_pattern5=$(grep -r "'$format' + nostamp" ./nuxt-modern-website --include="*.vue" 2>/dev/null | wc -l)
    vue_pattern6=$(grep -r "\"$format\" + nostamp" ./nuxt-modern-website --include="*.vue" 2>/dev/null | wc -l)
    vue_pattern7=$(grep -r "getImageSizeByFilterSets.*'$format'" ./nuxt-modern-website --include="*.vue" 2>/dev/null | wc -l)
    
    # Recherche dans les fichiers Twig
    twig_pattern1=$(grep -r "imagine_filter('$format')" ./digital-management-system --include="*.twig" 2>/dev/null | wc -l)
    twig_pattern2=$(grep -r "imagine_filter(\"$format\")" ./digital-management-system --include="*.twig" 2>/dev/null | wc -l)
    twig_pattern3=$(grep -r "filter='$format'" ./digital-management-system --include="*.twig" 2>/dev/null | wc -l)
    twig_pattern4=$(grep -r "filter=\"$format\"" ./digital-management-system --include="*.twig" 2>/dev/null | wc -l)
    
    # Recherche dans les fichiers PHP
    php_pattern1=$(grep -r "->setFilter('$format')" ./digital-management-system --include="*.php" 2>/dev/null | wc -l)
    php_pattern2=$(grep -r "->setFilter(\"$format\")" ./digital-management-system --include="*.php" 2>/dev/null | wc -l)
    php_pattern3=$(grep -r "'filter' => '$format'" ./digital-management-system --include="*.php" 2>/dev/null | wc -l)
    php_pattern4=$(grep -r "\"filter\" => \"$format\"" ./digital-management-system --include="*.php" 2>/dev/null | wc -l)
    
    # Calculer le total des occurrences
    vue_count=$((vue_pattern1 + vue_pattern2 + vue_pattern3 + vue_pattern4 + vue_pattern5 + vue_pattern6 + vue_pattern7))
    twig_count=$((twig_pattern1 + twig_pattern2 + twig_pattern3 + twig_pattern4))
    php_count=$((php_pattern1 + php_pattern2 + php_pattern3 + php_pattern4))
    
    total_count=$((vue_count + twig_count + php_count))
    
    # Obtenir les fichiers qui utilisent ce format
    files=""
    if [ $total_count -gt 0 ]; then
        files=$(grep -r -l "$format" --include="*.vue" --include="*.twig" --include="*.php" ./nuxt-modern-website ./digital-management-system/templates ./digital-management-system/src 2>/dev/null | grep -v "config" | head -3 | sed 's/^\.\///' | tr '\n' ' ')
    fi
    
    # Déterminer le statut d'utilisation
    if [ $total_count -eq 0 ]; then
        status="Non utilisé"
        COLOR=$RED
    else
        details=""
        if [ $vue_count -gt 0 ]; then
            details="Vue: $vue_count"
        fi
        if [ $twig_count -gt 0 ]; then
            if [ -n "$details" ]; then
                details="$details, Twig: $twig_count"
            else
                details="Twig: $twig_count"
            fi
        fi
        if [ $php_count -gt 0 ]; then
            if [ -n "$details" ]; then
                details="$details, PHP: $php_count"
            else
                details="PHP: $php_count"
            fi
        fi
        
        if [ $total_count -lt 3 ]; then
            status="Peu utilisé"
            COLOR=$YELLOW
        else
            status="Utilisé"
            COLOR=$GREEN
        fi
        
        details="$details ($files)"
    fi
    
    # Afficher la ligne du tableau avec couleur
    printf "| ${COLOR}%-20s${NC} | ${COLOR}%-10s${NC} |\n" "$format" "$status"
done

printf "+----------------------+------------+\n"
