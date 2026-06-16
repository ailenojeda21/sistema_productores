# Manual de Administrador — RUPAL Staff

**Sistema**: Registro Único de Productores Agropecuarios de Lavalle  
**Versión**: 1.0  
**ISO 9001:2015**: Cláusula 7.5 — Información Documentada

---

## ÍNDICE DE DOCUMENTOS

| Código | Documento | Descripción |
|--------|-----------|-------------|
| RUPAL-MA-01 | [Introducción](01-introduccion.md) | Roles, estructura del sistema staff, navegación, requisitos |
| RUPAL-MA-02 | [Acceso al Sistema](02-acceso.md) | Inicio de sesión, recuperación de contraseña, cierre de sesión |
| RUPAL-MA-03 | [Panel de Control](03-panel-de-control.md) | Dashboard con KPIs, gráficos estadísticos |
| RUPAL-MA-04 | [Gestión de Productores](04-gestion-productores.md) | Listado, búsqueda, detalle, exportación XLSX |
| RUPAL-MA-05 | [Gestión de Usuarios Staff](05-gestion-usuarios-staff.md) | CRUD de usuarios staff, roles, activación/desactivación |
| RUPAL-MA-06 | [API de Integración](06-api.md) | Documentación técnica de la API REST |
| RUPAL-MA-07 | [Instalación y Despliegue](07-instalacion.md) | Instalación, configuración, despliegue y mantenimiento |

---

## MAPA DE PROCESOS STAFF

```
┌─────────────────────────────────────────────────────────────┐
│                     ACCESO STAFF                            │
│         Inicio de Sesión → Dashboard                       │
└──────────────────────────┬──────────────────────────────────┘
                           │
               ┌───────────┼───────────┐
               │           │           │
               ▼           ▼           ▼
┌──────────────────┐ ┌──────────┐ ┌──────────────┐
│   DASHBOARD      │ │ PRODUCT. │ │  USUARIOS    │
│   KPIs           │ │ Listado  │ │  (solo admin)│
│   Gráficos       │ │ Filtros  │ │  Crear       │
│                  │ │ Detalle  │ │  Editar      │
│                  │ │ Exportar │ │  Eliminar    │
└──────────────────┘ └──────────┘ └──────────────┘
```

---

## GUÍA RÁPIDA

| Si necesita... | Vaya a... |
|----------------|-----------|
| Iniciar sesión como staff | [Acceso → Inicio de Sesión](02-acceso.md#3-procedimiento-inicio-de-sesión-staff) |
| Ver indicadores del sistema | [Panel de Control](03-panel-de-control.md) |
| Buscar un productor | [Gestión de Productores → Búsqueda](04-gestion-productores.md#4-procedimiento-búsqueda-y-filtros) |
| Exportar productores a Excel | [Gestión de Productores → Exportar](04-gestion-productores.md#6-procedimiento-exportar-productores-a-xlsx) |
| Crear un usuario staff | [Gestión de Usuarios Staff → Crear](05-gestion-usuarios-staff.md#5-procedimiento-crear-nuevo-usuario-staff) |
| Desactivar un usuario staff | [Gestión de Usuarios Staff → Activar/Desactivar](05-gestion-usuarios-staff.md#8-procedimiento-activardesactivar-usuario) |
| Consultar la API | [API de Integración](06-api.md) |
| Obtener un token de API | [API → Autenticación](06-api.md#3-autenticación) |
