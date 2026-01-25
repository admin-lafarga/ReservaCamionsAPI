# Usa la imagen oficial de PHP con FPM
FROM php:8.2-fpm

# Instala extensiones de PHP necesarias
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libonig-dev libpng-dev libpq-dev && \
    docker-php-ext-install pdo pdo_mysql mbstring zip

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copia el proyecto
WORKDIR /var/www
COPY . .

# Instala dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Expone el puerto de la API
EXPOSE 80

# Comando por defecto
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
