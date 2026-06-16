# Manual de Usuario — Cultivos

**Código**: RUPAL-MU-06  
**Versión**: 1.0  
**Fecha**: 2026-06-15  
**ISO 9001:2015**: Cláusula 7.5 — Información Documentada

---

## 1. OBJETIVO

Describir el procedimiento completo de gestión de cultivos en RUPAL, incluyendo alta, edición, eliminación y validación de hectáreas disponibles.

## 2. ALCANCE

Aplica a todos los productores que necesiten registrar los cultivos asociados a cada una de sus propiedades.

## 3. DESCRIPCIÓN DEL MÓDULO

El módulo de cultivos permite al productor:
- Registrar uno o más cultivos por propiedad.
- Especificar el tipo de cultivo (hortícola, vitícola, olivícola, frutícola).
- Seleccionar la variedad específica dentro de cada tipo.
- Indicar la superficie cultivada en hectáreas (no puede exceder las hectáreas disponibles de la propiedad).
- Definir el tipo de manejo, estación del año y tecnología de riego.

## 4. PROCEDIMIENTO: LISTADO DE CULTIVOS

[CAPTURA DE PANTALLA: Listado de cultivos]

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Hacer clic en **Cultivos** en el menú lateral | Se carga el listado de cultivos registrados |
| 2 | — | Se muestran tarjetas con: tipo de cultivo, variedad, propiedad asociada, hectáreas, acciones |

### 4.1 Columnas del listado

| Columna | Descripción |
|---------|-------------|
| Tipo | Tipo de cultivo (Fruticola, Horticola, Viticola, Olivicola) |
| Variedad | Variedad específica del cultivo |
| Propiedad | Dirección de la propiedad asociada |
| Hectáreas | Superficie cultivada |
| Temporada | Estación del año (Primavera, Verano, Otoño, Invierno) |
| Acciones | Botones: Editar, Eliminar |

## 5. PROCEDIMIENTO: CREAR NUEVO CULTIVO

[CAPTURA DE PANTALLA: Formulario de creación de cultivo]

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Hacer clic en **Agregar Cultivo** (botón superior) | Se abre el formulario de creación |
| 2 | Seleccionar la **Propiedad** del menú desplegable | Se despliega la lista de propiedades del productor |
| 3 | Seleccionar el **Tipo de Cultivo** | Se habilitan las variedades correspondientes |
| 4 | Seleccionar la **Variedad** | Se muestra la lista de variedades del tipo seleccionado |
| 5 | Completar los demás campos | Los campos se llenan |
| 6 | Hacer clic en **Guardar** | El sistema valida y guarda el cultivo |

### 5.1 Campos del formulario

| Campo | Tipo | Obligatorio | Descripción |
|-------|------|-------------|-------------|
| Propiedad | Selección | Sí | Propiedad donde se cultiva |
| Tipo | Selección | Sí | Fruticola, Horticola, Viticola, Olivicola |
| Variedad | Selección | Sí | Depende del tipo seleccionado |
| Hectáreas | Número | Sí | Superficie cultivada (mínimo 0.01) |
| Manejo | Selección | No | Tipo de manejo agrícola |
| Estación | Selección | No | Temporada del cultivo |
| Tecnología de Riego | Selección | No | Sistema de riego utilizado |
| Fecha de Siembra | Fecha | No | Fecha en que se realizó la siembra |

### 5.2 Tipos de cultivo y sus variedades

| Tipo | Variedades Disponibles |
|------|------------------------|
| **Hortícola** | Tomate, Lechuga, Zanahoria, Cebolla, Pimiento, Zapallo, Papa, Batata, Acelga, Espinaca, Brócoli, Coliflor, Repollo, Remolacha, Chaucha, Arveja, Habas, Maíz, Sandía, Melón, Frutilla, Perejil |
| **Viticola** | Malbec, Cabernet Sauvignon, Merlot, Syrah, Bonarda, Tempranillo, Criolla, Cereza, Torrontés, Chardonnay, Sauvignon Blanc, Pedro Giménez |
| **Olivícola** | Arauco, Arbequina, Coratina, Manzanilla, Nevadillo, Criolla |
| **Fruticola** | Durazno, Ciruela, Damasco, Pera, Manzana, Membrillo, Níspero, Higo, Naranja, Mandarina, Limón, Pomelo, Palta, Vid (Uva de Mesa) |

