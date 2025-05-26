# Casos de Uso - Gestión de Implementos

## CU-16: Listar implementos

**Actor Principal**: Usuario autenticado  
**Objetivo**: Visualizar todos los implementos registrados  
**Precondición**: Usuario autenticado en el sistema  
**Postcondición**: Lista de implementos mostrada  

**Flujo Básico**:
1. El usuario accede al módulo "Implementos".
2. El sistema muestra una tabla con todos los implementos registrados.
3. La tabla muestra: ID, Nombre, Tipo, Maquinaria asociada y opciones de acciones.

**Flujos Alternativos**:
- **Sin implementos**: Si no hay implementos registrados, el sistema muestra un mensaje indicando que no hay datos.

## CU-17: Crear nuevo implemento

**Actor Principal**: Usuario autenticado  
**Objetivo**: Registrar un nuevo implemento  
**Precondición**: Usuario autenticado en el sistema y al menos una maquinaria registrada  
**Postcondición**: Nuevo implemento registrado  

**Flujo Básico**:
1. El usuario accede al módulo "Implementos".
2. El usuario selecciona la opción "Nuevo Implemento".
3. El sistema muestra el formulario de creación.
4. El usuario completa los campos requeridos (nombre, tipo).
5. El usuario selecciona la maquinaria a la que pertenece el implemento.
6. El usuario envía el formulario.
7. El sistema valida los datos.
8. El sistema registra el nuevo implemento y muestra un mensaje de éxito.
9. El sistema redirecciona a la lista de implementos.

**Flujos Alternativos**:
- **Error en validación**: Si algún campo no cumple con los requisitos, el sistema muestra mensajes de error.
- **Sin maquinarias**: Si no hay maquinarias registradas, el sistema muestra un mensaje indicando que debe crear una maquinaria primero.

## CU-18: Editar implemento

**Actor Principal**: Usuario autenticado  
**Objetivo**: Modificar información de un implemento existente  
**Precondición**: Implemento existente en el sistema  
**Postcondición**: Información de implemento actualizada  

**Flujo Básico**:
1. El usuario accede al módulo "Implementos".
2. El usuario selecciona la opción "Editar" en el implemento deseado.
3. El sistema muestra el formulario con la información actual.
4. El usuario modifica los campos deseados.
5. El usuario puede cambiar la maquinaria asociada.
6. El usuario envía el formulario.
7. El sistema valida los datos.
8. El sistema actualiza la información y muestra un mensaje de éxito.
9. El sistema redirecciona a la lista de implementos.

**Flujos Alternativos**:
- **Error en validación**: Si algún campo no cumple con los requisitos, el sistema muestra mensajes de error.

## CU-19: Eliminar implemento

**Actor Principal**: Usuario autenticado  
**Objetivo**: Eliminar un implemento existente  
**Precondición**: Implemento existente en el sistema  
**Postcondición**: Implemento eliminado del sistema  

**Flujo Básico**:
1. El usuario accede al módulo "Implementos".
2. El usuario selecciona la opción "Eliminar" en el implemento deseado.
3. El sistema muestra un mensaje de confirmación.
4. El usuario confirma la eliminación.
5. El sistema elimina el implemento.
6. El sistema muestra un mensaje de éxito.
7. El sistema actualiza la lista de implementos.

**Flujos Alternativos**:
- **Cancelar eliminación**: Si el usuario cancela la confirmación, el sistema no realiza cambios.

## CU-20: Visualizar detalle de implemento

**Actor Principal**: Usuario autenticado  
**Objetivo**: Ver información detallada de un implemento  
**Precondición**: Implemento existente en el sistema  
**Postcondición**: Información detallada mostrada  

**Flujo Básico**:
1. El usuario accede al módulo "Implementos".
2. El usuario selecciona la opción "Ver detalle" o hace clic en el nombre del implemento.
3. El sistema muestra toda la información del implemento:
   - Datos básicos (nombre, tipo)
   - Maquinaria a la que pertenece
   - Propiedad indirectamente asociada (a través de la maquinaria)

**Flujos Alternativos**:
- No aplica.
