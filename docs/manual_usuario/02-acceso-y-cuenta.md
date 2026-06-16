# Manual de Usuario — Acceso y Cuenta

**Código**: RUPAL-MU-02  
**Versión**: 1.0  
**Fecha**: 2026-06-15  
**ISO 9001:2015**: Cláusula 7.5 — Información Documentada

---

## 1. OBJETIVO

Guiar al productor en el proceso de registro, inicio de sesión, verificación de correo electrónico y recuperación de contraseña en el sistema RUPAL.

## 2. ALCANCE

Aplica a todos los productores que necesiten crear una cuenta, acceder al sistema o recuperar sus credenciales de acceso.

## 3. PROCEDIMIENTO: REGISTRO DE NUEVA CUENTA

### 3.1 Acceder a la página de registro

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Abrir el navegador e ir a la URL del sistema (ej: `https://rupal.lavalle.gob.ar`) | Se muestra la página de inicio con los botones **Ingresar** y **Registrarse** |
| 2 | Hacer clic en el botón **Registrarse** | Se abre el formulario de registro |

![Página de inicio con botones Ingresar y Registrarse](../imagenes/02-acceso%20P%C3%A1gina%20de%20inicio%20con%20botones%20Ingresar%20y%20Registrarse%20.png)

### 3.2 Completar el formulario de registro

| Campo | Descripción | Obligatorio | Formato |
|-------|-------------|-------------|---------|
| Nombre | Nombre completo del productor | Sí | Texto, máximo 255 caracteres |
| Correo Electrónico | Dirección de correo válida | Sí | formato email, máximo 255 caracteres |
| Contraseña | Contraseña de acceso | Sí | Mínimo 8 caracteres |
| Confirmar Contraseña | Repetir la contraseña | Sí | Debe coincidir con el campo Contraseña |

![Formulario de registro con campos completados](../imagenes/02-acceso%20Formulario%20de%20registro%20con%20campos%20completados.png)

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 3 | Completar todos los campos del formulario | Los campos se llenan con los datos ingresados |
| 4 | Hacer clic en el botón **Registrarse** | El sistema procesa el registro |
| 5 | — | Si los datos son válidos: se crea la cuenta y se redirige al dashboard con un mensaje de bienvenida |
| 6 | — | Si hay errores: se muestran mensajes de validación en rojo debajo de cada campo |

### 3.3 Mensajes de error comunes en el registro

| Error | Causa | Solución |
|-------|-------|----------|
| "El campo correo electrónico ya ha sido tomado" | Ya existe una cuenta con ese email | Iniciar sesión o usar otro correo |
| "La contraseña debe tener al menos 8 caracteres" | Contraseña demasiado corta | Ingresar una contraseña de 8 o más caracteres |
| "La confirmación de contraseña no coincide" | Las contraseñas no son iguales | Escribir la misma contraseña en ambos campos |

## 4. PROCEDIMIENTO: VERIFICACIÓN DE CORREO ELECTRÓNICO

Después del registro, el sistema envía un correo de verificación.

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Revisar la bandeja de entrada del correo electrónico registrado | Se encuentra un correo de **Municipalidad de Lavalle — RUPAL** |
| 2 | Abrir el correo | El mensaje contiene un botón **Verificar Correo Electrónico** |
| 3 | Hacer clic en el botón **Verificar Correo Electrónico** | Se abre una ventana del navegador confirmando la verificación |

[CAPTURA DE PANTALLA: Correo electrónico de verificación con botón naranja]

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 4 | — | El sistema muestra un mensaje: "¡Correo electrónico verificado correctamente!" |
| 5 | — | Se redirige al dashboard del productor |

### 4.1 Reenviar correo de verificación

Si no se recibe el correo:

| Paso | Acción |
|------|--------|
| 1 | Iniciar sesión en RUPAL |
| 2 | Si el correo no está verificado, aparecerá una notificación en la parte superior |
| 3 | Hacer clic en el enlace "reenviar el correo de verificación" |
| 4 | Esperar unos minutos y revisar la bandeja de entrada (incluyendo la carpeta de spam) |

