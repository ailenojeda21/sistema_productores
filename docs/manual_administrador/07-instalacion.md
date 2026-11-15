# Manual de Administrador — Instalación y Despliegue

**Código**: RUPAL-MA-07  
**Versión**: 1.1  
**Fecha**: 2026-11-15  
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
| Entorno de producción | Servidor cloud (por definir) donde se ejecuta el sistema en vivo |
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
| PHP | 8.4.0 | 8.4.x |
| Composer | 2.x | 2.8+ |
| Node.js | 20.19 (Vite 8) | 22 LTS |
| NPM | 10.x | 11.x |
| MySQL | 8.0 | 8.4+ / MariaDB 11+ |
| Redis | 6.0 | 7.4+ (opcional) |
| Servidor Web | Nginx 1.24 / Apache 2.4 | Nginx 1.26+ |

> El proyecto declara `php: ^8.4` en `composer.json` (plataforma `8.4.1`) y la imagen final del `Dockerfile` es `php:8.4-cli`. Vite 8 exige Node `^20.19.0 || >=22.12.0`.

### 5.2 Extensiones PHP requeridas

Requeridas en producción según `composer check-platform-reqs --no-dev`:

| Extensión | Propósito |
|-----------|-----------|
| `pdo` | Conexión a base de datos |
| `pdo_mysql` | Driver MySQL/MariaDB |
| `zip` | Compresión (PhpSpreadsheet, paquetes Composer) |
| `gd` | Generación de imágenes en PhpSpreadsheet (exportación XLSX) |
| `dom`, `xml`, `xmlreader`, `xmlwriter`, `libxml` | Lectura y escritura de XML (PhpSpreadsheet, dependencias) |
| `mbstring`, `iconv` | Cadenas UTF-8 (español) |
| `fileinfo` | Detección de tipos MIME |
| `ctype`, `filter`, `hash`, `json`, `openssl`, `pcre`, `session`, `tokenizer`, `zlib` | Base de Laravel y sus dependencias |

La imagen `php:8.4-cli` ya compila `ctype`, `dom`, `fileinfo`, `filter`, `hash`, `iconv`, `json`, `libxml`, `mbstring`, `openssl`, `pcre`, `session`, `simplexml`, `tokenizer`, `xml`, `xmlreader`, `xmlwriter` y `zlib`. El `Dockerfile` agrega `pdo`, `pdo_mysql`, `zip` y `gd` (con `libpng`, `libjpeg` y `libfreetype`).

> `bcmath` no es requerida por ningún paquete en producción; si el hosting la ofrece, no molesta, pero no es obligatoria.

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
| `DB_HOST` | `127.0.0.1` | `<host-produccion>` |
| `DB_PORT` | `3306` | `<puerto-produccion>` |
| `DB_DATABASE` | `sist_prod` | `sist_prod` |
| `DB_USERNAME` | `laravel` | `<usuario-bd>` |
| `DB_PASSWORD` | *(vacío)* | `<contraseña-bd>` |
| `SESSION_DRIVER` | `database` | `database` |
| `SESSION_ENCRYPT` | `true` | `true` |
| `SESSION_SECURE_COOKIE` | `false` (HTTP local) | `true` (solo HTTPS) |
| `SESSION_LIFETIME` | `120` | `120` |
| `LOG_STACK` | `daily` | `daily` |
| `LOG_LEVEL` | `info` | `info` |
| `LOG_DAILY_DAYS` | `30` | `30` |
| `TRUSTED_PROXIES` | *(vacío)* | IP o rango del proxy (ver 7.4) |
| `CORS_ALLOWED_ORIGINS` | *(vacío)* | dominios extra, si hay (ver 7.4) |
| `MAIL_MAILER` | `log` | `smtp` |
| `MAIL_HOST` | — | `<host-smtp>` |
| `MAIL_PORT` | — | `587` |
| `MAIL_USERNAME` | — | `<usuario-smtp>` |
| `MAIL_PASSWORD` | — | `<contraseña-smtp>` |
| `MAIL_FROM_ADDRESS` | — | `noreply@lavalle.gob.ar` |
| `MAIL_FROM_NAME` | `RUPAL` | `RUPAL` |
| `BCRYPT_ROUNDS` | `12` | `12` |
| `SANCTUM_TOKEN_PREFIX` | `rupal_staff_` | `rupal_staff_` |

