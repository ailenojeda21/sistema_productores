# Manual de Usuario — Perfil

**Código**: RUPAL-MU-04  
**Versión**: 1.0  
**Fecha**: 2026-06-15  
**ISO 9001:2015**: Cláusula 7.5 — Información Documentada

---

## 1. OBJETIVO

Describir las operaciones de gestión del perfil del productor: visualización, edición de datos, selección de avatar, exportación de datos y eliminación de cuenta.

## 2. ALCANCE

Aplica a todos los productores registrados en RUPAL que deseen administrar su información personal.

## 3. PROCEDIMIENTO: VISUALIZAR PERFIL

![Página de visualización de perfil](../imagenes/04-perfil%20P%C3%A1gina%20de%20visualizaci%C3%B3n%20de%20perfil.png)

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Hacer clic en **Perfil** en el menú lateral | Se carga la página de perfil |
| 2 | — | Se muestran los datos personales del productor: nombre, email, DNI, teléfono, dirección |
| 3 | — | Se muestra el avatar actual y los indicadores de completitud |

## 4. PROCEDIMIENTO: EDITAR DATOS PERSONALES

![Formulario de edición de perfil](../imagenes/04-perfil%20Formulario%20de%20edici%C3%B3n%20de%20perfil.png)

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Hacer clic en **Perfil** → **Editar Perfil** (o el icono de lápiz) | Se abre el formulario de edición |
| 2 | Modificar los campos deseados | Los campos se actualizan con los nuevos valores |

### 4.1 Campos editables

| Campo | Descripción | Obligatorio | Validación |
|-------|-------------|-------------|------------|
| Nombre | Nombre completo del productor | Sí | Texto, máximo 255 caracteres |
| DNI | Documento Nacional de Identidad | No | Texto, máximo 20 caracteres |
| Teléfono | Número de contacto | No | Texto, máximo 20 caracteres |
| Dirección | Domicilio real del productor | No | Texto, máximo 255 caracteres |
| ¿Forma parte de una cooperativa? | Indicador sí/no | No | Selección binaria |
| Cooperativas | Selección de cooperativas | Si aplica | Lista de cooperativas disponibles |

### 4.2 Cooperativas disponibles

| Código | Nombre |
|--------|--------|
| cooperativa_nueva_california | Coop Nueva California |
| cooperativa_tulumaya | Coop Tulumaya |
| cooperativa_norte_mendocino | Coop Norte Mendocino |
| cooperativa_tres_de_mayo | Coop Tres de Mayo |
| cooperativa_altas_cumbres | Coop Altas Cumbres |
| cooperativa_tres_portenas | Coop Tres Porteñas |
| cooperativa_el_poniente | Coop El Poniente |
| cooperativa_pampanos_mendocinos | Coop Pámpanos Mendocinos |
| cooperativa_ingeniero_giagnoni | Coop Ingeniero Giagnoni |
| cooperativa_las_trincheras | Coop Las Trincheras |
| cooperativa_agricola_beltran | Coop Agrícola Beltrán |
| cooperativa_la_dormida | Coop La Dormida |
| cooperativa_del_algarrobal | Coop Del Algarrobal |
| cooperativa_el_libertador | Coop El Libertador |
| cooperativa_brindis | Coop Brindis |

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 3 | Hacer clic en **Guardar** | El sistema valida y guarda los cambios |
| 4 | — | Si es exitoso: muestra "Perfil actualizado correctamente" en la parte superior |
| 5 | — | Si hay errores: se muestran mensajes de validación en rojo |

## 5. PROCEDIMIENTO: CAMBIAR AVATAR

![Galería de avatares disponibles](../imagenes/04-perfil%20Galer%C3%ADa%20de%20avatares%20disponibles.png)

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Hacer clic en **Perfil** → **Cambiar Avatar** | Se abre la galería de avatares |
| 2 | Seleccionar uno de los avatares disponibles (5 opciones) | El avatar seleccionado se marca con un borde |
| 3 | Hacer clic en **Guardar** | El avatar se actualiza en todo el sistema |
| 4 | — | Mensaje de confirmación: "Avatar actualizado correctamente" |

![Perfil con nuevo avatar seleccionado](../imagenes/04-perfil%20Perfil%20con%20nuevo%20avatar%20seleccionado.png)

## 6. PROCEDIMIENTO: EXPORTAR DATOS PERSONALES

Este endpoint permite al productor descargar todos sus datos registrados en RUPAL en formato JSON, cumpliendo con el derecho de portabilidad (ISO 27701).

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Ir a **Perfil** | Se carga la página de perfil |
| 2 | Hacer clic en **Exportar mis datos** | El sistema descarga un archivo JSON |
| 3 | Guardar el archivo en la computadora | El archivo contiene todos los datos del productor |

### 6.1 Contenido del archivo exportado

```json
{
  "user": {
    "id": 1,
    "name": "Juan Pérez",
    "email": "juan@ejemplo.com",
    "dni": "12345678",
    "telefono": "+5492612345678",
    "direccion": "Calle Falsa 123",
    "cooperativas": ["cooperativa_nueva_california"],
    "tiene_cooperativas": true,
    "tipo_productor": null,
    "avatar": "uno.png",
    "created_at": "2026-01-15T10:30:00.000000Z",
    "updated_at": "2026-06-15T15:00:00.000000Z"
  },
  "propiedades": [ ... ],
  "cultivos": [ ... ],
  "maquinarias": [ ... ],
  "comercios": [ ... ]
}
```

## 7. PROCEDIMIENTO: ELIMINAR CUENTA

### 7.1 Consideraciones importantes

- La eliminación de cuenta es una acción **irreversible**.
- Todos los datos asociados al productor (propiedades, cultivos, maquinaria, comercios) serán **eliminados permanentemente** tras el período de retención legal.
- Se requiere ingresar la contraseña actual para confirmar la operación.

### 7.2 Pasos para eliminar la cuenta

[CAPTURA DE PANTALLA: Sección de eliminación de cuenta con campo de contraseña]

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Ir a **Perfil** → **Editar Perfil** | Se carga el formulario de edición |
| 2 | Desplazarse hasta la sección **Eliminar Cuenta** | Se muestra la advertencia y el campo de contraseña |
| 3 | Ingresar la **contraseña actual** | El campo se completa |
| 4 | Hacer clic en **Eliminar Cuenta** | Aparece un cuadro de diálogo de confirmación |
| 5 | Confirmar la eliminación | La cuenta se marca como eliminada (soft delete) |
| 6 | — | Se cierra la sesión y se redirige a la página de inicio |

[CAPTURA DE PANTALLA: Diálogo de confirmación de eliminación de cuenta]

### 7.3 Mensajes de error

| Mensaje | Causa |
|---------|-------|
| "La contraseña ingresada es incorrecta" | La contraseña no coincide con la registrada |
| "Error al eliminar la cuenta" | Problema interno del sistema — contactar a soporte |

## 8. REGISTRO DE AUDITORÍA

Cada modificación del perfil queda registrada en el sistema de logs del servidor para fines de auditoría (ISO 27001 A.12.4).
