# AGENTS.md - Development Guidelines for Sistema Productores

This file contains essential information for agentic coding agents working in this Laravel 12 + Vue.js agricultural management system.

## Build/Test/Lint Commands

### Development Server
```bash
# Start full development stack (PHP + Node + Database)
composer run dev

# Start only Laravel server
php artisan serve

# Start only Vite frontend server
npm run dev
```

### Testing Commands
```bash
# Run all tests
composer run test
# Or directly:
php artisan test

# Run single test file
php artisan test tests/Feature/ExampleTest.php

# Run specific test method
php artisan test --filter test_example

# Run tests with coverage
php artisan test --coverage

# Run tests in verbose mode
php artisan test --verbose
```

### Code Quality Commands
```bash
# Format PHP code (Laravel Pint)
vendor/bin/pint

# Format specific file
vendor/bin/pint app/Models/User.php

# Check formatting without fixing
vendor/bin/pint --dry-run

# Clear all caches (useful when debugging)
./clear_cache.cmd
```

### Database Commands
```bash
# Run migrations
php artisan migrate

# Fresh migration with seeding
php artisan migrate:fresh --seed

# Create new migration
php artisan make:migration create_table_name

# Rollback last migration
php artisan migrate:rollback
```

### Frontend Build Commands
```bash
# Build for development
npm run dev

# Build for production
npm run build

# Optimize production build
npm run build -- --minify
```

## Code Style Guidelines

### PHP Code Style

#### Formatting Standards
- Use **Laravel Pint** for all PHP formatting (PSR-12 compliant)
- 4 spaces for indentation (no tabs)
- LF line endings
- UTF-8 encoding

#### Naming Conventions
- **Classes**: PascalCase (e.g., `ProductController`, `UserModel`)
- **Methods**: camelCase (e.g., `getUserProducts()`, `validateData()`)
- **Variables**: camelCase (e.g., `$userId`, `$productList`)
- **Constants**: UPPER_SNAKE_CASE (e.g., `MAX_FILE_SIZE`, `DEFAULT_ROLE`)
- **Files**: PascalCase for classes (e.g., `ProductController.php`)

#### Import Organization
```php
<?php

// 1. External libraries
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// 2. Application imports (grouped by type)
use App\Models\User;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
```

#### Class Structure
```php
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // 1. Properties
    protected $productService;

    // 2. Constructor (dependency injection)
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    // 3. Public methods (CRUD order)
    public function index()
    {
        // Implementation
    }

    public function store(StoreProductRequest $request)
    {
        // Implementation
    }

    // 4. Private/protected methods
    private function validateProductData(array $data): bool
    {
        // Implementation
    }
}
```

### Vue.js Code Style

#### Component Structure
```vue
<script setup>
// 1. Imports
import { ref, computed, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'

// 2. Props definition
const props = defineProps({
    product: Object,
    editable: Boolean
})

// 3. Emits definition
const emit = defineEmits(['update', 'delete'])

// 4. Reactive state
const isLoading = ref(false)
const formData = ref({})

// 5. Computed properties
const isValid = computed(() => {
    return formData.value.name && formData.value.price
})

// 6. Methods
const submit = async () => {
    isLoading.value = true
    try {
        // Implementation
    } finally {
        isLoading.value = false
    }
}

// 7. Lifecycle hooks
onMounted(() => {
    // Initialization
})
</script>

<template>
    <!-- Template content -->
</template>

<style scoped>
/* Component-specific styles */
</style>
```

#### Import Organization
```javascript
// 1. Vue imports
import { ref, computed, onMounted } from 'vue'

// 2. Inertia.js imports
import { Link, usePage } from '@inertiajs/vue3'

// 3. Local components
import ProductCard from '@/Components/ProductCard.vue'

// 4. Utilities/Helpers
import { formatCurrency } from '@/utils/format'
```

### Blade Template Style

#### Structure Guidelines
```blade
{{-- Use Blade comments for developer notes --}}
@extends('layouts.app')

@section('title', 'Product List')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Header section --}}
    <header class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">
            {{ __('Products') }}
        </h1>
    </header>

    {{-- Main content --}}
    <main>
        @if($products->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        @else
            <x-empty-state message="No products found" />
        @endif
    </main>
</div>
@stop
```

## Error Handling Patterns

### Global Exception Handling
The application uses custom exception handling in `bootstrap/app.php`:
- CSRF token mismatches (419 errors) are handled gracefully
- AJAX requests receive JSON responses
- Regular requests receive redirect responses with error messages

