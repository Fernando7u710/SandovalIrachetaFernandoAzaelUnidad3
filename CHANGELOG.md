# Changelog

## [Versión 1.0] - 2025-12-01

### ✅ Corregido

#### 🔐 Gestión de Sesiones
- **Problema**: Las cookies de sesión no se enviaban automáticamente en las solicitudes fetch
- **Solución**: Agregado `credentials: 'include'` a todos los fetch calls en:
  - `public/js/auth.js` (login, registro, logout, obtener usuario)
  - `public/js/app.js` (7 endpoints CRUD)
  - `login.php` (formulario de login)
  - `registro.php` (formulario de registro)
- **Impacto**: Las sesiones ahora persisten correctamente entre solicitudes

#### 🌐 Middleware de Autenticación
- **Problema**: El middleware redirigía a login.php incluso en solicitudes API
- **Solución**: Actualizado `AuthMiddleware::verificar()` para devolver JSON con status 401 en solicitudes API
- **Impacto**: Las solicitudes sin autenticación ahora reciben respuestas consistentes

#### ⚠️ Warnings PHP
- **Problema**: Warning en `src/models/Tarea.php:129` por `bind_param` requiriendo referencias
- **Solución**: Refactorizado `obtenerPorUsuario()` para pasar parámetros correctamente por referencia
- **Impacto**: Eliminados warnings en logs, responses más limpias

#### 🚨 Manejo de Errores
- **Problema**: `cargarTareas()` no validaba `response.ok` antes de parsear JSON
- **Solución**: Agregado validación `if (!response.ok)` antes de `response.json()`
- **Impacto**: Mejor detección de errores HTTP

### 📋 Cambios Técnicos

**Archivos Modificados:**
1. `public/js/auth.js` - Agregado credentials a 4 fetch calls
2. `public/js/app.js` - Agregado credentials a 7 fetch calls + validación de response
3. `login.php` - Agregado credentials al formulario
4. `registro.php` - Agregado credentials al formulario
5. `src/middleware/AuthMiddleware.php` - Mejorado manejo de errores API
6. `src/models/Tarea.php` - Refactorizado bind_param

### 🧪 Testing

**Validado:**
- ✅ Login exitoso con credentials correctas
- ✅ Las sesiones persisten entre solicitudes
- ✅ GET /api/tareas/obtener devuelve datos correctos
- ✅ POST /api/tareas/crear funciona
- ✅ Actualización y eliminación de tareas funcionan
- ✅ Sin warnings en los logs de PHP
- ✅ JSON responses limpias y consistentes

### 🔐 Seguridad

**Protecciones Mantenidas:**
- ✅ Hash bcrypt para contraseñas
- ✅ Validación CSRF tokens
- ✅ Sanitización de inputs
- ✅ Protección contra XSS
- ✅ Session timeout
- ✅ SQL prepared statements

### 📚 Documentación

**Archivos Documentados:**
- README.md - Actualizado con instrucciones Docker
- CHANGELOG.md - Este archivo
- Código fuente - Comentarios mejoradores

## [Versión 0.9] - Pre-release

Versión inicial del proyecto con:
- Sistema de autenticación con JWT
- CRUD de tareas
- Dashboard responsive
- APIs de terceros
- Seguridad empresarial
- Docker Compose setup
