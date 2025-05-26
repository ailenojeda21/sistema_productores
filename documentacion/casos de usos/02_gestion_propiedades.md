# Casos de Uso - Gestión de Propiedades

## CU-06: Listar propiedades

**Actor Principal**: Usuario autenticado (Productor o Administrador)  
**Objetivo**: Visualizar todas las propiedades registradas  
**Precondición**: Usuario autenticado en el sistema  
**Postcondición**: Lista de propiedades mostrada  

**Flujo Básico**:
1. El usuario accede al módulo "Propiedades" desde el menú lateral.
2. El sistema muestra una tabla con todas las propiedades registradas con diseño SAP Fiori.
3. La tabla muestra: ID, Ubicación, Superficie (ha), Malla Antigranizo (Sí/No), Propietario (Sí/No), Derecho de Riego (Sí/No) y opciones de acciones.
4. El usuario puede ordenar y filtrar los resultados usando los controles de la tabla.

**Flujos Alternativos**:
- **Sin propiedades**: Si no hay propiedades registradas, el sistema muestra un estado vacío con ilustración y mensaje "No hay propiedades registradas".
- **Filtrado de datos**: El usuario puede utilizar la barra de búsqueda para filtrar por ubicación u otros criterios.
- **Rol administrador**: Si el usuario es administrador, puede ver propiedades de todos los usuarios, mientras que los productores solo ven sus propias propiedades.

## CU-07: Crear nueva propiedad

**Actor Principal**: Usuario autenticado (Productor o Administrador)  
**Objetivo**: Registrar una nueva propiedad agrícola  
**Precondición**: Usuario autenticado en el sistema  
**Postcondición**: Nueva propiedad registrada en el sistema  

**Flujo Básico**:
1. El usuario accede al módulo "Propiedades" desde el menú lateral.
2. El usuario selecciona la opción "Nueva Propiedad" (botón con icono de + en la esquina superior derecha).
3. El sistema muestra un formulario de creación en una ventana modal o pestaña nueva según el diseño MDI.
4. El usuario completa los campos requeridos:
   - Ubicación (texto obligatorio)
   - Superficie en hectáreas (número decimal positivo obligatorio)
   - Malla antigranizo (toggle switch - opcional)
   - Es propietario (toggle switch - opcional)
   - Derecho de riego (toggle switch - opcional)
5. El usuario envía el formulario mediante el botón "Guardar".
6. El sistema valida los datos en tiempo real, mostrando indicadores visuales de validación.
7. El sistema registra la nueva propiedad en la base de datos.
8. El sistema muestra una notificación de éxito con animación tipo toast.
9. El sistema actualiza la tabla de propiedades para incluir la nueva entrada.

**Flujos Alternativos**:
- **Error de validación**: Si algún campo no cumple con los requisitos, el sistema muestra mensajes de error específicos bajo cada campo, resaltando los campos problemáticos con borde rojo.
- **Cancelar operación**: El usuario puede cancelar la creación en cualquier momento haciendo clic en "Cancelar", cerrando la ventana modal, o cambiando de pestaña. El sistema pide confirmación si hay datos no guardados.

## CU-08: Editar propiedad

**Actor Principal**: Usuario autenticado (Productor o Administrador)  
**Objetivo**: Modificar información de una propiedad existente  
**Precondición**: Propiedad existente en el sistema  
**Postcondición**: Información de propiedad actualizada  

**Flujo Básico**:
1. El usuario accede al módulo "Propiedades" desde el menú lateral.
2. El usuario identifica la propiedad a editar y selecciona el icono de edición (lápiz) en la columna de acciones.
3. El sistema muestra un formulario con la información actual de la propiedad en una ventana modal o pestaña según el diseño MDI.
4. El usuario modifica los campos deseados:
   - Ubicación (texto obligatorio)
   - Superficie en hectáreas (número decimal positivo obligatorio)
   - Malla antigranizo (toggle switch)
   - Es propietario (toggle switch)
   - Derecho de riego (toggle switch)
