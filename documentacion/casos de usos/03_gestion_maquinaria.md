# Casos de Uso - Gestión de Maquinaria

## CU-11: Listar maquinaria

**Actor Principal**: Usuario autenticado  
**Objetivo**: Visualizar toda la maquinaria registrada  
**Precondición**: Usuario autenticado en el sistema  
**Postcondición**: Lista de maquinaria mostrada  

**Flujo Básico**:
1. El usuario accede al módulo "Maquinaria".
2. El sistema muestra una tabla con toda la maquinaria registrada.
3. La tabla muestra: ID, Nombre, Tipo, Propiedad asociada y opciones de acciones.

**Flujos Alternativos**:
- **Sin maquinaria**: Si no hay maquinaria registrada, el sistema muestra un mensaje indicando que no hay datos.

## CU-12: Crear nueva maquinaria

**Actor Principal**: Usuario autenticado  
**Objetivo**: Registrar una nueva maquinaria  
**Precondición**: Usuario autenticado en el sistema y al menos una propiedad registrada  
**Postcondición**: Nueva maquinaria registrada  

**Flujo Básico**:
1. El usuario accede al módulo "Maquinaria".
2. El usuario selecciona la opción "Nueva Máquina".
3. El sistema muestra el formulario de creación.
4. El usuario completa los campos requeridos (nombre, tipo).
5. El usuario selecciona la propiedad a la que pertenece la maquinaria.
6. El usuario envía el formulario.
7. El sistema valida los datos.
8. El sistema registra la nueva maquinaria y muestra un mensaje de éxito.
9. El sistema redirecciona a la lista de maquinaria.

**Flujos Alternativos**:
- **Error en validación**: Si algún campo no cumple con los requisitos, el sistema muestra mensajes de error.
- **Sin propiedades**: Si no hay propiedades registradas, el sistema muestra un mensaje indicando que debe crear una propiedad primero.

## CU-13: Editar maquinaria

**Actor Principal**: Usuario autenticado  
**Objetivo**: Modificar información de una maquinaria existente  
**Precondición**: Maquinaria existente en el sistema  
**Postcondición**: Información de maquinaria actualizada  

**Flujo Básico**:
1. El usuario accede al módulo "Maquinaria".
2. El usuario selecciona la opción "Editar" en la maquinaria deseada.
3. El sistema muestra el formulario con la información actual.
4. El usuario modifica los campos deseados.
5. El usuario puede cambiar la propiedad asociada.
6. El usuario envía el formulario.
7. El sistema valida los datos.
8. El sistema actualiza la información y muestra un mensaje de éxito.
9. El sistema redirecciona a la lista de maquinaria.

**Flujos Alternativos**:
- **Error en validación**: Si algún campo no cumple con los requisitos, el sistema muestra mensajes de error.

## CU-14: Eliminar maquinaria

**Actor Principal**: Usuario autenticado  
**Objetivo**: Eliminar una maquinaria existente  
**Precondición**: Maquinaria existente en el sistema  
**Postcondición**: Maquinaria eliminada del sistema  

**Flujo Básico**:
1. El usuario accede al módulo "Maquinaria".
2. El usuario selecciona la opción "Eliminar" en la maquinaria deseada.
3. El sistema muestra un mensaje de confirmación.
4. El usuario confirma la eliminación.
5. El sistema elimina la maquinaria y sus implementos asociados.
6. El sistema muestra un mensaje de éxito.
7. El sistema actualiza la lista de maquinaria.

**Flujos Alternativos**:
- **Cancelar eliminación**: Si el usuario cancela la confirmación, el sistema no realiza cambios.
- **Maquinaria con implementos**: Si la maquinaria tiene implementos asociados, el sistema puede mostrar una advertencia adicional.

## CU-15: Visualizar detalle de maquinaria

**Actor Principal**: Usuario autenticado  
**Objetivo**: Ver información detallada de una maquinaria  
**Precondición**: Maquinaria existente en el sistema  
**Postcondición**: Información detallada mostrada  

**Flujo Básico**:
1. El usuario accede al módulo "Maquinaria".
2. El usuario selecciona la opción "Ver detalle" o hace clic en el nombre de la maquinaria.
3. El sistema muestra toda la información de la maquinaria:
   - Datos básicos (nombre, tipo)
   - Propiedad a la que pertenece
   - Implementos asociados

**Flujos Alternativos**:
- **Sin implementos asociados**: Si la maquinaria no tiene implementos asociados, el sistema muestra una sección vacía con opción para agregar.