> `LOG_LEVEL=info` es deliberado: los eventos de auditoría (`LOGIN_SUCCESS`, `LOGOUT`) se registran con nivel `info`. Con `warning` o superior se pierden y no queda rastro de los accesos.
>
> `SESSION_SECURE_COOKIE=true` hace que la cookie de sesión viaje solo por HTTPS. En un entorno local servido por HTTP hay que ponerlo en `false`, o el navegador no enviará la cookie.

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
| 1 | Ejecutar `npm ci` | NPM descarga las dependencias exactas del `package-lock.json` |

```bash
npm ci
```

**Resultado esperado:**
```
added XXX packages, and audited XXX packages in Ys
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
| 2 | Crear la base de datos: `CREATE DATABASE sist_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;` | Base de datos creada |
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

### 6.9 Poblar la base (opcional)

`php artisan db:seed` ejecuta `DatabaseSeeder`, que crea los roles de Spatie (`admin`, `productor`) y, **solo en `local` y `testing`**, los seeders de dominio: propiedades, maquinaria, cultivos y el dataset de pruebas de 100 productores descrito en `docs/datos_prueba.md`.

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Ejecutar `php artisan db:seed` | Se crean los roles (y en local, los datos de demostración) |

```bash
php artisan db:seed

# equivalente a migrar y sembrar en un entorno local
php artisan migrate --seed
```

### 6.10 Enlace de storage (no requerido)

El código actual **no depende de `public/storage`**:

- Los archivos RUT se guardan en el disco privado `rut_files` (`storage/app/rut_files`, ver `config/filesystems.php`) y se descargan a través del controlador, no por URL pública.
- El disco `local` de Laravel apunta a `storage/app/private`, pero la aplicación **no** guarda los RUT ahí: `PropiedadController` escribe siempre con `Storage::disk('rut_files')`. Si una instalación anterior tiene PDFs en `storage/app/private/rut_files`, la versión actual no los lee: hay que moverlos a `storage/app/rut_files` conservando el nombre de archivo si la base todavía los referencia.
- No hay ningún uso del disco `public` para archivos subidos por los usuarios.

En Docker, `storage/app/rut_files` es un volumen persistente obligatorio (ver 7.1 → Volúmenes persistentes).

Por eso `php artisan storage:link` **no es necesario** para el funcionamiento del sistema. Solo hay que ejecutarlo si en el futuro se publican archivos mediante el disco `public` (`storage/app/public`).

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

## 7. PROCEDIMIENTO DE CONFIGURACIÓN — PRODUCCIÓN (hosting por definir)

### 7.1 Imagen Docker

El repositorio incluye un `Dockerfile` multi-etapa:

| Etapa | Imagen base | Qué hace |
|-------|-------------|----------|
| `vendor` | `composer:2` | `composer install --no-dev` para que Vite resuelva `tightenco/ziggy`, que importa `resources/js/app.js` |
| `assets` | `node:22-alpine` | `npm ci` + `npm run build`; los assets se copian a la imagen final |
| final | `php:8.4-cli` | extensiones `pdo`, `pdo_mysql`, `zip`, `gd` y `curl`; `composer install --no-dev --optimize-autoloader`; `route:cache` y `view:cache`; usuario `laravel` sin privilegios |

El build no requiere compilar los assets en la máquina que construye la imagen: los genera la etapa `assets`.

Al arrancar el contenedor, el `CMD` ejecuta `php artisan config:cache` y luego `php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"`:

- **La configuración no se cachea en el build.** `APP_KEY`, `DB_*`, `APP_URL`, `TRUSTED_PROXIES` y `CORS_ALLOWED_ORIGINS` llegan en runtime; un `config:cache` durante el build congelaría esos valores vacíos dentro de la imagen.
- El puerto se toma de `PORT` (por defecto `8080`, el mismo de `EXPOSE 8080`).
- `HEALTHCHECK` consulta `http://127.0.0.1:${PORT:-8080}/up` cada 30 segundos con `curl` (timeout 5 s, 3 reintentos, 30 s de gracia inicial).

