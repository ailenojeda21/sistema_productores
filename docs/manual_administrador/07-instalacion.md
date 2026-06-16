# Manual de Administrador — Instalación y Despliegue

**Código**: RUPAL-MA-07  
**Versión**: 1.0  
**Fecha**: 2026-06-15  
**ISO 9001:2015**: Cláusula 7.5 — Información Documentada, Cláusula 7.1.4 — Recursos

---

## 1. OBJETIVO

Describir el procedimiento completo de instalación, configuración y despliegue del sistema RUPAL en entornos de desarrollo, staging y producción.

## 2. ALCANCE

Aplica a administradores del sistema, DevOps y desarrolladores responsables de la instalación y mantenimiento del sistema RUPAL.

## 3. REFERENCIAS

| Documento | Descripción |
|-----------|-------------|
| ISO 27001:2022 | Seguridad de la información — Controles aplicables al despliegue |
| ISO 27017:2015 | Controles de seguridad en cloud |
| RUPAL-MA-06 | Manual de API de Integración |
| AGENTS.md | Guía de desarrollo y convenciones del proyecto |

## 4. DEFINICIONES

| Término | Definición |
|---------|------------|
| Entorno local | Máquina del desarrollador para pruebas locales |
| Entorno de producción | Servidor cloud (Railway) donde se ejecuta el sistema en vivo |
| Artisan | CLI de Laravel para tareas de administración |
| Vite | Bundler de frontend para assets JS/CSS |
| Sanctum | Paquete de autenticación por tokens |
| Queue worker | Proceso que ejecuta jobs en segundo plano |
| Scheduler | Programador de tareas CRON de Laravel |

---

## 5. REQUISITOS DEL SISTEMA

### 5.1 Requisitos de software

| Componente | Versión Mínima | Recomendada |
|------------|---------------|--------------|
| PHP | 8.2.0 | 8.3+ |
| Composer | 2.x | 2.8+ |
| Node.js | 20 LTS | 22 LTS |
| NPM | 10.x | 11.x |
| MySQL | 8.0 | 8.4+ / MariaDB 11+ |
| Redis | 6.0 | 7.4+ (opcional) |
| Servidor Web | Nginx 1.24 / Apache 2.4 | Nginx 1.26+ |

### 5.2 Extensiones PHP requeridas

| Extensión | Propósito |
|-----------|-----------|
| `pdo` | Conexión a base de datos |
| `pdo_mysql` | Driver MySQL/MariaDB |
| `zip` | Compresión (PhpSpreadsheet, paquetes Composer) |
| `bcmath` | Precisión matemática (Laravel core) |
| `ctype` | Validación de tipos (Laravel core) |
| `fileinfo` | Detección de tipos MIME |
| `json` | Procesamiento JSON |
| `mbstring` | Soportes multilenguaje (UTF-8, español) |
| `openssl` | Cifrado y conexiones HTTPS |
| `tokenizer` | Análisis de código PHP |
| `xml` | Procesamiento XML (PhpSpreadsheet) |

### 5.3 Requisitos de hardware

| Entorno | RAM | CPU | Disco |
|---------|-----|-----|-------|
| Desarrollo | 4 GB | 2 núcleos | 10 GB SSD |
| Producción (mínimo) | 2 GB | 2 núcleos | 20 GB SSD |
| Producción (recomendado) | 4 GB | 4 núcleos | 50 GB SSD |

### 5.4 Conectividad de red

| Destino | Puerto | Propósito |
|---------|--------|-----------|
| Base de datos MySQL | 3306 | Conexión a la BD |
| Servidor Redis | 6379 | Caché/sesiones (opcional) |
| `*.tile.openstreetmap.org` | 443 | Mapas interactivos (Leaflet) |
| `fonts.googleapis.com` | 443 | Fuentes web |
| `fonts.gstatic.com` | 443 | Fuentes web |

### 5.5 Navegadores soportados

| Navegador | Versión mínima |
|-----------|----------------|
| Google Chrome | 90+ |
| Mozilla Firefox | 90+ |
| Microsoft Edge | 90+ |
| Safari (macOS) | 14+ |

---

## 6. PROCEDIMIENTO DE INSTALACIÓN — ENTORNO LOCAL

