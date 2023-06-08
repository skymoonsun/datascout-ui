FROM php:7.4-fpm


RUN apt update \
    && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip \
    && docker-php-ext-install intl opcache pdo pdo_mysql \
    && pecl install apcu \
    && apt-get update && apt-get install -y \
               libfreetype6-dev \
               libjpeg62-turbo-dev \
               libpng-dev \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && apt -y install python3 \
    && apt -y install python3-pip \
    && python3 -m pip install requests \
    && python3 -m pip install beautifulsoup4 \
    && python3 -m pip install urllib3 \
    && apt -y install tzdata cron \
    && apt-get install -y procps && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENV TZ=Europe/Istanbul
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

#RUN apt-get -y remove tzdata
RUN rm -rf /var/cache/apk/*

RUN mkdir -p /var/log/cron

CMD cron && docker-php-entrypoint php-fpm

#RUN echo "0 */3 * * *  root php /var/www/app/cron/metrics.php schedule:run >> /var/log/cron.log 2>&1" >> /etc/crontab

RUN touch /var/log/cron.log