# Manual de Administrador — Acceso al Sistema

**Código**: RUPAL-MA-02  
**Versión**: 1.0  
**Fecha**: 2026-06-15  
**ISO 9001:2015**: Cláusula 7.5 — Información Documentada

---

## 1. OBJETIVO

Describir el procedimiento de inicio de sesión, cierre de sesión y recuperación de contraseña para usuarios del sistema staff.

## 2. ALCANCE

Aplica a todos los usuarios staff (admin y auditor) del sistema RUPAL.

## 3. PROCEDIMIENTO: INICIO DE SESIÓN STAFF

![Pantalla de inicio de sesión del staff](../imagenes/02-acceso%20Pantalla%20de%20inicio%20de%20sesión%20del%20staff.png)

### 3.1 Acceder al portal staff

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Abrir el navegador e ir a `https://rupal.lavalle.gob.ar/staff/login` | Se muestra la pantalla de inicio de sesión del staff |
| 2 | Ingresar el **correo electrónico** institucional | El campo se completa con el email |
| 3 | Ingresar la **contraseña** | El campo se completa (caracteres ocultos) |
| 4 | Hacer clic en **Ingresar** | El sistema valida las credenciales |

### 3.2 Resultados posibles

| Resultado | Mensaje | Acción requerida |
|-----------|---------|------------------|
| Credenciales correctas | — | Redirige al dashboard del staff |
| Contraseña incorrecta | "Credenciales incorrectas." | Verificar la contraseña e intentar nuevamente |
| Usuario inactivo | "Usuario inactivo. Contacte al administrador." | Contactar a otro admin para reactivar la cuenta |
| Bloqueo por intentos | "Demasiados intentos." | Esperar 1 minuto antes de reintentar |

## 4. PROCEDIMIENTO: RECUPERACIÓN DE CONTRASEÑA STAFF

![Formulario de recuperación de contraseña del staff](../imagenes/02-acceso%20Formulario%20de%20recuperaci%C3%B3n%20de%20contrase%C3%B1a.png)

### 4.1 Solicitar restablecimiento

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | En la pantalla de login, hacer clic en **¿Olvidaste tu contraseña?** | Se abre el formulario de recuperación |
| 2 | Ingresar el **correo electrónico** institucional | El campo se completa |
| 3 | Hacer clic en **Enviar enlace** | El sistema envía un correo con el enlace de restablecimiento |

### 4.2 Restablecer la contraseña

[CAPTURA DE PANTALLA: Correo electrónico de restablecimiento (estilo azul)]

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Revisar la bandeja de entrada del correo electrónico | Se encuentra el correo de restablecimiento (remitente: Municipalidad de Lavalle — Staff) |
| 2 | Hacer clic en el botón **Restablecer Contraseña** | Se abre el formulario para ingresar la nueva contraseña |
| 3 | Ingresar la **nueva contraseña** (mínimo 8 caracteres) | El campo se completa |
| 4 | **Confirmar la nueva contraseña** | El campo se completa |
| 5 | Hacer clic en **Restablecer Contraseña** | El sistema actualiza la contraseña |
| 6 | Iniciar sesión con la nueva contraseña | Acceso normal al sistema |

### 4.3 Requisitos de contraseña

| Requisito | Especificación |
|-----------|----------------|
| Longitud mínima | 8 caracteres |
| Debe coincidir | Confirmación idéntica a la contraseña |
| Vigencia del enlace | 60 minutos desde la solicitud |

## 5. PROCEDIMIENTO: CIERRE DE SESIÓN STAFF

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Hacer clic en **Cerrar sesión** en la barra lateral | Aparece un diálogo de confirmación |
| 2 | Hacer clic en **Sí, cerrar sesión** | Se cierra la sesión y se redirige al login staff |

![Diálogo de confirmación de cierre de sesión del staff](../imagenes/02-acceso%20Di%C3%A1logo%20de%20confirmaci%C3%B3n%20de%20cierre%20de%20sesi%C3%B3n.png)

## 6. RECOMENDACIONES DE SEGURIDAD

- No compartir las credenciales de acceso con otras personas.
- La contraseña debe ser única y no reutilizada en otros sistemas.
- Cerrar sesión siempre al finalizar el uso del sistema.
- Reportar inmediatamente cualquier acceso no autorizado.
- Las cuentas de staff son personales e intransferibles.
