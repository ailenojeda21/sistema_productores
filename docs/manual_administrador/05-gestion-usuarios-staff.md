# Manual de Administrador — Gestión de Usuarios Staff

**Código**: RUPAL-MA-05  
**Versión**: 1.0  
**Fecha**: 2026-06-15  
**ISO 9001:2015**: Cláusula 7.5 — Información Documentada

---

## 1. OBJETIVO

Describir las operaciones de administración de usuarios del sistema staff: alta, edición, activación/desactivación y eliminación de cuentas.

## 2. ALCANCE

Aplica exclusivamente a usuarios con rol **Admin** del sistema RUPAL. Los usuarios con rol **Auditor** no tienen acceso a este módulo.

## 3. ACCESO AL MÓDULO

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Iniciar sesión con una cuenta **admin** | Se carga el dashboard |
| 2 | Hacer clic en **Usuarios** en el menú lateral | Se carga el listado de usuarios staff |

[CAPTURA DE PANTALLA: Listado de usuarios staff]

## 4. PROCEDIMIENTO: LISTADO DE USUARIOS STAFF

[CAPTURA DE PANTALLA: Tabla de usuarios staff]

### 4.1 Columnas del listado

| Columna | Descripción |
|---------|-------------|
| Nombre | Nombre completo del usuario staff |
| Email | Correo electrónico institucional |
| Rol | Admin o Auditor |
| Estado | Activo (🟢) o Inactivo (🔴) |
| Último Acceso | Fecha y hora del último inicio de sesión |
| Acciones | Botones: Editar, Eliminar |

### 4.2 Búsqueda

| Campo | Descripción |
|-------|-------------|
| Nombre | Búsqueda parcial por nombre |
| Email | Búsqueda parcial por correo electrónico |

## 5. PROCEDIMIENTO: CREAR NUEVO USUARIO STAFF

[CAPTURA DE PANTALLA: Formulario de creación de usuario staff]

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Hacer clic en **Agregar Usuario** (botón superior) | Se abre el formulario de creación |
| 2 | Completar los campos del formulario | Los campos se llenan |
| 3 | Hacer clic en **Guardar** | El sistema valida y crea el usuario |

### 5.1 Campos del formulario

| Campo | Tipo | Obligatorio | Descripción |
|-------|------|-------------|-------------|
| Nombre | Texto | Sí | Nombre completo (máximo 255 caracteres) |
| Email | Email | Sí | Correo electrónico institucional (debe ser único) |
| Contraseña | Password | Sí | Mínimo 8 caracteres |
| Confirmar Contraseña | Password | Sí | Debe coincidir con la contraseña |
| Rol | Selección | Sí | Admin o Auditor |

### 5.2 Validaciones

| Regla | Mensaje de Error |
|-------|------------------|
| Email duplicado | "El campo correo electrónico ya ha sido tomado" |
| Contraseña < 8 caracteres | "La contraseña debe tener al menos 8 caracteres" |
| Contraseñas no coinciden | "La confirmación de contraseña no coincide" |
| Rol inválido | "El rol seleccionado no es válido" |

## 6. PROCEDIMIENTO: EDITAR USUARIO STAFF

[CAPTURA DE PANTALLA: Formulario de edición de usuario staff]

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | En el listado, hacer clic en **Editar** (✏️) | Se abre el formulario con los datos actuales precargados |
| 2 | Modificar los campos necesarios | Los campos se actualizan |

### 6.1 Campos editables

| Campo | Descripción |
|-------|-------------|
| Nombre | Se puede modificar libremente |
| Email | Se puede modificar (debe ser único) |
| Rol | Admin o Auditor |
| Contraseña | Opcional. Dejar vacío para mantener la actual |
| Estado (Activo/Inactivo) | Se puede alternar |

### 6.2 Comportamiento especial: auto-edición

Cuando un admin edita su **propio** usuario:

| Acción | Resultado |
|--------|-----------|
| Cambiar el propio rol | ❌ Bloqueado: "No puedes cambiar tu propio rol." |
| Desactivarse a sí mismo | ❌ Bloqueado: "No puedes desactivarte a ti mismo." |

### 6.3 Actualización parcial (solo estado)

El sistema permite cambiar únicamente el estado (activo/inactivo) sin modificar los demás campos:

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Marcar/desmarcar la casilla "Activo" | El estado cambia |
| 2 | Hacer clic en **Guardar** (sin modificar otros campos) | Solo se actualiza el estado |

## 7. PROCEDIMIENTO: ELIMINAR USUARIO STAFF

[CAPTURA DE PANTALLA: Confirmación de eliminación de usuario staff]

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | En el listado, hacer clic en **Eliminar** (🗑️) | Aparece un cuadro de diálogo de confirmación |
| 2 | Confirmar la eliminación | El usuario se elimina (soft delete) |
| 3 | — | Mensaje: "Usuario eliminado" |

### 7.1 Comportamiento especial: auto-eliminación

| Acción | Resultado |
|--------|-----------|
| Intentar eliminarse a sí mismo | ❌ Bloqueado: "No puedes eliminarte a ti mismo." |
| Eliminar a otro admin | ✅ Permitido (solo para admin) |

### 7.2 Soft Delete

- La eliminación es **lógica** (soft delete): el registro se marca como eliminado pero permanece en la base de datos.
- Los usuarios eliminados no pueden iniciar sesión.
- Los usuarios eliminados se pueden restaurar a nivel de base de datos por el administrador del sistema.
- Los registros eliminados hace más de 365 días se purgan automáticamente (job programado diario a las 03:00).

## 8. PROCEDIMIENTO: ACTIVAR/DESACTIVAR USUARIO

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | En el listado, hacer clic en **Editar** del usuario deseado | Se abre el formulario de edición |
| 2 | Marcar **Activo** para activar / Desmarcar para desactivar | El estado cambia |
| 3 | Hacer clic en **Guardar** | El cambio se aplica |

### 8.1 Efectos del estado

| Estado | Efecto |
|--------|--------|
| **Activo** | El usuario puede iniciar sesión normalmente |
| **Inactivo** | El usuario no puede iniciar sesión. Al intentarlo, recibe el mensaje: "Usuario inactivo. Contacte al administrador." |

## 9. REGISTRO DE AUDITORÍA

Todas las operaciones sobre usuarios staff (creación, modificación, eliminación) quedan registradas en los logs del servidor para fines de auditoría, incluyendo:

- Fecha y hora de la operación.
- Usuario que realizó la operación.
- Tipo de operación (crear, actualizar, eliminar).
- Datos modificados.
