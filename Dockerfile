FROM php:8.3-apache

RUN apt-get update && apt-get install -y libpq-dev git zip unzip zsh && docker-php-ext-install pdo pdo_mysql pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html/

RUN a2enmod rewrite

RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

RUN composer install

VOLUME [ "/var/www/html/vendor" ]

EXPOSE 80

CMD ["apache2-foreground"]