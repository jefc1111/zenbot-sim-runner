FROM php:8.0.6-apache-buster

COPY . /var/www/html
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY docker/start.sh /usr/local/bin/start
COPY docker/php.ini /usr/local/etc/php/conf.d/php-custom.ini

RUN chown -R www-data:www-data /var/www/html * \
    && chmod u+x /usr/local/bin/start \
    && chmod -R 777 /var/www/html/storage \
    && a2enmod rewrite \
    && docker-php-ext-install pdo pdo_mysql pcntl
RUN apt-get update && apt-get install -y \
    software-properties-common \    
    npm \
    git
RUN npm install npm@latest -g && \
    npm install n -g && \
    n 12.22.7
RUN /var/www/html/docker/zenbot-install.sh
COPY zenbot-conf.js /home/zenbot/conf.js

CMD ["/usr/local/bin/start"]