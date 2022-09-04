FROM --platform=linux/amd64 php:7.1-fpm

RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libmagickwand-dev \
    libzip-dev \
    unzip \
    wget \
    xfonts-75dpi \
    xfonts-base \
    gnupg \
    vim \
    default-mysql-client \
    less \
    cron \
    python2 \
    build-essential

RUN docker-php-ext-configure gd --with-freetype --with-jpeg

RUN pecl install zlib zip
RUN docker-php-ext-install pdo pdo_mysql gd zip

ARG USER_ID=1000
ARG GROUP_ID=1000

RUN addgroup --gid $GROUP_ID user
RUN adduser --disabled-password --gecos '' --uid $USER_ID --gid $GROUP_ID user
RUN usermod -aG www-data user

RUN apt-get install nginx -y
RUN chown user:www-data /var/log/nginx/access.log && chown user:www-data /var/log/nginx/error.log && chown user:www-data -R /var/lib/nginx && chown user:www-data -R /run
COPY ./docker/nginx/default /etc/nginx/sites-enabled/default
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --1
RUN curl -sL https://deb.nodesource.com/setup_8.x | bash -
RUN apt-get -y install nodejs
RUN node -v
RUN npm -v
RUN npm install --global yarn
#RUN yarn set version 1.22.1

RUN mkdir /var/www/laravel && chown -R user:user /var/www/laravel &&  chown user:user /etc/init.d/nginx

USER user
COPY --chown=user:user ./app /var/www/laravel/app
COPY --chown=user:www-data ./bootstrap /var/www/laravel/bootstrap
COPY --chown=user:user ./config /var/www/laravel/config
COPY --chown=user:user ./database /var/www/laravel/database
COPY --chown=user:www-data ./public /var/www/laravel/public
COPY --chown=user:user ./resources /var/www/laravel/resources
COPY --chown=user:user ./routes /var/www/laravel/routes
COPY --chown=user:www-data ./storage /var/www/laravel/storage
COPY --chown=user:user ./tests /var/www/laravel/tests
COPY --chown=user:user ./.env /var/www/laravel/
COPY --chown=user:user ./artisan /var/www/laravel/
COPY --chown=user:user ./composer.json /var/www/laravel/
COPY --chown=user:user ./composer.lock /var/www/laravel/
COPY --chown=user:user ./package.json /var/www/laravel/
COPY --chown=user:user ./yarn.lock /var/www/laravel/
COPY --chown=user:user ./webpack.mix.js /var/www/laravel/
COPY --chown=user:user  ./phpunit.xml /var/www/laravel/
WORKDIR /var/www/laravel

RUN chmod -R 775 storage
RUN chmod -R 775 bootstrap/cache

RUN composer install
#RUN yarn install
#RUN yarn run dev

CMD /etc/init.d/nginx start ; php-fpm
