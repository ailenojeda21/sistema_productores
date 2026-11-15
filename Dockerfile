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
# Vite 8 exige Node ^20.19 || >=22.12; node:22-alpine cumple.
FROM node:22-alpine AS assets

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
FROM php:8.4-cli

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

# Caches de Laravel que no dependen del entorno.
# `config:cache` NO se ejecuta aca: las variables (APP_KEY, DB_*, APP_URL,
# TRUSTED_PROXIES, CORS_ALLOWED_ORIGINS) llegan en runtime y quedarian
# congeladas vacias dentro de la imagen. Se genera al arrancar el contenedor.
RUN php artisan route:cache
RUN php artisan view:cache

# Usuario sin privilegios: solo storage y bootstrap/cache son escribibles
RUN groupadd --gid 1000 laravel \
    && useradd --uid 1000 --gid laravel --shell /bin/sh --create-home laravel \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R laravel:laravel storage bootstrap/cache

# Varias plataformas asignan PORT dinamicamente; 8080 es el valor por defecto.
EXPOSE 8080

# Health check contra la ruta de salud de Laravel (/up)
HEALTHCHECK --interval=30s --timeout=5s --start-period=30s --retries=3 \
    CMD curl -fsS "http://127.0.0.1:${PORT:-8080}/up" || exit 1

# Ejecutar como usuario sin privilegios
USER laravel

# Comando de inicio: compilar la config con el entorno real del contenedor y
# servir. `exec` reemplaza el shell para que las senales lleguen a artisan.
CMD php artisan config:cache \
    && exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"