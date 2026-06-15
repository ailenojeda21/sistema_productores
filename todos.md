# Security Audit Checklist — Sistema Productores

> Auditoría completa: junio 2026. Basada en ISO 27001 / 27002 / 27701 / 27017 / 27018.
> Seis vectores auditados: autenticación, autorización, validación/datos, sesión/cookies, PII/privacidad, dependencias/infra.

## Leyenda

| Columna | Significado |
|---|---|
| Área | Módulo afectado (Auth, Authz, Data, Session, PII, Deps, Infra) |
| ISO | Norma relacionada |
| Asignado | Responsable sugerido |
| Estado | `ok` → corregido / `pendiente` → sin resolver / `manual` → requiere acción fuera de código |

---

## 🔴 Críticos — acción inmediata

| # | Hallazgo | Área | ISO | Asignado | Estado |
|---|---|---|---|---|---|
| C1 | `APP_DEBUG=true` en `.env` — expone stack traces con credenciales en producción | Infra | 27002 A.14.2.1 | DevOps | manual |
| C2 | `APP_ENV=local` en `.env` — debe ser `production` en producción | Infra | 27002 A.14.2.1 | DevOps | manual |
| C3 | `APP_KEY` nunca rotada — ejecutar `php artisan key:generate` | Infra | 27002 A.10.1 | DevOps | manual |
| C4 | Dockerfile usa `php artisan serve` (dev server) — no apto para producción | Infra | 27017 A.17.1 | DevOps | pendiente |

---

## 🟠 Altos

| # | Hallazgo | Área | ISO | Asignado | Estado |
|---|---|---|---|---|---|
| A1 | Sin logging de intentos de login — no se registran accesos fallidos ni exitosos | Auth | 27001 A.12.4 / 27002 12.4.1 | Developer | ok |
| A2 | Spatie `HasRoles` es código muerto — roles existen en BD pero nunca se verifican | Authz | 27002 A.9.1.2 | Developer | ok |
| A3 | Staff no puede resetear su contraseña — `staff_users` password broker definido pero sin rutas | Auth | 27002 A.9.4.2 | Developer | pendiente |
| A4 | Export endpoint (`GET /staff/producers/export`) accesible a auditores (no solo admin) | PII | 27701 PII.6 / 27002 A.9.2.3 | Developer | ok |
| A5 | `$request->all()` loggeado en `MaquinariaController@update:177` — posible PII en logs | Data | 27002 A.12.4.2 | Developer | ok |
| A6 | esbuild CVE (5 high) — RCE vía `NPM_CONFIG_REGISTRY`; requiere upgrade vite v6→v8 | Deps | 27002 A.8.8 | Developer | ok |

---

## 🟡 Medios

| # | Hallazgo | Área | ISO | Asignado | Estado |
|---|---|---|---|---|---|
| M1 | Sin Content-Security-Policy header | Session | 27002 A.13.1.1 | Developer | pendiente |
| M2 | Sin HSTS, X-Frame-Options, X-Content-Type-Options | Session | 27002 A.13.1.1 | Developer | pendiente |
| M3 | `SESSION_ENCRYPT=false` — datos de sesión en texto plano en BD | Session | 27002 A.10.1 | Developer | pendiente |
| M4 | `SESSION_SECURE_COOKIE` no seteado explícitamente | Session | 27002 A.10.1 | DevOps | manual |
| M5 | No existe `AuthServiceProvider` — policies en `AppServiceProvider` | Authz | 27002 A.14.2.1 | Developer | pendiente |
| M6 | No existe `StaffUserPolicy` — `StaffUserController` sin `authorize()` | Authz | 27002 A.9.1.2 | Developer | pendiente |
| M7 | `$hidden` incompleto en `User` — faltan `dni`, `telefono`, `direccion`, `email` | PII | 27701 PII.1 / 27018 PII.6 | Developer | pendiente |
| M8 | Login staff revela "Usuario inactivo" — confirma existencia del email (info disclosure) | Auth | 27002 A.9.4.2 | Developer | pendiente |
| M9 | Auditores y admin ven mismos datos PII en panel staff | PII | 27701 PII.1 | Developer | pendiente |
| M10 | Log level `debug` — loguea consultas SQL y datos; subir a `warning` | Infra | 27002 A.12.4.1 | DevOps | manual |
| M11 | Password min: 6 (productores) vs 8 (staff) — inconsistencia | Auth | 27002 A.9.2.1 | Developer | pendiente |
| M12 | No hay endpoint de portabilidad (`GET /api/profile/export`) | PII | 27701 PII.6 | Developer | pendiente |
| M13 | Sanctum token prefix vacío — tokens sin identificar si leaked | Auth | 27002 A.8.13 | Developer | pendiente |
| M14 | Token expiration 24h — considerar reducirlo | Auth | 27002 A.9.1.2 | Developer | pendiente |
| M15 | No hay `config/hashing.php` — todo por defecto del framework | Infra | 27002 A.8.13 | Developer | pendiente |
| M16 | No hay purge job para soft-deletes de `StaffUser` | PII | 27701 PII.7 | Developer | pendiente |

