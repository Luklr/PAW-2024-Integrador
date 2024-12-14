# Usar una imagen base de PHP con Composer
FROM php:8.3-cli

# Instalar dependencias necesarias (si las hay)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev

RUN docker-php-ext-install pdo pdo_pgsql pgsql

# Establecer permisos para /var/www/html y /var/www/html/vendor
RUN mkdir -p /var/www/html/vendor && chown -R www-data:www-data /var/www && chmod -R 755 /var/www

# Establecer el directorio de trabajo
WORKDIR /var/www

# Instalar composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configuración de contenedor para ejecución
CMD ["php", "-S", "0.0.0.0:80", "-t", "public"]