# RUPAL — Sistema de Productores

Sistema de gestión de productores agrícolas: alta y mantenimiento de **propiedades, cultivos, maquinaria y comercialización** para productores, y un **panel staff** (roles `admin` / `auditor`) con consulta y exportación de productores y administración de usuarios staff.

El sistema tiene tres puertas de entrada:

| Superficie | Guard | Autenticación |
|---|---|---|
| Portal del productor | `web` | sesión con formulario (`/login`) |
| Panel staff | `staff` | sesión con formulario (`/staff/login`) |
| API staff | `staff-api` | tokens Sanctum (`POST /api/staff/login`) |

## Requisitos

- **PHP 8.4** con las extensiones `pdo`, `pdo_mysql`, `zip` y `gd` (ver `composer.json`, `composer check-platform-reqs --no-dev` y `Dockerfile`).
- **Composer 2**.
- **Node.js 22 LTS** recomendado para compilar assets (Vite 8 exige `^20.19.0 || >=22.12.0`).
- **MySQL** como base de datos (valores por defecto en `.env.example`).

## Instalación local

```bash
composer install
cp .env.example .env        # Windows PowerShell: Copy-Item .env.example .env
php artisan key:generate
```

Crear la base de datos y ajustar `DB_HOST`, `DB_DATABASE`, `DB_USERNAME` y `DB_PASSWORD` en `.env`. Luego:

```bash
php artisan migrate --seed
npm ci
npm run build               # en desarrollo: npm run dev
php artisan serve
```

Queda disponible en `http://localhost:8000`:

- Portal del productor: `/login`
- Panel staff: `/staff/login`
- Verificación pública de documentos (QR): `/verificar/{token}`
- Health check: `/up`

`php artisan serve` con la cola, los logs y Vite en un solo comando:

```bash
composer run dev
```

> El seeding de datos de demostración (usuarios demo, propiedades, maquinaria, cultivos y el dataset de prueba) **solo corre en `local` y `testing`**. En producción `migrate --seed` crea únicamente los roles.

## Autenticación y API staff

El login de la API es `POST /api/staff/login` (ruta pública, limitada a 5 intentos por minuto por `email|ip`):

```bash
curl -X POST http://localhost:8000/api/staff/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"usuario@example.com","password":"••••••••","device_name":"panel"}'
```

Respuesta: `{ "token": "...", "user": { "id": 1, "name": "...", "email": "...", "role": "admin" } }`.

El resto de las rutas se autentican con `Authorization: Bearer <token>` y están limitadas a 60 requests por minuto. `POST /api/staff/logout` revoca **todos** los tokens del usuario.

| Método | Ruta | Roles |
|---|---|---|
| `POST` | `/api/staff/login` | público |
| `GET` | `/api/staff/me` | admin, auditor |
| `POST` | `/api/staff/logout` | admin, auditor |
| `GET` | `/api/staff/dashboard` | admin, auditor |
| `GET` | `/api/staff/producers` | admin, auditor |
| `GET` | `/api/staff/producers/{id}` | admin, auditor |
| `GET` | `/api/staff/producers/export` | admin, auditor |
| `GET` `POST` `PATCH` `DELETE` | `/api/staff/users*` (index, create, store, edit, update, destroy) | admin |

## Matriz de roles staff

Fuente: `routes/web.php`, `routes/api.php`, los gates de `app/Providers/AuthServiceProvider.php` y `app/Policies/StaffUserPolicy.php`.

| Capacidad | Admin | Auditor |
|---|---|---|
| Panel `/staff/dashboard` (gate `view-dashboard`) | ✅ | ✅ |
| Consulta de productores: `/staff/producers`, `/staff/producers/{id}` (gate `view-producers`) | ✅ | ✅ |
| Exportación desde el panel: `/staff/producers/export`, `/staff/producers/export-all` (gate `export-producers`) | ✅ | ✅ |
| Informe PDF de un productor desde su detalle: botón **Exportar PDF** en `/staff/producers/{id}` | ✅ | ✅ |
| Gestión de usuarios staff `/staff/users*` (gate `manage-staff` y `StaffUserPolicy`) | ✅ | ❌ |
| API: `me`, `logout`, `dashboard`, `producers`, `producers/{id}`, `producers/export` | ✅ | ✅ |
| API: `users*` (grupo `staff.role:admin`) | ✅ | ❌ |

Notas:

