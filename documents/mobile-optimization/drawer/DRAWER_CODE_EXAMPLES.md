# Ejemplos de Código - Drawer Mobile

## 1. HTML Básico

### Header Mobile
```html
<!-- Visible solo en pantallas < 768px -->
<div class="md:hidden fixed top-0 left-0 right-0 bg-azul-marino text-white flex items-center justify-between px-4 py-3 shadow-lg z-40">
    <span class="font-bold text-lg">Panel</span>
    <button id="drawer-toggle" class="p-2 hover:bg-opacity-80 rounded transition" aria-label="Toggle menu">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
</div>
```

### Overlay
```html
<!-- Fondo oscuro detrás del drawer -->
<div id="drawer-overlay" class="fixed inset-0 bg-black bg-opacity-50 md:hidden hidden z-30 transition-opacity duration-300"></div>
```

### Drawer
```html
<!-- Menú deslizable -->
<aside id="drawer" class="fixed md:static top-0 left-0 h-screen md:h-auto w-64 bg-azul-marino text-white flex flex-col py-4 md:py-8 px-4 shadow-lg md:min-h-screen transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-40 md:z-auto">
    <!-- Contenido del menú -->
</aside>
```

---

## 2. JavaScript Completo

```javascript
document.addEventListener('DOMContentLoaded', function() {
    // Seleccionar elementos
    const drawerToggle = document.getElementById('drawer-toggle');
    const drawer = document.getElementById('drawer');
    const overlay = document.getElementById('drawer-overlay');

    // Función para alternar drawer
    function toggleDrawer() {
        const isOpen = drawer.classList.contains('translate-x-0');
        
        if (isOpen) {
            closeDrawer();
        } else {
            openDrawer();
        }
    }

    // Función para abrir drawer
    function openDrawer() {
        drawer.classList.remove('-translate-x-full');
        drawer.classList.add('translate-x-0');
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Función para cerrar drawer
    function closeDrawer() {
        drawer.classList.add('-translate-x-full');
        drawer.classList.remove('translate-x-0');
        overlay.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Event listener para botón hamburguesa
    drawerToggle.addEventListener('click', toggleDrawer);

    // Event listener para overlay
    overlay.addEventListener('click', closeDrawer);

    // Event listeners para enlaces del drawer
    const drawerLinks = drawer.querySelectorAll('a, button');
    drawerLinks.forEach(link => {
        link.addEventListener('click', function() {
            // No cerrar si es un botón de formulario
            if (this.tagName !== 'BUTTON' || !this.closest('form')) {
                closeDrawer();
            }
        });
    });

    // Event listener para resize de ventana
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            closeDrawer();
        }
    });
});
```

---

## 3. CSS Personalizado

### Estilos del Drawer
```css
/* Drawer container */
#drawer {
    box-shadow: 2px 0 8px rgba(0, 0, 0, 0.15);
    -webkit-overflow-scrolling: touch;
}

/* Drawer abierto */
#drawer.translate-x-0 {
    transform: translateX(0);
}

/* Drawer cerrado */
#drawer.-translate-x-full {
    transform: translateX(-100%);
}

/* Overlay */
#drawer-overlay {
    backdrop-filter: blur(2px);
}

/* Botón hamburguesa */
#drawer-toggle {
    min-width: 44px;
    min-height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Animación suave */
#drawer {
    transition: transform 300ms ease-in-out;
}
```

---

## 4. Ejemplos de Uso

### Abrir Drawer Programáticamente
```javascript
// Desde consola del navegador
document.getElementById('drawer-toggle').click();

// O directamente
const drawer = document.getElementById('drawer');
drawer.classList.remove('-translate-x-full');
drawer.classList.add('translate-x-0');
document.getElementById('drawer-overlay').classList.remove('hidden');
```

### Cerrar Drawer Programáticamente
```javascript
// Desde consola del navegador
document.getElementById('drawer-overlay').click();

// O directamente
const drawer = document.getElementById('drawer');
drawer.classList.add('-translate-x-full');
drawer.classList.remove('translate-x-0');
document.getElementById('drawer-overlay').classList.add('hidden');
```

### Verificar Estado del Drawer
```javascript
const drawer = document.getElementById('drawer');
const isOpen = drawer.classList.contains('translate-x-0');
console.log('Drawer abierto:', isOpen);
```

---

## 5. Estructura Completa del Layout

