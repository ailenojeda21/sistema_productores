# Guía de Uso del Drawer Mobile

## Descripción General

El sistema de drawer implementado en `resources/views/layouts/dashboard.blade.php` proporciona una experiencia de navegación optimizada para dispositivos móviles, manteniendo la compatibilidad con desktops.

---

## Componentes Principales

### 1. Header Mobile (Hamburguesa)
```html
<!-- Visible solo en pantallas < 768px -->
<div class="md:hidden fixed top-0 left-0 right-0 bg-azul-marino text-white flex items-center justify-between px-4 py-3 shadow-lg z-40">
    <span class="font-bold text-lg">Panel</span>
    <button id="drawer-toggle" class="p-2 hover:bg-opacity-80 rounded transition" aria-label="Toggle menu">
        <!-- Icono hamburguesa -->
    </button>
</div>
```

**Características:**
- Fijo en la parte superior
- Z-index 40 para estar sobre el contenido
- Visible solo en móviles (md:hidden)
- Botón hamburguesa con tamaño touch-friendly

### 2. Drawer Overlay
```html
<!-- Overlay oscuro detrás del drawer -->
<div id="drawer-overlay" class="fixed inset-0 bg-black bg-opacity-50 md:hidden hidden z-30 transition-opacity duration-300"></div>
```

**Características:**
- Fondo semi-transparente (50% opacidad)
- Efecto blur (backdrop-filter)
- Cierra el drawer al hacer click
- Transición suave de opacidad

### 3. Drawer/Sidebar
```html
<!-- Drawer en móviles, sidebar en desktop -->
<aside id="drawer" class="fixed md:static top-0 left-0 h-screen md:h-auto w-64 bg-azul-marino text-white flex flex-col py-4 md:py-8 px-4 shadow-lg md:min-h-screen transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-40 md:z-auto">
    <!-- Contenido del menú -->
</aside>
```

**Características:**
- Posición fija en móviles, estática en desktop
- Ancho fijo 256px (w-64)
- Altura completa de pantalla en móviles
- Animación de deslizamiento suave (300ms)
- Z-index 40 en móviles, auto en desktop

---

## Funcionamiento JavaScript

### Estados del Drawer

#### Cerrado (Inicial)
```
drawer: -translate-x-full (fuera de pantalla a la izquierda)
overlay: hidden
body: overflow auto
```

#### Abierto
```
drawer: translate-x-0 (visible)
overlay: visible
body: overflow hidden
```

### Funciones Principales

#### `toggleDrawer()`
Alterna entre abrir y cerrar el drawer.

```javascript
function toggleDrawer() {
    const isOpen = drawer.classList.contains('translate-x-0');
    if (isOpen) {
        closeDrawer();
    } else {
        openDrawer();
    }
}
```

#### `openDrawer()`
Abre el drawer con animación.

```javascript
function openDrawer() {
    drawer.classList.remove('-translate-x-full');
    drawer.classList.add('translate-x-0');
    overlay.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
```

#### `closeDrawer()`
Cierra el drawer con animación.

```javascript
function closeDrawer() {
    drawer.classList.add('-translate-x-full');
    drawer.classList.remove('translate-x-0');
    overlay.classList.add('hidden');
    document.body.style.overflow = 'auto';
}
```

### Event Listeners

1. **Botón Hamburguesa**: `drawer-toggle.addEventListener('click', toggleDrawer)`
2. **Overlay**: `overlay.addEventListener('click', closeDrawer)`
3. **Enlaces del Drawer**: Se cierran automáticamente al navegar
4. **Resize de Ventana**: Se cierra al cambiar a desktop

---

## Estilos CSS

### Animación de Deslizamiento
```css
#drawer {
    transform: translateX(-100%);
    transition: transform 300ms ease-in-out;
}

#drawer.translate-x-0 {
    transform: translateX(0);
}
```

### Overlay con Blur
```css
#drawer-overlay {
    backdrop-filter: blur(2px);
}
```

### Scroll Suave
```css
#drawer {
    -webkit-overflow-scrolling: touch;
}
```

---

## Comportamiento Responsivo