### Validation Patterns
```php
// Form Request validation
class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del producto es obligatorio.',
            'price.numeric' => 'El precio debe ser un número válido.',
        ];
    }
}
```

### Frontend Error Handling
```javascript
const submit = async () => {
    isLoading.value = true
    try {
        await axios.post('/products', formData.value)
        // Success handling
    } catch (error) {
        if (error.response?.status === 422) {
            // Validation errors
            validationErrors.value = error.response.data.errors
        } else {
            // Other errors
            errorMessage.value = 'Error al guardar el producto'
        }
    } finally {
        isLoading.value = false
    }
}
```

## Testing Guidelines

### Test Structure
```php
// tests/Feature/ProductManagementTest.php
test('user can create product', function () {
    // Arrange
    $user = User::factory()->create();
    $productData = Product::factory()->make()->toArray();

    // Act
    $response = $this
        ->actingAs($user)
        ->post('/products', $productData);

    // Assert
    $response->assertRedirect('/products');
    $this->assertDatabaseHas('products', [
        'name' => $productData['name'],
    ]);
});
```

### Testing Best Practices
- Use **Pest PHP** for all new tests
- Use descriptive test names that explain the behavior
- Follow Arrange-Act-Assert pattern
- Use factories for test data
- Test both success and failure scenarios
- Use `RefreshDatabase` trait for database tests

## Database Patterns

### Model Conventions
```php
class Product extends Model
{
    // 1. Fillable fields
    protected $fillable = [
        'name',
        'price',
        'category_id',
        'description',
    ];

    // 2. Casts
    protected $casts = [
        'price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // 3. Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class)
            ->withPivot('quantity', 'price');
    }

    // 4. Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // 5. Accessors/Mutators
    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price, 2);
    }
}
```

## Frontend Architecture

### Inertia.js Patterns
```javascript
// Page components in resources/js/Pages/
export default {
    props: {
        products: Object,
        filters: Object,
    },
    setup(props) {
        const search = ref(props.filters.search || '')
        
        const performSearch = debounce(() => {
            router.get('/products', { search }, {
                preserveState: true,
                preserveScroll: true,
            })
        }, 300)

        return { search, performSearch }
    }
}
```

### Component Organization
- **Pages**: `resources/js/Pages/` - Route-level components
- **Components**: `resources/js/Components/` - Reusable UI components
- **Layouts**: `resources/js/Layouts/` - Page layout components
- **Utils**: `resources/js/Utils/` - Helper functions

## Security Guidelines

### Authentication & Authorization
- Use Laravel Sanctum for API authentication
- Implement Spatie Laravel Permission for role-based access
- Always authorize user actions in controllers
- Use policy classes for complex authorization logic

### Input Validation
- Never trust user input
- Use Form Request classes for validation
- Sanitize file uploads
- Implement CSRF protection on all forms

### Data Protection
- Use mass assignment protection (`$fillable`)
- Hash passwords and sensitive data
- Implement proper error handling without exposing sensitive information
- Use HTTPS in production

## ISO 27001 / 27002 / 27701 / 27017 / 27018 — Security & Privacy by Design

### Principios generales
Todo código nuevo debe cumplir con los controles aplicables de las cinco normas ISO. La implementación se evalúa en cada code review.

### ISO 27001 — Sistema de Gestión de Seguridad de la Información (ISMS)

| Control | Implementación en el proyecto |
|---|---|
| A.9 (Control de acceso) | Autenticación con Laravel Sanctum + Spatie roles. Políticas por recurso (ownership checks). |
| A.12 (Seguridad operativa) | Tests automatizados (Pest), rate limiting en login/register, logging de accesos. |
| A.14 (Seguridad en desarrollo) | Revisión de dependencias (`composer audit`, `npm audit`), validación en Form Requests. |
| A.16 (Gestión de incidentes) | Exception handler global con respuestas diferenciadas (JSON / redirect). |
| A.18 (Cumplimiento) | Datos personales manejados según lineamientos de 27701 / 27018. |

#### Checklist obligatorio por feature
- [ ] Autenticación y autorización definidas antes de implementar
- [ ] Pruebas de éxito y fracaso escritas (Pest)
- [ ] Rate limiting aplicado en endpoints públicos
- [ ] `$fillable` / `$guarded` definido en nuevos modelos
- [ ] Auditoría de datos personales realizada (ISO 27701)

### ISO 27002 — Controles de seguridad detallados

