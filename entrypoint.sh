#!/bin/bash
# Ejecutar migraciones automáticamente al arrancar
php artisan migrate --force
# Limpiar caché
php artisan config:clear
php artisan route:clear
# Iniciar Apache
exec apache2-foreground