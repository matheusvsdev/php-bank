FROM php:8.2-apache

# Instalar extensões necessárias
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copiar código-fonte para o container
COPY ./public/ /var/www/html/

# Configurar permissões da pasta do Apache
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80