| Sección | Práctica obligatoria |
|---|---|
| **5.1-5.6** (Políticas) | No hardcodear credenciales, URLs, o tokens. Usar `.env` + `config/`. |
| **6.1-6.3** (Organización) | Separación de guards: `web` (productores), `staff` (sesión staff), `staff-api` (tokens Sanctum). |
| **8.1-8.12** (Activos) | Cachear respuestas de API cuando sea posible; documentar esquemas de BD en migraciones. |
| **8.13-8.18** (Controles criptográficos) | `APP_KEY` rotada en producción, contraseñas hasheadas con bcrypt, HTTPS forzado en Railway. |
| **9.1-9.4** (Control de acceso) | Mínimo privilegio: `staff.role:admin` para operaciones administrativas; ownership checks en policies. |
| **10.1** (Cifrado) | Sesiones con `session.secure` en producción; tokens Sanctum expiran 1440 min. |
| **11.1-11.2** (Seguridad física) | N/A (delegado a Railway / cloud provider). |
| **12.1-12.7** (Operaciones) | Logging de eventos críticos (login, logout, creación/eliminación de usuarios). Logs sin datos sensibles. |
| **13.1-13.2** (Comunicaciones) | HTTPS siempre; Content-Security-Policy definida; cookies `HttpOnly` + `SameSite`. |
| **14.1-14.3** (Adquisición/desarrollo) | Migraciones versionadas; validación server-side + client-side; pruebas de seguridad. |
| **15.1-15.2** (Proveedores) | `composer.lock` / `package-lock.json` commitheados para trazabilidad. |
| **16.1** (Incidentes) | Respuesta a 419 (CSRF), 403 (rol), 422 (validación), 500 (error global) manejadas. |
| **18.1-18.2** (Cumplimiento) | Revisión de licencias de dependencias; no usar paquetes con licencias GPL si el proyecto es propietario. |

#### Reglas de código para 27002
```php
// ✅ Correcto: validación con Form Request + saneamiento
use App\Http\Requests\StoreUserRequest;

public function store(StoreUserRequest $request)
{
    $user = User::create($request->validated());
}

// ❌ Incorrecto: confiar en $request->all() sin filtrar
$user = User::create($request->all());
```

```php
// ✅ Correcto: owner check en Policy
public function update(User $user, Propiedad $propiedad): bool
{
    return $user->id === $propiedad->usuario_id
        || $user->hasRole('admin');
}

// ❌ Incorrecto: permitir sin verificar ownership
return true;
```

### ISO 27701 — Gestión de Información de Privacidad (PIMS)

#### Datos personales identificados en el sistema
| Entidad | Datos personales | Propósito | Retención |
|---|---|---|---|
| `users` | nombre, email, teléfono, password (hash) | Identificación del productor | Mientras la cuenta esté activa + 1 año |
| `staff_users` | nombre, email, password (hash) | Acceso staff al sistema | Mientras el staff esté activo |
| `propiedades` | calle, localidad, coordenadas (mapa) | Geolocalización de fincas | Indefinido mientras el productor exista |
| `cultivos` | variedad, fecha siembra | Historial productivo | Asociado al productor |
| `comercios` | nombre, dirección, contacto | Puntos de venta | Indefinido |

#### Reglas obligatorias (27701)
1. **Minimización**: solo recolectar datos estrictamente necesarios para la funcionalidad
2. **Consentimiento**: el registro implica aceptación de términos; no compartir datos con terceros
3. **Portabilidad**: el productor puede solicitar exportación de sus datos (endpoint `GET /api/profile/export`)
4. **Eliminación**: `SoftDeletes` en `StaffUser`; eliminación lógica con purge programado
5. **Notificación de brechas**: el sistema debe poder identificar qué datos de qué usuarios fueron expuestos
6. **Data Protection Impact Assessment (DPIA)**: documentar antes de agregar nuevas entidades que recolecten datos personales

```php
// ✅ Correcto: exponer solo campos necesarios en APIs
return response()->json([
    'id' => $user->id,
    'name' => $user->name,
    'email' => $user->email,
    // ❌ Nunca exponer: password, remember_token, api tokens
]);

// ✅ Correcto: soft delete + anonimización al purgar
$user->delete(); // SoftDeletes
// En purge job:
$user->forceDelete(); // Solo si ha pasado el período de retención
```

### ISO 27017 — Controles de seguridad en cloud

Aplica a la infraestructura en Railway (o cualquier cloud provider).