- Un usuario staff puede ver y editar sus propios datos según `StaffUserPolicy`, pero **no puede cambiar su propio rol** ni eliminarse a sí mismo; además las rutas de gestión (`/staff/users*` y `/api/staff/users*`) están restringidas al rol `admin`.
- La exportación de productores es idéntica para Admin y Auditor: en el panel, el listado filtrado (`StaffProducerController::export`) y el completo (`exportAll`) generan XLSX; en la API, `GET /api/staff/producers/export` también responde a ambos roles.
- El **informe PDF de un productor no lo genera el backend**: `resources/js/Pages/Staff/Producers/Show.vue` renderiza el informe (`#pdf-report`) y lo imprime con `window.print()` desde el detalle del productor. No hay ninguna librería PDF en `composer.json` ni en `package.json`, y la API no sirve PDF.
- Regla de negocio: los filtros **Distrito, Variedad, Tipo y Manejo** alimentan el Excel de la lista; las búsquedas por **Nombre, DNI y RUT** localizan al productor, cuyo informe PDF se obtiene desde su detalle; **Exportar todos** genera el Excel completo del padrón.
- Los productores usan roles de Spatie (`admin`, `productor`) y policies por recurso con verificación de propiedad (`app/Policies/`).

## Variables de entorno sensibles

### `TRUSTED_PROXIES`

Lista de IPs o rangos separados por coma (`10.0.0.1,10.0.0.0/8`). Sin valor, la aplicación no confía en ningún proxy y **ignora** `X-Forwarded-*`: `request()->ip()` devuelve la IP de la conexión.

Si la aplicación queda detrás de un proxy o balanceador y esta variable no se configura:

- Todos los clientes comparten la IP del proxy, así que el límite de intentos de login (`email|ip`, 5 por minuto) pasa a ser un único bucket compartido y unos pocos intentos fallidos bloquean a todo el mundo.
- La detección de esquema y host (`https`, `APP_URL`) puede ser incorrecta.

`*` hace que se confíe en la IP que abre la conexión, cualquiera sea. **Solo es seguro si la aplicación es accesible únicamente a través del proxy del hosting**; si no, cargar la IP o el rango del proxy.

### `CORS_ALLOWED_ORIGINS`

`APP_URL` siempre se permite. Los orígenes adicionales se agregan separados por coma (`https://panel.example.com,https://admin.example.com`). Si se omite, los navegadores solo pueden llamar a la API desde `APP_URL`, y una SPA o panel alojado en otro dominio recibe la API sin encabezados CORS. El comodín `*` se ignora a propósito: la API no se abre a cualquier sitio.

### Cookies de sesión

`SESSION_SECURE_COOKIE=true` (solo HTTPS) en producción; `false` únicamente para pruebas locales servidas por HTTP. Las sesiones se cifran con `SESSION_ENCRYPT=true` y requieren `APP_KEY` (`php artisan key:generate`).

## Tests y calidad de código

```bash
php artisan test                  # suite completa (Pest)
php artisan test --filter=nombre  # un test puntual
composer run test                 # limpia la config cacheada y corre la suite
./vendor/bin/pint                 # formatea con Laravel Pint
./vendor/bin/pint --test          # verifica sin corregir
```

Los tests corren sobre SQLite en memoria (`phpunit.xml`), sin tocar la base de datos de desarrollo.

## Docker

El `Dockerfile` compila los assets con Vite en una etapa aparte, instala solo dependencias de producción y sirve la aplicación con `php artisan serve` en el puerto indicado por la variable `PORT` (`EXPOSE 8080`). Al ejecutar el contenedor hay que montar volúmenes persistentes para `storage/app/rut_files` (PDF de RUT) y `storage/logs`; el detalle de despliegue, volúmenes, respaldo y monitoreo está en el manual.

## Documentación

| Documento | Contenido |
|---|---|
| [`docs/index.md`](docs/index.md) | Índice general de la documentación |
| [`docs/manual_administrador/07-instalacion.md`](docs/manual_administrador/07-instalacion.md) | Instalación, configuración y despliegue en producción |
| [`docs/manual_administrador/`](docs/manual_administrador/) | Manual del administrador (panel, productores, usuarios, API) |
| [`docs/manual_usuario/`](docs/manual_usuario/) | Manual del productor |
| [`docs/datos_prueba.md`](docs/datos_prueba.md) | Dataset de pruebas |
| [`AGENTS.md`](AGENTS.md) | Convenciones de desarrollo y controles ISO 27001/27002/27701 |
