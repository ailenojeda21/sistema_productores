FROM php:8.2-cli

# Directorio de trabajo
WORKDIR /app

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    zip \
    curl

# Instalar extensiones PHP necesarias para Laravel
RUN docker-php-ext-install pdo pdo_mysql zip

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Generar caches de Laravel
RUN php artisan config:clear || true
RUN php artisan route:clear || true
RUN php artisan view:clear || true

# Usuario sin privilegios: solo storage y bootstrap/cache son escribibles
RUN groupadd --gid 1000 laravel \
    && useradd --uid 1000 --gid laravel --shell /bin/sh --create-home laravel \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R laravel:laravel storage bootstrap/cache

# Exponer puerto (varias plataformas asignan PORT dinámicamente)
EXPOSE 8080

# Ejecutar como usuario sin privilegios
USER laravel

# Comando de inicio
CMD php artisan serve --host=0.0.0.0 --port=$PORT