#### Volúmenes persistentes

El contenedor es efímero: lo que se escribe en `storage/` se pierde al recrearlo. La imagen no debe contener datos de usuarios (están excluidos en `.dockerignore`), así que al ejecutarla hay que montar volúmenes para:

| Ruta en el contenedor | Contenido | Si falta el volumen |
|---|---|---|
| `/app/storage/app/rut_files` | PDF de RUT subidos por los productores (disco `rut_files`, ver `config/filesystems.php`) | los documentos se pierden al recrear el contenedor y sus descargas responden 404 |
| `/app/storage/logs` | Logs diarios (`laravel-YYYY-MM-DD.log`), incluidos `LOGIN_SUCCESS` y `LOGOUT` | se pierde el rastro de auditoría de accesos |

Ejemplo con bind mounts (el contenedor corre como usuario `laravel`, UID/GID 1000):

```bash
sudo mkdir -p /srv/rupal/rut_files /srv/rupal/logs
sudo chown -R 1000:1000 /srv/rupal/rut_files /srv/rupal/logs

docker run -d --name rupal \
  -p 8080:8080 \
  --env-file /srv/rupal/.env \
  -v /srv/rupal/rut_files:/app/storage/app/rut_files \
  -v /srv/rupal/logs:/app/storage/logs \
  rupal:latest
```

> Los bind mounts conservan el dueño del directorio del host: sin el `chown 1000:1000` la aplicación no puede escribir. Con volúmenes nombrados (`-v rupal_rut_files:/app/storage/app/rut_files`), Docker crea el punto de montaje como `root` cuando la ruta no existe en la imagen; en ese caso hay que crear el directorio con el dueño correcto antes de arrancar.

`storage/app/private` y `storage/app/public` no reciben archivos de usuario: los RUT se guardan en el disco `rut_files` (`storage/app/rut_files`) y el disco `public` no se usa para subidas (ver 6.10). Solo harían falta volúmenes en esas rutas si en el futuro se empiezan a usar los discos `local` o `public`.

**Deuda conocida**: la imagen sirve la aplicación con `php artisan serve`, el servidor embebido de PHP, porque el hosting todavía no está definido. Cuando se defina, conviene migrar a **php-fpm + Nginx** (configuración de referencia en 8.3): `artisan serve` es adecuado para desarrollo y pruebas, no para tráfico de producción.

> **Sin verificar**: en el entorno de desarrollo de referencia no hay Docker instalado, así que `docker build` no se pudo ejecutar. Se validaron la sintaxis del `Dockerfile`, `route:cache`/`view:cache` (incluso sin `.env` ni `APP_KEY`, como en el build), `config:cache` al arrancar y la respuesta `200` de `/up` con `php artisan serve` local.

### 7.2 Variables de entorno en el proveedor de hosting (por definir)

Configurar las siguientes variables en el panel del proveedor de hosting (por definir):

| Variable | Valor | Notas |
|----------|-------|-------|
| `APP_ENV` | `production` | Obligatorio |
| `APP_DEBUG` | `false` | No exponer stack traces |
| `APP_KEY` | *(generada)* | `php artisan key:generate --show` en local y pegar el valor en el panel |
| `APP_URL` | `https://rupal.lavalle.gob.ar` | URL pública |
| `TRUSTED_PROXIES` | IP o rango del proxy | Ver 7.4. Vacío si la app no está detrás de un proxy |
| `CORS_ALLOWED_ORIGINS` | dominios extra, si hay | Ver 7.4. `APP_URL` siempre se permite |
| `DB_CONNECTION` | `mysql` | — |
| `DB_HOST` | *(provisto por el hosting)* | Host de la BD MySQL |
| `DB_PORT` | `3306` | — |
| `DB_DATABASE` | *(provisto por el hosting)* | Nombre de la BD |
| `DB_USERNAME` | *(provisto por el hosting)* | Usuario de la BD |
| `DB_PASSWORD` | *(provisto por el hosting)* | Contraseña de la BD |
| `SESSION_DRIVER` | `database` | Sesiones persistentes |
| `SESSION_ENCRYPT` | `true` | Cifrar sesiones |
| `SESSION_SECURE_COOKIE` | `true` | Cookies solo HTTPS |
| `LOG_STACK` | `daily` | Un archivo por día |
| `LOG_LEVEL` | `info` | Nivel mínimo; `info` conserva los eventos de auditoría |
| `LOG_DAILY_DAYS` | `30` | Retención de logs diarios |
| `MAIL_MAILER` | `smtp` | Mailtrap o SendGrid |
| `MAIL_HOST` | *(provisto)* | — |
| `MAIL_PORT` | `587` | — |
| `MAIL_USERNAME` | *(provisto)* | — |
| `MAIL_PASSWORD` | *(provisto)* | — |
| `MAIL_FROM_ADDRESS` | `noreply@lavalle.gob.ar` | — |
| `MAIL_FROM_NAME` | `RUPAL` | — |
| `BCRYPT_ROUNDS` | `12` | — |