---

## 🔵 Bajos

| # | Hallazgo | Área | ISO | Asignado | Estado |
|---|---|---|---|---|---|
| B1 | `auth.verification.expire` no definido en config — cae a default 60 min | Auth | 27002 A.14.2.1 | Developer | pendiente |
| B2 | Double rate limiting en login productor — ruta + LoginRequest interno | Auth | 27002 A.12.1.1 | Developer | pendiente |
| B3 | `StaffRoleMiddleware` solo soporta 1 rol — no permite múltiples | Authz | 27002 A.9.2.3 | Developer | pendiente |
| B4 | No hay manejo global de excepciones para API — solo handler para 403 | Data | 27002 A.16.1 | Developer | pendiente |
| B5 | IDOR entre admins staff — admin A puede modificar admin B sin ownership check | Authz | 27002 A.9.1.2 | Developer | pendiente |
| B6 | OpenStreetMap recibe coordenadas de fincas vía CDN tiles | PII | 27018 PII.4 | Developer | pendiente |
| B7 | Form Request infrautilizado — solo 1 (`LoginRequest`) | Data | 27002 A.14.2.1 | Developer | pendiente |
| B8 | Mailtrap credenciales en `.env` — rotar si `.env` se filtra | Infra | 27002 A.8.13 | DevOps | manual |
| B9 | `User` hard-delete — sin SoftDeletes ni anonimización | PII | 27701 PII.7 | Developer | pendiente |
| B10 | Sin confirmación de logout en productores — AuthenticatedLayout dispara inmediato | Session | 27002 A.9.4.2 | Developer | ok |

---

## ✅ Pas (controles correctos)

| # | Control | Área | ISO |
|---|---|---|---|
| P1 | Todos los modelos tienen `$fillable` definido | Data | 27002 A.14.2.1 |
| P2 | SQL injection: 0 vectores — `whereRaw()` usa parámetros vinculados | Data | 27002 A.14.2.5 |
| P3 | XSS: 0 vectores — todo output Blade usa `{{ }}`, export usa `htmlspecialchars()` | Data | 27002 A.14.2.1 |
| P4 | CSRF: 0 excepciones — todas las rutas web protegidas | Session | 27002 A.13.1.1 |
| P5 | Session fixation: todos los login/logout regeneran sesión + token | Auth | 27002 A.9.4.2 |
| P6 | Rate limiting: 5 limitadores configurados (login, register, forgot-password) | Auth | 27002 A.12.1.1 |
| P7 | HTTPS forzado en producción vía `URL::forceScheme('https')` | Infra | 27002 A.13.1.1 |
| P8 | Cookies: HttpOnly=true, SameSite=lax | Session | 27002 A.13.1.1 |
| P9 | `.env` nunca commiteado a git | Infra | 27002 A.8.13 |
| P10 | `composer.lock` y `package-lock.json` commiteados | Deps | 27002 A.8.8 |
| P11 | bcrypt rounds=12 | Auth | 27002 A.8.13 |
| P12 | Ownership checks en todas las policies (5/5) | Authz | 27002 A.9.1.2 |
| P13 | StaffRoleMiddleware correcto (checks staff + staff-api guards) | Authz | 27002 A.9.2.3 |
| P14 | SoftDeletes en StaffUser | PII | 27701 PII.7 |
| P15 | `password` en `$hidden` de ambos modelos User y StaffUser | PII | 27701 PII.1 |
| P16 | Guard separation: 3 guards (web, staff, staff-api) con modelos distintos | Auth | 27002 A.6.1.2 |
| P17 | File upload validado (mime pdf, max 10MB) | Data | 27002 A.14.2.1 |
| P18 | Email verification con signed URLs + rate limiting | Auth | 27002 A.9.4.2 |
| P19 | No hay `eval()`, `unserialize()`, `exec()` en el código | Data | 27002 A.14.2.1 |
| P20 | Rate limiting en forgot-password (3/60min) | Auth | 27002 A.12.1.1 |

---

## Progreso

| Severidad | Total | Resueltos | Pendientes | Manuales |
|---|---|---|---|---|
| 🔴 Críticos | 4 | 0 | 1 | 3 |
| 🟠 Altos | 6 | 5 | 1 | 0 |
| 🟡 Medios | 16 | 0 | 15 | 1 |
| 🔵 Bajos | 10 | 1 | 8 | 1 |
| ✅ Pas | 20 | 20 | 0 | 0 |
| **Total** | **56** | **26** | **25** | **5** |
