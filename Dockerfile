FROM php:8.4-apache

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar directorio
WORKDIR /var/www/html
COPY . .

# Instalar dependencias de Laravel
RUN composer install --optimize-autoloader --no-dev

# Permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Configurar Apache
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

EXPOSE 80