[CAPTURA DE PANTALLA: Notificación de correo no verificado con enlace para reenviar]

## 5. PROCEDIMIENTO: INICIO DE SESIÓN

![Pantalla de inicio de sesión](../imagenes/02-acceso%20Pantalla%20de%20inicio%20de%20sesion.png)

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Ir a la URL del sistema | Se muestra la página de inicio |
| 2 | Hacer clic en **Ingresar** | Se abre el formulario de inicio de sesión |
| 3 | Ingresar el **correo electrónico** | El campo se llena con el email |
| 4 | Ingresar la **contraseña** | El campo se llena (los caracteres se ocultan con •) |
| 5 | Opcional: marcar **Recordarme** | La sesión durará más tiempo |
| 6 | Hacer clic en **Ingresar** | El sistema valida las credenciales |
| 7 | — | Si las credenciales son correctas: se redirige al dashboard |
| 8 | — | Si las credenciales son incorrectas: aparece el mensaje "Estas credenciales no coinciden con nuestros registros" |

### 5.1 Bloqueo por intentos fallidos

- Después de **5 intentos fallidos** en 1 minuto, el sistema bloqueará temporalmente el acceso.
- El bloqueo se levanta automáticamente después de 1 minuto.
- Durante el bloqueo, se muestra el mensaje: "Demasiados intentos de inicio de sesión. Inténtelo de nuevo en X segundos."

## 6. PROCEDIMIENTO: RECUPERACIÓN DE CONTRASEÑA

### 6.1 Solicitar restablecimiento

![Formulario de recuperación de contraseña](../imagenes/02-acceso%20Formulario%20de%20recuperaci%C3%B3n%20de%20contrase%C3%B1a.png)

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | En la pantalla de inicio de sesión, hacer clic en **¿Olvidaste tu contraseña?** | Se abre el formulario de recuperación |
| 2 | Ingresar el **correo electrónico** registrado | El campo se completa |
| 3 | Hacer clic en **Enviar enlace de restablecimiento** | El sistema envía un correo con el enlace |
| 4 | — | Aparece un mensaje: "Te hemos enviado por correo electrónico el enlace para restablecer tu contraseña" |

### 6.2 Restablecer la contraseña

[CAPTURA DE PANTALLA: Correo electrónico de restablecimiento de contraseña (estilo naranja)]

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Revisar la bandeja de entrada del correo | Se encuentra el correo de restablecimiento |
| 2 | Hacer clic en el botón **Restablecer Contraseña** | Se abre el formulario de nueva contraseña |
| 3 | Ingresar la **nueva contraseña** (mínimo 8 caracteres) | El campo se completa |
| 4 | **Confirmar la nueva contraseña** | El campo se completa |
| 5 | Hacer clic en **Restablecer Contraseña** | El sistema actualiza la contraseña |
| 6 | — | Aparece un mensaje de éxito y se redirige al inicio de sesión |
| 7 | Iniciar sesión con la nueva contraseña | Acceso normal al sistema |

## 7. PROCEDIMIENTO: CIERRE DE SESIÓN

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Hacer clic en el icono de usuario (esquina superior derecha) | Se despliega un menú |
| 2 | Hacer clic en **Cerrar Sesión** | Aparece un cuadro de diálogo de confirmación |
| 3 | Hacer clic en **Sí, cerrar sesión** | Se cierra la sesión y se redirige a la página de inicio |

![Diálogo de confirmación de cierre de sesión](../imagenes/02-acceso%20Di%C3%A1logo%20de%20confirmaci%C3%B3n%20de%20cierre%20de%20sesi%C3%B3n.png)

## 8. RECOMENDACIONES DE SEGURIDAD

- No compartir la contraseña con terceros.
- Utilizar una contraseña única para RUPAL (no reutilizar contraseñas de otros sitios).
- Cerrar sesión al finalizar cada uso, especialmente en computadoras compartidas.
- Mantener actualizados los datos de contacto (correo electrónico).
- Reportar inmediatamente cualquier actividad sospechosa en la cuenta al equipo de soporte.