```html
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col md:flex-row bg-gray-100" id="dashboard-container">
    
    <!-- Mobile Header with Hamburger Menu -->
    <div class="md:hidden fixed top-0 left-0 right-0 bg-azul-marino text-white flex items-center justify-between px-4 py-3 shadow-lg z-40">
        <span class="font-bold text-lg">Panel</span>
        <button id="drawer-toggle" class="p-2 hover:bg-opacity-80 rounded transition" aria-label="Toggle menu">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <!-- Drawer Overlay (Mobile) -->
    <div id="drawer-overlay" class="fixed inset-0 bg-black bg-opacity-50 md:hidden hidden z-30 transition-opacity duration-300"></div>

    <!-- Sidebar/Drawer -->
    <aside id="drawer" class="fixed md:static top-0 left-0 h-screen md:h-auto w-64 bg-azul-marino text-white flex flex-col py-4 md:py-8 px-4 shadow-lg md:min-h-screen transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-40 md:z-auto">
        
        <!-- Avatar y Panel -->
        <div class="flex flex-col items-center mb-6 md:mb-10"> 
            <div class="bg-blue-50 rounded-full p-0 shadow-md overflow-hidden h-20 w-20 md:h-24 md:w-24">
                <img 
                    src="{{ Auth::user()->avatar ? asset('images/avatars/' . Auth::user()->avatar) : asset('images/avatars/uno.png') }}" 
                    alt="Avatar" 
                    class="h-full w-full object-cover"
                >
            </div>
            <span class="font-bold text-base md:text-lg mt-2">Panel</span>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex flex-col space-y-1 md:space-y-2 overflow-y-auto">
            <!-- Menu items aquí -->
        </nav>

    </aside>

    <!-- Main Panel -->
    <main class="flex-1 p-4 md:p-8 flex flex-col justify-start items-center overflow-y-auto text-base w-full mt-16 md:mt-0">
        @yield('dashboard-content')
    </main>

</div>

<!-- Drawer Toggle Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const drawerToggle = document.getElementById('drawer-toggle');
    const drawer = document.getElementById('drawer');
    const overlay = document.getElementById('drawer-overlay');

    function toggleDrawer() {
        const isOpen = drawer.classList.contains('translate-x-0');
        if (isOpen) {
            closeDrawer();
        } else {
            openDrawer();
        }
    }

    function openDrawer() {
        drawer.classList.remove('-translate-x-full');
        drawer.classList.add('translate-x-0');
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDrawer() {
        drawer.classList.add('-translate-x-full');
        drawer.classList.remove('translate-x-0');
        overlay.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    drawerToggle.addEventListener('click', toggleDrawer);
    overlay.addEventListener('click', closeDrawer);

    const drawerLinks = drawer.querySelectorAll('a, button');
    drawerLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (this.tagName !== 'BUTTON' || !this.closest('form')) {
                closeDrawer();
            }
        });
    });

    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            closeDrawer();
        }
    });
});
</script>

@endsection

<style>
html, body {
    margin: 0;
    padding: 0;
    height: 100%;
}

table {
    border-collapse: collapse;
    width: 100%;
}

td, th {
    word-wrap: break-word;
    white-space: normal;
}

@media (max-width: 767px) {
    #drawer {
        box-shadow: 2px 0 8px rgba(0, 0, 0, 0.15);
    }
    
    #drawer.translate-x-0 {
        transform: translateX(0);
    }
    
    #drawer.-translate-x-full {
        transform: translateX(-100%);
    }
}

body.drawer-open {
    overflow: hidden;
}
</style>
```

---

## 6. Variaciones Personalizadas

### Drawer Más Ancho
```html
<!-- Cambiar w-64 a w-80 -->
<aside id="drawer" class="... w-80 ...">
```

### Animación Más Rápida
```html
<!-- Cambiar duration-300 a duration-200 -->
<aside id="drawer" class="... transition-transform duration-200 ...">
```

### Overlay Más Oscuro
```html
<!-- Cambiar bg-opacity-50 a bg-opacity-75 -->
<div id="drawer-overlay" class="... bg-opacity-75 ...">
```

### Drawer con Borde
```html
<!-- Agregar border-r -->
<aside id="drawer" class="... border-r border-gray-200 ...">
```

---

## 7. Testing

### Test Manual en Consola
```javascript
// Verificar que los elementos existen
console.log('Drawer:', document.getElementById('drawer'));
console.log('Toggle:', document.getElementById('drawer-toggle'));
console.log('Overlay:', document.getElementById('drawer-overlay'));

// Verificar estado
const drawer = document.getElementById('drawer');
console.log('Clases:', drawer.classList);
console.log('Abierto:', drawer.classList.contains('translate-x-0'));

// Simular click
document.getElementById('drawer-toggle').click();
console.log('Después del click:', drawer.classList.contains('translate-x-0'));
```

### Test Automatizado (Cypress)
```javascript
describe('Drawer Mobile', () => {
    it('debe abrir drawer al hacer click en hamburguesa', () => {
        cy.get('#drawer-toggle').click();
        cy.get('#drawer').should('have.class', 'translate-x-0');
        cy.get('#drawer-overlay').should('not.have.class', 'hidden');
    });

    it('debe cerrar drawer al hacer click en overlay', () => {
        cy.get('#drawer-toggle').click();
        cy.get('#drawer-overlay').click();
        cy.get('#drawer').should('have.class', '-translate-x-full');
        cy.get('#drawer-overlay').should('have.class', 'hidden');
    });

    it('debe cerrar drawer al navegar', () => {
        cy.get('#drawer-toggle').click();
        cy.get('#drawer a').first().click();
        cy.get('#drawer').should('have.class', '-translate-x-full');
    });
});
```

---

## 8. Troubleshooting

### El drawer no se anima
```javascript
// Verificar que las clases se están aplicando
const drawer = document.getElementById('drawer');
drawer.addEventListener('transitionend', () => {
    console.log('Animación completada');
});
```

### El overlay no desaparece
```javascript
// Verificar que la clase 'hidden' se está agregando
const overlay = document.getElementById('drawer-overlay');
console.log('Clases del overlay:', overlay.classList);
```

### El scroll no se previene
```javascript
// Verificar que overflow se está cambiando
console.log('Overflow del body:', document.body.style.overflow);
```

