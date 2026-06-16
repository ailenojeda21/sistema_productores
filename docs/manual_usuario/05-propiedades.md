# Manual de Usuario — Propiedades

**Código**: RUPAL-MU-05  
**Versión**: 1.0  
**Fecha**: 2026-06-15  
**ISO 9001:2015**: Cláusula 7.5 — Información Documentada

---

## 1. OBJETIVO

Describir el procedimiento completo de gestión de propiedades rurales en RUPAL, incluyendo alta, visualización, edición, eliminación, geolocalización y descarga de RUT.

## 2. ALCANCE

Aplica a todos los productores que necesiten registrar y administrar sus propiedades agropecuarias en el sistema.

## 3. DESCRIPCIÓN DEL MÓDULO

El módulo de propiedades permite al productor:
- Registrar una o más propiedades con datos catastrales y geográficos.
- Asociar un documento RUT (Registro Único Tributario) en formato PDF.
- Geolocalizar la propiedad en un mapa interactivo (Leaflet + OpenStreetMap).
- Definir el tipo de tenencia y derechos de riego.
- Especificar características como alambrado perimetral, media sombra, etc.

## 4. PROCEDIMIENTO: LISTADO DE PROPIEDADES

[CAPTURA DE PANTALLA: Listado de propiedades con tabla y tarjetas]

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Hacer clic en **Propiedades** en el menú lateral | Se carga el listado de propiedades registradas |
| 2 | — | En escritorio: se muestra una tabla con columnas (dirección, distrito, hectáreas, acciones) |
| 3 | — | En móvil: se muestran tarjetas apiladas con la misma información |

### 4.1 Columnas del listado (escritorio)

| Columna | Descripción |
|---------|-------------|
| Dirección | Dirección completa de la propiedad |
| Distrito | Distrito del departamento de Lavalle |
| Hectáreas | Superficie total en hectáreas |
| Acciones | Botones: Ver, Editar, Eliminar |

### 4.2 Acciones disponibles

| Botón | Icono | Descripción |
|-------|-------|-------------|
| Ver | 👁️ | Muestra el detalle completo de la propiedad con mapa |
| Editar | ✏️ | Abre el formulario de edición |
| Eliminar | 🗑️ | Elimina la propiedad (previa confirmación) |

### 4.3 Estado vacío

[CAPTURA DE PANTALLA: Estado vacío del listado de propiedades]

Si no hay propiedades registradas, se muestra un mensaje: **"No tienes propiedades registradas"** con un botón **Agregar Propiedad**.

## 5. PROCEDIMIENTO: CREAR NUEVA PROPIEDAD

[CAPTURA DE PANTALLA: Formulario de creación de propiedad]

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Hacer clic en **Agregar Propiedad** (botón superior) | Se abre el formulario de creación |
| 2 | Completar los datos de la propiedad | Los campos se llenan |

### 5.1 Campos del formulario

| Campo | Tipo | Obligatorio | Descripción |
|-------|------|-------------|-------------|
| Dirección | Texto | Sí | Dirección completa de la propiedad |
| Distrito | Selección | Sí | Seleccionar de la lista desplegable (21 distritos) |
| Hectáreas | Número | Sí | Superficie total en hectáreas (mínimo 0.01) |
| Tipo de Tenencia | Selección | Sí | Propietario, Arrendatario, Ocupante, etc. |
| Derecho de Riego | Selección | Sí | Permanente, Eventual, Sin riego, Mixto |
| Alambrado Perimetral | Sí/No | No | Indica si la propiedad tiene alambrado |
| Media Sombra | Sí/No | No | Indica si la propiedad tiene media sombra |
| RUT | Archivo | No | Documento RUT en formato PDF (máximo 10MB) |
| Latitud | Número | No | Coordenada de geolocalización |
| Longitud | Número | No | Coordenada de geolocalización |

### 5.2 Distritos disponibles (21)

| # | Distrito |
|---|----------|
| 1 | Asunción |
| 2 | La Pega |
| 3 | El Plumero |
| 4 | San Miguel |
| 5 | Las Violetas |
| 6 | La Palmera |
| 7 | Costa de Araujo |
| 8 | El Vergel |
| 9 | Jocolí |
| 10 | La Holanda |
| 11 | Villa del Carmen |
| 12 | San Francisco |
| 13 | El Retiro |
| 14 | El Algarrobal |
| 15 | Colonia 3 de Mayo |
| 16 | Colonia Tres de Mayo (Viejo) |
| 17 | El Cavadito |
| 18 | El Sauce |
| 19 | Tres de Mayo (Nuevo) |
| 20 | La Pega (Zona Rural) |
| 21 | Lagunas de Lavalle |

### 5.3 Tipos de Tenencia

| Opción | Descripción |
|--------|-------------|
| Propietario | Dueño del terreno |
| Arrendatario | Alquila el terreno |
| Ocupante | Ocupa sin título formal |
| Mediero | Produce a medias con el dueño |
| Comodatario | Uso gratuito con autorización |
| Sucesión Indivisa | Herencia no dividida formalmente |
| concesionario | Concesión del estado |
| Escribano | En trámite de escrituración |

