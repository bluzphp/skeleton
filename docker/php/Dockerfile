FROM php:7.0-fpm

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

RUN apt-get clean && rm -rf /var/lib/apt/lists/*
WORKDIR /var/www