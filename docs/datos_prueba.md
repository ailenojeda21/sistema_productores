# Dataset de pruebas

Generador de datos de prueba para el sistema de productores. Produce un conjunto
determinista, coherente con las validaciones reales de la aplicación y con
suficientes casos borde para ejercitar filtros, dashboard, export PDF/XLSX y
paginación sin tener que fabricar datos a mano.

- Comando: `php artisan datos:prueba`
- Seeder: `php artisan db:seed --class=DatosPruebaSeeder`
- Configuración: `config/datos-prueba.php`

---

## 1. Uso rápido

```bash
# Ver el plan sin tocar la base
php artisan datos:prueba --perfil=mediano --dry-run

# Generar el dataset de referencia (100 productores)
php artisan datos:prueba

# Perfil grande (500 productores), para medir export y dashboard
php artisan datos:prueba --perfil=grande

# Smoke test
php artisan datos:prueba --perfil=pequeno

# Dataset reproducible byte a byte
php artisan datos:prueba --perfil=mediano --semilla=20260905 --referencia="2026-09-26 12:00:00"
```

### Opciones

| Opción | Por defecto | Efecto |
|---|---|---|
| `--perfil=` | `mediano` | `pequeno`, `mediano` o `grande` |
| `--semilla=` | `20260905` | Misma semilla ⇒ mismo dataset |
| `--referencia=` | ahora | Instante contra el que se calculan todas las fechas |
| `--password=` | `DatosPrueba2026!` | Password de productores, staff y escenarios |
| `--fresh` | off | **Borra todas** las tablas de dominio antes de insertar |
| `--sin-escenarios` | off | Omite las filas golden de `EscenariosSeeder` |
| `--dry-run` | off | Imprime el plan y sale sin escribir |

> `--fresh` es destructivo: elimina los seeders demo también, no solo el
> namespace generado. El comando avisa antes de hacerlo.

Los mismos valores se pueden fijar por entorno, para no repetirlos en cada
invocación:

```dotenv
DATOS_PRUEBA_PERFIL=mediano
DATOS_PRUEBA_SEMILLA=20260905
DATOS_PRUEBA_PASSWORD=DatosPrueba2026!
DATOS_PRUEBA_REFERENCIA=2026-09-26 12:00:00
```

---

## 2. Perfiles

| Perfil | Productores | Propiedades | Cultivos | Maquinarias | Comercios | Staff |
|---|---|---|---|---|---|---|
| `pequeno` | 30 | 55 | 81 | 33 | 20 | 8 |
| `mediano` | 100 | 210 | 307 | 131 | 67 | 12 |
| `grande` | 500 | 1017 | 1537 | 631 | 334 | 40 |

`mediano` es el perfil de referencia: sus grupos densos tienen 11 productores,
justo lo necesario para que `paginate(10)` devuelva 2 páginas. `pequeno` no
sirve para verificar paginación (sus grupos densos quedan en 3).

El perfil `grande` existe para medir el export XLSX y el dashboard; genera
~4.000 filas en menos de 5 segundos.

---

## 3. Cuentas de acceso

| Rol | Email | Password |
|---|---|---|
| Productor | `productor000@demo.test` … `productor{NNN}@demo.test` | `DatosPrueba2026!` |
| Staff admin | `admin00@rupal.test` … `admin03@rupal.test` | `DatosPrueba2026!` |
| Staff auditor | `auditor04@rupal.test` … | `DatosPrueba2026!` |
| Escenarios | `escenarios@datos-prueba.test` | `DatosPrueba2026!` |

El prefijo del email es correlativo con el índice del plan, así que los auditores
arrancan en `04` (los índices 0..3 son los admins). Un tercio del total es admin
y el resto auditor, con un mínimo de 2 admins.

El password cumple `Password::defaults()` (mínimo 8, mayúscula, minúscula,
dígito y símbolo).

### Namespaces

Cada namespace se purga por separado, así que el seed convive con los seeders
demo sin borrarlos:

- `@demo.test` — productores generados
- `@rupal.test` — usuarios staff generados
- `@datos-prueba.test` — filas golden de `EscenariosSeeder`

El comando imprime el resumen con estos conteos al terminar.

---

## 4. Criterios cubiertos

