# Manual de Administrador — Introducción

**Código**: RUPAL-MA-01  
**Versión**: 1.0  
**Fecha**: 2026-06-15  
**ISO 9001:2015**: Cláusula 7.5 — Información Documentada

---

## 1. OBJETIVO

Describir la estructura y funcionalidades del sistema de administración RUPAL, destinado al personal municipal de Lavalle.

## 2. ALCANCE

Aplica a todos los usuarios con rol **Staff** (admin y auditor) del sistema RUPAL.

## 3. ROLES Y PERMISOS

El sistema staff distingue dos roles con distintos niveles de acceso:

| Rol | Permisos |
|-----|----------|
| **Admin** | Acceso completo: dashboard, visualización de productores, exportación, gestión de usuarios staff, API |
| **Auditor** | Acceso parcial: dashboard, visualización de productores, exportación. Sin acceso a gestión de usuarios staff |

### 3.1 Matriz de permisos

| Módulo | Admin | Auditor |
|--------|-------|---------|
| Dashboard (KPIs) | ✅ | ✅ |
| Listado de Productores | ✅ | ✅ |
| Detalle de Productor | ✅ | ✅ |
| Exportación de Productores (XLSX) | ✅ | ✅ |
| Gestión de Usuarios Staff | ✅ | ❌ |
| API (token) | ✅ | ✅ |

## 4. ESTRUCTURA DEL SISTEMA STAFF

```
Sistema Staff RUPAL
├── Inicio (Dashboard)
│   ├── Total de productores registrados
│   ├── Nuevos productores (últimos 30 días)
│   ├── Gráfico de crecimiento de usuarios (6 meses)
│   └── Gráfico de hectáreas cultivadas
├── Productores
│   ├── Listado con filtros (búsqueda)
│   ├── Detalle del productor
│   └── Exportación a XLSX
├── Usuarios (solo Admin)
│   ├── Listado de usuarios staff
│   ├── Crear usuario staff
│   ├── Editar usuario staff
│   └── Eliminar usuario staff
└── API (integración)
    ├── Autenticación por token (Sanctum)
    └── Endpoints REST
```

## 5. BARRA DE NAVEGACIÓN STAFF

[CAPTURA DE PANTALLA: Barra lateral del sistema staff con menú negro]

La barra lateral izquierda (color negro) contiene:

| Elemento | Descripción |
|----------|-------------|
| Logo RUPAL | Logotipo grande en la parte superior |
| Inicio | Enlace al dashboard con KPIs |
| Productores | Enlace al listado de productores |
| Usuarios | Enlace a gestión de usuarios staff (solo visible para admin) |
| Cerrar sesión | Botón para cerrar la sesión |

### 5.1 Indicadores de acceso

- Los elementos del menú a los que el usuario no tiene acceso se muestran atenuados (grises).
- El módulo activo se resalta con un indicador visual.

## 6. BARRA SUPERIOR STAFF

[CAPTURA DE PANTALLA: Barra superior del sistema staff]

| Elemento | Descripción |
|----------|-------------|
| Título de página | Nombre de la sección actual |
| Nombre de usuario | Nombre del staff autenticado |
| Botón de menú (☰) | En móvil, abre/cierra el menú lateral |

## 7. REQUISITOS TÉCNICOS

| Requisito | Especificación |
|-----------|----------------|
| Navegador | Chrome 90+, Firefox 90+, Edge 90+, Safari 14+ |
| Conexión | Mínimo 1 Mbps |
| Resolución | Mínimo 1280×720 (escritorio), 360×640 (móvil) |
| JavaScript | Obligatorio (aplicación Inertia/Vue) |

## 8. SEGURIDAD

- El acceso está protegido por autenticación con contraseña.
- Las sesiones expiran después de 120 minutos de inactividad.
- Los tokens de API expiran después de 120 minutos.
- Se aplica rate limiting: máximo 5 intentos de inicio de sesión por minuto.
- Todos los accesos quedan registrados en los logs del sistema.
