# Casos de Uso - Gestión de Archivos

## CU-31: Listar archivos

**Actor Principal**: Usuario autenticado  
**Objetivo**: Visualizar todos los archivos registrados  
**Precondición**: Usuario autenticado en el sistema  
**Postcondición**: Lista de archivos mostrada  

**Flujo Básico**:
1. El usuario accede al módulo "Archivos".
2. El sistema muestra una tabla con todos los archivos registrados.
3. La tabla muestra: ID, Nombre, Tipo, Tamaño, Fecha de carga, Entidad asociada y opciones de acciones.

**Flujos Alternativos**:
- **Sin archivos**: Si no hay archivos registrados, el sistema muestra un mensaje indicando que no hay datos.
- **Filtrar por entidad**: El usuario puede filtrar los archivos por tipo de entidad asociada (propiedad, maquinaria, etc.).

## CU-32: Cargar nuevo archivo

**Actor Principal**: Usuario autenticado  
**Objetivo**: Cargar un nuevo archivo al sistema  
**Precondición**: Usuario autenticado en el sistema  
**Postcondición**: Nuevo archivo cargado y registrado  

**Flujo Básico**:
1. El usuario accede al módulo "Archivos".
2. El usuario selecciona la opción "Cargar Archivo".
3. El sistema muestra el formulario de carga.
4. El usuario selecciona el archivo a cargar.
5. El usuario ingresa un nombre o descripción para el archivo.
6. El usuario selecciona opcionalmente una entidad a la que asociar el archivo (propiedad, maquinaria, etc.).
7. El usuario envía el formulario.
8. El sistema valida el archivo (tipo, tamaño).
9. El sistema almacena el archivo y registra sus metadatos.
10. El sistema muestra un mensaje de éxito.
11. El sistema redirecciona a la lista de archivos.

**Flujos Alternativos**:
- **Error en validación**: Si el archivo no cumple con los requisitos (tipo o tamaño no permitido), el sistema muestra un mensaje de error.
- **Error en carga**: Si ocurre un error durante la carga, el sistema muestra un mensaje de error y permite reintentar.

## CU-33: Descargar archivo

**Actor Principal**: Usuario autenticado  
**Objetivo**: Descargar un archivo existente  
**Precondición**: Archivo existente en el sistema  
**Postcondición**: Archivo descargado al dispositivo del usuario  

**Flujo Básico**:
1. El usuario accede al módulo "Archivos".
2. El usuario localiza el archivo deseado.
3. El usuario selecciona la opción "Descargar".
4. El sistema inicia la descarga del archivo.

**Flujos Alternativos**:
- **Error en descarga**: Si ocurre un error durante la descarga, el sistema muestra un mensaje de error.
- **Archivo no encontrado**: Si el archivo físico no se encuentra en el almacenamiento, el sistema muestra un mensaje de error.

## CU-34: Eliminar archivo

**Actor Principal**: Usuario autenticado  
**Objetivo**: Eliminar un archivo existente  
**Precondición**: Archivo existente en el sistema  
**Postcondición**: Archivo eliminado del sistema  

**Flujo Básico**:
1. El usuario accede al módulo "Archivos".
2. El usuario localiza el archivo deseado.
3. El usuario selecciona la opción "Eliminar".
4. El sistema muestra un mensaje de confirmación.
5. El usuario confirma la eliminación.
6. El sistema elimina el archivo físico y su registro.
7. El sistema muestra un mensaje de éxito.
8. El sistema actualiza la lista de archivos.

**Flujos Alternativos**:
- **Cancelar eliminación**: Si el usuario cancela la confirmación, el sistema no realiza cambios.
- **Error en eliminación**: Si ocurre un error durante la eliminación del archivo físico, el sistema muestra un mensaje de error.

## CU-35: Asociar archivo a entidad

**Actor Principal**: Usuario autenticado  
**Objetivo**: Asociar un archivo existente a una entidad del sistema  
**Precondición**: Archivo existente en el sistema  
**Postcondición**: Archivo asociado a la entidad seleccionada  

**Flujo Básico**:
1. El usuario accede al módulo "Archivos".
2. El usuario localiza el archivo deseado.
3. El usuario selecciona la opción "Asociar a entidad".
4. El sistema muestra un formulario con las posibles entidades para asociar.
5. El usuario selecciona el tipo de entidad (propiedad, maquinaria, etc.).
6. El sistema muestra las entidades disponibles del tipo seleccionado.
7. El usuario selecciona la entidad específica.
8. El usuario envía el formulario.
9. El sistema establece la asociación.
10. El sistema muestra un mensaje de éxito.

**Flujos Alternativos**:
- **Error en asociación**: Si ocurre un error al establecer la asociación, el sistema muestra un mensaje de error.
- **Sin entidades disponibles**: Si no hay entidades del tipo seleccionado, el sistema muestra un mensaje indicando que debe crear entidades primero.

## CU-36: Visualizar archivos de una entidad

**Actor Principal**: Usuario autenticado  
**Objetivo**: Ver todos los archivos asociados a una entidad específica  
**Precondición**: Entidad existente en el sistema  
**Postcondición**: Lista de archivos asociados mostrada  

**Flujo Básico**:
1. El usuario accede al detalle de una entidad (propiedad, maquinaria, etc.).
2. El sistema muestra una sección "Archivos" dentro del detalle de la entidad.
3. El sistema lista todos los archivos asociados a esa entidad.
4. El usuario puede descargar o gestionar los archivos desde esta vista.

**Flujos Alternativos**:
- **Sin archivos asociados**: Si la entidad no tiene archivos asociados, el sistema muestra un mensaje indicándolo y ofrece la opción de cargar nuevos archivos.
