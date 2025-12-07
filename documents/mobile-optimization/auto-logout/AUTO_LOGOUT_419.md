# Auto Logout en Error 419 (Modo Móvil)

## 🎯 Descripción

Se implementó una funcionalidad que detecta automáticamente errores 419 (Page Expired) en dispositivos móviles y realiza un logout automático sin intervención del usuario.

## 🔧 Funcionalidad

### Detección de Error 419

El script intercepta dos tipos de solicitudes HTTP:

1. **Fetch API**
   ```javascript
   window.fetch = function(...args) {
       return originalFetch.apply(this, args).then(response => {
           if (response.status === 419 && isMobile()) {
               handleAutoLogout();
           }
           return response;
       });
   }
   ```

2. **XMLHttpRequest**
   ```javascript
   XMLHttpRequest.prototype.open = function(method, url, ...rest) {
       this.addEventListener('load', function() {
           if (this.status === 419 && isMobile()) {
               handleAutoLogout();
           }
       });
   }
   ```

### Detección de Dispositivo Móvil

```javascript
function isMobile() {
    return window.innerWidth < 768;
}
```

**Breakpoint**: 768px (igual al breakpoint del drawer)

### Proceso de Logout Automático

1. **Mostrar Notificación**
   - Mensaje: "Sesión expirada. Cerrando sesión automáticamente..."
   - Ubicación: Top en móviles, bottom en desktop
   - Duración: 5 segundos

2. **Buscar Formulario de Logout**
   ```javascript
   const logoutForm = document.querySelector('form[action*="logout"]');
   ```

3. **Enviar Formulario**
   - Espera 1.5 segundos para que el usuario vea la notificación
   - Envía el formulario de logout
   - Token CSRF se incluye automáticamente

4. **Fallback**
   - Si no encuentra el formulario, redirige a `/login`

## 📱 Comportamiento

### En Móviles (< 768px)

```
Usuario hace solicitud HTTP
        ↓
Servidor responde con 419
        ↓
Script detecta error 419
        ↓
Muestra notificación amarilla
        ↓
Espera 1.5 segundos
        ↓
Envía formulario de logout
        ↓
Usuario desconectado automáticamente
```

### En Desktop (≥ 768px)

- El script sigue activo pero NO ejecuta logout automático
- El usuario ve el error 419 normalmente
- Puede hacer logout manualmente

## 🔄 Verificación de Sesión

El script también verifica la validez de la sesión cuando el usuario vuelve a la pestaña:

```javascript
document.addEventListener('visibilitychange', function() {
    if (document.hidden === false) {
        checkSessionValidity();
    }
});
```

**Nota**: Requiere endpoint `/api/check-session` (opcional)

## 📝 Notificación Visual

```
┌─────────────────────────────────────┐
│ ⚠️ Sesión expirada. Cerrando...     │
└─────────────────────────────────────┘
```

**Estilos**:
- Fondo: Amarillo claro (bg-yellow-100)
- Borde: Amarillo (border-yellow-400)
- Texto: Amarillo oscuro (text-yellow-700)
- Posición: Fixed top-4 en móviles
- Z-index: 50 (sobre todo)

## 🔐 Seguridad

- ✅ Token CSRF se incluye automáticamente
- ✅ Solo funciona en móviles
- ✅ No interfiere con solicitudes normales
- ✅ No expone información sensible

## 📁 Archivos Modificados

- ✅ `resources/views/app.blade.php` - Script de auto logout agregado

## 🧪 Pruebas

### Prueba Manual

1. **Abrir en móvil**
   - Abrir aplicación en dispositivo móvil
   - O usar DevTools en modo responsive (< 768px)

2. **Simular error 419**
   - Hacer una solicitud que genere error 419
   - O esperar a que la sesión expire

3. **Verificar comportamiento**
   - Debe aparecer notificación amarilla
   - Debe hacer logout automático después de 1.5 segundos
   - Debe redirigir a login

### Prueba en Desktop

1. **Abrir en desktop**
   - Abrir aplicación en desktop (> 768px)

2. **Simular error 419**
   - Hacer una solicitud que genere error 419

3. **Verificar comportamiento**
   - NO debe hacer logout automático
   - Debe mostrar error 419 normalmente

## 🔧 Configuración

### Cambiar Breakpoint Móvil

En `resources/views/app.blade.php`:
```javascript
function isMobile() {
    return window.innerWidth < 640; // Cambiar 768 por otro valor
}
```

### Cambiar Tiempo de Espera

En `resources/views/app.blade.php`:
```javascript
setTimeout(function() {
    logoutForm.submit();
}, 3000); // Cambiar 1500 por otro valor (en ms)
```

### Cambiar Mensaje de Notificación

En `resources/views/app.blade.php`:
```javascript
showNotification('Tu mensaje personalizado aquí');
```

## 📊 Flujo de Datos

```
┌─────────────────────────────────────┐
│ Usuario en Móvil                    │
└──────────────┬──────────────────────┘
               │
               ↓
┌─────────────────────────────────────┐
│ Hace solicitud HTTP                 │
└──────────────┬──────────────────────┘
               │
               ↓
┌─────────────────────────────────────┐
│ Servidor responde 419               │
└──────────────┬──────────────────────┘
               │
               ↓
┌─────────────────────────────────────┐
│ Script intercepta respuesta          │
└──────────────┬──────────────────────┘
               │
               ↓
┌─────────────────────────────────────┐
│ Verifica: ¿Es 419? ¿Es móvil?      │
└──────────────┬──────────────────────┘
               │
        ┌──────┴──────┐
        │             │
       NO             SÍ
        │             │
        ↓             ↓
    Continuar    Mostrar notificación
                      │
                      ↓
                  Esperar 1.5s
                      │
                      ↓
                  Enviar logout
                      │
                      ↓
                  Desconectar
```

## ⚠️ Consideraciones

1. **Experiencia del Usuario**
   - El logout es automático, el usuario no puede cancelarlo
   - Se muestra notificación para informar al usuario

2. **Seguridad**
   - Protege contra sesiones expiradas en móviles
   - Evita que el usuario continúe con sesión inválida

3. **Compatibilidad**
   - Funciona en todos los navegadores modernos
   - Soporta Fetch API y XMLHttpRequest

## 🚀 Ventajas

- ✅ Experiencia mejorada en móviles
- ✅ Logout automático sin intervención
- ✅ Notificación clara al usuario
- ✅ Seguridad mejorada
- ✅ Sin dependencias externas

## 📌 Notas

- El script se ejecuta en TODAS las páginas (app.blade.php es el layout base)
- Solo afecta a dispositivos móviles (< 768px)
- No interfiere con el funcionamiento normal de la aplicación
- Se puede desactivar comentando el script si es necesario