5. El usuario hace clic en el botón "Guardar Cambios".
6. El sistema valida los datos modificados en tiempo real.
7. El sistema actualiza la información en la base de datos.
8. El sistema muestra una notificación de éxito con animación tipo toast.
9. El sistema actualiza la tabla de propiedades para reflejar los cambios.

**Flujos Alternativos**:
- **Error de validación**: Si algún campo no cumple con los requisitos, el sistema muestra mensajes de error específicos bajo cada campo con el estilo Fiori.
- **Cancelar edición**: El usuario puede cancelar la edición haciendo clic en "Cancelar" o cerrando la ventana. Si hay cambios no guardados, el sistema solicita confirmación.
- **Error de permisos**: Si el usuario intenta editar una propiedad que no le pertenece (excepto administradores), el sistema muestra un mensaje de error de permisos.

## CU-09: Eliminar propiedad

**Actor Principal**: Usuario autenticado (Productor o Administrador)  
**Objetivo**: Eliminar una propiedad del sistema  
**Precondición**: Propiedad existente en el sistema  
**Postcondición**: Propiedad eliminada del sistema  

**Flujo Básico**:
1. El usuario accede al módulo "Propiedades" desde el menú lateral.
2. El usuario identifica la propiedad a eliminar y selecciona el icono de eliminación (papelera) en la columna de acciones.
3. El sistema muestra un diálogo de confirmación con estilo Fiori:
   - Título: "Confirmar eliminación"
   - Mensaje: "¿Está seguro que desea eliminar la propiedad ubicada en [ubicación]? Esta acción no se puede deshacer."
   - Botones: "Cancelar" (botón secundario) y "Eliminar" (botón de peligro)
4. El usuario confirma la eliminación haciendo clic en "Eliminar".
5. El sistema elimina la propiedad y todas sus entidades relacionadas (maquinarias, cultivos, etc.) de la base de datos.
6. El sistema muestra una notificación de éxito con animación tipo toast.
7. El sistema actualiza la tabla de propiedades, eliminando la fila correspondiente.

**Flujos Alternativos**:
- **Cancelar eliminación**: El usuario puede cancelar la eliminación haciendo clic en "Cancelar" o cerrando el diálogo.
- **Error de permisos**: Si el usuario intenta eliminar una propiedad que no le pertenece (excepto administradores), el sistema muestra un mensaje de error de permisos.
- **Error de dependencias**: Si la eliminación falla debido a restricciones de integridad referencial, el sistema muestra un mensaje explicativo.

## CU-10: Visualizar detalle de propiedad

**Actor Principal**: Usuario autenticado (Productor o Administrador)  
**Objetivo**: Ver información detallada de una propiedad y sus entidades relacionadas  
**Precondición**: Propiedad existente en el sistema  
**Postcondición**: Información detallada de la propiedad mostrada  

**Flujo Básico**:
1. El usuario accede al módulo "Propiedades" desde el menú lateral.
2. El usuario identifica la propiedad de interés y selecciona el icono de detalles (ojo) en la columna de acciones.
3. El sistema abre una nueva pestaña o panel según el diseño MDI con la vista detallada de la propiedad.
4. La vista de detalle incluye:
   - Cabecera con la ubicación y características principales de la propiedad
   - Pestañas o secciones para cada tipo de entidad relacionada:
     - Maquinarias
     - Cultivos
     - Tecnologías de riego
     - Archivos/Documentos
   - Gráficos y estadísticas relevantes (uso de superficie, distribución de cultivos)
   - Mapa de ubicación si está disponible
5. El usuario puede navegar entre las diferentes secciones para ver toda la información relacionada.
6. El usuario puede realizar acciones contextuales como:
   - Agregar nuevas entidades relacionadas
   - Editar la información de la propiedad
   - Generar reportes

**Flujos Alternativos**:
- **Sin datos relacionados**: Si una sección no tiene datos (por ejemplo, no hay cultivos), el sistema muestra un estado vacío con ilustración y opción para agregar.
- **Error de permisos**: Si el usuario intenta ver detalles de una propiedad que no le pertenece (excepto administradores), el sistema muestra un mensaje de error de permisos.
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
