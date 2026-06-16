# Manual de Administrador — API de Integración

**Código**: RUPAL-MA-06  
**Versión**: 1.0  
**Fecha**: 2026-06-15  
**ISO 9001:2015**: Cláusula 7.5 — Información Documentada

---

## 1. OBJETIVO

Documentar la API REST del sistema RUPAL para integración con sistemas externos, automatización de tareas y consultas programáticas.

## 2. ALCANCE

Aplica a desarrolladores y administradores del sistema que necesiten integrar sistemas externos con RUPAL a través de la API REST.

## 3. AUTENTICACIÓN

La API utiliza **Laravel Sanctum** para autenticación basada en tokens.

### 3.1 Obtener token de acceso

**Endpoint**: `POST /api/staff/login`

**Request**:
```json
{
    "email": "admin@ejemplo.com",
    "password": "contraseña",
    "device_name": "sistema-externo" (opcional)
}
```

**Response** (200 OK):
```json
{
    "token": "1|sD3fG5hJ7kL9qW2eR4tY6uI8oP0aZ1bC2vB3nM4x",
    "user": {
        "id": 1,
        "name": "Admin Principal",
        "email": "admin@ejemplo.com",
        "role": "admin"
    }
}
```

**Response** (422 Unprocessable):
```json
{
    "message": "Credenciales incorrectas.",
    "errors": {
        "email": ["Credenciales incorrectas."]
    }
}
```

### 3.2 Usar el token

Incluir el token en el header `Authorization` de todas las solicitudes:

```
Authorization: Bearer 1|sD3fG5hJ7kL9qW2eR4tY6uI8oP0aZ1bC2vB3nM4x
```

### 3.3 Cerrar sesión (invalidar token)

**Endpoint**: `POST /api/staff/logout`

**Response** (200 OK):
```json
{
    "message": "Sesión cerrada correctamente."
}
```

### 3.4 Verificar sesión

**Endpoint**: `GET /api/staff/me`

**Response** (200 OK):
```json
{
    "id": 1,
    "name": "Admin Principal",
    "email": "admin@ejemplo.com",
    "role": "admin"
}
```

## 4. ENDPOINTS DISPONIBLES

### 4.1 Dashboard

**Endpoint**: `GET /api/staff/dashboard`

**Roles**: Admin, Auditor

**Response** (200 OK):
```json
{
    "total_users": 150,
    "new_users_30d": 12,
    "users_over_time": [
        {"month": "2026-01", "count": 5},
        {"month": "2026-02", "count": 8},
        {"month": "2026-03", "count": 15},
        {"month": "2026-04", "count": 20},
        {"month": "2026-05", "count": 18},
        {"month": "2026-06", "count": 12}
    ],
    "hectareas_por_tipo": {
        "Fruticola": 250.5,
        "Horticola": 180.0,
        "Viticola": 420.75,
        "Olivicola": 95.25
    },
    "porcentaje_departamento": 12.5
}
```

### 4.2 Productores — Listado

**Endpoint**: `GET /api/staff/producers`

**Roles**: Admin, Auditor

**Parámetros de consulta (query params)**:

| Parámetro | Tipo | Descripción |
|-----------|------|-------------|
| `dni` | string | Búsqueda parcial por DNI |
| `name` | string | Búsqueda parcial por nombre |
| `distrito` | string | Filtro por distrito exacto |
| `variedad` | string | Filtro por tipo de cultivo |
| `tipo` | string | Filtro por tipo |
| `per_page` | integer | Resultados por página (default: 10) |
| `page` | integer | Número de página (default: 1) |

**Response** (200 OK):
```json
{
    "data": [
        {
            "id": 1,
            "name": "Juan Pérez",
            "email": "juan@ejemplo.com",
            "dni": "12345678",
            "telefono": "+5492612345678",
            "direccion": "Calle Falsa 123",
            "cooperativas": ["cooperativa_nueva_california"],
            "tiene_cooperativas": true,
            "email_verified_at": "2026-01-15T12:00:00.000000Z",
            "propiedades_count": 2,
            "cultivos_count": 5,
            "maquinarias_count": 1,
            "comercios_count": 1
        }
    ],
    "total": 150,
    "per_page": 10,
    "current_page": 1,
    "last_page": 15
}
```

### 4.3 Productores — Detalle

**Endpoint**: `GET /api/staff/producers/{id}`

**Roles**: Admin, Auditor

**Response** (200 OK):
```json
{
    "user": {
        "id": 1,
        "name": "Juan Pérez",
        "email": "juan@ejemplo.com",
        "dni": "12345678",
        "telefono": "+5492612345678",
        "direccion": "Calle Falsa 123"
    },
    "propiedades": [
        {
            "id": 1,
            "direccion": "Ruta 34 Km 15",
            "distrito": "Asunción",
            "hectareas": 50.0,
            "tipo_tenencia": "Propietario",
            "derecho_riego": "Permanente",
            "tiene_alambrado": true,
            "tiene_media_sombra": false,
            "latitud": -32.5,
            "longitud": -68.0
        }
    ],
    "cultivos": [
        {
            "id": 1,
            "tipo": "Viticola",
            "variedad": "Malbec",
            "hectareas": 20.0,
            "manejo": "Convencional",
            "tecnologia_riego": "Goteo",
            "fecha_siembra": "2020-09-15",
            "propiedad_id": 1,
            "propiedad_direccion": "Ruta 34 Km 15"
        }
    ],
    "maquinarias": [
        {
            "id": 1,
            "tractor_anio": 2018,
            "implementos": ["arado", "rastra", "sembradora"],
            "propiedad_id": 1,
            "propiedad_direccion": "Ruta 34 Km 15"
        }
    ],
    "comercios": [
        {
            "id": 1,
            "nombre": "Finca Don Juan",
            "direccion": "Ruta 34 Km 15",
            "contacto": "2604123456",
            "infraestructura_empaque": true,
            "vende_en_finca": true,
            "mercados": ["Mercado Local", "Feria Franca"],
            "cooperativas": ["Coop Nueva California"]
        }
    ]
}
```

