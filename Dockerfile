# Utiliser une image PHP officielle avec Apache
FROM php:8.2-apache

# Installer les extensions PHP nécessaires pour MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Copier les fichiers de l'application
COPY app/ /var/www/html/

# Définir les permissions appropriées
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html
