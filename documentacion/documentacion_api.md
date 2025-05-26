# Documentación de API - Sistema de Gestión para Productores Agrícolas

Esta documentación describe los endpoints disponibles en la API REST del Sistema de Gestión para Productores Agrícolas. La API sigue los principios RESTful y utiliza JSON como formato de intercambio de datos.

## Índice

1. [Autenticación](#autenticación)
2. [Convenciones generales](#convenciones-generales)
3. [Endpoints por recurso](#endpoints-por-recurso)
   - [Propiedades](#propiedades)
   - [Maquinaria](#maquinaria)
   - [Implementos](#implementos)
   - [Cultivos](#cultivos)
   - [Tecnologías de Riego](#tecnologías-de-riego)
   - [Archivos](#archivos)
4. [Códigos de respuesta](#códigos-de-respuesta)
5. [Ejemplos de uso](#ejemplos-de-uso)
6. [Errores comunes](#errores-comunes)

## Autenticación

La API utiliza Laravel Sanctum para la autenticación mediante tokens. Para acceder a los endpoints protegidos, es necesario incluir el token de autenticación en la cabecera de las solicitudes.

### Obtener un token de autenticación

```
POST /api/login
```

**Parámetros de solicitud:**

```json
{
  "email": "usuario@ejemplo.com",
  "password": "contraseña"
}
```

**Respuesta exitosa:**

```json
{
  "user": {
    "id": 1,
    "name": "Nombre Usuario",
    "email": "usuario@ejemplo.com",
    "created_at": "2025-01-01T00:00:00.000000Z",
    "updated_at": "2025-01-01T00:00:00.000000Z"
  },
  "token": "1|abcdefghijklmnopqrstuvwxyz123456789"
}
```

### Uso del token en solicitudes

Para todas las solicitudes a endpoints protegidos, incluir la siguiente cabecera:

```
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz123456789
```

## Convenciones generales

- Todos los endpoints retornan y aceptan datos en formato JSON.
- Las fechas se representan en formato ISO 8601: `YYYY-MM-DDTHH:MM:SS.sssZ`.
- Los IDs de recursos se incluyen en la URL para operaciones sobre recursos específicos.
- Las solicitudes de creación (POST) y actualización (PUT/PATCH) deben incluir los datos del recurso en el cuerpo de la solicitud.
- Las respuestas incluyen el código de estado HTTP apropiado.

## Endpoints por recurso

### Propiedades

Las propiedades representan los terrenos o inmuebles agrícolas de los productores.

#### Listar todas las propiedades

```
GET /api/propiedades
```

**Parámetros de consulta (opcionales):**
- `usuario_id`: Filtrar por ID de usuario
- `with`: Relaciones a incluir (comma-separated)

**Respuesta:**

```json
[
  {
    "id": 1,
    "usuario_id": 1,
    "ubicacion": "Ruta 40 Km 3500, Mendoza",
    "superficie": 150.5,
    "malla_antigranizo": true,
    "es_propietario": false,
    "derecho_riego": true,
    "created_at": "2025-01-01T00:00:00.000000Z",
    "updated_at": "2025-01-01T00:00:00.000000Z",
    "usuario": {
      "id": 1,
      "name": "Juan Productor",
      "email": "productor@demo.com"
    },
    "maquinarias": [],
    "cultivos": [],
    "tecnologiaRiegos": [],
    "archivos": []
  }
]
```

#### Obtener una propiedad específica

```
GET /api/propiedades/{id}
```

**Respuesta:**

```json
{
  "id": 1,
  "usuario_id": 1,
  "ubicacion": "Ruta 40 Km 3500, Mendoza",
  "superficie": 150.5,
  "malla_antigranizo": true,
  "es_propietario": false,
  "derecho_riego": true,
  "created_at": "2025-01-01T00:00:00.000000Z",
  "updated_at": "2025-01-01T00:00:00.000000Z",
  "usuario": {
    "id": 1,
    "name": "Juan Productor",
    "email": "productor@demo.com"
  },
  "maquinarias": [],
  "cultivos": [],
  "tecnologiaRiegos": [],
  "archivos": []
}
```

#### Crear una nueva propiedad

```
POST /api/propiedades
```

**Cuerpo de la solicitud:**

```json
{
  "usuario_id": 1,
  "ubicacion": "Ruta 40 Km 3500, Mendoza",
  "superficie": 150.5,
  "malla_antigranizo": true,
  "es_propietario": false,
  "derecho_riego": true
}
```

**Respuesta (201 Created):**

```json
{
  "id": 1,
  "usuario_id": 1,
  "ubicacion": "Ruta 40 Km 3500, Mendoza",
  "superficie": 150.5,
  "malla_antigranizo": true,
  "es_propietario": false,
  "derecho_riego": true,
  "created_at": "2025-01-01T00:00:00.000000Z",
  "updated_at": "2025-01-01T00:00:00.000000Z"
}
```

#### Actualizar una propiedad existente

```
PUT /api/propiedades/{id}
```

**Cuerpo de la solicitud:**

```json
{
  "ubicacion": "Ruta 40 Km 3600, Mendoza",
  "superficie": 200.0
}
```

**Respuesta:**

```json
{
  "id": 1,
  "usuario_id": 1,
  "ubicacion": "Ruta 40 Km 3600, Mendoza",
  "superficie": 200.0,
  "malla_antigranizo": true,
  "es_propietario": false,
  "derecho_riego": true,
  "created_at": "2025-01-01T00:00:00.000000Z",
  "updated_at": "2025-01-01T10:30:00.000000Z"
}
```

#### Eliminar una propiedad

```
DELETE /api/propiedades/{id}
```

**Respuesta:**

```json
{
  "message": "Propiedad eliminada correctamente"
}
```

### Maquinaria

Representa la maquinaria agrícola asociada a las propiedades.

#### Listar toda la maquinaria

```
GET /api/maquinarias
```

**Parámetros de consulta (opcionales):**
- `propiedad_id`: Filtrar por ID de propiedad
- `tipo`: Filtrar por tipo de maquinaria

**Respuesta:**

```json
[
  {
    "id": 1,
    "propiedad_id": 1,
    "tipo": "Tractor",
    "modelo": "John Deere 5090E",
    "potencia": "90HP",
    "anio_fabricacion": 2020,
    "estado": "Activo",
    "created_at": "2025-01-01T00:00:00.000000Z",
    "updated_at": "2025-01-01T00:00:00.000000Z",
    "propiedad": {
      "id": 1,
      "ubicacion": "Ruta 40 Km 3500, Mendoza"
    },
    "implementos": []
  }
]
```

#### Obtener una maquinaria específica

```
GET /api/maquinarias/{id}
```

**Respuesta:**

```json
{
  "id": 1,
  "propiedad_id": 1,
  "tipo": "Tractor",
  "modelo": "John Deere 5090E",
  "potencia": "90HP",
  "anio_fabricacion": 2020,
  "estado": "Activo",
  "created_at": "2025-01-01T00:00:00.000000Z",
  "updated_at": "2025-01-01T00:00:00.000000Z",
  "propiedad": {
    "id": 1,
    "ubicacion": "Ruta 40 Km 3500, Mendoza"
  },
  "implementos": []
}
```

#### Crear una nueva maquinaria

```
POST /api/maquinarias
```

**Cuerpo de la solicitud:**

```json
{
  "propiedad_id": 1,
  "tipo": "Tractor",
  "modelo": "John Deere 5090E",
  "potencia": "90HP",
  "anio_fabricacion": 2020,
  "estado": "Activo"
}
```

**Respuesta (201 Created):**

```json
{
  "id": 1,
  "propiedad_id": 1,
  "tipo": "Tractor",
  "modelo": "John Deere 5090E",
  "potencia": "90HP",
  "anio_fabricacion": 2020,
  "estado": "Activo",
  "created_at": "2025-01-01T00:00:00.000000Z",
  "updated_at": "2025-01-01T00:00:00.000000Z"
}
```

#### Actualizar una maquinaria existente

```
PUT /api/maquinarias/{id}
```

**Cuerpo de la solicitud:**

```json
{
  "estado": "En mantenimiento",
  "potencia": "95HP"
}
```

**Respuesta:**

```json
{
  "id": 1,
  "propiedad_id": 1,
  "tipo": "Tractor",
  "modelo": "John Deere 5090E",
  "potencia": "95HP",
  "anio_fabricacion": 2020,
  "estado": "En mantenimiento",
  "created_at": "2025-01-01T00:00:00.000000Z",
  "updated_at": "2025-01-01T10:30:00.000000Z"
}
```

#### Eliminar una maquinaria

```
DELETE /api/maquinarias/{id}
```

**Respuesta:**

```json
{
  "message": "Maquinaria eliminada correctamente"
}
```

### Implementos

Representa los implementos o herramientas asociados a la maquinaria agrícola.

#### Listar todos los implementos

```
GET /api/implementos
```

**Parámetros de consulta (opcionales):**
- `maquinaria_id`: Filtrar por ID de maquinaria
- `tipo`: Filtrar por tipo de implemento

**Respuesta:**

```json
[
  {
    "id": 1,
    "maquinaria_id": 1,
    "tipo": "Arado",
    "modelo": "Vogel & Noot XS 950",
    "ancho_trabajo": "3.5m",
    "estado": "Activo",
    "created_at": "2025-01-01T00:00:00.000000Z",
    "updated_at": "2025-01-01T00:00:00.000000Z",
    "maquinaria": {
      "id": 1,
      "tipo": "Tractor",
      "modelo": "John Deere 5090E"
    }
  }
]
```

#### Obtener un implemento específico

```
GET /api/implementos/{id}
```

**Respuesta:**

```json
{
  "id": 1,
  "maquinaria_id": 1,
  "tipo": "Arado",
  "modelo": "Vogel & Noot XS 950",
  "ancho_trabajo": "3.5m",
  "estado": "Activo",
  "created_at": "2025-01-01T00:00:00.000000Z",
  "updated_at": "2025-01-01T00:00:00.000000Z",
  "maquinaria": {
    "id": 1,
    "tipo": "Tractor",
    "modelo": "John Deere 5090E"
  }
}
```

#### Crear un nuevo implemento

```
POST /api/implementos
```

**Cuerpo de la solicitud:**

```json
{
  "maquinaria_id": 1,
  "tipo": "Arado",
  "modelo": "Vogel & Noot XS 950",
  "ancho_trabajo": "3.5m",
  "estado": "Activo"
}
```

**Respuesta (201 Created):**

```json
{
  "id": 1,
  "maquinaria_id": 1,
  "tipo": "Arado",
  "modelo": "Vogel & Noot XS 950",
  "ancho_trabajo": "3.5m",
  "estado": "Activo",
  "created_at": "2025-01-01T00:00:00.000000Z",
  "updated_at": "2025-01-01T00:00:00.000000Z"
}
```

#### Actualizar un implemento existente

```
PUT /api/implementos/{id}
```

**Cuerpo de la solicitud:**

```json
{
  "estado": "En reparación",
  "ancho_trabajo": "4.0m"
}
```

**Respuesta:**

```json
{
  "id": 1,
  "maquinaria_id": 1,
  "tipo": "Arado",
  "modelo": "Vogel & Noot XS 950",
  "ancho_trabajo": "4.0m",
  "estado": "En reparación",
  "created_at": "2025-01-01T00:00:00.000000Z",
  "updated_at": "2025-01-01T10:30:00.000000Z"
}
```

#### Eliminar un implemento

```
DELETE /api/implementos/{id}
```

**Respuesta:**

```json
{
  "message": "Implemento eliminado correctamente"
}
```

### Cultivos

Representa los cultivos asociados a las propiedades.

#### Listar todos los cultivos

```
GET /api/cultivos
```

**Parámetros de consulta (opcionales):**
- `propiedad_id`: Filtrar por ID de propiedad
- `tipo`: Filtrar por tipo de cultivo

**Respuesta:**

```json
[
  {
    "id": 1,
    "propiedad_id": 1,
    "tipo": "Viñedo",
    "variedad": "Malbec",
    "superficie": 50.5,
    "fecha_siembra": "2024-09-15",
    "fecha_cosecha_estimada": "2025-03-20",
    "estado": "En crecimiento",
    "created_at": "2025-01-01T00:00:00.000000Z",
    "updated_at": "2025-01-01T00:00:00.000000Z",
    "propiedad": {
      "id": 1,
      "ubicacion": "Ruta 40 Km 3500, Mendoza"
    }
  }
]
```

#### Obtener un cultivo específico

```
GET /api/cultivos/{id}
```

**Respuesta:**

```json
{
  "id": 1,
  "propiedad_id": 1,
  "tipo": "Viñedo",
  "variedad": "Malbec",
  "superficie": 50.5,
  "fecha_siembra": "2024-09-15",
  "fecha_cosecha_estimada": "2025-03-20",
  "estado": "En crecimiento",
  "created_at": "2025-01-01T00:00:00.000000Z",
  "updated_at": "2025-01-01T00:00:00.000000Z",
  "propiedad": {
    "id": 1,
    "ubicacion": "Ruta 40 Km 3500, Mendoza"
  }
}
```

#### Crear un nuevo cultivo

```
POST /api/cultivos
```

**Cuerpo de la solicitud:**

```json
{
  "propiedad_id": 1,
  "tipo": "Viñedo",
  "variedad": "Malbec",
  "superficie": 50.5,
  "fecha_siembra": "2024-09-15",
  "fecha_cosecha_estimada": "2025-03-20",
  "estado": "En crecimiento"
}
```

**Respuesta (201 Created):**

```json
{
  "id": 1,
  "propiedad_id": 1,
  "tipo": "Viñedo",
  "variedad": "Malbec",
  "superficie": 50.5,
  "fecha_siembra": "2024-09-15",
  "fecha_cosecha_estimada": "2025-03-20",
  "estado": "En crecimiento",
  "created_at": "2025-01-01T00:00:00.000000Z",
  "updated_at": "2025-01-01T00:00:00.000000Z"
}
```

#### Actualizar un cultivo existente

```
PUT /api/cultivos/{id}
```

**Cuerpo de la solicitud:**

```json
{
  "estado": "Listo para cosechar",
  "fecha_cosecha_estimada": "2025-03-15"
}
```

**Respuesta:**

```json
{
  "id": 1,
  "propiedad_id": 1,
  "tipo": "Viñedo",
  "variedad": "Malbec",
  "superficie": 50.5,
  "fecha_siembra": "2024-09-15",
  "fecha_cosecha_estimada": "2025-03-15",
  "estado": "Listo para cosechar",
  "created_at": "2025-01-01T00:00:00.000000Z",
  "updated_at": "2025-01-01T10:30:00.000000Z"
}
```

#### Eliminar un cultivo

```
DELETE /api/cultivos/{id}
```

**Respuesta:**

```json
{
  "message": "Cultivo eliminado correctamente"
}
```

### Tecnologías de Riego

Representa las tecnologías de riego aplicadas en las propiedades.

#### Listar todas las tecnologías de riego

```
GET /api/tecnologia-riegos
```

**Parámetros de consulta (opcionales):**
- `propiedad_id`: Filtrar por ID de propiedad
- `tipo`: Filtrar por tipo de tecnología

**Respuesta:**

```json
[
  {
    "id": 1,
    "propiedad_id": 1,
    "tipo": "Goteo",
    "cobertura": 35.5,
    "estado": "Operativo",
    "fecha_instalacion": "2024-08-10",
    "eficiencia": 95,
    "created_at": "2025-01-01T00:00:00.000000Z",
    "updated_at": "2025-01-01T00:00:00.000000Z",
    "propiedad": {
      "id": 1,
      "ubicacion": "Ruta 40 Km 3500, Mendoza"
    }
  }
]
```

#### Obtener una tecnología de riego específica

```
GET /api/tecnologia-riegos/{id}
```

**Respuesta:**

```json
{
  "id": 1,
  "propiedad_id": 1,
  "tipo": "Goteo",
  "cobertura": 35.5,
  "estado": "Operativo",
  "fecha_instalacion": "2024-08-10",
  "eficiencia": 95,
  "created_at": "2025-01-01T00:00:00.000000Z",
  "updated_at": "2025-01-01T00:00:00.000000Z",
  "propiedad": {
    "id": 1,
    "ubicacion": "Ruta 40 Km 3500, Mendoza"
  }
}
```

#### Crear una nueva tecnología de riego

```
POST /api/tecnologia-riegos
```

**Cuerpo de la solicitud:**

```json
{
  "propiedad_id": 1,
  "tipo": "Goteo",
  "cobertura": 35.5,
  "estado": "Operativo",
  "fecha_instalacion": "2024-08-10",
  "eficiencia": 95
}
```

**Respuesta (201 Created):**

```json
{
  "id": 1,
  "propiedad_id": 1,
  "tipo": "Goteo",
  "cobertura": 35.5,
  "estado": "Operativo",
  "fecha_instalacion": "2024-08-10",
  "eficiencia": 95,
  "created_at": "2025-01-01T00:00:00.000000Z",
  "updated_at": "2025-01-01T00:00:00.000000Z"
}
```

#### Actualizar una tecnología de riego existente

```
PUT /api/tecnologia-riegos/{id}
```

**Cuerpo de la solicitud:**

```json
{
  "estado": "En mantenimiento",
  "eficiencia": 90
}
```

**Respuesta:**

```json
{
  "id": 1,
  "propiedad_id": 1,
  "tipo": "Goteo",
  "cobertura": 35.5,
  "estado": "En mantenimiento",
  "fecha_instalacion": "2024-08-10",
  "eficiencia": 90,
  "created_at": "2025-01-01T00:00:00.000000Z",
  "updated_at": "2025-01-01T10:30:00.000000Z"
}
```

#### Eliminar una tecnología de riego

```
DELETE /api/tecnologia-riegos/{id}
```

**Respuesta:**

```json
{
  "message": "Tecnología de riego eliminada correctamente"
}
```

### Archivos

Representa los archivos asociados a las propiedades (documentos, imágenes, etc.).

#### Listar todos los archivos

```
GET /api/archivos
```

**Parámetros de consulta (opcionales):**
- `propiedad_id`: Filtrar por ID de propiedad
- `tipo`: Filtrar por tipo de archivo

**Respuesta:**

```json
[
  {
    "id": 1,
    "propiedad_id": 1,
    "nombre": "plano_catastral.pdf",
    "tipo": "documento",
    "ruta": "archivos/propiedades/1/plano_catastral.pdf",
    "tamanio": 1250000,
    "created_at": "2025-01-01T00:00:00.000000Z",
    "updated_at": "2025-01-01T00:00:00.000000Z",
    "propiedad": {
      "id": 1,
      "ubicacion": "Ruta 40 Km 3500, Mendoza"
    }
  }
]
```

#### Obtener un archivo específico

```
GET /api/archivos/{id}
```

**Respuesta:**

```json
{
  "id": 1,
  "propiedad_id": 1,
  "nombre": "plano_catastral.pdf",
  "tipo": "documento",
  "ruta": "archivos/propiedades/1/plano_catastral.pdf",
  "tamanio": 1250000,
  "created_at": "2025-01-01T00:00:00.000000Z",
  "updated_at": "2025-01-01T00:00:00.000000Z",
  "propiedad": {
    "id": 1,
    "ubicacion": "Ruta 40 Km 3500, Mendoza"
  }
}
```

#### Subir un nuevo archivo

```
POST /api/archivos
```

**Cuerpo de la solicitud (multipart/form-data):**

```
propiedad_id: 1
tipo: documento
archivo: [ARCHIVO_BINARIO]
```

**Respuesta (201 Created):**

```json
{
  "id": 1,
  "propiedad_id": 1,
  "nombre": "plano_catastral.pdf",
  "tipo": "documento",
  "ruta": "archivos/propiedades/1/plano_catastral.pdf",
  "tamanio": 1250000,
  "created_at": "2025-01-01T00:00:00.000000Z",
  "updated_at": "2025-01-01T00:00:00.000000Z"
}
```

#### Actualizar información de un archivo

```
PUT /api/archivos/{id}
```

**Cuerpo de la solicitud:**

```json
{
  "nombre": "plano_catastral_actualizado.pdf",
  "tipo": "documento_legal"
}
```

**Respuesta:**

```json
{
  "id": 1,
  "propiedad_id": 1,
  "nombre": "plano_catastral_actualizado.pdf",
  "tipo": "documento_legal",
  "ruta": "archivos/propiedades/1/plano_catastral.pdf",
  "tamanio": 1250000,
  "created_at": "2025-01-01T00:00:00.000000Z",
  "updated_at": "2025-01-01T10:30:00.000000Z"
}
```

#### Eliminar un archivo

```
DELETE /api/archivos/{id}
```

**Respuesta:**

```json
{
  "message": "Archivo eliminado correctamente"
}
```

## Códigos de respuesta

La API utiliza los siguientes códigos de estado HTTP:

- `200 OK`: La solicitud se ha completado correctamente
- `201 Created`: El recurso se ha creado correctamente
- `400 Bad Request`: La solicitud contiene datos inválidos o falta información necesaria
- `401 Unauthorized`: Falta el token de autenticación o es inválido
- `403 Forbidden`: El usuario no tiene permisos para acceder al recurso
- `404 Not Found`: El recurso solicitado no existe
- `422 Unprocessable Entity`: Los datos proporcionados no pasaron la validación
- `500 Internal Server Error`: Error interno del servidor

## Ejemplos de uso

### Ejemplo 1: Autenticación y listado de propiedades

```javascript
// Paso 1: Obtener token de autenticación
fetch('https://midominio.com/api/login', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    email: 'productor@demo.com',
    password: 'productor123'
  })
})
.then(response => response.json())
.then(data => {
  const token = data.token;
  
  // Paso 2: Usar el token para obtener propiedades
  return fetch('https://midominio.com/api/propiedades', {
    headers: {
      'Authorization': `Bearer ${token}`
    }
  });
})
.then(response => response.json())
.then(propiedades => {
  console.log('Propiedades:', propiedades);
})
.catch(error => {
  console.error('Error:', error);
});
```

### Ejemplo 2: Crear una nueva propiedad y asociarle un cultivo

```javascript
// Asumiendo que ya tenemos un token
const token = 'token_obtenido_previamente';

// Paso 1: Crear una propiedad
fetch('https://midominio.com/api/propiedades', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${token}`
  },
  body: JSON.stringify({
    usuario_id: 1,
    ubicacion: "Ruta 7 Km 1020, San Rafael",
    superficie: 75.2,
    malla_antigranizo: false,
    es_propietario: true,
    derecho_riego: true
  })
})
.then(response => response.json())
.then(propiedad => {
  console.log('Propiedad creada:', propiedad);
  
  // Paso 2: Crear un cultivo asociado a esta propiedad
  return fetch('https://midominio.com/api/cultivos', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${token}`
    },
    body: JSON.stringify({
      propiedad_id: propiedad.id,
      tipo: "Olivar",
      variedad: "Arauco",
      superficie: 30.0,
      fecha_siembra: "2024-10-01",
      fecha_cosecha_estimada: "2025-04-15",
      estado: "Plantación reciente"
    })
  });
})
.then(response => response.json())
.then(cultivo => {
  console.log('Cultivo creado:', cultivo);
})
.catch(error => {
  console.error('Error:', error);
});
```

### Ejemplo 3: Subir un archivo asociado a una propiedad

```javascript
// Asumiendo que ya tenemos un token y un input de tipo file en el HTML
const token = 'token_obtenido_previamente';
const fileInput = document.querySelector('input[type="file"]');

const formData = new FormData();
formData.append('propiedad_id', 1);
formData.append('tipo', 'imagen');
formData.append('archivo', fileInput.files[0]);

fetch('https://midominio.com/api/archivos', {
  method: 'POST',
  headers: {
    'Authorization': `Bearer ${token}`
  },
  body: formData
})
.then(response => response.json())
.then(archivo => {
  console.log('Archivo subido:', archivo);
})
.catch(error => {
  console.error('Error:', error);
});
```

## Errores comunes

### Error de validación

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "superficie": [
      "El campo superficie debe ser un número positivo."
    ],
    "ubicacion": [
      "El campo ubicación es obligatorio."
    ]
  }
}
```

### Error de autenticación

```json
{
  "message": "Unauthenticated."
}
```

### Error de recurso no encontrado

```json
{
  "message": "No query results for model [App\\Models\\Propiedad] 999"
}
```

---

## Notas adicionales

- Para la documentación interactiva de la API, visite `/api/documentation`
- Los endpoints pueden estar sujetos a cambios. Esta documentación se actualizó por última vez el 26 de mayo de 2025.
- Para cualquier consulta relacionada con la API, contacte al equipo de desarrollo.