### 4.4 Productores — Exportación

**Endpoint**: `GET /api/staff/producers/export`

**Roles**: Admin (Auditor también tiene acceso en web, pero el endpoint API está restringido a admin)

**Parámetros de consulta**: Mismos filtros que el listado (dni, name, distrito, variedad, tipo)

**Response**: Archivo XLSX (Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet)

### 4.5 Usuarios Staff — Listado

**Endpoint**: `GET /api/staff/users`

**Roles**: Admin únicamente

**Response** (200 OK):
```json
{
    "filters": {
        "name": "",
        "email": ""
    },
    "users": {
        "data": [
            {
                "id": 1,
                "name": "Admin Principal",
                "email": "admin@ejemplo.com",
                "role": "admin",
                "active": true,
                "last_login_at": "2026-06-15T10:00:00.000000Z",
                "last_access_at": "2026-06-15T10:00:00.000000Z"
            }
        ],
        "total": 5,
        "per_page": 10,
        "current_page": 1,
        "last_page": 1
    }
}
```

### 4.6 Usuarios Staff — Crear

**Endpoint**: `POST /api/staff/users`

**Roles**: Admin únicamente

**Request**:
```json
{
    "name": "Nuevo Staff",
    "email": "nuevo@ejemplo.com",
    "password": "contraseña123",
    "password_confirmation": "contraseña123",
    "role": "auditor"
}
```

**Response** (201 Created):
```json
{
    "message": "Usuario staff creado exitosamente",
    "user": {
        "id": 6,
        "name": "Nuevo Staff",
        "email": "nuevo@ejemplo.com",
        "role": "auditor"
    }
}
```

### 4.7 Usuarios Staff — Obtener para edición

**Endpoint**: `GET /api/staff/users/{id}/edit`

**Roles**: Admin únicamente

**Response** (200 OK):
```json
{
    "id": 6,
    "name": "Nuevo Staff",
    "email": "nuevo@ejemplo.com",
    "role": "auditor",
    "active": true
}
```

### 4.8 Usuarios Staff — Actualizar

**Endpoint**: `PATCH /api/staff/users/{id}`

**Roles**: Admin únicamente

**Request**:
```json
{
    "name": "Staff Actualizado",
    "email": "actualizado@ejemplo.com",
    "role": "admin",
    "password": "nueva_contraseña",
    "password_confirmation": "nueva_contraseña",
    "active": true
}
```

**Response** (200 OK):
```json
{
    "message": "Usuario actualizado exitosamente",
    "user": {
        "id": 6,
        "name": "Staff Actualizado",
        "email": "actualizado@ejemplo.com",
        "role": "admin",
        "active": true
    }
}
```

### 4.9 Usuarios Staff — Eliminar

**Endpoint**: `DELETE /api/staff/users/{id}`

**Roles**: Admin únicamente

**Response** (200 OK):
```json
{
    "message": "Usuario eliminado"
}
```

## 5. CÓDIGOS DE RESPUESTA

| Código | Significado |
|--------|-------------|
| 200 | OK — Solicitud exitosa |
| 201 | Created — Recurso creado exitosamente |
| 401 | Unauthorized — Token no proporcionado o inválido |
| 403 | Forbidden — No tiene permisos para esta acción |
| 404 | Not Found — Recurso no encontrado |
| 422 | Unprocessable Entity — Error de validación |
| 429 | Too Many Requests — Límite de tasa excedido |
| 500 | Internal Server Error — Error interno del servidor |

## 6. MANEJO DE ERRORES

Todos los errores siguen el formato:

```json
{
    "message": "Descripción del error"
}
```

Para errores de validación (422):

```json
{
    "message": "El campo email es obligatorio.",
    "errors": {
        "email": ["El campo email es obligatorio."],
        "password": ["El campo password es obligatorio."]
    }
}
```

## 7. RATE LIMITING

| Endpoint | Límite |
|----------|--------|
| `POST /api/staff/login` | 5 solicitudes por minuto |
| Endpoints autenticados | 60 solicitudes por minuto (por defecto) |

## 8. SEGURIDAD

- Todos los endpoints requieren HTTPS en producción.
- Los tokens de acceso expiran después de 120 minutos de inactividad.
- Las contraseñas se almacenan con hash bcrypt (12 rondas).
- No se exponen campos sensibles (contraseñas, tokens, datos internos).
- Las respuestas incluyen headers de seguridad (CSP, X-Frame-Options, etc.).