### 6.1 Clonar el repositorio

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Abrir una terminal | Símbolo del sistema listo |
| 2 | Ejecutar `git clone <url-del-repositorio> sistema-productores` | Se crea el directorio `sistema-productores` |
| 3 | Ejecutar `cd sistema-productores` | El prompt cambia al directorio del proyecto |

```bash
git clone https://github.com/municipalidad-lavalle/sistema-productores.git
cd sistema-productores
```

### 6.2 Configurar el archivo de entorno

[CAPTURA DE PANTALLA: Archivo .env.example abierto en un editor de texto]

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Ejecutar `copy .env.example .env` (Windows) o `cp .env.example .env` (Linux/Mac) | Se crea el archivo `.env` |
| 2 | Abrir `.env` en un editor de texto | Se visualizan todas las variables de configuración |
| 3 | Generar la clave de la aplicación: `php artisan key:generate` | El comando escribe `APP_KEY` en el `.env` |

**Resultado esperado del comando key:generate:**
```
Application key set successfully.
```

### 6.3 Variables de entorno esenciales

| Variable | Desarrollo | Producción |
|----------|------------|------------|
| `APP_ENV` | `local` | `production` |
| `APP_DEBUG` | `true` | `false` |
| `APP_URL` | `http://localhost:8000` | `https://rupal.lavalle.gob.ar` |
| `APP_NAME` | `RUPAL` | `RUPAL` |
| `APP_LOCALE` | `es` | `es` |
| `DB_CONNECTION` | `mysql` | `mysql` |
| `DB_HOST` | `127.0.0.1` | `<host-railway>` |
| `DB_PORT` | `3306` | `<puerto-railway>` |
| `DB_DATABASE` | `sist_prod_dev` | `sist_prod` |
| `DB_USERNAME` | `root` | `<usuario-bd>` |
| `DB_PASSWORD` | *(vacío)* | `<contraseña-bd>` |
| `SESSION_DRIVER` | `database` | `database` |
| `SESSION_ENCRYPT` | `true` | `true` |
| `SESSION_SECURE_COOKIE` | *(comentado)* | `true` |
| `SESSION_LIFETIME` | `120` | `120` |
| `LOG_LEVEL` | `debug` | `warning` |
| `MAIL_MAILER` | `log` | `smtp` |
| `MAIL_HOST` | — | `<host-smtp>` |
| `MAIL_PORT` | — | `587` |
| `MAIL_USERNAME` | — | `<usuario-smtp>` |
| `MAIL_PASSWORD` | — | `<contraseña-smtp>` |
| `MAIL_FROM_ADDRESS` | — | `noreply@lavalle.gob.ar` |
| `MAIL_FROM_NAME` | `RUPAL` | `RUPAL` |
| `BCRYPT_ROUNDS` | `12` | `12` |
| `SANCTUM_TOKEN_PREFIX` | `rupal_staff_` | `rupal_staff_` |

### 6.4 Instalar dependencias de PHP

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Ejecutar `composer install` | Composer descarga e instala todas las dependencias |

```bash
composer install
```

**Resultado esperado:**
```
Installing dependencies from lock file
Package operations: XX installs, 0 updates, 0 removals
  - Installing ...
Generating optimized autoload files
> Illuminate\Foundation\ComposerScripts::postAutoloadDump
> @php artisan package:discover --ansi
Discovered Package: ...
```

### 6.5 Instalar dependencias de Node.js

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Ejecutar `npm install` | NPM descarga las dependencias de frontend |

```bash
npm install
```

**Resultado esperado:**
```
up to date, audited XXX packages in Ys
found 0 vulnerabilities
```

### 6.6 Compilar assets de frontend

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Ejecutar `npm run build` | Vite compila los assets JS/CSS |

```bash
npm run build
```

**Resultado esperado:**
```
vite v8.0.16 building for production...
✓ XX modules transformed.
public/build/manifest.json   XX kB
public/build/assets/app-XXXX.js    XXX kB
public/build/assets/app-XXXX.css   XX kB
```

### 6.7 Crear la base de datos

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Acceder a MySQL: `mysql -u root -p` | Se abre el cliente MySQL |
| 2 | Crear la base de datos: `CREATE DATABASE sist_prod_dev CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;` | Base de datos creada |
| 3 | Salir: `EXIT;` | Vuelve a la terminal |

### 6.8 Ejecutar migraciones

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Ejecutar `php artisan migrate` | Se crean todas las tablas en la base de datos |