### En Móviles (< 768px)
```
┌─────────────────────┐
│ Panel        [≡]    │  ← Header fijo
├─────────────────────┤
│                     │
│   Contenido         │
│   Principal         │
│                     │
└─────────────────────┘

Al hacer click en [≡]:
┌──────────────────────────────┐
│ Panel        [≡]             │
├──────────────────────────────┤
│ ┌─────────┐                  │
│ │ ▓▓▓▓▓▓▓ │ Overlay          │
│ │ Dashboard                  │
│ │ Perfil                     │
│ │ Propiedades                │
│ │ Cultivos                   │
│ │ Maquinarias                │
│ │ Comercialización           │
│ │ Cerrar sesión              │
│ └─────────┘                  │
└──────────────────────────────┘
```

### En Desktop (≥ 768px)
```
┌──────────────────────────────────────┐
│ ┌─────────┐ ┌──────────────────────┐ │
│ │ Panel   │ │                      │ │
│ │ ▓▓▓▓▓▓▓ │ │  Contenido Principal │ │
│ │ Dashboard                         │ │
│ │ Perfil  │ │                      │ │
│ │ Propied │ │                      │ │
│ │ Cultivos│ │                      │ │
│ │ Maquina │ │                      │ │
│ │ Comerci │ │                      │ │
│ │ Cerrar  │ │                      │ │
│ └─────────┘ └──────────────────────┘ │
└──────────────────────────────────────┘
```

---

## Pruebas

### Pruebas Manuales

1. **Abrir Drawer**
   - Hacer click en el botón hamburguesa
   - Verificar que el drawer se desliza desde la izquierda
   - Verificar que el overlay aparece

2. **Cerrar Drawer**
   - Hacer click en un enlace del menú
   - Hacer click en el overlay
   - Presionar ESC (opcional, no implementado)

3. **Responsividad**
   - Redimensionar ventana a desktop
   - Verificar que el drawer se convierte en sidebar
   - Redimensionar de vuelta a móvil

4. **Scroll**
   - Abrir drawer
   - Verificar que el contenido principal no se desplaza
   - Verificar que el drawer tiene scroll independiente

### Pruebas en DevTools

```javascript
// Abrir drawer desde consola
document.getElementById('drawer-toggle').click();

// Cerrar drawer
document.getElementById('drawer-overlay').click();

// Verificar clases
console.log(document.getElementById('drawer').classList);
```

---

## Personalización

### Cambiar Ancho del Drawer
Modificar en `dashboard.blade.php`:
```html
<aside id="drawer" class="... w-64 ...">
```
Cambiar `w-64` por otro valor (ej: `w-72`, `w-80`)

### Cambiar Color del Header
Modificar en `dashboard.blade.php`:
```html
<div class="... bg-azul-marino ...">
```
Cambiar `bg-azul-marino` por otro color

### Cambiar Velocidad de Animación
Modificar en `dashboard.blade.php`:
```html
<aside id="drawer" class="... transition-transform duration-300 ...">
```
Cambiar `duration-300` por `duration-200`, `duration-500`, etc.

### Cambiar Opacidad del Overlay
Modificar en `dashboard.blade.php`:
```html
<div id="drawer-overlay" class="... bg-black bg-opacity-50 ...">
```
Cambiar `bg-opacity-50` por otro valor (0-100)

---

## Compatibilidad

- ✅ Chrome/Edge (versiones recientes)
- ✅ Firefox (versiones recientes)
- ✅ Safari (iOS 12+)
- ✅ Android Browser
- ✅ Opera

---

## Notas Importantes

1. **Sin Dependencias**: El drawer usa JavaScript vanilla, sin librerías externas
2. **Performance**: Usa CSS transforms para animaciones suaves
3. **Accesibilidad**: Botón con `aria-label`
4. **Mobile-First**: Diseñado primero para móviles, luego mejorado para desktop
5. **Prevención de Scroll**: Evita scroll de fondo cuando drawer está abierto

---

## Solución de Problemas

### El drawer no se abre
- Verificar que el ID `drawer-toggle` existe
- Verificar que JavaScript está habilitado
- Revisar la consola del navegador para errores

### El drawer se ve cortado
- Verificar que `w-64` no es mayor que el ancho de pantalla
- Verificar que `h-screen` está correctamente aplicado

### El overlay no aparece
- Verificar que `z-30` es menor que `z-40` del drawer
- Verificar que la clase `hidden` se está removiendo correctamente

### El contenido se desplaza cuando drawer está abierto
- Verificar que `document.body.style.overflow = 'hidden'` se está aplicando
- Verificar que se está removiendo correctamente al cerrar

