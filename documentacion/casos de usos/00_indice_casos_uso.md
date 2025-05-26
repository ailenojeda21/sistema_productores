# Índice de Casos de Uso - Sistema Agrícola SAP

Este documento proporciona un índice completo de todos los casos de uso del Sistema Agrícola SAP, organizados por módulo.

## 1. Gestión de Usuarios y Perfiles

- [CU-01: Registro de nuevo usuario](01_gestion_usuarios.md#cu-01-registro-de-nuevo-usuario)
- [CU-02: Iniciar sesión](01_gestion_usuarios.md#cu-02-iniciar-sesión)
- [CU-03: Cerrar sesión](01_gestion_usuarios.md#cu-03-cerrar-sesión)
- [CU-04: Editar perfil de usuario](01_gestion_usuarios.md#cu-04-editar-perfil-de-usuario)
- [CU-05: Cambiar contraseña](01_gestion_usuarios.md#cu-05-cambiar-contraseña)

## 2. Gestión de Propiedades

- [CU-06: Listar propiedades](02_gestion_propiedades.md#cu-06-listar-propiedades)
- [CU-07: Crear nueva propiedad](02_gestion_propiedades.md#cu-07-crear-nueva-propiedad)
- [CU-08: Editar propiedad](02_gestion_propiedades.md#cu-08-editar-propiedad)
- [CU-09: Eliminar propiedad](02_gestion_propiedades.md#cu-09-eliminar-propiedad)
- [CU-10: Visualizar detalle de propiedad](02_gestion_propiedades.md#cu-10-visualizar-detalle-de-propiedad)

## 3. Gestión de Maquinaria

- [CU-11: Listar maquinaria](03_gestion_maquinaria.md#cu-11-listar-maquinaria)
- [CU-12: Crear nueva maquinaria](03_gestion_maquinaria.md#cu-12-crear-nueva-maquinaria)
- [CU-13: Editar maquinaria](03_gestion_maquinaria.md#cu-13-editar-maquinaria)
- [CU-14: Eliminar maquinaria](03_gestion_maquinaria.md#cu-14-eliminar-maquinaria)
- [CU-15: Visualizar detalle de maquinaria](03_gestion_maquinaria.md#cu-15-visualizar-detalle-de-maquinaria)

## 4. Gestión de Implementos

- [CU-16: Listar implementos](04_gestion_implementos.md#cu-16-listar-implementos)
- [CU-17: Crear nuevo implemento](04_gestion_implementos.md#cu-17-crear-nuevo-implemento)
- [CU-18: Editar implemento](04_gestion_implementos.md#cu-18-editar-implemento)
- [CU-19: Eliminar implemento](04_gestion_implementos.md#cu-19-eliminar-implemento)
- [CU-20: Visualizar detalle de implemento](04_gestion_implementos.md#cu-20-visualizar-detalle-de-implemento)

## 5. Gestión de Cultivos

- [CU-21: Listar cultivos](05_gestion_cultivos.md#cu-21-listar-cultivos)
- [CU-22: Crear nuevo cultivo](05_gestion_cultivos.md#cu-22-crear-nuevo-cultivo)
- [CU-23: Editar cultivo](05_gestion_cultivos.md#cu-23-editar-cultivo)
- [CU-24: Eliminar cultivo](05_gestion_cultivos.md#cu-24-eliminar-cultivo)
- [CU-25: Visualizar detalle de cultivo](05_gestion_cultivos.md#cu-25-visualizar-detalle-de-cultivo)

## 6. Gestión de Tecnologías de Riego

- [CU-26: Listar tecnologías de riego](06_gestion_tecnologia_riego.md#cu-26-listar-tecnologías-de-riego)
- [CU-27: Crear nueva tecnología de riego](06_gestion_tecnologia_riego.md#cu-27-crear-nueva-tecnología-de-riego)
- [CU-28: Editar tecnología de riego](06_gestion_tecnologia_riego.md#cu-28-editar-tecnología-de-riego)
- [CU-29: Eliminar tecnología de riego](06_gestion_tecnologia_riego.md#cu-29-eliminar-tecnología-de-riego)
- [CU-30: Visualizar detalle de tecnología de riego](06_gestion_tecnologia_riego.md#cu-30-visualizar-detalle-de-tecnología-de-riego)

## 7. Gestión de Archivos

- [CU-31: Listar archivos](07_gestion_archivos.md#cu-31-listar-archivos)
- [CU-32: Cargar nuevo archivo](07_gestion_archivos.md#cu-32-cargar-nuevo-archivo)
- [CU-33: Descargar archivo](07_gestion_archivos.md#cu-33-descargar-archivo)
- [CU-34: Eliminar archivo](07_gestion_archivos.md#cu-34-eliminar-archivo)
- [CU-35: Asociar archivo a entidad](07_gestion_archivos.md#cu-35-asociar-archivo-a-entidad)
- [CU-36: Visualizar archivos de una entidad](07_gestion_archivos.md#cu-36-visualizar-archivos-de-una-entidad)

## 8. Interfaz MDI y Navegación

- [CU-37: Acceder al dashboard](08_interfaz_mdi_navegacion.md#cu-37-acceder-al-dashboard)
- [CU-38: Utilizar navegación principal](08_interfaz_mdi_navegacion.md#cu-38-utilizar-navegación-principal)
- [CU-39: Abrir ventana MDI](08_interfaz_mdi_navegacion.md#cu-39-abrir-ventana-mdi)
- [CU-40: Cerrar ventana MDI](08_interfaz_mdi_navegacion.md#cu-40-cerrar-ventana-mdi)
- [CU-41: Utilizar acceso rápido](08_interfaz_mdi_navegacion.md#cu-41-utilizar-acceso-rápido)

## Diagrama de Relaciones de Entidades

A continuación, se presenta la estructura principal de relaciones entre las entidades del sistema:

```
Usuario
  ↓ 1:N
Propiedad
  ↓ 1:N
  ├─→ Maquinaria
  │     ↓ 1:N
  │     └─→ Implemento
  ├─→ Cultivo
  ├─→ TecnologiaRiego
  └─→ Archivo
```

### Relaciones principales:

- Un usuario puede tener múltiples propiedades
- Una propiedad puede tener múltiples maquinarias, cultivos y tecnologías de riego
- Una maquinaria puede tener múltiples implementos
- Todas las entidades pueden tener archivos asociados