```bash
php artisan migrate
```

**Resultado esperado:**
```
INFO  Running migrations.

  2014_10_12_000000_create_users_table ................. XXms DONE
  2014_10_12_100000_create_password_reset_tokens_table . XXms DONE
  ...
  XX/XX migrations ejecutadas.
```

### 6.9 Poblar datos de prueba (opcional)

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Ejecutar `php artisan db:seed --class=RoleSeeder` | Se crean los roles de Spatie |
| 2 | Ejecutar `php artisan db:seed` | Se cargan datos de prueba |

### 6.10 Crear enlace simbólico de storage

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Ejecutar `php artisan storage:link` | Se crea el enlace `public/storage → storage/app/public` |

### 6.11 Iniciar el servidor de desarrollo

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Ejecutar `php artisan serve` (solo PHP) o `npm run dev` (PHP + Vite) | El servidor se inicia en `http://localhost:8000` |

**Opción 1 — Solo PHP:**
```bash
php artisan serve
# Servidor iniciado en http://localhost:8000
```

**Opción 2 — Stack completo (PHP + Vite HMR + Queue):**
```bash
composer run dev
```

[CAPTURA DE PANTALLA: Terminal con servidor de desarrollo en ejecución]

### 6.12 Verificar la instalación

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Abrir `http://localhost:8000` en el navegador | Se carga la página de inicio de RUPAL |
| 2 | Abrir `http://localhost:8000/staff/login` | Se carga la página de login del staff |
| 3 | Ejecutar `php artisan test` | Todos los tests pasan (verde) |

---

## 7. PROCEDIMIENTO DE CONFIGURACIÓN — PRODUCCIÓN (RAILWAY)

### 7.1 Preparar el Dockerfile

El proyecto incluye un `Dockerfile` listo para producción:

```dockerfile
FROM php:8.2-cli
WORKDIR /app
RUN apt-get update && apt-get install -y git unzip libzip-dev zip curl
RUN docker-php-ext-install pdo pdo_mysql zip
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY . .
RUN composer install --no-dev --optimize-autoloader
RUN php artisan config:clear || true
RUN php artisan route:clear || true
RUN php artisan view:clear || true
EXPOSE 8080
CMD php artisan serve --host=0.0.0.0 --port=$PORT
```

> **Nota**: El build de frontend (`npm run build`) debe ejecutarse antes del build de Docker o por separado, ya que el Dockerfile no incluye Node.js.

### 7.2 Variables de entorno en Railway

Configurar las siguientes variables en el panel de Railway:

| Variable | Valor | Notas |
|----------|-------|-------|
| `APP_ENV` | `production` | Obligatorio |
| `APP_DEBUG` | `false` | No exponer stack traces |
| `APP_KEY` | *(generada)* | Ejecutar `php artisan key:generate` |
| `APP_URL` | `https://rupal.lavalle.gob.ar` | URL pública |
| `DB_CONNECTION` | `mysql` | — |
| `DB_HOST` | *(provisto por Railway)* | Host de la BD MySQL |
| `DB_PORT` | `3306` | — |
| `DB_DATABASE` | *(provisto por Railway)* | Nombre de la BD |
| `DB_USERNAME` | *(provisto por Railway)* | Usuario de la BD |
| `DB_PASSWORD` | *(provisto por Railway)* | Contraseña de la BD |
| `SESSION_DRIVER` | `database` | Sesiones persistentes |
| `SESSION_ENCRYPT` | `true` | Cifrar sesiones |
| `SESSION_SECURE_COOKIE` | `true` | Cookies solo HTTPS |
| `LOG_LEVEL` | `warning` | No loguear debug |
| `MAIL_MAILER` | `smtp` | Mailtrap o SendGrid |
| `MAIL_HOST` | *(provisto)* | — |
| `MAIL_PORT` | `587` | — |
| `MAIL_USERNAME` | *(provisto)* | — |
| `MAIL_PASSWORD` | *(provisto)* | — |
| `MAIL_FROM_ADDRESS` | `noreply@lavalle.gob.ar` | — |
| `MAIL_FROM_NAME` | `RUPAL` | — |
| `BCRYPT_ROUNDS` | `12` | — |

[CAPTURA DE PANTALLA: Panel de Railway con variables de entorno configuradas]

### 7.3 Post-despliegue