Cada criterio tiene al menos un test que lo verifica en
`tests/Feature/DatosPruebaIntegridadTest.php`.

| # | Criterio | Qué garantiza |
|---|---|---|
| C1 | Cardinalidad | Cada productor con propiedades tiene ≥2 cultivos; como máximo 1 maquinaria por propiedad y 1 registro de comercialización por productor |
| C2 | Unicidad | DNI y emails generados no se repiten |
| C3 | Determinismo | Misma semilla + misma referencia ⇒ mismo plan |
| C4 | Dominios | Se cubren los 69 pares `(tipo, variedad)` de `Cultivo::TIPOS` |
| C5 | Distrito | 4 distritos densos (11 productores) + 5 distritos fuera del whitelist |
| C6 | Temporal | Cohortes antigua (fuera de 90 días) y frontera de 29/31 días |
| C7 | Similitud | Pares de nombres que solo difieren en el acento, y un nombre duplicado exacto |
| C8 | Paginación | Prefijo de DNI compartido, apellido denso y dominio de staff con >10 filas |
| C9 | Sentinelas | Sin perfil, email sin verificar, propiedades sin cultivos, sin maquinaria, sin comercio |
| C10 | Exportabilidad | `mercados`/`cooperativas` como JSON válido o `null`, con claves fuera del vocabulario a propósito |

### Invariantes verificadas

- `suma(hectareas de cultivos) <= propiedad.hectareas`
- `propiedad.hectareas_malla <= propiedad.hectareas` y nunca negativo
- `tipo_tenencia = 'otros'` ⇒ `especificar_tenencia` no vacío
- `tractor = 1` ⇒ `modelo_tractor` en `[1900, año actual]`; `tractor = 0` ⇒ `NULL`
- `malla = 0` ⇒ `hectareas_malla IS NULL`
- Todo cultivo usa `(tipo, variedad)` del whitelist de `Cultivo::getVariedadesForTipo()`
- Todo comercio tiene al menos una vía de venta (`mercados`, `cooperativas` o `vende_en_finca`)
- `rut = 0` ⇒ el filtro `rut` la excluye (aunque `rut_valor` tenga valor)

---

## 5. Cohortes de cola

Reservan los últimos índices del dataset para que el resto de las cohortes por
módulo conserven sus conteos:

| Tag | Cantidad | Para qué sirve |
|---|---|---|
| `soft_deleted` | 4 | Filtros con `SoftDeletes`; la papelera anonimiza el email |
| `propiedad_sin_cultivos` | 3 | Propiedad con 0 cultivos |
| `sin_propiedades` | 5 | Productor sin propiedades (excepción a C1, intencional) |
| `antiguo` | ≥5 | Fuera de la ventana de 90 días del dashboard |
| `frontera_30_dias` | 2 | A 29 y 31 días de la referencia |

Las cohortes por módulo se reparten con `indice % mod === res`, así que la
cantidad de cada una escala con el tamaño del perfil.

---

## 6. Escenarios golden

`EscenariosSeeder` agrega un conjunto pequeño y fijo de filas que cubren los
límites exactos de cada `FormRequest`, con emails en `@datos-prueba.test`:

- Hectáreas en 0, en el máximo y en el máximo + 1
- Tractor con `modelo_tractor` en 1900, en el año actual y fuera de rango
- Tenencia `otros` con y sin `especificar_tenencia`
- RUT con ceros iniciales, huérfano y de longitud máxima
- Mercados y cooperativas como `null`, `[]`, con claves válidas y con claves
  inventadas
- Cultivo con 0 hectáreas y cultivo que consume toda la superficie

Se omiten con `--sin-escenarios`.

Estos límites se ejercitan sobre los formularios y el detalle del productor. La
exportación XLSX arma sus columnas según el filtro activo: con `distrito`, `rut`,
`dni` o `name` lleva Perfil + Propiedad; con `variedad` o `tipo` agrega el módulo
Cultivo. Maquinaria y Comercios no se exportan nunca, porque ninguna búsqueda
filtra por ellos. Por eso estos escenarios llegan al export sobre todo a través
de los filtros, que leen desde `propiedades` y `cultivos` para elegir qué filas
entran: los límites de `hectareas` y `hectareas_malla` acotan el módulo
Propiedad, el RUT decide qué propiedad se incluye, la variedad larga hace
matchear al productor por `variedad`, y los `name` y `direccion` en su longitud
máxima aparecen completos en el módulo Perfil, en la columna `Dirección
Productor`. `Calle` y `Numeracion` no se exportan por separado: el escenario de
`calle` con guiones se lee en la columna `Dirección Propiedad`, que los
capitaliza.

