# Usa a imagem oficial do PHP com Apache
FROM php:8.2-apache

# Instala extensões PHP necessárias
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Instalar dependências
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install zip

# Habilita o módulo de reescrita do Apache
RUN a2enmod rewrite && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Baixando e instalando o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia o arquivo de configuração PHP personalizado
COPY docker/php/php.ini /usr/local/etc/php/

COPY ./src /var/www/html
COPY ./docker/php/vhost.conf /etc/apache2/sites-available/000-default.conf

# Define a pasta de trabalho
WORKDIR /var/www/html

#Expondo a porta 80
EXPOSE 80