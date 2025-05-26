# Casos de Uso - Gestión de Tecnologías de Riego

## CU-26: Listar tecnologías de riego

**Actor Principal**: Usuario autenticado  
**Objetivo**: Visualizar todas las tecnologías de riego registradas  
**Precondición**: Usuario autenticado en el sistema  
**Postcondición**: Lista de tecnologías de riego mostrada  

**Flujo Básico**:
1. El usuario accede al módulo "Tecnología Riego".
2. El sistema muestra una tabla con todas las tecnologías de riego registradas.
3. La tabla muestra: ID, Nombre, Tipo, Propiedad asociada y opciones de acciones.

**Flujos Alternativos**:
- **Sin tecnologías de riego**: Si no hay tecnologías registradas, el sistema muestra un mensaje indicando que no hay datos.

## CU-27: Crear nueva tecnología de riego

**Actor Principal**: Usuario autenticado  
**Objetivo**: Registrar una nueva tecnología de riego  
**Precondición**: Usuario autenticado en el sistema y al menos una propiedad registrada  
**Postcondición**: Nueva tecnología de riego registrada  

**Flujo Básico**:
1. El usuario accede al módulo "Tecnología Riego".
2. El usuario selecciona la opción "Nueva Tecnología".
3. El sistema muestra el formulario de creación.
4. El usuario completa los campos requeridos (nombre, tipo).
5. El usuario selecciona la propiedad a la que pertenece la tecnología.
6. El usuario envía el formulario.
7. El sistema valida los datos.
8. El sistema registra la nueva tecnología de riego y muestra un mensaje de éxito.
9. El sistema redirecciona a la lista de tecnologías de riego.

**Flujos Alternativos**:
- **Error en validación**: Si algún campo no cumple con los requisitos, el sistema muestra mensajes de error.
- **Sin propiedades**: Si no hay propiedades registradas, el sistema muestra un mensaje indicando que debe crear una propiedad primero.

## CU-28: Editar tecnología de riego

**Actor Principal**: Usuario autenticado  
**Objetivo**: Modificar información de una tecnología de riego existente  
**Precondición**: Tecnología de riego existente en el sistema  
**Postcondición**: Información de tecnología de riego actualizada  

**Flujo Básico**:
1. El usuario accede al módulo "Tecnología Riego".
2. El usuario selecciona la opción "Editar" en la tecnología deseada.
3. El sistema muestra el formulario con la información actual.
4. El usuario modifica los campos deseados.
5. El usuario puede cambiar la propiedad asociada.
6. El usuario envía el formulario.
7. El sistema valida los datos.
8. El sistema actualiza la información y muestra un mensaje de éxito.
9. El sistema redirecciona a la lista de tecnologías de riego.

**Flujos Alternativos**:
- **Error en validación**: Si algún campo no cumple con los requisitos, el sistema muestra mensajes de error.

## CU-29: Eliminar tecnología de riego

**Actor Principal**: Usuario autenticado  
**Objetivo**: Eliminar una tecnología de riego existente  
**Precondición**: Tecnología de riego existente en el sistema  
**Postcondición**: Tecnología de riego eliminada del sistema  

**Flujo Básico**:
1. El usuario accede al módulo "Tecnología Riego".
2. El usuario selecciona la opción "Eliminar" en la tecnología deseada.
3. El sistema muestra un mensaje de confirmación.
4. El usuario confirma la eliminación.
5. El sistema elimina la tecnología de riego.
6. El sistema muestra un mensaje de éxito.
7. El sistema actualiza la lista de tecnologías de riego.

**Flujos Alternativos**:
- **Cancelar eliminación**: Si el usuario cancela la confirmación, el sistema no realiza cambios.

## CU-30: Visualizar detalle de tecnología de riego

**Actor Principal**: Usuario autenticado  
**Objetivo**: Ver información detallada de una tecnología de riego  
**Precondición**: Tecnología de riego existente en el sistema  
**Postcondición**: Información detallada mostrada  

**Flujo Básico**:
1. El usuario accede al módulo "Tecnología Riego".
2. El usuario selecciona la opción "Ver detalle" o hace clic en el nombre de la tecnología.
3. El sistema muestra toda la información de la tecnología de riego:
   - Datos básicos (nombre, tipo)
   - Propiedad a la que pertenece
   - Información adicional específica del tipo de tecnología

**Flujos Alternativos**:
- No aplica.
