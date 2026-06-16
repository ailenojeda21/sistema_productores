## Goal
- Auditar, corregir y endurecer integralmente el sistema de gestión agrícola RUPAL (Productores + Staff)

## Constraints & Preferences
- Commits separados por severidad  
- No romper los tests existentes  
- Correcciones de `.env` son manuales, no se commitean  
- `todos.md` mantenido actualizado

## Progress
### Done
- Auditoría integral completada (6 agentes paralelos, 56 hallazgos)  
- 25 errores BD corregidos  
- 10 archivos de commit code security  
- Composer update: 71 paquetes, 25 CVEs parcheados  
- npm overrides: shell-quote 1.8.4, vite 6→8, 0 vulnerabilidades npm  
- **A1** — Login logging (EventServiceProvider)  
- **A2** — Spatie Gate::before bypass admin  
- **A3** — Staff password reset (controller, Vue, email azul)  
- **A4** — Export endpoint movido a grupo staff.role:admin  
- **A5** — Log PII eliminado  
- **A6** — esbuild CVE (vite 6→8)  
- **M7** — $hidden (dni, telefono, direccion)  
- **M8** — Login staff info disclosure  
- **M11** — Password min 8  
- **RUT files protegidos** (disco privado, downloadRut, auth owner/staff)  
- **StaffProducerController@export** reescrito con PhpSpreadsheet (XLSX real)  
- **StaffProducer route export** movido a shared group (admin + auditor)  
- **M13** — Sanctum token prefix default `rupal_staff_`  
- **M15** — `config/hashing.php` creado (bcrypt 12 rounds)  
- **B1** — `auth.verification.expire` agregado (60 min)  
- **B3** — StaffRoleMiddleware acepta múltiples roles (comma-separated)  
- **M3** — `SESSION_ENCRYPT=true` (default cambiado)  
- **M1+M2** — `SecureHeadersMiddleware` creado (CSP, HSTS, XFO, XCTO, Referrer-Policy, Permissions-Policy)  
- **M5+M6+B5** — AuthServiceProvider + StaffUserPolicy + authorize() en StaffUserController  
- **M12** — Portabilidad endpoint (`GET /profile/export`) en ProfileController  
- **M16** — Purge job (`app:purge-soft-deleted-records`) + schedule diario a las 03:00  
- **B2** — Double rate limiting eliminado (LoginRequest simplificado, route throttle mantenido)  
- **B4** — Global API exception handling (401/403/404/419/429/500 JSON responses)  
- **B9** — User SoftDeletes (nueva migración + trait)  
- 111 tests pasan, 2 skipped

### Remaining
- **B6** — OpenStreetMap recibe coordenadas vía CDN (requiere análisis arquitectónico)  
- **B7** — Form Request infrautilizado (recomendación, no bug)  
- **Manuales (.env):** C1, C2, C3, M4, M10, B8

## Key Decisions
- RUT files: disco privado + ruta con auth en vez de modificar symlink público  
- Staff password reset: Inertia+Vue (mismo stack que staff login)  
- User reset password email: mismo approach con branding naranja  
- $hidden no incluye email porque lo necesita Inertia sidebar  
- HandleInertiaRequests explicita campos en vez de pasar modelo completo  
- Export reescrito con PhpSpreadsheet (HTML crudo eliminado)  
- Security headers en middleware Laravel (no nginx, no disponible)  
- AuthServiceProvider creado separado de AppServiceProvider (Laravel 12 pattern)  
- StaffRoleMiddleware soporta múltiples roles en un string separado por comas  
- Portabilidad endpoint como ruta web (no API) para productores autenticados  
- Purge job: 365 días para soft-deletes, dry-run soportado, schedule diario  
- API exception handlers: ValidationException pasa al handler nativo de Laravel  
- User SoftDeletes: migración + trait, account deletion es soft-delete ahora  
- Self-deletion check en StaffUserController precede al authorize() para mensaje amigable

## Next Steps
- **Manuales:** Aplicar C1, C2, C3, M4, M10, B8 en `.env` de producción  
- **Commit:** Hacer commit separado por severidad (altos→medios→bajos)

## Critical Context
- **111 tests pasan, 2 skipped**  
- **Base de datos:** SQLite local, MySQL en Railway  
- **Dual frontend:** Blade (Productores) + Inertia/Vue 3 (Staff)  
- **Dos guards:** web (Productores, Spatie) + staff (StaffUser, columna role) + staff-api (Sanctum)  
- **Sanctum tokens:** expiran 2h (ya no 24h)  
- **npm audit:** 0 vulnerabilidades; composer audit: 0  
- **Timezone:** UTC (Argentina -3h)  
- **todos.md:** 55 hallazgos, 46 resueltos, 2 pendientes, 6 manuales

## Relevant Files
- `config/sanctum.php`: token_prefix `rupal_staff_`, expiration 120 min  
- `config/hashing.php`: nuevo, bcrypt 12 rounds  
- `config/auth.php`: verification.expire agregado  
- `config/session.php`: encrypt default true  
- `app/Http/Middleware/StaffRoleMiddleware.php`: acepta roles separados por coma  
- `app/Http/Middleware/SecureHeadersMiddleware.php`: CSP + HSTS + XFO + XCTO + RP + PP  
- `bootstrap/app.php`: SecureHeadersMiddleware, AuthServiceProvider, API exception handlers preppended  
- `app/Providers/AuthServiceProvider.php`: nuevo, policies + Gate::before + manage-staff  
- `app/Providers/AppServiceProvider.php`: limpio, sin policies ni Gate  
- `app/Policies/StaffUserPolicy.php`: nuevo, viewAny/create/view/update/delete con ownership  
- `app/Http/Controllers/StaffUserController.php`: authorize() en todos los métodos  
- `app/Http/Controllers/ProfileController.php`: export() método de portabilidad  
- `app/Http/Requests/Auth/LoginRequest.php`: simplificado, sin ensureIsNotRateLimited duplicado  
- `app/Console/Commands/PurgeSoftDeletedRecords.php`: nuevo, purge + dry-run  
- `routes/console.php`: schedule purge diario a 03:00  
- `routes/web.php`: profile.export route, login-producer throttle  
- `app/Models/User.php`: SoftDeletes trait agregado  
- `database/migrations/*_add_soft_deletes_to_users_table.php`: nueva migración  
- `tests/Feature/ProfileTest.php`: assertSoftDeleted en vez de assertNull
