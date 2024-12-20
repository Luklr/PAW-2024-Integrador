# Definir variable con el nombre del contenedor
$containerName = docker compose ps -q app

# Verificar si el contenedor existe
if (-not $containerName) {
    Write-Error "No se encontró el contenedor 'app'. Asegúrate de que Docker esté en funcionamiento y el contenedor esté iniciado."
    exit 1
}

# Ejecutar composer install dentro del contenedor
Write-Host "Ejecutando composer install..."
docker exec $containerName git config --global --add safe.directory /var/www
docker exec $containerName composer install --no-dev --prefer-dist --optimize-autoloader

# Inicializar la base de datos
Write-Host "Inicializando la base de datos..."
docker exec $containerName bash db/scripts/init_db.sh

Write-Host "Proceso de instalación y migración completado."
