# Casos de Uso - Gestión de Cultivos

## CU-21: Listar cultivos

**Actor Principal**: Usuario autenticado  
**Objetivo**: Visualizar todos los cultivos registrados  
**Precondición**: Usuario autenticado en el sistema  
**Postcondición**: Lista de cultivos mostrada  

**Flujo Básico**:
1. El usuario accede al módulo "Cultivos".
2. El sistema muestra una tabla con todos los cultivos registrados.
3. La tabla muestra: ID, Nombre, Tipo, Propiedad asociada y opciones de acciones.

**Flujos Alternativos**:
- **Sin cultivos**: Si no hay cultivos registrados, el sistema muestra un mensaje indicando que no hay datos.

## CU-22: Crear nuevo cultivo

**Actor Principal**: Usuario autenticado  
**Objetivo**: Registrar un nuevo cultivo  
**Precondición**: Usuario autenticado en el sistema y al menos una propiedad registrada  
**Postcondición**: Nuevo cultivo registrado  

**Flujo Básico**:
1. El usuario accede al módulo "Cultivos".
2. El usuario selecciona la opción "Nuevo Cultivo".
3. El sistema muestra el formulario de creación.
4. El usuario completa los campos requeridos (nombre, tipo).
5. El usuario selecciona la propiedad a la que pertenece el cultivo.
6. El usuario envía el formulario.
7. El sistema valida los datos.
8. El sistema registra el nuevo cultivo y muestra un mensaje de éxito.
9. El sistema redirecciona a la lista de cultivos.

**Flujos Alternativos**:
- **Error en validación**: Si algún campo no cumple con los requisitos, el sistema muestra mensajes de error.
- **Sin propiedades**: Si no hay propiedades registradas, el sistema muestra un mensaje indicando que debe crear una propiedad primero.

## CU-23: Editar cultivo

**Actor Principal**: Usuario autenticado  
**Objetivo**: Modificar información de un cultivo existente  
**Precondición**: Cultivo existente en el sistema  
**Postcondición**: Información de cultivo actualizada  

**Flujo Básico**:
1. El usuario accede al módulo "Cultivos".
2. El usuario selecciona la opción "Editar" en el cultivo deseado.
3. El sistema muestra el formulario con la información actual.
4. El usuario modifica los campos deseados.
5. El usuario puede cambiar la propiedad asociada.
6. El usuario envía el formulario.
7. El sistema valida los datos.
8. El sistema actualiza la información y muestra un mensaje de éxito.
9. El sistema redirecciona a la lista de cultivos.

**Flujos Alternativos**:
- **Error en validación**: Si algún campo no cumple con los requisitos, el sistema muestra mensajes de error.

## CU-24: Eliminar cultivo

**Actor Principal**: Usuario autenticado  
**Objetivo**: Eliminar un cultivo existente  
**Precondición**: Cultivo existente en el sistema  
**Postcondición**: Cultivo eliminado del sistema  

**Flujo Básico**:
1. El usuario accede al módulo "Cultivos".
2. El usuario selecciona la opción "Eliminar" en el cultivo deseado.
3. El sistema muestra un mensaje de confirmación.
4. El usuario confirma la eliminación.
5. El sistema elimina el cultivo.
6. El sistema muestra un mensaje de éxito.
7. El sistema actualiza la lista de cultivos.

**Flujos Alternativos**:
- **Cancelar eliminación**: Si el usuario cancela la confirmación, el sistema no realiza cambios.

## CU-25: Visualizar detalle de cultivo

**Actor Principal**: Usuario autenticado  
**Objetivo**: Ver información detallada de un cultivo  
**Precondición**: Cultivo existente en el sistema  
**Postcondición**: Información detallada mostrada  

**Flujo Básico**:
1. El usuario accede al módulo "Cultivos".
2. El usuario selecciona la opción "Ver detalle" o hace clic en el nombre del cultivo.
3. El sistema muestra toda la información del cultivo:
   - Datos básicos (nombre, tipo)
   - Propiedad a la que pertenece
   - Información estacional (si está configurada)
   - Área cultivada (si está configurada)

**Flujos Alternativos**:
- No aplica.
