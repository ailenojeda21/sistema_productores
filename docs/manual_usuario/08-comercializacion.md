# Manual de Usuario — Comercialización

**Código**: RUPAL-MU-08  
**Versión**: 1.0  
**Fecha**: 2026-06-15  
**ISO 9001:2015**: Cláusula 7.5 — Información Documentada

---

## 1. OBJETIVO

Describir el procedimiento completo de gestión de la comercialización en RUPAL, incluyendo registro de comercios, mercados de venta y afiliación a cooperativas.

## 2. ALCANCE

Aplica a todos los productores que necesiten registrar sus canales de comercialización y puntos de venta.

## 3. DESCRIPCIÓN DEL MÓDULO

El módulo de comercialización permite al productor:
- Registrar uno o más comercios o puntos de venta.
- Indicar si dispone de infraestructura de empaque.
- Indicar si realiza venta en finca.
- Seleccionar los mercados donde comercializa sus productos.
- Seleccionar las cooperativas a las que está afiliado (si aplica).

## 4. PROCEDIMIENTO: LISTADO DE COMERCIOS

[CAPTURA DE PANTALLA: Listado de comercios]

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Hacer clic en **Comercialización** en el menú lateral | Se carga el listado de comercios registrados |
| 2 | — | Se muestran tarjetas con: nombre del comercio, mercados, cooperativas, acciones |

### 4.1 Columnas del listado

| Columna | Descripción |
|---------|-------------|
| Nombre | Nombre del comercio o punto de venta |
| Dirección | Dirección del comercio |
| Mercados | Mercados donde comercializa |
| Cooperativas | Cooperativas a las que pertenece |
| Acciones | Botones: Ver, Editar, Eliminar |

## 5. PROCEDIMIENTO: CREAR NUEVO COMERCIO

[CAPTURA DE PANTALLA: Formulario de creación de comercio]

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Hacer clic en **Agregar Comercio** (botón superior) | Se abre el formulario de creación |
| 2 | Completar los datos del comercio | Los campos se llenan |
| 3 | Hacer clic en **Guardar** | El sistema valida y guarda el comercio |

### 5.1 Campos del formulario

| Campo | Tipo | Obligatorio | Descripción |
|-------|------|-------------|-------------|
| Nombre del Comercio | Texto | Sí | Nombre del punto de venta |
| Dirección | Texto | Sí | Ubicación del comercio |
| Contacto | Texto | Sí | Información de contacto (teléfono, email) |
| Infraestructura de Empaque | Sí/No | No | Indica si dispone de equipo de empaque |
| Venta en Finca | Sí/No | No | Indica si vende directamente desde la finca |
| Mercados | Selección múltiple | Sí | Uno o más mercados donde vende |
| Cooperativas | Selección múltiple | No aplica | Cooperativas a las que pertenece |

### 5.2 Mercados disponibles

| Mercado | Descripción |
|---------|-------------|
| Mercado Local | Venta en el departamento de Lavalle |
| Mercado Provincial | Venta en la provincia de Mendoza |
| Mercado Nacional | Venta en otras provincias de Argentina |
| Mercado Internacional | Exportación a otros países |
| Venta Directa | Venta al consumidor final sin intermediarios |
| Feria Franca | Venta en ferias municipales o provinciales |
| Supermercados | Venta a cadenas de supermercados |
| Industria | Venta a la industria alimenticia |
| Cooperativa | Venta a través de cooperativas |

[CAPTURA DE PANTALLA: Selección múltiple de mercados]

### 5.3 Cooperativas disponibles

| Cooperativa |
|-------------|
| Coop Nueva California |
| Coop Tulumaya |
| Coop Norte Mendocino |
| Coop Tres de Mayo |
| Coop Altas Cumbres |
| Coop Tres Porteñas |
| Coop El Poniente |
| Coop Pámpanos Mendocinos |
| Coop Ingeniero Giagnoni |
| Coop Las Trincheras |
| Coop Agrícola Beltrán |
| Coop La Dormida |
| Coop Del Algarrobal |
| Coop El Libertador |
| Coop Brindis |

## 6. PROCEDIMIENTO: VER DETALLE DE COMERCIO

[CAPTURA DE PANTALLA: Vista de detalle de comercio]

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | En el listado, hacer clic en **Ver** (👁️) | Se carga la página de detalle |
| 2 | — | Se muestra toda la información del comercio: nombre, dirección, contacto, mercados, cooperativas |

## 7. PROCEDIMIENTO: EDITAR COMERCIO

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | En el listado, hacer clic en **Editar** (✏️) | Se abre el formulario con los datos actuales precargados |
| 2 | Modificar los campos necesarios | Los campos se actualizan |
| 3 | Hacer clic en **Guardar** | El sistema valida y actualiza el comercio |
| 4 | — | Mensaje de confirmación: "Comercio actualizado exitosamente" |

## 8. PROCEDIMIENTO: ELIMINAR COMERCIO

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | En el listado, hacer clic en **Eliminar** (🗑️) | Aparece un cuadro de diálogo de confirmación |
| 2 | Confirmar la eliminación | El comercio se elimina |

## 9. VALIDACIONES DEL FORMULARIO

| Campo | Regla de Validación |
|-------|---------------------|
| Nombre | Requerido, máximo 255 caracteres |
| Dirección | Requerido, máximo 255 caracteres |
| Contacto | Requerido, máximo 255 caracteres |
| Mercados | Requerido, al menos un mercado seleccionado |

## 10. COMPORTAMIENTO ESPECIAL

- El campo **Cooperativas** solo se muestra si el productor indicó que pertenece a una cooperativa en su perfil (`tiene_cooperativas = true`).
- Se puede seleccionar más de un mercado por comercio.
- Una cooperativa seleccionada que esté repetida se guarda una sola vez.
- Si el productor no tiene cooperativas, puede dejar ese campo vacío.
- El productor puede tener múltiples comercios registrados.
