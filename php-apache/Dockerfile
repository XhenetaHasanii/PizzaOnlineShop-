FROM php:7-apache

RUN apt-get clean
RUN apt-get update
RUN apt-get install --no-install-recommends --assume-yes --quiet ca-certificates curl git
RUN apt-get install --no-install-recommends --assume-yes --quiet vim &&\
    apt-get install --no-install-recommends --assume-yes --quiet iputils-ping &&\
    apt-get clean &&\
    rm -rf /var/lib/apt/lists/*

#Set a color scheme for the vim-editor (in the container)
COPY ./vimrc.txt /root/.vimrc

# Use the default production configuration
#RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini-production_"
#RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# or Use default development configuration
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# RUN pecl install xdebug-2.5.5 && docker-php-ext-enable xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN docker-php-ext-install pdo pdo_mysql mysqli

# Copy xdebug configuration for remote debugging
COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Activate XDebug extension in PHP.ini
#RUN echo 'zend_extension="/usr/local/lib/php/extensions/no-debug-non-zts-20180731/xdebug.so"' >> $PHP_INI_DIR/php.ini

#Activate Export of environment variables (for demo)
RUN echo 'variables_order = "EGPCS"' >> $PHP_INI_DIR/php.ini

#Include special settings for EWA
COPY ./httpd-ewa.conf $APACHE_CONFDIR/conf-enabled