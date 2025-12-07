# Ejemplos - Auto Logout en Error 419

## 1. Escenario: Usuario en Móvil hace Solicitud

### Situación
```
- Usuario: Navegando en iPhone (375px)
- Acción: Hace click en un enlace
- Servidor: Responde con error 419 (sesión expirada)
```

### Flujo
```
1. Usuario hace click en enlace
   ↓
2. Navegador envía solicitud HTTP
   ↓
3. Servidor responde: 419 Page Expired
   ↓
4. Script intercepta respuesta
   ↓
5. Verifica: ¿Es 419? SÍ
   ↓
6. Verifica: ¿Es móvil? SÍ (375px < 768px)
   ↓
7. Muestra notificación:
   "Sesión expirada. Cerrando sesión automáticamente..."
   ↓
8. Espera 1.5 segundos
   ↓
9. Busca formulario de logout
   ↓
10. Envía formulario con token CSRF
    ↓
11. Servidor procesa logout
    ↓
12. Usuario redirigido a /login
```

## 2. Escenario: Usuario en Desktop hace Solicitud

### Situación
```
- Usuario: Navegando en Chrome Desktop (1920px)
- Acción: Hace click en un enlace
- Servidor: Responde con error 419
```

### Flujo
```
1. Usuario hace click en enlace
   ↓
2. Navegador envía solicitud HTTP
   ↓
3. Servidor responde: 419 Page Expired
   ↓
4. Script intercepta respuesta
   ↓
5. Verifica: ¿Es 419? SÍ
   ↓
6. Verifica: ¿Es móvil? NO (1920px >= 768px)
   ↓
7. NO hace logout automático
   ↓
8. Usuario ve error 419 normalmente
   ↓
9. Usuario puede hacer logout manualmente
```

## 3. Escenario: Usuario Vuelve a la Pestaña

### Situación
```
- Usuario: Tenía pestaña abierta hace 2 horas
- Acción: Vuelve a la pestaña (click en la pestaña)
- Sesión: Probablemente expirada
```

### Flujo
```
1. Usuario hace click en la pestaña
   ↓
2. Evento visibilitychange se dispara
   ↓
3. Script verifica: ¿Sesión válida?
   ↓
4. Hace solicitud a /api/check-session
   ↓
5. Si error 419 y es móvil:
   - Muestra notificación
   - Hace logout automático
   ↓
6. Si sesión válida:
   - Continúa normalmente
```

## 4. Código de Ejemplo: Interceptar Fetch

```javascript
// Antes de la corrección
const response = await fetch('/api/data');
// Si error 419, el usuario no sabía qué pasó

// Después de la corrección
const response = await fetch('/api/data');
// Si error 419 en móvil:
// - Script lo detecta
// - Muestra notificación
// - Hace logout automático
```

## 5. Código de Ejemplo: Interceptar XMLHttpRequest

```javascript
// Antes de la corrección
const xhr = new XMLHttpRequest();
xhr.open('POST', '/api/data');
xhr.send();
// Si error 419, el usuario no sabía qué pasó

// Después de la corrección
const xhr = new XMLHttpRequest();
xhr.open('POST', '/api/data');
xhr.send();
// Si error 419 en móvil:
// - Script lo detecta
// - Muestra notificación
// - Hace logout automático
```

## 6. Notificación Visual - Ejemplo

### En Móvil
```
┌─────────────────────────────────────┐
│ ⚠️ Sesión expirada. Cerrando...     │
└─────────────────────────────────────┘

(Aparece en la parte superior)
(Se auto-elimina después de 5 segundos)
(O cuando se completa el logout)
```

### En Desktop
```
(No aparece notificación automática)
(Usuario ve error 419 normalmente)
```

## 7. Prueba Manual en DevTools

### Paso 1: Abrir DevTools
```
F12 o Ctrl+Shift+I
```

### Paso 2: Activar Modo Responsive
```
Ctrl+Shift+M o Cmd+Shift+M
```

### Paso 3: Seleccionar Dispositivo Móvil
```
Dropdown de dispositivos → iPhone 12 (390px)
```