### 5.3 Tipos de manejo

| Opción | Descripción |
|--------|-------------|
| Convencional | Agricultura tradicional con insumos sintéticos |
| Orgánico | Agricultura sin insumos sintéticos, certificada |
| Agroecológico | Sistema productivo sustentable |
| Transición | En proceso de conversión a orgánico |
| Buenas Prácticas Agrícolas (BPA) | Implementación de BPA |

### 5.4 Tecnologías de riego

| Opción | Descripción |
|--------|-------------|
| Gravedad | Riego por surcos o melgas |
| Aspersión | Riego por aspersores |
| Goteo | Riego localizado de alta frecuencia |
| Microaspersión | Riego localizado con microaspersores |
| Cañón | Riego con cañón viajero |
| Manta | Riego por inundación controlada |
| Cinta | Riego por cinta exudante |
| Otro | Otra tecnología no listada |

## 6. VALIDACIÓN DE HECTÁREAS DISPONIBLES

El sistema valida automáticamente que la suma de hectáreas cultivadas no supere el total de hectáreas de la propiedad.

[CAPTURA DE PANTALLA: Error de validación por exceso de hectáreas]

| Condición | Comportamiento |
|-----------|---------------|
| Hectáreas cultivadas ≤ hectáreas de la propiedad | El cultivo se guarda correctamente |
| Hectáreas cultivadas > hectáreas disponibles | El sistema muestra el error: "La propiedad no tiene suficientes hectáreas disponibles" |
| Se elimina un cultivo | Las hectáreas se liberan automáticamente |

### 6.1 Cálculo de hectáreas disponibles

El sistema calcula las hectáreas disponibles de la siguiente forma:

```
Hectáreas Disponibles = Hectáreas Totales de la Propiedad - Suma de Hectáreas de Todos los Cultivos Existentes
```

## 7. PROCEDIMIENTO: EDITAR CULTIVO

[CAPTURA DE PANTALLA: Formulario de edición de cultivo precargado]

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | En el listado, hacer clic en **Editar** (✏️) | Se abre el formulario con los datos actuales precargados |
| 2 | Modificar los campos necesarios | Los campos se actualizan |
| 3 | Hacer clic en **Guardar** | El sistema valida y actualiza el cultivo |
| 4 | — | Mensaje de confirmación: "Cultivo actualizado exitosamente" |

## 8. PROCEDIMIENTO: ELIMINAR CULTIVO

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | En el listado, hacer clic en **Eliminar** (🗑️) | Aparece un cuadro de diálogo de confirmación |
| 2 | Confirmar la eliminación | El cultivo se elimina |
| 3 | — | Las hectáreas se liberan para nuevos cultivos |

## 9. VALIDACIONES DEL FORMULARIO

| Campo | Regla de Validación |
|-------|---------------------|
| Propiedad | Requerido, debe existir y pertenecer al productor |
| Tipo | Requerido, debe ser un tipo válido |
| Variedad | Requerido, debe ser una variedad válida para el tipo seleccionado |
| Hectáreas | Requerido, numérico, mínimo 0.01, no puede exceder las hectáreas disponibles de la propiedad |

## 10. COMPORTAMIENTO ESPECIAL

- Al seleccionar un **Tipo de Cultivo**, las opciones de **Variedad** se filtran automáticamente para mostrar solo las correspondientes a ese tipo.
- Si la propiedad no tiene hectáreas suficientes, el botón **Guardar** se deshabilita y se muestra una advertencia.
- Un cultivo está asociado a una única propiedad. Para registrar el mismo cultivo en otra propiedad, debe crearse por separado.
