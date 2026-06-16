# Manual de Administrador — Gestión de Productores

**Código**: RUPAL-MA-04  
**Versión**: 1.0  
**Fecha**: 2026-06-15  
**ISO 9001:2015**: Cláusula 7.5 — Información Documentada

---

## 1. OBJETIVO

Describir las operaciones de consulta, búsqueda, visualización detallada y exportación de datos de productores en el sistema staff.

## 2. ALCANCE

Aplica a todos los usuarios staff (admin y auditor) del sistema RUPAL.

## 3. PROCEDIMIENTO: LISTADO DE PRODUCTORES

[CAPTURA DE PANTALLA: Listado de productores con filtros]

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Hacer clic en **Productores** en el menú lateral | Se carga el listado completo de productores registrados |
| 2 | — | Se muestra una tabla paginada con los datos de los productores |

### 3.1 Columnas del listado

| Columna | Descripción |
|---------|-------------|
| Nombre | Nombre completo del productor |
| Email | Correo electrónico registrado |
| DNI | Documento Nacional de Identidad |
| Teléfono | Número de contacto |
| Distrito | Distrito del departamento de Lavalle |
| Verificado | Indicador de email verificado (✅/❌) |

### 3.2 Paginación

- El listado muestra 10 productores por página.
- En la parte inferior se encuentran los controles de paginación.
- Se muestra el total de resultados: "Mostrando X de Y productores".

## 4. PROCEDIMIENTO: BÚSQUEDA Y FILTROS

[CAPTURA DE PANTALLA: Barra de filtros del listado de productores]

El sistema dispone de seis (6) filtros de búsqueda:

| Filtro | Tipo | Descripción |
|--------|------|-------------|
| **DNI** | Texto | Búsqueda parcial por número de DNI |
| **Nombre** | Texto | Búsqueda parcial por nombre del productor |
| **Distrito** | Selección | Filtro por distrito (21 distritos de Lavalle) |
| **Variedad** | Selección | Filtro por tipo de cultivo |
| **Tipo** | Selección | Filtro por tipo de cultivo |

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Completar uno o más campos de filtro | Los campos se llenan |
| 2 | Hacer clic en **Buscar** | El listado se actualiza con los resultados filtrados |
| 3 | Hacer clic en **Limpiar** | Todos los filtros se restablecen y se muestra el listado completo |

### 4.1 Comportamiento de los filtros

- Los filtros se combinan con **AND** (todos deben coincidir).
- La búsqueda por texto (nombre, DNI) es **parcial** (no requiere el valor exacto).
- Los filtros por selección (distrito, variedad, tipo) muestran todas las opciones disponibles.
- Los parámetros de búsqueda se mantienen en la URL, permitiendo compartir enlaces con filtros aplicados.

## 5. PROCEDIMIENTO: VER DETALLE DEL PRODUCTOR

[CAPTURA DE PANTALLA: Detalle completo de un productor]

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | En el listado, hacer clic en el nombre de un productor | Se carga la página de detalle del productor |
| 2 | — | Se muestra la información completa organizada en secciones |

### 5.1 Secciones del detalle

#### 5.1.1 Datos personales

| Dato | Descripción |
|------|-------------|
| Nombre | Nombre completo |
| Email | Correo electrónico |
| DNI | Documento Nacional de Identidad |
| Teléfono | Número de contacto |
| Dirección | Domicilio real |
| Cooperativas | Cooperativas a las que pertenece |
| Miembro desde | Fecha de registro |

#### 5.1.2 Propiedades

[CAPTURA DE PANTALLA: Sección de propiedades en el detalle del productor]

Listado de todas las propiedades registradas por el productor, mostrando:
- Dirección completa.
- Distrito.
- Hectáreas totales.
- Tipo de tenencia.
- Derecho de riego.
- RUT (enlace de descarga).

#### 5.1.3 Cultivos

[CAPTURA DE PANTALLA: Sección de cultivos en el detalle del productor]

Listado de todos los cultivos registrados, mostrando:
- Tipo de cultivo y variedad.
- Propiedad asociada.
- Hectáreas cultivadas.
- Tecnología de riego.
- Fecha de siembra.

#### 5.1.4 Maquinaria

Listado de maquinaria registrada, mostrando:
- Propiedad asociada.
- Año del tractor.
- Implementos disponibles.

#### 5.1.5 Comercialización

Listado de comercios, mostrando:
- Nombre del comercio.
- Dirección y contacto.
- Mercados de venta.
- Cooperativas.
- Infraestructura de empaque y venta en finca.

## 6. PROCEDIMIENTO: EXPORTAR PRODUCTORES A XLSX

[CAPTURA DE PANTALLA: Botón de exportación y progreso]

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | En el listado de productores, hacer clic en **Exportar** | El sistema genera un archivo XLSX |
| 2 | — | El archivo se descarga automáticamente |
| 3 | Abrir el archivo en Excel o LibreOffice Calc | Se visualizan los datos exportados |

### 6.1 Contenido del archivo exportado

El archivo XLSX contiene las siguientes columnas:

| Columna | Descripción |
|---------|-------------|
| ID | Identificador único del productor |
| Nombre | Nombre completo |
| Email | Correo electrónico |
| DNI | Documento Nacional de Identidad |
| Teléfono | Número de contacto |
| Dirección | Domicilio |
| Distrito | Distrito de Lavalle |
| Propiedades | Cantidad de propiedades registradas |
| Cultivos | Cantidad de cultivos registrados |
| Maquinarias | Cantidad de maquinaria registrada |
| Comercios | Cantidad de comercios registrados |
| Miembro desde | Fecha de registro |
| Verificado | Email verificado (Sí/No) |

### 6.2 Filtros en exportación

La exportación respeta los filtros aplicados en el listado. Si se aplicaron filtros de búsqueda, solo se exportan los resultados filtrados.

### 6.3 Formato del archivo

| Propiedad | Especificación |
|-----------|----------------|
| **Formato** | XLSX (Office Open XML) |
| **Biblioteca** | PhpSpreadsheet |
| **Codificación** | UTF-8 |
| **Aplicaciones compatibles** | Microsoft Excel 2007+, LibreOffice Calc, Google Sheets |

## 7. PROCEDIMIENTO: DESCARGAR RUT DE PRODUCTOR

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | En el detalle del productor, sección Propiedades | Se muestra un enlace **Descargar RUT** si el documento está disponible |
| 2 | Hacer clic en **Descargar RUT** | El sistema inicia la descarga del archivo PDF |

## 8. COMPORTAMIENTO ESPECIAL

- Si un productor no tiene datos en algún módulo (ej: no tiene cultivos registrados), la sección correspondiente se muestra vacía con un mensaje "Sin datos registrados".
- La exportación puede demorar unos segundos si hay muchos productores.
- El botón de exportación está disponible tanto para admin como para auditor.