| Control | Implementación |
|---|---|
| **17.1** (Responsabilidades compartidas) | El proveedor (Railway) maneja seguridad de red/física; el equipo maneja seguridad de aplicación y datos. |
| **17.2** (Aislamiento) | Base de datos con credenciales separadas por entorno; contenedores sin acceso a host. |
| **17.3** (Configuración de cloud) | Variables de entorno para todas las credenciales; no commitear `.env`. |
| **17.4** (Monitoreo) | Logs de acceso a Railway; rate limiting por IP. |
| **17.5** (Virtualización) | N/A (Railway maneja la capa de contenedores). |

#### Prácticas cloud obligatorias
```bash
# ✅ Correcto: usar variables de entorno
DB_PASSWORD=secret

# ❌ Incorrecto: valores hardcodeados en config/database.php
'password' => 'root'
```

```php
// ✅ Correcto: forzar HTTPS en producción
if (app()->environment('production')) {
    URL::forceScheme('https');
}

// ✅ Correcto: sesiones seguras en producción
// config/session.php
'secure' => env('SESSION_SECURE_COOKIE', true),
'same_site' => 'lax',
```

### ISO 27018 — Protección de datos personales identificables (PII) en clouds públicos

| Requisito | Implementación |
|---|---|
| **PII.1** (Consentimiento) | Términos y condiciones aceptados al registrarse. |
| **PII.2** (Propósito) | Los datos solo se usan para la operación del sistema RUPAL. |
| **PII.3** (Retención mínima) | Período de retención definido por entidad; purge job pendiente. |
| **PII.4** (Cifrado en tránsito) | HTTPS forzado en producción (Railway). |
| **PII.5** (Cifrado en reposo) | Base de datos cifrada a nivel de disco por Railway. |
| **PII.6** (Notificación al titular) | Endpoint de exportación de datos implementado. |
| **PII.7** (Eliminación segura) | SoftDeletes + forceDelete diferido en StaffUser. |
| **PII.8** (Registro de procesamiento) | Logging de accesos a datos personales (login/logout). |

#### Reglas para endpoints que sirven PII
```php
// ✅ Correcto: paginar + ocultar datos sensibles
public function index()
{
    return User::select('id', 'name', 'email')
        ->paginate(20);
}

// ❌ Incorrecto: exponer datos sensibles sin filtrar
return User::all(); // Expone password, remember_token, etc.
```

#### Resumen de responsabilidades por norma
| Norma | Ámbito | Responsable |
|---|---|---|
| ISO 27001 | SGSI general | Equipo de desarrollo + CISO |
| ISO 27002 | Controles de seguridad | Developer (code review) |
| ISO 27701 | Privacidad de datos | Developer + DPO |
| ISO 27017 | Seguridad cloud | DevOps / Railway admin |
| ISO 27018 | PII en cloud pública | Developer + DPO |

## Development Workflow

### Git Workflow
- Use feature branches for new development
- Write clear, descriptive commit messages
- Create pull requests for code review
- Ensure all tests pass before merging

### Code Review Checklist
- [ ] Code follows style guidelines
- [ ] Tests are written and passing
- [ ] No sensitive data is committed
- [ ] Error handling is implemented
- [ ] Documentation is updated if needed

### Performance Considerations
- Use eager loading for database relationships
- Implement proper indexing for database queries
- Optimize frontend assets with Vite
- Use caching strategies where appropriate

## Language and Localization

### Multi-language Support
- All user-facing text should use Laravel's translation system
- Spanish is the primary language (`es` locale)
- Translation files are in `lang/es/`
- Use `__('translation.key')` in Blade templates
- Use `trans('translation.key')` in JavaScript

### Translation Examples
```php
// In Blade
{{ __('products.title') }}

// In validation messages
'name.required' => __('validation.required', ['attribute' => 'name'])
```

## Common Patterns and Utilities

### Reusable Vue Composables
```javascript
// resources/js/Composables/usePagination.js
export function usePagination(initialPage = 1) {
    const page = ref(initialPage)
    
    const goToPage = (pageNumber) => {
        page.value = pageNumber
    }
    
    return {
        page: readonly(page),
        goToPage,
    }
}
```

### Helper Functions
```php
// app/Helpers/FormatHelper.php
if (!function_exists('formatCurrency')) {
    function formatCurrency($amount): string
    {
        return '$' . number_format($amount, 2);
    }
}
```

This AGENTS.md file should be updated whenever new patterns emerge or the development workflow changes. All agents working in this codebase should familiarize themselves with these guidelines to maintain consistency and code quality.