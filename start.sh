#!/bin/bash

# Limpiar todas las cachés
php artisan cache:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear

# (Opcional) Volver a generar la caché para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Iniciar Apache en primer plano
exec apache2-foreground
