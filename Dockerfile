FROM php:7.3.4-apache

# Install PDO MySQL extension, xdebug
# Enable mod_rewrite Apache module
# Use development ini file
RUN docker-php-ext-install pdo_mysql \
  && pecl install xdebug-2.7.1 && docker-php-ext-enable xdebug \
  && a2enmod rewrite \
  && service apache2 restart \
  && mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Enable virtual host config.
WORKDIR /etc/apache2/sites-available/
COPY vhosts.conf ./
RUN a2ensite vhosts.conf \
  && a2dissite 000-default.conf \
  && service apache2 restart