### 5.4 Derechos de Riego

| Opción | Descripción |
|--------|-------------|
| Permanente | Riego asegurado todo el año |
| Eventual | Riego sujeto a disponibilidad |
| Sin riego | Secano, sin derecho de riego |
| Mixto | Combinación de riego permanente y eventual |

[CAPTURA DE PANTALLA: Mapa interactivo para selección de coordenadas]

### 5.5 Geolocalización en el mapa

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Al hacer clic en el campo de coordenadas o en el botón de mapa | Se abre un modal con el mapa de OpenStreetMap |
| 2 | Hacer clic en la ubicación exacta de la propiedad | Las coordenadas se completan automáticamente en los campos Latitud/Longitud |
| 3 | Cerrar el modal | Los valores de coordenadas se mantienen en el formulario |

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 4 | Hacer clic en **Guardar** | El sistema valida y guarda la propiedad |
| 5 | — | Si es exitoso: redirige al listado con mensaje "Propiedad creada exitosamente" |
| 6 | — | Si hay errores de validación: se muestran en rojo debajo de cada campo |

## 6. PROCEDIMIENTO: VER DETALLE DE PROPIEDAD

[CAPTURA DE PANTALLA: Vista de detalle de propiedad con mapa]

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | En el listado, hacer clic en el botón **Ver** (👁️) de una propiedad | Se carga la página de detalle |
| 2 | — | Se muestra toda la información de la propiedad: dirección, distrito, hectáreas, tenencia, riego |
| 3 | — | Se muestra el mapa con un marcador en la ubicación exacta |
| 4 | — | Si tiene RUT, se muestra un enlace para descargar el documento |
| 5 | — | Si tiene cultivos asociados, se listan en una sección inferior |
| 6 | — | Si tiene maquinaria asociada, se lista en una sección inferior |

[CAPTURA DE PANTALLA: Mapa Leaflet con marcador de propiedad]

## 7. PROCEDIMIENTO: EDITAR PROPIEDAD

[CAPTURA DE PANTALLA: Formulario de edición de propiedad precargado]

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | En el listado, hacer clic en el botón **Editar** (✏️) | Se abre el formulario con los datos actuales precargados |
| 2 | Modificar los campos necesarios | Los campos se actualizan |
| 3 | Si se desea cambiar el RUT, cargar un nuevo archivo PDF | El archivo se adjunta |
| 4 | Hacer clic en **Guardar** | El sistema valida y actualiza la propiedad |
| 5 | — | Mensaje de confirmación: "Propiedad actualizada exitosamente" |

## 8. PROCEDIMIENTO: DESCARGAR RUT

[CAPTURA DE PANTALLA: Botón de descarga de RUT en detalle de propiedad]

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | En el detalle de la propiedad, hacer clic en **Descargar RUT** | El sistema inicia la descarga del archivo PDF |
| 2 | Guardar el archivo en la computadora | El documento se descarga con el nombre: `RUT_[id_propiedad].pdf` |

### 8.1 Consideraciones

- Solo el propietario de la propiedad puede descargar el RUT.
- El personal staff (admin/auditor) también puede descargar el RUT.
- El archivo se almacena en un disco privado del servidor (no accesible públicamente).

## 9. PROCEDIMIENTO: ELIMINAR PROPIEDAD

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | En el listado, hacer clic en el botón **Eliminar** (🗑️) | Aparece un cuadro de diálogo de confirmación |
| 2 | Confirmar la eliminación | La propiedad y sus cultivos/maquinaria asociados se eliminan |
| 3 | — | Mensaje: "Propiedad eliminada exitosamente" |
| 4 | — | La propiedad desaparece del listado |

[CAPTURA DE PANTALLA: Diálogo de confirmación de eliminación de propiedad]

### 9.1 Restricciones

- No se puede eliminar una propiedad que tenga **cultivos registrados** sin eliminarlos primero.
- No se puede eliminar una propiedad que tenga **maquinaria asociada** sin eliminarla primero.
- Solo el propietario de la propiedad puede eliminarla.

## 10. VALIDACIONES DEL FORMULARIO

| Campo | Regla de Validación |
|-------|---------------------|
| Dirección | Requerido, máximo 255 caracteres |
| Distrito | Requerido, debe ser un distrito válido |
| Hectáreas | Requerido, numérico, mínimo 0.01 |
| Tipo de Tenencia | Requerido, opción válida |
| Derecho de Riego | Requerido, opción válida |
| RUT | Archivo PDF, máximo 10MB |

## 11. MAPA INTERACTIVO

- El mapa utiliza la biblioteca **Leaflet** con tiles de **OpenStreetMap**.
- Al hacer clic en el mapa, se coloca un marcador en la ubicación seleccionada.
- Las coordenadas se actualizan automáticamente en los campos de latitud y longitud.
- El marcador se puede arrastrar para ajustar la ubicación.
- En la vista de detalle, el mapa se muestra en modo solo lectura con el marcador fijo.
