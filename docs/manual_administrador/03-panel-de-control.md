# Manual de Administrador — Panel de Control

**Código**: RUPAL-MA-03  
**Versión**: 1.0  
**Fecha**: 2026-06-15  
**ISO 9001:2015**: Cláusula 7.5 — Información Documentada

---

## 1. OBJETIVO

Describir la funcionalidad del panel de control (dashboard) del staff, sus indicadores clave de rendimiento (KPI) y gráficos estadísticos.

## 2. ALCANCE

Aplica a todos los usuarios staff (admin y auditor) del sistema RUPAL.

## 3. ACCESO AL DASHBOARD

| Paso | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Iniciar sesión en el sistema staff | El sistema redirige automáticamente al dashboard |
| 2 | Hacer clic en **Inicio** en el menú lateral | Se carga el panel de control |

## 4. ESTRUCTURA DEL DASHBOARD

[CAPTURA DE PANTALLA: Dashboard completo del staff con KPIs y gráficos]

El dashboard staff se compone de cuatro secciones principales:

### 4.1 Tarjetas de KPIs

En la parte superior se muestran dos (2) indicadores numéricos principales:

[CAPTURA DE PANTALLA: Tarjetas de KPIs]

| KPI | Descripción | Fórmula |
|-----|-------------|---------|
| **Total de Productores** | Número total de productores registrados en el sistema | `COUNT(users)` |
| **Nuevos (30 días)** | Productores registrados en los últimos 30 días | `COUNT(users WHERE created_at >= NOW() - 30d)` |

Cada tarjeta muestra:
- Icono representativo (👥 para total, 📈 para nuevos).
- Valor numérico grande.
- Etiqueta descriptiva.

### 4.2 Gráfico de Evolución de Usuarios

[CAPTURA DE PANTALLA: Gráfico de líneas de evolución de usuarios]

| Propiedad | Descripción |
|-----------|-------------|
| **Tipo** | Gráfico de líneas (Line Chart) |
| **Eje X** | Últimos 6 meses (mes a mes) |
| **Eje Y** | Cantidad de nuevos registros por mes |
| **Período** | Ventana móvil de 6 meses |
| **Biblioteca** | Chart.js (vue-chartjs) |
| **Color** | Línea azul con área sombreada |

Este gráfico permite visualizar la tendencia de adopción del sistema por parte de los productores.

### 4.3 Gráfico de Hectáreas Cultivadas

[CAPTURA DE PANTALLA: Gráfico de dona de hectáreas cultivadas por tipo]

| Propiedad | Descripción |
|-----------|-------------|
| **Tipo** | Gráfico de dona (Doughnut Chart) |
| **Segmentos** | Tipo de cultivo: Fruticola, Horticola, Viticola, Olivicola |
| **Valor** | Suma total de hectáreas cultivadas por tipo |
| **Leyenda** | Colores distintivos para cada tipo |

Este gráfico muestra la distribución de la superficie cultivada según el tipo de cultivo.

### 4.4 Indicador de Superficie Departamental

[CAPTURA DE PANTALLA: Indicador de porcentaje del departamento cultivado]

| Indicador | Descripción |
|-----------|-------------|
| **Valor** | Porcentaje calculado como `(Total hectáreas cultivadas en RUPAL / Total hectáreas departamento de Lavalle) × 100` |
| **Visualización** | Barra de progreso con etiqueta porcentual |

## 5. ACTUALIZACIÓN DE DATOS

- Los KPIs y gráficos se actualizan en **tiempo real** con cada nuevo registro o modificación.
- No es necesario refrescar la página manualmente; los datos provienen del servidor en cada carga.
- La fuente de datos es la base de datos del sistema (MySQL en producción, SQLite en desarrollo).

## 6. INTERPRETACIÓN DE LOS INDICADORES

| Indicador | Valor esperado | Interpretación |
|-----------|----------------|----------------|
| Total de Productores | Creciente | Indica la adopción del sistema |
| Nuevos (30 días) | Variable | Picos pueden asociarse a campañas de registro |
| Hectáreas cultivadas | Representativa | Debe reflejar la realidad productiva del departamento |
| % Departamento | < 100% | Indica la cobertura del registro |

## 7. COMPORTAMIENTO ESPECIAL

- Si no hay datos suficientes para generar los gráficos (ej: cero productores), se muestra un mensaje informativo en lugar del gráfico vacío.
- Los datos se obtienen a través del endpoint del controlador `StaffDashboardController`.
- En modo API, el dashboard responde con JSON en lugar de renderizar la página Inertia.
