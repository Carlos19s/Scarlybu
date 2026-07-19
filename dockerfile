# Usar la imagen oficial de PHP 8.4 con Apache compatible con Laravel 13
FROM php:8.4-apache

# Instalar dependencias básicas del sistema
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP necesarias para Laravel de forma confiable
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions pdo_mysql gd zip intl bcmath opcache

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar Node.js y NPM para compilar tus estilos (Vite)
RUN curl -sL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Configurar el directorio de trabajo dentro del contenedor
WORKDIR /var/www/html

# Copiar todos los archivos de tu proyecto al contenedor
COPY . .

# Instalar dependencias de PHP para producción
RUN composer install --no-dev --optimize-autoloader

# Instalar dependencias de Node y compilar el frontend (reemplaza a npm run dev)
RUN npm install
RUN npm run build

# Configurar permisos requeridos por Laravel para la escritura de logs y caché
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Apuntar Apache directamente a la carpeta 'public' de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Habilitar el módulo rewrite de Apache (crucial para las rutas de Laravel)
RUN a2enmod rewrite

# Exponer el puerto por defecto
EXPOSE 80
CMD php artisan config:clear && php artisan migrate --force && apache2-foreground