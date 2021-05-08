FROM php:8.0.6-apache-buster

COPY . /var/www/html
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY docker/start.sh /usr/local/bin/start

RUN chown -R www-data:www-data /var/www/html * \
    && chmod u+x /usr/local/bin/start \
    && chmod -R 777 /var/www/html/storage \
    && a2enmod rewrite \
    && docker-php-ext-install pdo pdo_mysql pcntl

CMD ["/usr/local/bin/start"]