# Guía de Estilo y Componentes UI - Sistema de Gestión para Productores Agrícolas

Esta guía documenta los estilos visuales y componentes de interfaz de usuario utilizados en el Sistema de Gestión para Productores Agrícolas, basado en el diseño SAP Fiori.

## Índice

1. [Introducción a SAP Fiori](#introducción-a-sap-fiori)
2. [Paleta de Colores](#paleta-de-colores)
3. [Tipografía](#tipografía)
4. [Componentes Básicos](#componentes-básicos)
5. [Patrones de Diseño](#patrones-de-diseño)
6. [Iconografía](#iconografía)
7. [Estados y Mensajes](#estados-y-mensajes)
8. [Responsive Design](#responsive-design)
9. [Implementación Técnica](#implementación-técnica)
10. [Buenas Prácticas](#buenas-prácticas)

## Introducción a SAP Fiori

SAP Fiori es un conjunto de pautas de diseño y una biblioteca de componentes que ofrece una experiencia de usuario coherente y moderna para aplicaciones empresariales. Nuestro sistema adopta estos principios para proporcionar:

- **Simpleza**: Interfaces limpias centradas en tareas específicas
- **Coherencia**: Experiencia unificada a través de todos los módulos
- **Eficiencia**: Flujos de trabajo optimizados para tareas empresariales
- **Responsividad**: Adaptabilidad a diferentes dispositivos y tamaños de pantalla

## Paleta de Colores

### Colores Primarios
- **Azul Principal**: `#0A6ED1` - Usado en cabeceras, botones primarios y elementos de navegación
- **Azul Oscuro**: `#0070F2` - Para estados hover, elementos destacados
- **Azul Grisáceo**: `#354A5F` - Elementos secundarios, bordes de paneles importantes

### Colores Neutros
- **Fondo Principal**: `#F5F6F7` - Fondo general de la aplicación
- **Gris Claro**: `#D9D9D9` - Bordes, separadores, elementos deshabilitados
- **Texto Principal**: `#32363A` - Texto principal, títulos
- **Texto Secundario**: `#6A6D70` - Texto de ayuda, etiquetas, pie de página

### Colores de Estado
- **Verde (Éxito)**: `#107E3E` - Mensajes de éxito, acciones completadas
- **Rojo (Error)**: `#BB0000` - Mensajes de error, acciones peligrosas
- **Naranja (Advertencia)**: `#E9730C` - Advertencias, acciones que requieren atención
- **Azul (Información)**: `#0A6ED1` - Mensajes informativos
- **Amarillo (En Proceso)**: `#E9A713` - Estados en proceso, pendientes

### Uso de Colores

```css
/* Ejemplo de uso */
.btn-primary {
    background-color: #0A6ED1;
    color: white;
}

.btn-primary:hover {
    background-color: #0070F2;
}

.alert-success {
    background-color: rgba(16, 126, 62, 0.1);
    border-left: 4px solid #107E3E;
    color: #107E3E;
}
```

## Tipografía

El sistema utiliza una jerarquía tipográfica clara para mejorar la legibilidad y estructurar la información:

### Fuente Principal
- **Familia**: "72", "72-Light", -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, sans-serif
- **Alternativa**: Si la fuente "72" no está disponible, se utiliza Roboto o una fuente sans-serif del sistema

### Tamaños
- **Títulos Principales**: 24px (1.5rem)
- **Subtítulos**: 20px (1.25rem)
- **Títulos de Sección**: 16px (1rem)
- **Texto Regular**: 14px (0.875rem)
- **Texto Pequeño**: 12px (0.75rem)

### Estilos
- **Negrita**: 700 (títulos, elementos importantes)
- **Semi-negrita**: 600 (subtítulos, elementos destacados)
- **Regular**: 400 (texto general)
- **Ligera**: 300 (texto secundario, etiquetas)

```css
/* Ejemplo de implementación */
body {
    font-family: "72", "72-Light", -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, sans-serif;
    font-size: 14px;
    color: #32363A;
}

h1 {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 1rem;
}
```

## Componentes Básicos

### Botones

#### Botón Primario
- Fondo: `#0A6ED1`
- Texto: Blanco
- Borde: Ninguno
- Hover: `#0070F2`
- Bordes redondeados: 4px

#### Botón Secundario
- Fondo: Transparente
- Texto: `#0A6ED1`
- Borde: 1px sólido `#0A6ED1`
- Hover: Fondo `rgba(10, 110, 209, 0.1)`

#### Botón de Peligro
- Fondo: `#BB0000`
- Texto: Blanco
- Hover: Sombra y oscurecimiento leve

#### Botón Deshabilitado
- Fondo: `#D9D9D9`
- Texto: `#6A6D70`
- Cursor: not-allowed

### Campos de Formulario

#### Input de Texto
- Borde: 1px sólido `#D9D9D9`
- Fondo: Blanco
- Texto: `#32363A`
- Focus: Borde `#0A6ED1`
- Padding: 8px 12px
- Bordes redondeados: 4px

#### Select
- Apariencia similar a inputs de texto
- Indicador de flecha personalizado

#### Checkbox y Radio
- Estilo personalizado siguiendo el diseño Fiori
- Estados hover y checked con animación sutil

### Tablas

- Cabeceras: Fondo `#F5F6F7`, texto en negrita
- Filas: Borde inferior 1px sólido `#D9D9D9`
- Filas alternas: Fondo `#FAFAFA`
- Hover: Fondo `rgba(10, 110, 209, 0.05)`
- Celdas: Padding 12px 16px

### Tarjetas (Cards)

- Fondo: Blanco
- Sombra: 0 2px 6px rgba(0, 0, 0, 0.1)
- Bordes redondeados: 4px
- Cabecera: Padding 16px, borde inferior 1px sólido `#D9D9D9`
- Cuerpo: Padding 16px

## Patrones de Diseño

### Interfaz MDI (Multiple Document Interface)

El sistema implementa un patrón MDI donde:

1. **Panel de Navegación Principal**: Ubicado a la izquierda, con categorías y acciones principales
2. **Área de Trabajo Central**: Donde se abren múltiples "documentos" o formularios
3. **Barra Superior**: Contiene el logo, buscador global, notificaciones y perfil de usuario

### Gestión de Formularios

- **Formularios en Pestañas**: Permite trabajar con múltiples entidades simultáneamente
- **Formularios en Modal**: Para acciones rápidas que no requieren un contexto completo

### Estructura de Página

- **Encabezado**: Título de sección + acciones principales
- **Filtros y Búsqueda**: Ubicados bajo el encabezado
- **Contenido Principal**: Tablas o tarjetas con datos
- **Pie de Página**: Acciones secundarias, paginación, totales

## Iconografía

El sistema utiliza iconos de Material Design con estilos adaptados a SAP Fiori:

- **Tamaño**: 20px para navegación, 16px para acciones contextuales
- **Color**: Heredado del texto o específico según contexto
- **Nombres de Iconos Comunes**:
  - `add` - Añadir nuevo elemento
  - `edit` - Editar elemento
  - `delete` - Eliminar elemento
  - `search` - Buscar
  - `filter_list` - Filtrar
  - `description` - Ver detalles
  - `check_circle` - Completado/Éxito
  - `warning` - Advertencia
  - `error` - Error

```html
<!-- Ejemplo de uso -->
<button class="btn-primary">
    <span class="material-icons">add</span>
    Añadir Propiedad
</button>
```

## Estados y Mensajes

### Notificaciones

- **Éxito**: Fondo verde claro, icono de verificación, tiempo de auto-cierre: 5s
- **Error**: Fondo rojo claro, icono de error, requiere acción para cerrar
- **Advertencia**: Fondo naranja claro, icono de advertencia
- **Información**: Fondo azul claro, icono de información

### Estados de Formulario

- **Validación Inline**: Mensajes bajo cada campo con error
- **Feedback Visual**: Cambios de color de borde y fondo para validación
- **Indicadores de Carga**: Spinners y barras de progreso consistentes

## Responsive Design

### Puntos de Quiebre

- **Móvil**: < 600px
- **Tablet**: 600px - 1024px
- **Desktop**: > 1024px

### Adaptaciones

- En móvil, el menú lateral se colapsa a un menú hamburguesa
- Las tablas se transforman en tarjetas en vista móvil
- Los formularios se reorganizan en una sola columna en dispositivos pequeños

## Implementación Técnica

### CSS Framework

El sistema utiliza una combinación de Tailwind CSS personalizado y estilos propios:

```js
// tailwind.config.js
module.exports = {
  theme: {
    extend: {
      colors: {
        'fiori-blue': '#0A6ED1',
        'fiori-blue-dark': '#0070F2',
        'fiori-text': '#32363A',
        'fiori-success': '#107E3E',
        'fiori-error': '#BB0000',
        'fiori-warning': '#E9730C',
        // ...otros colores
      },
      // ...otras personalizaciones
    }
  }
}
```

### Componentes Blade

El sistema utiliza componentes Blade para mantener la consistencia:

```php
// Ejemplo de componente botón
@component('components.button', ['type' => 'primary'])
    {{ $slot }}
@endcomponent
```

### JavaScript Interacciones

- Usa clases de utilidad para comportamientos comunes
- Interacciones basadas en eventos para componentes interactivos
- Transiciones y animaciones sutiles para mejorar la experiencia

## Buenas Prácticas

### Creación de Nuevas Interfaces

1. **Seguir la jerarquía visual establecida**
2. **Mantener la consistencia** con componentes existentes
3. **Priorizar la simplicidad** y la claridad
4. **Utilizar componentes reutilizables** en lugar de crear nuevos estilos
5. **Validar la accesibilidad** de nuevos componentes

### Extensión de Componentes

Para extender la biblioteca de componentes:

1. Documentar el nuevo componente en esta guía
2. Crear un componente Blade reutilizable
3. Añadir ejemplos de uso
4. Asegurar compatibilidad con diferentes tamaños de pantalla

### Checklist de Diseño

- [ ] Los colores utilizados pertenecen a la paleta oficial
- [ ] La tipografía sigue la jerarquía establecida
- [ ] Los componentes son consistentes con el resto del sistema
- [ ] La interfaz es usable en dispositivos móviles
- [ ] Se han seguido las prácticas de accesibilidad básicas

---

## Ejemplos Visuales

### Ejemplo de Botones

```html
<button class="btn-primary">Botón Primario</button>
<button class="btn-secondary">Botón Secundario</button>
<button class="btn-danger">Botón de Peligro</button>
<button class="btn-primary" disabled>Botón Deshabilitado</button>
```

### Ejemplo de Formulario

```html
<div class="fiori-form-group">
  <label for="fieldName">Nombre del Campo</label>
  <input type="text" id="fieldName" class="fiori-input" placeholder="Ingrese un valor">
  <small class="fiori-help-text">Texto de ayuda para este campo</small>
</div>
```

### Ejemplo de Tabla

```html
<table class="fiori-table">
  <thead>
    <tr>
      <th>Nombre</th>
      <th>Descripción</th>
      <th>Estado</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Propiedad 1</td>
      <td>Descripción de la propiedad</td>
      <td><span class="badge badge-success">Activo</span></td>
      <td class="actions">
        <button class="btn-icon"><span class="material-icons">edit</span></button>
        <button class="btn-icon"><span class="material-icons">delete</span></button>
      </td>
    </tr>
  </tbody>
</table>
```

---

Esta guía de estilo debe considerarse como un documento vivo que evolucionará con el sistema. Para cualquier consulta o sugerencia relacionada con el diseño, contacte al equipo de UX/UI.
