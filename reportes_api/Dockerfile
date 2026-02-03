# ============================================
# Stage 1: Builder - Instalación de dependencias
# ============================================
FROM composer:2.8 AS builder

# Establecer directorio de trabajo
WORKDIR /app

# Copiar archivos de composer
COPY composer.json composer.lock* ./

# Instalar dependencias de PHP (sin dev)
RUN composer install \
    --no-dev \
    --no-autoloader \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader \
    --no-interaction

# Copiar el resto del código fuente
COPY . .

# Generar autoloader
RUN composer dump-autoload --optimize --classmap-authoritative

# ============================================
# Stage 2: Development - Para entorno de desarrollo
# ============================================
FROM php:8.2-apache AS development

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    libpq-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP necesarias para Laravel
RUN docker-php-ext-install pdo pdo_pgsql pdo_sqlite mbstring exif pcntl bcmath gd zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Configurar Apache para usar el directorio public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias de PHP (incluyendo dev)
RUN composer install --optimize-autoloader --no-interaction

# Copiar archivo de entorno de ejemplo
RUN cp .env.example .env

# Generar clave de aplicación
RUN php artisan key:generate

# Configurar permisos de directorios
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Exponer puerto 80
EXPOSE 80

# Comando de inicio
CMD ["apache2-foreground"]

# ============================================
# Stage 3: Production - Para entorno de producción
# ============================================
FROM php:8.2-apache AS production

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Instalar dependencias del sistema (solo las necesarias)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    libpq-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP necesarias para Laravel
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Configurar Apache para usar el directorio public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Configurar Apache para producción
RUN echo "ServerTokens Prod" >> /etc/apache2/apache2.conf && \
    echo "ServerSignature Off" >> /etc/apache2/apache2.conf

# Copiar dependencias desde el stage builder
COPY --from=builder --chown=www-data:www-data /app /var/www/html

# Copiar archivo de entorno de ejemplo
RUN cp .env.example .env

# Configurar permisos de directorios
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Optimizar Laravel para producción
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Exponer puerto 80
EXPOSE 80

# Comando de inicio
CMD ["apache2-foreground"]
