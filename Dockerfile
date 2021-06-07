#start with our base image (the foundation) - version 7.1.5
FROM php:7.3-fpm

#install all the system dependencies and enable PHP modules 
RUN apt-get update && apt-get install -y \
      libicu-dev \
      libpq-dev \
      libmcrypt-dev \
      net-tools \
      git \
      zlib1g-dev \
      libzip-dev \
      zip \
      unzip \
      libpng-dev \
      libwebp-dev \
      libjpeg62-turbo-dev \
      libpng-dev libxpm-dev \
      libfreetype6-dev \
      pkg-config \
      patch \
      && rm -r /var/lib/apt/lists/*

ADD https://git.archlinux.org/svntogit/packages.git/plain/trunk/freetype.patch?h=packages/php /tmp/freetype.patch
RUN docker-php-source extract; \
      cd /usr/src/php; \
      patch -p1 -i /tmp/freetype.patch; \
      rm /tmp/freetype.patch

RUN docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd
RUN docker-php-ext-configure gd \
      --with-gd \
      --with-webp-dir \
      --with-jpeg-dir \
      --with-png-dir \
      --with-zlib-dir \
      --with-xpm-dir \
      --with-freetype-dir
      #--enable-gd-native-ttf
RUN docker-php-ext-install \
      intl \
      mbstring \
      #mcrypt \
      pcntl \
      pdo_mysql \
      pdo_pgsql \
      pgsql \
      zip \
      calendar \
      gd \
      opcache

RUN pecl install mcrypt-1.0.2
RUN docker-php-ext-enable mcrypt

#install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

#set our application folder as an environment variable
WORKDIR /usr/share/nginx/html/
ENV APP_HOME /usr/share/nginx/html/

#change uid and gid of apache to docker user uid/gid
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

#change the web_root to cakephp /var/www/html/webroot folder
#RUN sed -i -e "s/html/html\/webroot/g" /etc/apache2/sites-enabled/000-default.conf

#restart php-fpm
#RUN service php-fpm restart