Una vez desplegado el contenedor, ejecutar los siguientes comandos vía Railway Console:

| Paso | Comando | Propósito |
|------|---------|-----------|
| 1 | `php artisan migrate --force` | Ejecutar migraciones |
| 2 | `php artisan storage:link` | Crear enlace de storage |
| 3 | `php artisan key:generate` (si no se generó) | Generar APP_KEY |
| 4 | `php artisan config:cache` | Cachear configuración |
| 5 | `php artisan route:cache` | Cachear rutas |
| 6 | `php artisan view:cache` | Compilar vistas Blade |

[CAPTURA DE PANTALLA: Consola de Railway ejecutando comandos post-despliegue]

---

## 8. CONFIGURACIÓN ADICIONAL

### 8.1 Configurar el scheduler (CRON)

Agregar la siguiente entrada al crontab del servidor:

```bash
* * * * * cd /ruta/a/sistema-productores && php artisan schedule:run >> /dev/null 2>&1
```

Esto ejecuta cada minuto el scheduler de Laravel, que a su vez ejecuta las tareas programadas.

**Tareas programadas actuales:**

| Comando | Horario | Descripción |
|---------|---------|-------------|
| `app:purge-soft-deleted-records --days=365` | 03:00 diario | Purga registros soft-delete > 365 días |

### 8.2 Configurar el queue worker

En producción, iniciar un worker de cola para procesar jobs:

```bash
php artisan queue:work --tries=3 --timeout=90
```

Se recomienda gestionar este proceso con Supervisor (Linux) o similar.

### 8.3 Configurar Nginx (proxy reverso)

[CAPTURA DE PANTALLA: Archivo de configuración de Nginx]

Ejemplo de configuración de Nginx:

```nginx
server {
    listen 80;
    server_name rupal.lavalle.gob.ar;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name rupal.lavalle.gob.ar;

    ssl_certificate /etc/ssl/certs/rupal.crt;
    ssl_certificate_key /etc/ssl/private/rupal.key;

    root /var/www/sistema-productores/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    location /storage/ {
        alias /var/www/sistema-productores/storage/app/public/;
        expires 30d;
        add_header Cache-Control "public, immutable";
    }

    # Headers de seguridad (adicionales a SecureHeadersMiddleware)
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-Frame-Options "DENY" always;
}
```

### 8.4 Configurar SSL/TLS

Se recomienda usar Let's Encrypt (Certbot) para certificados SSL:

```bash
apt-get install certbot python3-certbot-nginx
certbot --nginx -d rupal.lavalle.gob.ar
```

O configurar SSL directamente desde Railway (SSL automático con *.railway.app o dominio personalizado).

---

## 9. VERIFICACIÓN POST-INSTALACIÓN

### 9.1 Checklist de verificación

| # | Verificación | Comando/Acción | Resultado Esperado |
|---|---|---|---|
| 1 | Versión de PHP | `php -v` | PHP 8.2 o superior |
| 2 | Extensiones PHP | `php -m \| grep -E "pdo\|mysql\|zip\|bcmath"` | Todas las extensiones listadas |
| 3 | Conexión a BD | `php artisan db:show` | Detalles de la BD mostrados |
| 4 | Migraciones | `php artisan migrate:status` | Todas las migraciones ejecutadas |
| 5 | APP_KEY generada | `grep APP_KEY .env` | Clave de 32 caracteres base64 |
| 6| Cache de configuración | `php artisan config:cache` | Configuration cached successfully |
| 7 | Cache de rutas | `php artisan route:cache` | Routes cached successfully |
| 8 | Almacenamiento | `php artisan storage:link` | The links have been created |
| 9 | Tests | `php artisan test` | Todos los tests pasan |
| 10 | Página de inicio | Curl a `https://rupal.lavalle.gob.ar` | Código 200 |

### 9.2 Verificación de seguridad

| # | Verificación | Método |
|---|---|---|
| 1 | HTTPS forzado | `curl -I http://rupal.lavalle.gob.ar` debe redirigir a HTTPS |
| 2 | Headers de seguridad | `curl -I https://rupal.lavalle.gob.ar` debe incluir CSP, X-Frame-Options, HSTS |
| 3 | APP_DEBUG=false | Verificar que no se muestren stack traces en errores |
| 4 | Rate limiting | Enviar 6 requests a `/login` en 1 minuto debe devolver 429 |
| 5 | Registro de sesiones | Verificar tabla `sessions` en la BD |