[CAPTURA DE PANTALLA: Panel del proveedor de hosting (por definir) con variables de entorno configuradas]

### 7.3 Post-despliegue

Con el contenedor en ejecución, ejecutar en la consola del proveedor de hosting (por definir):

| Paso | Comando | Propósito |
|------|---------|-----------|
| 1 | `php artisan migrate --force` | Aplicar las migraciones pendientes |
| 2 | `php artisan db:seed --force` | Crear los roles de Spatie (en producción no crea datos de demostración) |
| 3 | `curl -fsS https://<dominio>/up` | Verificar la ruta de salud: debe responder `200` |

No hace falta ejecutar `config:cache`, `route:cache`, `view:cache` ni `storage:link`: los caches los generan el build y el arranque del contenedor (7.1) y el enlace de storage no se usa (6.10).

[CAPTURA DE PANTALLA: Consola del proveedor de hosting (por definir) ejecutando comandos post-despliegue]

### 7.4 Proxies de confianza y CORS

Son las dos variables que más se olvidan en producción y las que más alteran el comportamiento del sistema.

#### `TRUSTED_PROXIES`

`config/trustedproxy.php` lee `TRUSTED_PROXIES`: lista de IPs o rangos separados por coma (`10.0.0.1,10.0.0.0/8`).

- **Sin valor (o vacío)** no se confía en ningún proxy: los encabezados `X-Forwarded-*` se ignoran y `request()->ip()` devuelve la IP de la conexión.
- Si la aplicación está detrás de un proxy o balanceador y la variable queda vacía, **todos los clientes comparten la IP del proxy**. El límite de intentos de login usa la clave `email|ip` (5 por minuto en `/login`, `/staff/login` y `/api/staff/login`), así que unos pocos intentos fallidos bloquean a todos los usuarios. Además la detección de HTTPS puede ser incorrecta y afectar a las cookies `secure`.
- `*` confía en la IP que abre la conexión, cualquiera sea. **Solo es seguro si la aplicación es accesible únicamente a través del proxy del hosting**; si también hay acceso directo, cualquiera puede falsificar `X-Forwarded-For` y saltar el límite de intentos.

#### `CORS_ALLOWED_ORIGINS`

`config/cors.php` permite `APP_URL` más los orígenes de `CORS_ALLOWED_ORIGINS` (separados por coma). El comodín `*` se descarta a propósito: la API no se abre a cualquier sitio.

Si la variable se omite, los navegadores solo pueden llamar a `/api/*` y `/sanctum/csrf-cookie` desde `APP_URL`; una SPA o panel alojado en otro dominio recibe la respuesta sin encabezados CORS.

#### Auto-confianza en Laravel Forge, Vapor y Laravel Cloud

`Illuminate\Http\Middleware\TrustProxies` confía en todos los proxies (`*`) cuando `TRUSTED_PROXIES` no está definida y el host de la petición termina en `.on-forge.com` o `.on-vapor.com`, o cuando la aplicación corre en Laravel Cloud. En cualquier otra plataforma conviene definir `TRUSTED_PROXIES` explícitamente.

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
| `sanctum:prune-expired` | 00:00 diario | Elimina tokens Sanctum vencidos |

