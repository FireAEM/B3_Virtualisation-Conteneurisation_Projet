FROM php:8.2-apache

# Installer extensions nécessaires
RUN apt-get update && apt-get install -y --no-install-recommends \
    default-mysql-client \
    netcat-openbsd \
  && docker-php-ext-install pdo_mysql \
  && a2enmod rewrite \
  && rm -rf /var/lib/apt/lists/*

# Apache ne peut pas écouter sur 80 en non-root, donc on passe à 8080
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf \
  && sed -i 's/:80>/:8080>/' /etc/apache2/sites-available/000-default.conf

# Copier l'application (en prod on build l'image)
COPY src/ /var/www/html/
COPY docker/wait-for-it.sh /usr/local/bin/wait-for-it.sh
RUN chmod +x /usr/local/bin/wait-for-it.sh
RUN chown -R www-data:www-data /var/www/html

# Passer en utilisateur non-root
USER www-data

WORKDIR /var/www/html

EXPOSE 8080
CMD ["apache2-foreground"]
