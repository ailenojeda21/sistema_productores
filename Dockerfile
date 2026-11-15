# =========================================================
# Etapa 1: dependencias PHP que necesita el build de assets
# =========================================================
# `resources/js/app.js` importa `../../vendor/tightenco/ziggy`, asi que Vite
# necesita ese paquete de Composer ademas de `node_modules`. Se resuelve en
# una etapa aparte para no arrastrar todo `vendor/` al contexto de Node.
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
        --no-dev \
        --no-interaction \
        --no-progress \
        --prefer-dist \
        --no-scripts \
        --ignore-platform-reqs

# =========================================================
# Etapa 2: build de assets con Vite
# =========================================================
# `public/build` no se versiona (.gitignore), por eso se genera aca: la imagen
# nunca depende de assets compilados en la maquina del que la construye.
# Vite 8 exige Node ^20.19 || >=22.12; node:20-alpine cumple.
FROM node:20-alpine AS assets

WORKDIR /build

# Solo los manifiestos primero, para cachear `npm ci` entre builds
COPY package.json package-lock.json ./
RUN npm ci

COPY --from=vendor /app/vendor/tightenco/ziggy ./vendor/tightenco/ziggy

COPY vite.config.js postcss.config.js tailwind.config.js ./
COPY resources ./resources

RUN npm run build

# =========================================================
# Etapa 3: imagen final
# =========================================================
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

# Assets compilados en la etapa `assets` (public/build no se versiona)
COPY --from=assets /build/public/build ./public/build

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