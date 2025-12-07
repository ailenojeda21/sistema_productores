# Guía Visual - Drawer Mobile

## 1. Estado Inicial (Móvil)

```
┌─────────────────────────────────┐
│ Panel              [≡]          │  ← Header fijo
├─────────────────────────────────┤
│                                 │
│                                 │
│     Contenido Principal         │
│                                 │
│                                 │
│                                 │
│                                 │
└─────────────────────────────────┘
```

**Características:**
- Header fijo en la parte superior
- Botón hamburguesa (≡) en la esquina superior derecha
- Drawer oculto fuera de pantalla (a la izquierda)
- Contenido principal visible

---

## 2. Drawer Abierto

```
┌─────────────────────────────────┐
│ Panel              [≡]          │
├──────────────┐                  │
│ ▓▓▓▓▓▓▓▓▓▓▓▓ │ Overlay          │
│ Dashboard    │                  │
│ Perfil       │ Contenido        │
│ Propiedades  │ Principal        │
│ Cultivos     │ (oscurecido)     │
│ Maquinarias  │                  │
│ Comercial.   │                  │
│ Cerrar sesión│                  │
│              │                  │
└──────────────┘                  │
└─────────────────────────────────┘
```

**Características:**
- Drawer deslizado desde la izquierda
- Overlay oscuro semi-transparente (50% opacidad)
- Efecto blur en el fondo
- Menú completamente visible
- Scroll independiente en el drawer

---

## 3. Animación de Apertura

```
Paso 1: Cerrado
┌─────────────────────────────────┐
│ Panel              [≡]          │
├─────────────────────────────────┤
│                                 │
│     Contenido Principal         │
│                                 │
└─────────────────────────────────┘

        ↓ Click en [≡]

Paso 2: Deslizando (50%)
┌─────────────────────────────────┐
│ Panel              [≡]          │
├──────────┐                      │
│ ▓▓▓▓▓▓▓▓ │ Overlay (fade in)   │
│ Dashboard│ Contenido (fade out) │
│ Perfil   │                      │
│ ...      │                      │
└──────────┘                      │
└─────────────────────────────────┘

        ↓ Animación 300ms

Paso 3: Abierto
┌─────────────────────────────────┐
│ Panel              [≡]          │
├──────────────┐                  │
│ ▓▓▓▓▓▓▓▓▓▓▓▓ │ Overlay          │
│ Dashboard    │ Contenido        │
│ Perfil       │ (oscurecido)     │
│ ...          │                  │
└──────────────┘                  │
└─────────────────────────────────┘
```

**Duración:** 300ms con easing ease-in-out

---

## 4. Estado Desktop (≥ 768px)

```
┌──────────────────────────────────────────┐
│ ┌──────────────┐ ┌────────────────────┐  │
│ │ Panel        │ │                    │  │
│ │ ▓▓▓▓▓▓▓▓▓▓▓▓ │ │  Contenido         │  │
│ │ Dashboard    │ │  Principal         │  │
│ │ Perfil       │ │                    │  │
│ │ Propiedades  │ │                    │  │
│ │ Cultivos     │ │                    │  │
│ │ Maquinarias  │ │                    │  │
│ │ Comercial.   │ │                    │  │
│ │ Cerrar sesión│ │                    │  │
│ └──────────────┘ └────────────────────┘  │
└──────────────────────────────────────────┘
```

**Características:**
- Header hamburguesa desaparece
- Sidebar siempre visible (izquierda)
- Contenido principal ocupa espacio disponible
- Sin overlay
- Comportamiento tradicional

---

## 5. Interacciones

### Hacer Click en Hamburguesa
```
[≡] → Drawer se abre
      ↓
      Overlay aparece
      ↓
      Body scroll se desactiva
```

### Hacer Click en Overlay
```
Overlay → Drawer se cierra
          ↓
          Overlay desaparece
          ↓
          Body scroll se reactiva
```

### Hacer Click en Enlace del Menú
```
Enlace → Drawer se cierra automáticamente
         ↓
         Usuario navega a la página
         ↓
         Body scroll se reactiva
```

### Redimensionar a Desktop
```
Ventana ≥ 768px → Drawer se cierra
                  ↓
                  Sidebar siempre visible
                  ↓
                  Header hamburguesa desaparece
```

---

## 6. Componentes Detallados

### Header Mobile
```
┌─────────────────────────────────┐
│ Panel              [≡]          │
└─────────────────────────────────┘
  ↑                    ↑
  Logo               Botón Hamburguesa
  (Fijo)             (44x44px)
```

**Propiedades:**
- Altura: 56px (py-3 + padding)
- Ancho: 100% (full width)
- Posición: fixed top-0
- Z-index: 40
- Color: azul-marino

### Drawer
```
┌──────────────┐
│ Panel        │ ← Avatar + Nombre
├──────────────┤
│ Dashboard    │ ← Enlace con ícono
│ Perfil       │
│ Propiedades  │
│ Cultivos     │
│ Maquinarias  │
│ Comercial.   │
├──────────────┤
│ Cerrar sesión│ ← Botón logout
└──────────────┘
```

**Propiedades:**
- Ancho: 256px (w-64)
- Altura: 100vh (h-screen)
- Posición: fixed top-0 left-0
- Scroll: independiente
- Z-index: 40

