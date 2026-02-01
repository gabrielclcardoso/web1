FROM php:8.2-fpm

# Instala dependências do sistema e extensões PHP necessárias para o Camagru
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Define o diretório de trabalho no container
WORKDIR /var/www/html

# Ajusta permissões para que o PHP possa salvar as imagens capturadas
RUN chown -R www-data:www-data /var/www/html
