# Casos de Uso - Gestión de Propiedades

## CU-06: Listar propiedades

**Actor Principal**: Usuario autenticado  
**Objetivo**: Visualizar todas las propiedades registradas  
**Precondición**: Usuario autenticado en el sistema  
**Postcondición**: Lista de propiedades mostrada  

**Flujo Básico**:
1. El usuario accede al módulo "Propiedades".
2. El sistema muestra una tabla con todas las propiedades registradas.
3. La tabla muestra: ID, Nombre, Ubicación y opciones de acciones.

**Flujos Alternativos**:
- **Sin propiedades**: Si no hay propiedades registradas, el sistema muestra un mensaje indicando que no hay datos.

## CU-07: Crear nueva propiedad

**Actor Principal**: Usuario autenticado  
**Objetivo**: Registrar una nueva propiedad  
**Precondición**: Usuario autenticado en el sistema  
**Postcondición**: Nueva propiedad registrada  

**Flujo Básico**:
1. El usuario accede al módulo "Propiedades".
2. El usuario selecciona la opción "Nueva Propiedad".
3. El sistema muestra el formulario de creación.
4. El usuario completa los campos requeridos (nombre, ubicación).
5. El usuario envía el formulario.
6. El sistema valida los datos.
7. El sistema registra la nueva propiedad y muestra un mensaje de éxito.
8. El sistema redirecciona a la lista de propiedades.

**Flujos Alternativos**:
- **Error en validación**: Si algún campo no cumple con los requisitos, el sistema muestra mensajes de error.

## CU-08: Editar propiedad

**Actor Principal**: Usuario autenticado  
**Objetivo**: Modificar información de una propiedad existente  
**Precondición**: Propiedad existente en el sistema  
**Postcondición**: Información de propiedad actualizada  

**Flujo Básico**:
1. El usuario accede al módulo "Propiedades".
2. El usuario selecciona la opción "Editar" en la propiedad deseada.
3. El sistema muestra el formulario con la información actual.
4. El usuario modifica los campos deseados.
5. El usuario envía el formulario.
6. El sistema valida los datos.
7. El sistema actualiza la información y muestra un mensaje de éxito.
8. El sistema redirecciona a la lista de propiedades.

**Flujos Alternativos**:
- **Error en validación**: Si algún campo no cumple con los requisitos, el sistema muestra mensajes de error.

## CU-09: Eliminar propiedad

**Actor Principal**: Usuario autenticado  
**Objetivo**: Eliminar una propiedad existente  
**Precondición**: Propiedad existente en el sistema  
**Postcondición**: Propiedad eliminada del sistema  

**Flujo Básico**:
1. El usuario accede al módulo "Propiedades".
2. El usuario selecciona la opción "Eliminar" en la propiedad deseada.
3. El sistema muestra un mensaje de confirmación.
4. El usuario confirma la eliminación.
5. El sistema elimina la propiedad y sus relaciones asociadas.
6. El sistema muestra un mensaje de éxito.
7. El sistema actualiza la lista de propiedades.

**Flujos Alternativos**:
- **Cancelar eliminación**: Si el usuario cancela la confirmación, el sistema no realiza cambios.
- **Propiedad con dependencias**: Si la propiedad tiene elementos asociados (cultivos, maquinarias, etc.), el sistema puede mostrar una advertencia adicional.

## CU-10: Visualizar detalle de propiedad

**Actor Principal**: Usuario autenticado  
**Objetivo**: Ver información detallada de una propiedad  
**Precondición**: Propiedad existente en el sistema  
**Postcondición**: Información detallada mostrada  

**Flujo Básico**:
1. El usuario accede al módulo "Propiedades".
2. El usuario selecciona la opción "Ver detalle" o hace clic en el nombre de la propiedad.
3. El sistema muestra toda la información de la propiedad:
   - Datos básicos (nombre, ubicación)
   - Cultivos asociados
   - Maquinarias asociadas
   - Tecnologías de riego asociadas
   - Archivos asociados

**Flujos Alternativos**:
- **Sin elementos asociados**: Si la propiedad no tiene elementos asociados, el sistema muestra secciones vacías con opciones para agregar.