---

## 7. Soft delete y el email determinista

`User` tiene un hook en `booted()` que anonimiza la fila al borrarla: reemplaza
`name` y `email` y setea `deleted_at`. Eso rompe la idempotencia, porque la
segunda corrida buscaría `productor000@demo.test` y no lo encontraría.

`ProductoresSeeder` lo resuelve **restaurando el email determinista después del
soft delete**. El resultado es que la fila queda anonimizada pero sigue siendo
localizable por su clave, y el purge de la siguiente corrida la encuentra.

`StaffUser` no anonimiza el email, así que no necesita este rodeo.

---

## 8. Purge e idempotencia

`DatosPruebaSeeder::purgar()` borra en orden de claves foráneas:

```
cultivos → maquinarias → comercios → propiedades → users → staff_users
```

El orden importa: borra los hijos antes que los padres. Es portable entre MySQL
y SQLite, a diferencia de desactivar `FOREIGN_KEY_CHECKS`.

Correr el seeder dos veces con la misma semilla no duplica filas: los productores
se resuelven por email con `upsert` y las filas hijas se borran y recrean.

---

## 9. Tests

```bash
php artisan test tests/Feature/DatosPruebaIntegridadTest.php   # 38 tests, invariantes
php artisan test tests/Feature/DatosPruebaFiltrosTest.php      # 34 tests, endpoints reales
php artisan test tests/Feature/DatosPruebaFactoriesTest.php    # 19 tests, factories
```

- **Integridad** siembra el dataset en SQLite `:memory:` y verifica los criterios C1..C10.
- **Filtros** siembra igual y ejercita `/staff/producers` con `Accept: application/json`:
  auth, filtros, paginación, export y permisos. Usa la ruta web a propósito, porque
  `/api/producers` va por el guard Sanctum `staff-api`.
- **Factories** verifica que `Model::factory()` produce filas que las `Store*Request`
  reales aceptarían, para que un test que use factories no herede datos que la app
  rechazaría.

Los tests nuevos van en estilo Pest. `tests/Pest.php` aplica `RefreshDatabase`
a todo `Feature`.

---

## 10. Discrepancia conocida

`StaffProducerController::index()` y `::export()` normalizan el parámetro
`distrito` de forma distinta: `index()` no parte bien los distritos de varias
palabras, así que `distrito=la pega` devuelve filas que `export()` sí encuentra
con `distrito=la-pega`.

El dataset incluye deliberadamente un distrito denso de varias palabras
(`la-pega`, `san-jose`, `el-carmen`) para que la regresión sea visible si alguien
toca esa normalización. No se corrigió porque es un cambio preexistente fuera
del alcance de este trabajo.

---

## 11. Estructura del código

```
app/Support/TestData/
  Rng.php         RNG determinista (xorshift), sin dependencia de Faker
  Plan.php        DTO del plan: totales, specs de usuario y staff
  Contexto.php    Mapas plan → IDs reales, y hash de password memoizado
  Poblador.php     planificación y construcción del plan; nada escribe en la base

database/seeders/DatosPrueba/
  ProductoresSeeder.php    usuarios + roles + soft delete
  PropiedadesSeeder.php   propiedades
  CultivosSeeder.php       cultivos (INSERT masivo por chunks)
  MaquinariasSeeder.php    maquinarias (INSERT masivo por chunks)
  ComerciosSeeder.php      comercios (INSERT masivo)
  StaffUsersSeeder.php     usuarios staff
  EscenariosSeeder.php     filas golden de límites
```

`Poblador` no toca la base: construye el plan en memoria. `DatosPruebaSeeder`
lo persiste dentro de una transacción. Esa separación es la que permite que
`--dry-run` muestre el plan sin escribir nada.

El hash de password se calcula una sola vez por corrida y se reutiliza: bcrypt
era el cuello de botella (11.6 s → 0.5 s en el perfil pequeño) porque
`User` y `StaffUser` tienen cast `hashed` y cada fila re-hasheaba.
