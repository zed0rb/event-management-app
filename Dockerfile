FROM phpstorm/php-apache:8.2-xdebug3.2

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN apt-get update && \
    apt-get -y install libzip-dev libicu-dev && \
    docker-php-ext-install pdo pdo_mysql zip intl && \
    docker-php-ext-enable pdo pdo_mysql zip intl

RUN a2enmod rewrite

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

COPY symfony.conf /etc/apache2/conf-available/symfony.conf
RUN a2enconf symfony

COPY . /var/www/html

EXPOSE 80
