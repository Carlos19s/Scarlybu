# Usar la imagen oficial de PHP 8.4 con Apache compatible con Laravel 13
FROM php:8.4-apache

# Instalar dependencias básicas del sistema y librerías cliente nativas de PostgreSQL
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    curl \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Instalar EXCLUSIVAMENTE las extensiones de PHP para PostgreSQL y Laravel
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions pdo pdo_pgsql pgsql zip intl bcmath opcache

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar Node.js y NPM para compilar tus estilos (Vite)
RUN curl -sL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Configurar el directorio de trabajo dentro del contenedor
WORKDIR /var/www/html

# Copiar todos los archivos de tu proyecto al contenedor
COPY . .

# Crear directorios que Laravel necesita antes de ejecutar composer
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache \
    /var/www/html/storage/framework/cache/data \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/logs && \
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Instalar dependencias de PHP para producción
RUN composer install --no-dev --optimize-autoloader

# Instalar dependencias de Node y compilar el frontend
RUN npm install
RUN npm run build

# Asignamos propietario (www-data) Y permisos de escritura recursivos (775)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Apuntar Apache directamente a la carpeta 'public' de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Habilitar el módulo rewrite de Apache (crucial para las rutas de Laravel)
RUN a2enmod rewrite

# Startup: configura el puerto dinámico de Render y arranca Apache
CMD sh -c "\
    mkdir -p storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs && \
    php artisan storage:link || true && \
    php artisan optimize:clear && \
    sed -i 's/Listen 80/Listen '\${PORT}'/g' /etc/apache2/ports.conf && \
    sed -i 's/<VirtualHost \*:80>/<VirtualHost *:'\${PORT}'>/g' /etc/apache2/sites-available/*.conf && \
    apache2-foreground"