---

## 10. MANTENIMIENTO

### 10.1 Limpiar caches

| Comando | Propósito |
|---------|-----------|
| `php artisan cache:clear` | Limpiar caché de la aplicación |
| `php artisan config:clear` | Limpiar caché de configuración |
| `php artisan route:clear` | Limpiar caché de rutas |
| `php artisan view:clear` | Limpiar caché de vistas Blade |
| `clear_cache.cmd` | Script que ejecuta todos los comandos anteriores |

### 10.2 Actualizar dependencias

```bash
# PHP
composer update

# Node.js
npm update

# Verificar vulnerabilidades
composer audit
npm audit
```

### 10.3 Monitoreo de logs

| Archivo | Propósito |
|---------|-----------|
| `storage/logs/laravel.log` | Log principal de la aplicación |
| `storage/logs/purge-soft-deleted.log` | Log del purge de soft-deletes |

### 10.4 Respaldos

Se recomienda realizar respaldos periódicos de:

- Base de datos MySQL
- Directorio `storage/app/rut_files/` (documentos RUT)
- Archivo `.env`

---

## 11. SOLUCIÓN DE PROBLEMAS

### 11.1 Error: "No application encryption key specified"

**Causa**: `APP_KEY` no está configurada en el `.env`.

**Solución**:
```bash
php artisan key:generate
```

### 11.2 Error: "Target class [...] does not exist"

**Causa**: Cache de configuración desactualizada o archivos de clase no cargados.

**Solución**:
```bash
composer dump-autoload
php artisan config:clear
php artisan route:clear
```

### 11.3 Error: "Base table or view not found"

**Causa**: Migraciones no ejecutadas.

**Solución**:
```bash
php artisan migrate
```

### 11.4 Error: 419 Page Expired

**Causa**: Token CSRF expirado o sesión expirada.

**Solución**:
- Refrescar la página
- Si persiste: limpiar cookies del navegador
- En producción: verificar `SESSION_LIFETIME` y `SESSION_DRIVER`

### 11.5 Error: 403 Forbidden en staff

**Causa**: El usuario no tiene el rol adecuado.

**Solución**:
- Verificar que el usuario tenga rol `admin` o `auditor`
- Verificar que el usuario esté activo (`active = true`)

### 11.6 Error: "Class "..." not found" al usar PhpSpreadsheet

**Causa**: Dependencia de Composer no instalada.

**Solución**:
```bash
composer install
```

### 11.7 Error: Vite no conecta en desarrollo

**Causa**: El servidor de Vite no está corriendo o el puerto no coincide.

**Solución**:
```bash
npm run dev
# Verificar que Vite escuche en http://127.0.0.1:5173
```

### 11.8 Error: "Maximum execution time exceeded"

**Causa**: Límite de tiempo de ejecución de PHP excedido (común en exportaciones grandes).

**Solución**:
- Aumentar `max_execution_time` en `php.ini` (recomendado: 300 segundos)
- O configurar en el `.env`: `PHP_CLI_SERVER_WORKERS=4`

---

## 12. REFERENCIAS DE COMANDOS ÚTILES

| Comando | Descripción |
|---------|-------------|
| `composer run dev` | Iniciar stack completo de desarrollo |
| `composer run test` | Ejecutar todos los tests |
| `php artisan serve` | Iniciar servidor PHP |
| `npm run dev` | Iniciar Vite HMR |
| `npm run build` | Compilar assets para producción |
| `php artisan migrate` | Ejecutar migraciones pendientes |
| `php artisan migrate:fresh --seed` | Reiniciar BD con datos de prueba |
| `php artisan queue:work` | Procesar jobs de la cola |
| `php artisan storage:link` | Crear enlace simbólico de storage |
| `php artisan app:purge-soft-deleted-records --dry-run` | Vista previa de registros a purgar |
| `php artisan config:cache` | Cachear configuración |
| `php artisan route:cache` | Cachear rutas |
| `php artisan view:cache` | Compilar vistas |
| `php artisan key:generate` | Generar APP_KEY |
| `vendor/bin/pint` | Formatear código PHP |
| `php artisan test --coverage` | Tests con cobertura |
| `composer audit` | Auditar vulnerabilidades de PHP |
| `npm audit` | Auditar vulnerabilidades de Node.js |