### Overlay
```
┌─────────────────────────────────┐
│ ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ │
│ ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ │
│ ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ │
│ ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ │
└─────────────────────────────────┘
  ↑
  bg-black bg-opacity-50
  backdrop-filter: blur(2px)
```

**Propiedades:**
- Cubre toda la pantalla (inset-0)
- Opacidad: 50%
- Efecto blur: 2px
- Z-index: 30
- Clickeable para cerrar

---

## 7. Z-Index Stack

```
┌─────────────────────────────────┐
│ z-40: Header Mobile             │ ← Más arriba
├─────────────────────────────────┤
│ z-40: Drawer                    │
├─────────────────────────────────┤
│ z-30: Overlay                   │
├─────────────────────────────────┤
│ z-auto: Contenido Principal     │ ← Más abajo
└─────────────────────────────────┘
```

---

## 8. Transiciones

### Apertura del Drawer
```
Tiempo: 0ms
transform: translateX(-100%)
opacity: 0

        ↓ 150ms (50%)

transform: translateX(-50%)
opacity: 0.5

        ↓ 300ms (100%)

transform: translateX(0)
opacity: 1
```

### Cierre del Drawer
```
Tiempo: 0ms
transform: translateX(0)
opacity: 1

        ↓ 150ms (50%)

transform: translateX(-50%)
opacity: 0.5

        ↓ 300ms (100%)

transform: translateX(-100%)
opacity: 0
```

---

## 9. Responsive Breakpoints

### Mobile (< 640px)
```
┌─────────────────────────────────┐
│ Panel              [≡]          │
├─────────────────────────────────┤
│                                 │
│     Contenido Principal         │
│                                 │
└─────────────────────────────────┘
```

### Tablet Pequeña (640px - 768px)
```
┌─────────────────────────────────┐
│ Panel              [≡]          │
├─────────────────────────────────┤
│                                 │
│     Contenido Principal         │
│                                 │
└─────────────────────────────────┘
```

### Tablet/Desktop (≥ 768px)
```
┌──────────────────────────────────────────┐
│ ┌──────────────┐ ┌────────────────────┐  │
│ │ Panel        │ │ Contenido Principal│  │
│ │ ▓▓▓▓▓▓▓▓▓▓▓▓ │ │                    │  │
│ │ Dashboard    │ │                    │  │
│ │ ...          │ │                    │  │
│ └──────────────┘ └────────────────────┘  │
└──────────────────────────────────────────┘
```

---

## 10. Flujo de Interacción Completo

```
┌─────────────────────────────────┐
│ Página Cargada                  │
│ - Drawer cerrado                │
│ - Header visible                │
│ - Contenido visible             │
└──────────────┬──────────────────┘
               │
               ↓
        ┌──────────────┐
        │ Usuario hace │
        │ click en [≡] │
        └──────────────┘
               │
               ↓
┌─────────────────────────────────┐
│ Drawer Abierto                  │
│ - Animación 300ms               │
│ - Overlay visible               │
│ - Body scroll desactivado       │
└──────────────┬──────────────────┘
               │
        ┌──────┴──────┐
        │             │
        ↓             ↓
   ┌─────────┐   ┌─────────┐
   │ Click   │   │ Click   │
   │ Overlay │   │ Enlace  │
   └────┬────┘   └────┬────┘
        │             │
        └──────┬──────┘
               │
               ↓
┌─────────────────────────────────┐
│ Drawer Cerrado                  │
│ - Animación 300ms               │
│ - Overlay oculto                │
│ - Body scroll reactivado        │
│ - (Si fue enlace: navega)       │
└─────────────────────────────────┘
```

---

## 11. Estados del Drawer

| Estado | Clase | Transform | Overlay | Body Scroll |
|--------|-------|-----------|---------|-------------|
| Cerrado | `-translate-x-full` | translateX(-100%) | hidden | auto |
| Abierto | `translate-x-0` | translateX(0) | visible | hidden |

---

## 12. Dimensiones

```
Header Mobile:
┌─────────────────────────────────┐
│ Panel              [≡]          │ ← 56px (py-3 + padding)
└─────────────────────────────────┘
  └─ Ancho: 100%

Drawer:
┌──────────────┐
│              │
│              │ ← 256px (w-64)
│              │
│              │ ← 100vh (h-screen)
│              │
│              │
└──────────────┘

Contenido Principal:
┌─────────────────────────────────┐
│ Panel              [≡]          │ ← 56px
├─────────────────────────────────┤
│                                 │
│     Contenido Principal         │ ← calc(100vh - 56px)
│                                 │
└─────────────────────────────────┘
```

---

## 13. Colores

```
Header: bg-azul-marino (#223362)
├─ Texto: text-white
└─ Hover: hover:bg-opacity-80

Drawer: bg-azul-marino (#223362)
├─ Texto: text-white
├─ Hover: hover:bg-amarillo-claro
└─ Hover Texto: hover:text-azul-marino

Overlay: bg-black bg-opacity-50
└─ Blur: backdrop-filter: blur(2px)

Botón Logout: text-red-400
└─ Hover: hover:bg-red-500 hover:text-white
```

---

## 14. Iconos

```
Hamburguesa (≡):
┌─────────┐
│ ─────── │
│ ─────── │
│ ─────── │
└─────────┘

Cerrar Sesión (→):
┌─────────┐
│ → ─ ─ ─ │
│ ─ ─ ─ → │
└─────────┘
```

