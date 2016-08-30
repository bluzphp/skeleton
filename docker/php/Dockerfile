FROM php:7.0-fpm

RUN apt-get update && apt-get install -y apt-utils re2c g++ \
zlib1g zlib1g-dbg zlib1g-dev zlibc \
git

RUN docker-php-ext-install pdo_mysql && docker-php-ext-enable pdo_mysql \
&& docker-php-ext-install zip && docker-php-ext-enable zip

#===Install Composer===#
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
&& php composer-setup.php \
&& php -r "unlink('composer-setup.php');"

#===Xdebug===#
ADD http://xdebug.org/files/xdebug-2.4.0rc4.tgz /
RUN tar -xvzf /xdebug-2.4.0rc4.tgz \
    && cd xdebug-2.4.0RC4 \
    && phpize && \
    ./configure \
    && make \
    && cp modules/xdebug.so /usr/local/lib/php/extensions/no-debug-non-zts-20151012/

COPY xdebug.ini /usr/local/etc/php/conf.d/
RUN docker-php-ext-enable xdebug

ENV PATH=$PATH:/var/www/docker/bin:/var/www/vendor/bin

RUN apt-get clean && rm -rf /var/lib/apt/lists/*
WORKDIR /var/www