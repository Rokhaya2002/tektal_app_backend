FROM php:8.2-apache

# Installer les extensions nécessaires
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite zip

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier le projet
COPY . /var/www/html

WORKDIR /var/www/html

# Installer les dépendances Laravel et préparer l'app
RUN composer install && php artisan key:generate && php artisan migrate --force

# Donner les bons droits à Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Démarrer Apache
CMD ["apache2-foreground"]
