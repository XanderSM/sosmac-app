#!/bin/bash
# Asegurar permisos correctos (esencial en contenedores)
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Limpiar caché forzadamente
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Ejecutar migraciones y poblar base de datos
php artisan migrate --seed --force

# Iniciar Apache
exec apache2-foreground