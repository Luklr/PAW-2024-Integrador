#!/bin/bash

# Definir el nombre del contenedor
CONTAINER_NAME=php-app-paw

# Ejecutar composer install dentro del contenedor
echo "Ejecutando composer install..."
docker exec $CONTAINER_NAME git config --global --add safe.directory /var/www
docker exec $CONTAINER_NAME composer install --no-dev --prefer-dist --optimize-autoloader

# Inicializar la base de datos
docker exec $CONTAINER_NAME bash db/scripts/init_db.sh

echo "Proceso de instalación y migración completado."