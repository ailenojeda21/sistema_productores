# Casos de Uso - Gestión de Usuarios y Perfiles

## CU-01: Registro de nuevo usuario

**Actor Principal**: Usuario no registrado  
**Objetivo**: Crear una nueva cuenta en el sistema  
**Precondición**: Ninguna  
**Postcondición**: Usuario registrado en el sistema  

**Flujo Básico**:
1. El usuario accede a la página de registro.
2. El sistema muestra el formulario de registro.
3. El usuario completa los campos requeridos (nombre, email, contraseña).
4. El usuario envía el formulario.
5. El sistema valida los datos ingresados.
6. El sistema crea la cuenta y redirecciona al dashboard.

**Flujos Alternativos**:
- **Error en validación**: Si algún campo no cumple con los requisitos, el sistema muestra mensajes de error y vuelve al paso 3.
- **Email ya registrado**: Si el email ya existe en el sistema, se muestra un mensaje de error.

## CU-02: Iniciar sesión

**Actor Principal**: Usuario registrado  
**Objetivo**: Acceder al sistema con credenciales  
**Precondición**: Usuario registrado en el sistema  
**Postcondición**: Usuario autenticado en el sistema  

**Flujo Básico**:
1. El usuario accede a la página de inicio de sesión.
2. El sistema muestra el formulario de inicio de sesión.
3. El usuario ingresa su email y contraseña.
4. El usuario envía el formulario.
5. El sistema valida las credenciales.
6. El sistema inicia la sesión y redirecciona al dashboard.

**Flujos Alternativos**:
- **Credenciales incorrectas**: Si el email o la contraseña son incorrectos, el sistema muestra un mensaje de error.

## CU-03: Cerrar sesión

**Actor Principal**: Usuario autenticado  
**Objetivo**: Cerrar la sesión actual  
**Precondición**: Usuario autenticado en el sistema  
**Postcondición**: Sesión cerrada  

**Flujo Básico**:
1. El usuario hace clic en la opción "Cerrar Sesión".
2. El sistema cierra la sesión.
3. El sistema redirecciona a la página de inicio de sesión.

## CU-04: Editar perfil de usuario

**Actor Principal**: Usuario autenticado  
**Objetivo**: Modificar información personal  
**Precondición**: Usuario autenticado en el sistema  
**Postcondición**: Información de perfil actualizada  

**Flujo Básico**:
1. El usuario accede a la sección "Perfil".
2. El sistema muestra el formulario con la información actual.
3. El usuario modifica los campos deseados.
4. El usuario envía el formulario.
5. El sistema valida los datos.
6. El sistema actualiza la información y muestra un mensaje de éxito.

**Flujos Alternativos**:
- **Error en validación**: Si algún campo no cumple con los requisitos, el sistema muestra mensajes de error.

## CU-05: Cambiar contraseña

**Actor Principal**: Usuario autenticado  
**Objetivo**: Modificar la contraseña de acceso  
**Precondición**: Usuario autenticado en el sistema  
**Postcondición**: Contraseña actualizada  

**Flujo Básico**:
1. El usuario accede a la sección "Perfil".
2. El usuario selecciona la opción "Cambiar contraseña".
3. El sistema muestra el formulario para cambiar la contraseña.
4. El usuario ingresa la contraseña actual y la nueva contraseña (con confirmación).
5. El usuario envía el formulario.
6. El sistema valida los datos.
7. El sistema actualiza la contraseña y muestra un mensaje de éxito.

**Flujos Alternativos**:
- **Contraseña actual incorrecta**: Si la contraseña actual es incorrecta, el sistema muestra un mensaje de error.
- **Las contraseñas no coinciden**: Si la nueva contraseña y la confirmación no coinciden, el sistema muestra un mensaje de error.