Para confirmar que el cron está activo: `php artisan schedule:list` muestra las tareas y su próxima ejecución. Si el cron no corre, la purga y la limpieza de tokens no se ejecutan nunca.

### 8.2 Configurar el queue worker

En producción, iniciar un worker de cola para procesar jobs:

```bash
php artisan queue:work --tries=3 --timeout=90
```

Se recomienda gestionar este proceso con Supervisor (Linux) o similar.

### 8.3 Configurar Nginx (proxy reverso)

Aplica cuando la aplicación se sirva con **php-fpm + Nginx** en lugar de la imagen con `php artisan serve` (ver la deuda conocida en 7.1). El proyecto exige PHP 8.4.

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
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
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

O configurar SSL directamente desde el proveedor de hosting (por definir) (SSL automático con el dominio del hosting o dominio personalizado).

---

## 9. VERIFICACIÓN POST-INSTALACIÓN

### 9.1 Checklist de verificación

| # | Verificación | Comando/Acción | Resultado Esperado |
|---|---|---|---|
| 1 | Versión de PHP | `php -v` | PHP 8.4.x |
| 2 | Extensiones PHP | `php -m \| grep -E "pdo\|mysql\|zip"` | Todas las extensiones listadas |
| 3 | Conexión a BD | `php artisan db:show` | Detalles de la BD mostrados |
| 4 | Migraciones | `php artisan migrate:status` | Todas las migraciones ejecutadas |
| 5 | APP_KEY generada | `grep APP_KEY .env` | Clave de 32 caracteres base64 |
| 6 | Caches de la aplicación | `ls bootstrap/cache` | `config.php`, `routes-v*.php` y `packages.php` presentes |
| 7 | Ruta de salud | `curl -fsS https://rupal.lavalle.gob.ar/up` | Código 200 |
| 8 | Tests | `php artisan test` | Todos los tests pasan |
| 9 | Página de inicio | `curl -I https://rupal.lavalle.gob.ar` | Código 200 |

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

> En la imagen Docker la configuración se cachea al arrancar el contenedor: después de `php artisan config:clear` hay que reiniciar el contenedor (o ejecutar `php artisan config:cache`) para volver a compilarla.

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

### 10.3 Logs de la aplicación

Con `LOG_STACK=daily` y `LOG_LEVEL=info` (valores de `.env.example`) la aplicación escribe **un archivo por día**:

| Archivo | Propósito |
|---------|-----------|
| `storage/logs/laravel-YYYY-MM-DD.log` | Log principal; se conservan `LOG_DAILY_DAYS` días (30 por defecto) |

Los eventos de auditoría `LOGIN_SUCCESS` y `LOGOUT` se registran con nivel `info`, por eso `LOG_LEVEL=info`: con `warning` se descartan. El comando `app:purge-soft-deleted-records` no escribe un log propio; informa por la salida del comando.

### 10.4 Respaldos

La base de datos es MySQL (`DB_CONNECTION=mysql`). Hay que respaldar:

1. La base de datos.
2. El directorio `storage/app/rut_files/` (documentos RUT, que no viven en la base de datos).
3. El archivo `.env` (contiene `APP_KEY` y credenciales): guardarlo cifrado y con acceso restringido.

Comando de dump, usando los mismos valores de `DB_HOST`, `DB_PORT`, `DB_USERNAME` y `DB_DATABASE` del `.env`:

```bash
mysqldump \
  --single-transaction --quick --routines --triggers \
  -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" -p \
  "$DB_DATABASE" | gzip > "respaldo_$(date +%F_%H%M).sql.gz"
```

- `--single-transaction` evita bloquear las tablas InnoDB durante el dump.
- La contraseña se pide de forma interactiva (`-p` sin valor). Para automatizar, usar un archivo de opciones de MySQL (`~/.my.cnf`, permisos `600`), nunca la contraseña en la línea de comandos ni en el historial.

**Frecuencia y retención sugeridas**

| Respaldo | Frecuencia | Retención |
|----------|------------|-----------|
| Base de datos | Diario, fuera del horario de uso | 30 días |
| `storage/app/rut_files/` | Diario | 30 días |
| Copia mensual (BD + RUT + `.env`) | Mensual | 12 meses |

