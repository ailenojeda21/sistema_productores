# Guía de Instalación - Sistema de Gestión para Productores Agrícolas

Esta guía proporciona instrucciones detalladas para configurar el entorno de desarrollo del Sistema de Gestión para Productores Agrícolas basado en Laravel con diseño SAP Fiori.

## Requisitos Previos

Antes de comenzar, asegúrate de tener instalado:

- PHP 8.2 o superior
- Composer (versión 2.x recomendada)
- Node.js (versión 18.x o superior) y npm
- Git
- MySQL, PostgreSQL o SQLite
- Servidor web local (como Apache, Nginx o el servidor integrado de PHP)
- Editor de código (VS Code, PhpStorm, etc.)

## Paso 1: Clonar el Repositorio

```bash
git clone [URL_DEL_REPOSITORIO] sistema_productores
cd sistema_productores
```

## Paso 2: Instalar Dependencias de PHP

```bash
composer install
```

## Paso 3: Configurar el Entorno

1. Crea una copia del archivo de entorno:

```bash
cp .env.example .env
```

2. Genera la clave de aplicación:

```bash
php artisan key:generate
```

3. Abre el archivo `.env` y configura la conexión a la base de datos:

```
DB_CONNECTION=mysql   # mysql, pgsql, sqlite
DB_HOST=127.0.0.1
DB_PORT=3306          # 3306 para MySQL, 5432 para PostgreSQL
DB_DATABASE=sistema_productores
DB_USERNAME=root      # Reemplaza con tu usuario
DB_PASSWORD=          # Reemplaza con tu contraseña
```

## Paso 4: Configurar la Base de Datos

1. Crea una base de datos vacía con el nombre configurado en `.env`:

```bash
# Para MySQL
mysql -u root -p
CREATE DATABASE sistema_productores;
exit
```

2. Ejecuta las migraciones y seeders para crear las tablas y datos iniciales:

```bash
php artisan migrate --seed
```

## Paso 5: Instalar Dependencias de Frontend

```bash
npm install
```

## Paso 6: Compilar Assets de Frontend

```bash
npm run dev
```

Para producción, usa:

```bash
npm run build
```

## Paso 7: Crear Enlace Simbólico para Almacenamiento

```bash
php artisan storage:link
```

## Paso 8: Configurar Permisos (Solo para entornos Linux/Unix)

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

## Paso 9: Iniciar el Servidor de Desarrollo

```bash
php artisan serve
```

El sistema estará disponible en [http://localhost:8000](http://localhost:8000).

## Credenciales de Acceso Predeterminadas

| Rol       | Usuario             | Contraseña    |
|-----------|---------------------|---------------|
| Admin     | admin@demo.com      | admin123      |
| Productor | productor@demo.com  | productor123  |

## Configuraciones Adicionales

### Configurar Cola de Trabajos (Opcional)

Si se requiere procesamiento en segundo plano:

```bash
php artisan queue:work
```

### Regenerar Documentación de API (Swagger)

```bash
php artisan l5-swagger:generate
```

### Limpiar Caché

Si encuentras problemas después de cambios importantes:

```bash
php artisan optimize:clear
```

o usa el script incluido:

```bash
./clear_cache.cmd
```

## Solución de Problemas Comunes

### Error: "No application encryption key has been specified"
Ejecuta `php artisan key:generate` para generar la clave de aplicación.

### Error: "The stream or file storage/logs/laravel.log could not be opened"
Verifica que los permisos de carpeta sean correctos para `storage` y `bootstrap/cache`.

### Error de conexión a la base de datos
Verifica las credenciales en el archivo `.env` y asegúrate de que el servicio de base de datos esté funcionando.

### Error al compilar assets
Prueba con `npm clean-install` seguido de `npm run dev`.

## Entornos de Producción

Para configurar el sistema en un entorno de producción, sigue estos pasos adicionales:

1. Configura el archivo `.env` para producción:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - Configura una URL válida en `APP_URL`

2. Optimiza la aplicación:
   ```bash
   php artisan optimize
   ```

3. Asegura la configuración del servidor web (Apache/Nginx) para que apunte al directorio `public`.

4. Configura un certificado SSL para HTTPS.

## Soporte y Contacto

Para soporte técnico o consultas sobre la instalación, contacta al equipo de desarrollo a través de [correo/canal de soporte].
