# Casos de Uso - Interfaz MDI y Navegación

## CU-37: Acceder al dashboard

**Actor Principal**: Usuario autenticado  
**Objetivo**: Visualizar el panel de control principal  
**Precondición**: Usuario autenticado en el sistema  
**Postcondición**: Dashboard mostrado con acceso a todos los módulos  

**Flujo Básico**:
1. El usuario inicia sesión en el sistema.
2. El sistema muestra el dashboard principal.
3. El dashboard presenta:
   - Resumen de elementos registrados por módulo
   - Accesos directos a los módulos principales
   - Sección de "Acceso Rápido" para funciones frecuentes

**Flujos Alternativos**:
- **Sesión expirada**: Si la sesión ha expirado, el sistema redirige a la página de inicio de sesión.

## CU-38: Utilizar navegación principal

**Actor Principal**: Usuario autenticado  
**Objetivo**: Navegar entre los diferentes módulos del sistema  
**Precondición**: Usuario autenticado en el sistema  
**Postcondición**: Usuario accede al módulo seleccionado  

**Flujo Básico**:
1. El usuario visualiza el menú de navegación principal en la parte superior.
2. El usuario selecciona el módulo deseado.
3. El sistema muestra la página principal del módulo seleccionado.

**Flujos Alternativos**:
- **Navegación en dispositivo móvil**: En dispositivos móviles, el usuario debe primero desplegar el menú antes de seleccionar un módulo.

## CU-39: Abrir ventana MDI

**Actor Principal**: Usuario autenticado  
**Objetivo**: Abrir un formulario o vista en formato ventana dentro del sistema  
**Precondición**: Usuario autenticado en el sistema  
**Postcondición**: Ventana MDI abierta con el contenido solicitado  

**Flujo Básico**:
1. El usuario selecciona una acción que requiere abrir un formulario (ej. "Nueva Propiedad").
2. El sistema abre una ventana modal con el formulario solicitado.
3. La ventana incluye:
   - Título descriptivo
   - Botón de cierre
   - Contenido específico (formulario, detalle, etc.)
   - Botones de acción relevantes

**Flujos Alternativos**:
- **Abrir múltiples ventanas**: El usuario puede tener múltiples ventanas MDI abiertas simultáneamente.

## CU-40: Cerrar ventana MDI

**Actor Principal**: Usuario autenticado  
**Objetivo**: Cerrar una ventana MDI abierta  
**Precondición**: Ventana MDI abierta  
**Postcondición**: Ventana MDI cerrada  

**Flujo Básico**:
1. El usuario hace clic en el botón de cierre de la ventana.
2. El sistema cierra la ventana.
3. Si no hay más ventanas abiertas, el sistema oculta el contenedor de ventanas MDI.

**Flujos Alternativos**:
- **Cerrar con botón Cancelar**: El usuario puede cerrar la ventana utilizando un botón "Cancelar" o similar dentro del formulario.
- **Cerrar con acción completada**: Al completar exitosamente una acción (guardar, eliminar, etc.), el sistema puede cerrar automáticamente la ventana.

## CU-41: Utilizar acceso rápido

**Actor Principal**: Usuario autenticado  
**Objetivo**: Acceder rápidamente a funciones frecuentes  
**Precondición**: Usuario autenticado en el sistema  
**Postcondición**: Función de acceso rápido ejecutada  

**Flujo Básico**:
1. El usuario visualiza la sección "Acceso Rápido" en el dashboard.
2. El usuario selecciona una de las funciones disponibles.
3. El sistema ejecuta la acción correspondiente:
   - Abrir un formulario en ventana MDI
   - Navegar a una sección específica
   - Ejecutar una acción directa

**Flujos Alternativos**:
- No aplica.