Los respaldos deben guardarse **fuera del servidor de aplicación** (otro host u object storage en la nube), cifrados y con acceso restringido: contienen datos personales (ISO 27701 / ISO 27018).

**Procedimiento de prueba de restauración** (trimestral y después de cada cambio de hosting)

1. Crear una base temporal:
   ```bash
   mysql -h "$DB_HOST" -u "$DB_USERNAME" -p \
     -e "CREATE DATABASE sist_prod_restore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   ```
2. Restaurar el dump en la base temporal:
   ```bash
   gunzip -c respaldo_2026-11-15_0300.sql.gz \
     | mysql -h "$DB_HOST" -u "$DB_USERNAME" -p sist_prod_restore
   ```
3. Apuntar una copia de la aplicación a la base restaurada (`DB_DATABASE=sist_prod_restore`) y verificar el esquema:
   ```bash
   php artisan migrate:status
   php artisan db:show
   ```
4. Comparar conteos con producción:
   ```sql
   SELECT 'users' AS tabla, COUNT(*) AS filas FROM users
   UNION ALL SELECT 'propiedades', COUNT(*) FROM propiedades
   UNION ALL SELECT 'cultivos', COUNT(*) FROM cultivos
   UNION ALL SELECT 'maquinarias', COUNT(*) FROM maquinarias;
   ```
5. Probar un inicio de sesión y la descarga de un RUT desde esa copia.
6. Registrar fecha, responsable y resultado de la prueba, y eliminar la base temporal al terminar.

### 10.5 Monitoreo

Revisión mínima sugerida (diaria en producción):

| Qué revisar | Cómo | Señal de problema |
|-------------|------|-------------------|
| Disponibilidad | `curl -fsS https://<dominio>/up` o el `HEALTHCHECK` del contenedor (cada 30 s) | Sin `200`, o contenedor `unhealthy` |
| Errores de aplicación | `storage/logs/laravel-YYYY-MM-DD.log`, buscando `ERROR` y `CRITICAL` | Errores repetidos o stack traces |
| Accesos y auditoría | Eventos `LOGIN_SUCCESS` y `LOGOUT` en el mismo log | Intentos masivos o IPs inesperadas |
| Espacio en disco | `df -h` en el host; en Docker, `docker system df` | Menos de 20 % libre |
| Scheduler | `php artisan schedule:list` y la salida del cron | Tareas vencidas: el cron no corre |
| Queue worker | Proceso `php artisan queue:work` activo y tamaño de la tabla `jobs` | Jobs acumulados sin procesar |
| Respaldos | Existencia y tamaño del dump del día | Dump faltante o de 0 bytes |

`LOG_DAILY_DAYS=30` acota el crecimiento de `storage/logs`; si aun así el disco se llena, ajustar la retención. Los registros de monitoreo y respaldos deben conservarse según la política de la organización (mínimo 12 meses).

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
- Aumentar `max_execution_time` en `php.ini` (recomendado: 300 segundos) y el `memory_limit` si el error es de memoria.
- Exportar por tramos: la pantalla de productores permite exportar el listado filtrado (`/staff/producers/export`) en lugar del completo (`/staff/producers/export-all`).
- `PHP_CLI_SERVER_WORKERS` solo define cuántos workers usa `php artisan serve`; no cambia el tiempo máximo de ejecución.

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
| `php artisan schedule:list` | Ver las tareas programadas y su próxima ejecución |
| `php artisan db:show` | Ver el estado y tamaño de la base de datos |
| `php artisan storage:link` | Crear el enlace de storage (no requerido por el código actual, ver 6.10) |
| `php artisan app:purge-soft-deleted-records --dry-run` | Vista previa de registros a purgar |
| `php artisan config:cache` | Cachear configuración |
| `php artisan route:cache` | Cachear rutas |
| `php artisan view:cache` | Compilar vistas |
| `php artisan key:generate` | Generar APP_KEY |
| `vendor/bin/pint` | Formatear código PHP |
| `php artisan test --coverage` | Tests con cobertura |
| `composer audit` | Auditar vulnerabilidades de PHP |
| `npm audit` | Auditar vulnerabilidades de Node.js |
