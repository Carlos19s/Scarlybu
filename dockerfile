# Usar una imagen oficial de PHP con Apache o Nginx
FROM php:8.2-apache

# Instalar extensiones del sistema y de PHP necesarias para Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar Node.js (necesario para compilar el frontend)
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Configurar el directorio de trabajo
WORKDIR /var/www/html

# Copiar los archivos del proyecto
COPY . .

# Instalar dependencias de PHP y Node, y compilar assets para producción
RUN composer install --no-dev --optimize-autoloader
RUN npm install
RUN npm run build

# Configurar permisos para Laravel
RUN chown -r www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Apuntar Apache al directorio 'public' de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Habilitar el módulo rewrite de Apache (vital para las rutas de Laravel)
RUN a2enmod rewrite

# Exponer el puerto por defecto
EXPOSE 80