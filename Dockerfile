FROM php:8.3-apache

RUN apt-get update && apt-get install -y libpq-dev git zip unzip zsh \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN echo "[xdebug]" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "zend_extension=xdebug" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.mode=coverage,debug,develop" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.discover_client_host=true" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.log=/tmp/xdebug.log" >> /usr/local/etc/php/conf.d/xdebug.ini

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html/

RUN a2enmod rewrite

RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

RUN composer install

VOLUME [ "/var/www/html/vendor" ]

EXPOSE 80

CMD ["apache2-foreground"]