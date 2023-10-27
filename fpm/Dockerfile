FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
	libpng-dev \
	libonig-dev \
	libzip-dev \
	libmemcached-dev \
	libmcrypt-dev \
	&& docker-php-ext-install -j$(nproc) iconv mbstring mysqli pdo_mysql zip
        

#RUN apt-get update && apt-get install -y git        

COPY ./php.ini /usr/local/etc/php/conf.d/php-custom.ini
COPY ./zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf

#RUN curl -sS https://get.symfony.com/cli/installer | bash
#RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

RUN curl -sS https://getcomposer.org/installer | php -- \
        --filename=composer \
        --install-dir=/usr/local/bin

RUN addgroup --gid 101 --system nginx
RUN adduser --uid 101 --system --disabled-login --disabled-password --gid 33 nginx

WORKDIR /data/mysite.local

CMD ["php-fpm"]
