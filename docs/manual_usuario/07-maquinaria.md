# Manual de Usuario — Maquinaria

**Código**: RUPAL-MU-07  
**Versión**: 1.0  
**Fecha**: 2026-06-15  
**ISO 9001:2015**: Cláusula 7.5 — Información Documentada

---

## 1. OBJETIVO

Describir el procedimiento completo de gestión de maquinaria agrícola e implementos en RUPAL.

## 2. ALCANCE

Aplica a todos los productores que necesiten registrar la maquinaria disponible y los implementos asociados a cada una de sus propiedades.

## 3. DESCRIPCIÓN DEL MÓDULO

El módulo de maquinaria permite al productor:
- Registrar tractores y otra maquinaria agrícola asociada a una propiedad.
- Especificar el año del tractor.
- Seleccionar los implementos disponibles entre 13 tipos diferentes.

## 4. PROCEDIMIENTO: LISTADO DE MAQUINARIA

[CAPTURA DE PANTALLA: Listado de maquinaria]

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Hacer clic en **Maquinarias** en el menú lateral | Se carga el listado de maquinaria registrada |
| 2 | — | Se muestran tarjetas con: tractor, año, propiedad asociada, implementos |

### 4.1 Columnas del listado

| Columna | Descripción |
|---------|-------------|
| Propiedad | Dirección de la propiedad asociada |
| Año del Tractor | Año de fabricación del tractor |
| Implementos | Cantidad y tipos de implementos registrados |
| Acciones | Botones: Editar, Eliminar |

## 5. PROCEDIMIENTO: CREAR NUEVA MAQUINARIA

[CAPTURA DE PANTALLA: Formulario de creación de maquinaria]

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Hacer clic en **Agregar Maquinaria** (botón superior) | Se abre el formulario de creación |
| 2 | Seleccionar la **Propiedad** del menú desplegable | Se despliega la lista de propiedades del productor |
| 3 | Ingresar el **año del tractor** | Campo numérico |
| 4 | Seleccionar los **implementos** disponibles | Casillas de verificación (una o más) |
| 5 | Hacer clic en **Guardar** | El sistema valida y guarda la maquinaria |

### 5.1 Campos del formulario

| Campo | Tipo | Obligatorio | Descripción |
|-------|------|-------------|-------------|
| Propiedad | Selección | Sí | Propiedad donde opera la maquinaria |
| Año del Tractor | Número | No | Año de fabricación del tractor |
| Implementos | Checkboxes | No | Selección múltiple de implementos disponibles |

### 5.2 Tipos de implementos (13)

| # | Implemento | Descripción |
|---|------------|-------------|
| 1 | Arado | Equipo para labranza primaria |
| 2 | Rastra | Equipo para desterronar y nivelar |
| 3 | Niveladora Común | Equipo para nivelación de terreno |
| 4 | Niveladora Láser | Niveladora con sistema de guiado láser |
| 5 | Surcador | Equipo para abrir surcos de riego |
| 6 | Sembradora | Equipo para siembra de semillas |
| 7 | Cultivadora | Equipo para control de malezas entre surcos |
| 8 | Aporcador | Equipo para formar camellones |
| 9 | Pulverizadora | Equipo para aplicación de fitosanitarios |
| 10 | Acoplado Tolva | Remolque para transporte de granos |
| 11 | Acoplado Común | Remolque para carga general |
| 12 | Rollito | Equipo para henificar forraje |
| 13 | Otros | Cualquier otro implemento no listado |

[CAPTURA DE PANTALLA: Sección de implementos con checkboxes]

## 6. PROCEDIMIENTO: EDITAR MAQUINARIA

[CAPTURA DE PANTALLA: Formulario de edición de maquinaria precargado]

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | En el listado, hacer clic en **Editar** (✏️) | Se abre el formulario con los datos actuales precargados |
| 2 | Modificar el año del tractor | Campo numérico |
| 3 | Marcar/desmarcar implementos | Las casillas se actualizan |
| 4 | Hacer clic en **Guardar** | El sistema valida y actualiza la maquinaria |
| 5 | — | Mensaje de confirmación: "Maquinaria actualizada exitosamente" |

## 7. PROCEDIMIENTO: ELIMINAR MAQUINARIA

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | En el listado, hacer clic en **Eliminar** (🗑️) | Aparece un cuadro de diálogo de confirmación |
| 2 | Confirmar la eliminación | La maquinaria y sus implementos asociados se eliminan |

## 8. VALIDACIONES DEL FORMULARIO

| Campo | Regla de Validación |
|-------|---------------------|
| Propiedad | Requerido, debe existir y pertenecer al productor |
| Año del Tractor | Numérico, 4 dígitos, año válido |

## 9. COMPORTAMIENTO ESPECIAL

- Se puede seleccionar más de un implemento por maquinaria.
- Una propiedad puede tener múltiples registros de maquinaria.
- Si no se selecciona ningún implemento, la maquinaria se registra igualmente (solo el tractor).
