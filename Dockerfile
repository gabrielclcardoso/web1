FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
	msmtp \
	ca-certificates \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Setup email
COPY ./conf/msmtprc /etc/msmtprc
RUN chmod 600 /etc/msmtprc && chown www-data:www-data /etc/msmtprc
RUN echo "sendmail_path = /usr/bin/msmtp -t" >> /usr/local/etc/php/conf.d/sendmail.ini


# Lets php save captured images
WORKDIR /var/www/html
RUN chown -R www-data:www-data /var/www/html
