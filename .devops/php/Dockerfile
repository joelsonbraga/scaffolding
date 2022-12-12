FROM php:8.0-apache

# Instala e configura componentes essenciais
RUN apt-get update && \
    dpkg --configure -a && \
    rm /var/lib/dpkg/lock && \
    a2enmod rewrite &&  \
    a2enconf httpd && \
    service apache2 restart &&\
    apt-get install -y --no-install-recommends unzip && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer; \
    echo "America/Sao_Paulo" > /etc/timezone; \
    rm -f /etc/apache2/sites-enabled/000-default.conf

RUN apt-get install -y --no-install-recommends libpq-dev && \
    docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-install mysqli pdo_mysql pgsql pdo_pgsql;

# GD
# Setup GD extension
RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd
RUN docker-php-ext-enable gd

# Copy existing application directory contents
COPY . /var/www/html

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www/html

## Change current user to www
#USER www-data

EXPOSE 80
