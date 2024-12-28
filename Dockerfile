# Utiliser une image de base PHP avec Apache
FROM php:7.4-apache

# Installer les dépendances nécessaires pour PDO et MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Activer mod_rewrite pour Apache
RUN echo "ServerName localhot" >> /etc/apache2/apache2.conf

# Définir le répertoire de travail
COPY /app /var/www/html/

EXPOSE 80
