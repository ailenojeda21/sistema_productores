# Manual de Usuario — Panel de Control

**Código**: RUPAL-MU-03  
**Versión**: 1.0  
**Fecha**: 2026-06-15  
**ISO 9001:2015**: Cláusula 7.5 — Información Documentada

---

## 1. OBJETIVO

Describir la funcionalidad del panel de control (dashboard) del productor, donde se visualiza el resumen de completitud del perfil y el estado general de la información registrada.

## 2. ALCANCE

Aplica a todos los productores autenticados que acceden al sistema RUPAL.

## 3. ACCESO AL PANEL DE CONTROL

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Iniciar sesión en RUPAL | El sistema redirige automáticamente al panel de control |
| 2 | Alternativamente, hacer clic en **Inicio** en el menú lateral | Se carga el panel de control |

## 4. ESTRUCTURA DEL PANEL

[CAPTURA DE PANTALLA: Panel de control completo del productor]

El panel de control se compone de las siguientes secciones:

### 4.1 Barra de progreso general

En la parte superior se muestra una barra de progreso que indica el porcentaje total de completitud del perfil del productor.

| Elemento | Descripción |
|----------|-------------|
| Porcentaje numérico | Valor de 0% a 100% |
| Barra de progreso visual | Barra horizontal coloreada (verde cuando está completa) |
| Etiqueta | Texto indicativo: "Perfil completo" o "Completá tu perfil" |

[CAPTURA DE PANTALLA: Barra de progreso general al 75%]

### 4.2 Tarjetas de módulos

[CAPTURA DE PANTALLA: Tarjetas de módulos en el dashboard]

Cada módulo del sistema se representa mediante una tarjeta que contiene:

| Elemento | Descripción |
|----------|-------------|
| Icono | Icono representativo del módulo |
| Nombre del módulo | Título del módulo |
| Círculo de progreso | Indicador circular con el % de completitud |
| Descripción breve | Texto explicativo del módulo |

Los módulos presentados son:

| Módulo | Icono | Descripción |
|--------|-------|-------------|
| Perfil | 👤 | Datos personales del productor |
| Propiedades | 🏠 | Propiedades rurales registradas |
| Cultivos | 🌱 | Cultivos declarados por propiedad |
| Maquinarias | 🚜 | Maquinaria e implementos agrícolas |
| Comercialización | 🛒 | Canales de comercialización |

### 4.3 Indicador de completitud por módulo

Cada módulo tiene un indicador circular que muestra el progreso:

| Rango | Color | Significado |
|-------|-------|-------------|
| 0% | Sin color / gris | Módulo sin comenzar |
| 1% – 49% | Rojo | Módulo incompleto |
| 50% – 99% | Naranja / Amarillo | Módulo parcialmente completo |
| 100% | Verde | Módulo completo |

[CAPTURA DE PANTALLA: Detalle de círculo de progreso en cada estado]

## 5. CRITERIOS DE COMPLETITUD

### 5.1 Perfil
- Nombre completo registrado: 50%
- DNI registrado: 25%
- Teléfono registrado: 25%

### 5.2 Propiedades
- Al menos una propiedad registrada: 50%
- Dirección completa en todas las propiedades: 25%
- Tipo de tenencia definido: 25%

### 5.3 Cultivos
- Al menos un cultivo registrado: 50%
- Variedad especificada: 25%
- Hectáreas cultivadas informadas: 25%

### 5.4 Maquinarias
- Al menos una maquinaria registrada: 50%
- Implementos especificados: 50%

### 5.5 Comercialización
- Al menos un comercio registrado: 50%
- Mercados seleccionados: 25%
- Cooperativas seleccionadas (si aplica): 25%

## 6. MENSAJES INFORMATIVOS

El panel puede mostrar mensajes contextuales:

| Mensaje | Condición |
|---------|-----------|
| "Bienvenido a RUPAL" | Primer inicio de sesión |
| "Completá tu perfil para acceder a todas las funcionalidades" | Perfil incompleto |
| "¡Todos los datos están completos!" | Perfil al 100% |
| "Verificá tu correo electrónico" | Cuenta no verificada |

## 7. NAVEGACIÓN A MÓDULOS

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Hacer clic en cualquier tarjeta del dashboard | El sistema redirige al listado del módulo correspondiente |
| 2 | Usar el menú lateral izquierdo | Navegación directa a cada módulo |

## 8. COMPORTAMIENTO ESPERADO

- El panel de control se actualiza automáticamente cada vez que se modifica algún módulo.
- Los indicadores de progreso reflejan en tiempo real el estado de la información cargada.
- No es posible modificar datos directamente desde el panel; solo es una vista de resumen.
