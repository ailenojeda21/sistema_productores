# Manual de Usuario — RUPAL

**Sistema**: Registro Único de Productores Agropecuarios de Lavalle  
**Versión**: 1.0  
**ISO 9001:2015**: Cláusula 7.5 — Información Documentada

---

## ÍNDICE DE DOCUMENTOS

| Código | Documento | Descripción |
|--------|-----------|-------------|
| RUPAL-MU-01 | [Introducción](01-introduccion.md) | Propósito del sistema, estructura, navegación, requisitos técnicos |
| RUPAL-MU-02 | [Acceso y Cuenta](02-acceso-y-cuenta.md) | Registro, inicio de sesión, verificación de email, recuperación de contraseña |
| RUPAL-MU-03 | [Panel de Control](03-panel-de-control.md) | Dashboard del productor, indicadores de completitud |
| RUPAL-MU-04 | [Perfil](04-perfil.md) | Edición de datos personales, avatar, exportación, eliminación de cuenta |
| RUPAL-MU-05 | [Propiedades](05-propiedades.md) | Gestión de propiedades: CRUD, RUT, geolocalización, mapa interactivo |
| RUPAL-MU-06 | [Cultivos](06-cultivos.md) | Gestión de cultivos: tipos, variedades, validación de hectáreas |
| RUPAL-MU-07 | [Maquinaria](07-maquinaria.md) | Gestión de maquinaria e implementos agrícolas |
| RUPAL-MU-08 | [Comercialización](08-comercializacion.md) | Gestión de comercios, mercados y cooperativas |

---

## MAPA DE PROCESOS

```
┌─────────────────────────────────────────────────────────────┐
│                     ACCESO AL SISTEMA                       │
│  Registro → Verificación Email → Inicio de Sesión          │
└──────────────────────────┬──────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│                    PANEL DE CONTROL                         │
│              Visualización de progreso                      │
└──────┬──────────┬──────────┬──────────┬─────────────────────┘
       │          │          │          │
       ▼          ▼          ▼          ▼
┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────────┐
│  PERFIL  │ │ PROPIED. │ │ CULTIVOS │ │ MAQUINARIA   │
│  Datos   │ │ Crear    │ │ Crear    │ │ Crear        │
│  Avatar  │ │ Editar   │ │ Editar   │ │ Editar       │
│  Export  │ │ Ver      │ │ Eliminar │ │ Eliminar     │
│  Elim.   │ │ Eliminar │ │          │ │              │
└──────────┘ └──────────┘ └──────────┘ └──────┬───────┘
                                              │
                                              ▼
                                      ┌──────────────┐
                                      │ COMERCIALIZ. │
                                      │ Crear        │
                                      │ Editar       │
                                      │ Eliminar     │
                                      └──────────────┘
```

---

## GUÍA RÁPIDA

| Si necesita... | Vaya a... |
|----------------|-----------|
| Crear una cuenta | [Acceso y Cuenta → Registro](02-acceso-y-cuenta.md#3-procedimiento-registro-de-nueva-cuenta) |
| Iniciar sesión | [Acceso y Cuenta → Inicio de Sesión](02-acceso-y-cuenta.md#5-procedimiento-inicio-de-sesión) |
| Recuperar mi contraseña | [Acceso y Cuenta → Recuperación](02-acceso-y-cuenta.md#6-procedimiento-recuperación-de-contraseña) |
| Ver mi progreso | [Panel de Control](03-panel-de-control.md) |
| Actualizar mis datos | [Perfil → Editar](04-perfil.md#4-procedimiento-editar-datos-personales) |
| Registrar una propiedad | [Propiedades → Crear](05-propiedades.md#5-procedimiento-crear-nueva-propiedad) |
| Registrar un cultivo | [Cultivos → Crear](06-cultivos.md#5-procedimiento-crear-nuevo-cultivo) |
| Registrar maquinaria | [Maquinaria → Crear](07-maquinaria.md#5-procedimiento-crear-nueva-maquinaria) |
| Registrar un comercio | [Comercialización → Crear](08-comercializacion.md#5-procedimiento-crear-nuevo-comercio) |
| Exportar mis datos | [Perfil → Exportar](04-perfil.md#6-procedimiento-exportar-datos-personales) |
| Eliminar mi cuenta | [Perfil → Eliminar](04-perfil.md#7-procedimiento-eliminar-cuenta) |