### Paso 4: Abrir Consola
```
Pestaña Console
```

### Paso 5: Simular Error 419
```javascript
// Opción 1: Simular fetch con error 419
fetch('/api/test', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    }
}).then(r => {
    if (r.status === 419) {
        console.log('Error 419 detectado');
    }
});

// Opción 2: Verificar si isMobile() funciona
console.log('¿Es móvil?', window.innerWidth < 768);
```

## 8. Casos de Uso Reales

### Caso 1: Usuario en Móvil con Conexión Lenta
```
- Usuario en 4G lento
- Hace solicitud que tarda 2 minutos
- Sesión expira mientras espera
- Servidor responde 419
- Script detecta y hace logout automático
- Usuario no queda confundido
```

### Caso 2: Usuario Deja Móvil Abierto
```
- Usuario abre app en móvil
- Deja el móvil en la mesa
- Sesión expira después de 1 hora
- Usuario vuelve y hace click
- Script detecta 419
- Hace logout automático
- Usuario redirigido a login
```

### Caso 3: Usuario Cambia de Red
```
- Usuario en WiFi
- Se desconecta y cambia a 4G
- Solicitud en tránsito
- Sesión expira
- Servidor responde 419
- Script detecta y hace logout
- Usuario no queda con sesión inválida
```

## 9. Configuración Personalizada

### Cambiar Tiempo de Espera

**Archivo**: `resources/views/app.blade.php`

**Antes** (1.5 segundos):
```javascript
setTimeout(function() {
    logoutForm.submit();
}, 1500);
```

**Después** (3 segundos):
```javascript
setTimeout(function() {
    logoutForm.submit();
}, 3000);
```

### Cambiar Mensaje

**Antes**:
```javascript
showNotification('Sesión expirada. Cerrando sesión automáticamente...');
```

**Después**:
```javascript
showNotification('Tu sesión ha expirado. Cerrando sesión...');
```

### Cambiar Breakpoint Móvil

**Antes** (768px):
```javascript
function isMobile() {
    return window.innerWidth < 768;
}
```

**Después** (640px):
```javascript
function isMobile() {
    return window.innerWidth < 640;
}
```

## 10. Debugging

### Verificar si el Script Está Activo

```javascript
// En la consola del navegador
console.log('Script cargado:', typeof handleAutoLogout !== 'undefined');
```

### Verificar Detección de Móvil

```javascript
// En la consola del navegador
console.log('Ancho de ventana:', window.innerWidth);
console.log('¿Es móvil?', window.innerWidth < 768);
```

### Verificar Formulario de Logout

```javascript
// En la consola del navegador
console.log('Formulario de logout:', document.querySelector('form[action*="logout"]'));
```

### Simular Logout Automático

```javascript
// En la consola del navegador
// (Solo si isMobile() es true)
const logoutForm = document.querySelector('form[action*="logout"]');
if (logoutForm) {
    logoutForm.submit();
}
```

## 11. Flujo Completo de Sesión

```
┌─────────────────────────────────────┐
│ Usuario Inicia Sesión               │
└──────────────┬──────────────────────┘
               │
               ↓
┌─────────────────────────────────────┐
│ Sesión Válida (1 hora)              │
└──────────────┬──────────────────────┘
               │
               ↓
┌─────────────────────────────────────┐
│ Usuario Navega Normalmente          │
└──────────────┬──────────────────────┘
               │
               ↓
┌─────────────────────────────────────┐
│ 1 Hora Pasa - Sesión Expira         │
└──────────────┬──────────────────────┘
               │
               ↓
┌─────────────────────────────────────┐
│ Usuario Hace Solicitud              │
└──────────────┬──────────────────────┘
               │
               ↓
┌─────────────────────────────────────┐
│ Servidor Responde 419               │
└──────────────┬──────────────────────┘
               │
        ┌──────┴──────┐
        │             │
    Móvil         Desktop
        │             │
        ↓             ↓
   Auto Logout   Mostrar Error
        │             │
        ↓             ↓
   Redirige a    Usuario Hace
   Login         Logout Manual
```

