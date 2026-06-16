# RUPAL — Sistema de Gestión de Productores Agropecuarios de Lavalle

## Documentación del Sistema (ISO 9001:2015)

---

## CONTROL DE DOCUMENTOS

| Versión | Fecha | Elaboró | Revisó | Aprobó | Descripción del Cambio |
|---------|-------|---------|--------|--------|------------------------|
| 1.0 | 2026-06-15 | Equipo de Desarrollo | — | — | Versión inicial |
| | | | | | |
| | | | | | |

---

## ÍNDICE DE DOCUMENTOS ASOCIADOS

| Código | Documento | Descripción | Destinatario |
|--------|-----------|-------------|--------------|
| RUPAL-MU-01 | Manual de Usuario — Introducción | Alcance, requisitos técnicos, soporte | Productor |
| RUPAL-MU-02 | Manual de Usuario — Acceso y Cuenta | Registro, inicio de sesión, verificación, recuperación de contraseña | Productor |
| RUPAL-MU-03 | Manual de Usuario — Panel de Control | Dashboard del productor | Productor |
| RUPAL-MU-04 | Manual de Usuario — Perfil | Edición de perfil, avatar, exportación de datos, baja de cuenta | Productor |
| RUPAL-MU-05 | Manual de Usuario — Propiedades | Gestión de propiedades (CRUD), RUT, geolocalización | Productor |
| RUPAL-MU-06 | Manual de Usuario — Cultivos | Gestión de cultivos (CRUD), variedades, hectáreas | Productor |
| RUPAL-MU-07 | Manual de Usuario — Maquinaria | Gestión de maquinaria e implementos | Productor |
| RUPAL-MU-08 | Manual de Usuario — Comercialización | Gestión de comercios, mercados, cooperativas | Productor |
| RUPAL-MA-01 | Manual de Administrador — Introducción | Alcance del sistema staff, roles | Staff |
| RUPAL-MA-02 | Manual de Administrador — Acceso | Inicio de sesión, recuperación de contraseña staff | Staff |
| RUPAL-MA-03 | Manual de Administrador — Panel de Control | Dashboard con KPIs, gráficos | Staff |
| RUPAL-MA-04 | Manual de Administrador — Gestión de Productores | Visualización, búsqueda, exportación de productores | Staff |
| RUPAL-MA-05 | Manual de Administrador — Gestión de Usuarios Staff | CRUD de usuarios administradores/auditores | Staff (admin) |
| RUPAL-MA-06 | Manual de Administrador — API | Endpoints disponibles para integración | Staff (desarrollador) |
| RUPAL-MA-07 | Manual de Administrador — Instalación y Despliegue | Instalación, configuración, despliegue y mantenimiento | Staff (admin) |

---

## 1. PROPÓSITO

Este conjunto de documentos constituye el manual de usuario y administrador del sistema **RUPAL (Registro Único de Productores Agropecuarios de Lavalle)**. Su propósito es:

- Proveer instrucciones detalladas paso a paso para el uso correcto del sistema.
- Asegurar la comprensión uniforme de los procesos por parte de todos los usuarios.
- Servir como referencia oficial para la capacitación de nuevos usuarios.
- Cumplir con los requisitos de documentación del Sistema de Gestión de Calidad ISO 9001:2015 (Cláusula 7.5 — Información Documentada).

## 2. ALCANCE

La documentación cubre la totalidad de los módulos funcionales del sistema RUPAL:

- **Sistema Productores (Frontend Blade)**: Módulos de autenticación, perfil, propiedades, cultivos, maquinaria y comercialización. Destinado a productores agropecuarios del departamento de Lavalle, Mendoza.
- **Sistema Staff (Frontend Inertia/Vue)**: Módulos de administración, visualización de productores, gestión de usuarios staff y panel de control con indicadores. Destinado al personal municipal de Lavalle.

## 3. REFERENCIAS

| Documento | Descripción |
|-----------|-------------|
| ISO 9001:2015 | Sistema de Gestión de Calidad — Requisitos |
| ISO 27001:2022 | Sistema de Gestión de Seguridad de la Información |
| ISO 27701:2019 | Extensión de privacidad para ISO 27001 |
| Ley 25.326 | Ley de Protección de Datos Personales (Argentina) |
| AGENTS.md | Guía de desarrollo y convenciones del proyecto |

## 4. DEFINICIONES Y ABREVIATURAS

| Término | Definición |
|---------|------------|
| RUPAL | Registro Único de Productores Agropecuarios de Lavalle |
| Productor | Usuario final del sistema, productor agropecuario del departamento de Lavalle |
| Staff | Personal municipal que administra y consulta el sistema |
| Admin | Rol de staff con permisos totales sobre el sistema |
| Auditor | Rol de staff con permisos de consulta y exportación |
| RUT | Registro Único Tributario (documento asociado a la propiedad) |
| CRUD | Create, Read, Update, Delete — operaciones básicas de gestión |
| KPI | Key Performance Indicator — indicador clave de rendimiento |
| DNI | Documento Nacional de Identidad |
| CUIT | Clave Única de Identificación Tributaria |
| Inertia | Biblioteca que conecta Laravel con Vue.js sin API intermedia |
| Sanctum | Sistema de autenticación por tokens para API |

## 5. RESPONSABILIDADES

| Rol | Responsabilidad |
|-----|----------------|
| Productor | Mantener sus datos actualizados, completar todos los módulos del sistema |
| Staff (Admin) | Administrar usuarios staff, supervisar datos, exportar información |
| Staff (Auditor) | Consultar y verificar datos de productores, exportar reportes |
| Administrador del Sistema | Mantener la infraestructura, aplicar actualizaciones de seguridad |

## 6. REQUISITOS TÉCNICOS

### 6.1 Navegadores Soportados

| Navegador | Versión Mínima |
|-----------|----------------|
| Google Chrome | 90+ |
| Mozilla Firefox | 90+ |
| Microsoft Edge | 90+ |
| Safari (macOS) | 14+ |

### 6.2 Conectividad

- Conexión a Internet estable (mínimo 1 Mbps).
- Puertos requeridos: 80 (HTTP), 443 (HTTPS).
- Para la funcionalidad de mapas (Leaflet/OpenStreetMap), se requiere acceso a `*.tile.openstreetmap.org`.

### 6.3 Resolución de Pantalla Recomendada

- Escritorio: 1280×720 píxeles o superior.
- Móvil: 360×640 píxeles o superior (interfaz responsive).

---

## 7. CONTROL DE CAMBIOS

Este documento y todos los documentos asociados están sujetos a control de versiones. Cualquier modificación debe ser registrada en la tabla de control de documentos al inicio de este archivo.

Los cambios serán gestionados a través del repositorio Git del proyecto y requieren aprobación antes de su publicación.
