# 📋 Guía de Desarrollo y Testing

## 🧪 Testing Manual

### Test de Autenticación

1. **Registro nuevo usuario**
   - [ ] Ir a /registro.php
   - [ ] Ingresar nombre válido
   - [ ] Ingresar email válido
   - [ ] Ingresar contraseña con mínimo 8 caracteres
   - [ ] Confirmar contraseña
   - [ ] Click en "Registrarse"
   - [ ] Verificar redirección a login

2. **Login exitoso**
   - [ ] Ir a /login.php
   - [ ] Ingresar email válido
   - [ ] Ingresar contraseña correcta
   - [ ] Verificar redirección a dashboard

3. **Login fallido**
   - [ ] Ingresar email incorrecto
   - [ ] Verificar mensaje de error
   - [ ] Ingresar contraseña incorrecta
   - [ ] Verificar mensaje de error

### Test de Gestión de Tareas

4. **Crear tarea**
   - [ ] En dashboard, ingresar título
   - [ ] (Opcional) Ingresar descripción
   - [ ] Seleccionar prioridad
   - [ ] (Opcional) Seleccionar fecha vencimiento
   - [ ] Click "Crear"
   - [ ] Verificar que aparece en lista

5. **Filtrar tareas**
   - [ ] Click en "Pendientes"
   - [ ] Verificar que solo muestra pendientes
   - [ ] Click en "En Progreso"
   - [ ] Verificar que solo muestra en progreso
   - [ ] Click en "Completadas"
   - [ ] Verificar que solo muestra completadas

6. **Actualizar tarea**
   - [ ] Click en "En Progreso" de una tarea pendiente
   - [ ] Verificar cambio de estado
   - [ ] Click en "Completar"
   - [ ] Verificar que tarea aparece como completada

7. **Eliminar tarea**
   - [ ] Click en "Eliminar"
   - [ ] Confirmar eliminación
   - [ ] Verificar que tarea desaparece

### Test de Seguridad

8. **CSRF Protection**
   - [ ] Abrir DevTools
   - [ ] Network tab
   - [ ] Enviar formulario
   - [ ] Verificar que CSRF token se envía

9. **XSS Prevention**
   - [ ] Intentar ingresar `<script>alert('xss')</script>` en título
   - [ ] Verificar que no ejecuta script
   - [ ] Verificar que aparece como texto

10. **SQL Injection**
    - [ ] Intentar ingresar `'; DROP TABLE tareas; --` en búsqueda
    - [ ] Verificar que no ejecuta query
    - [ ] Verificar que búsqueda funciona normalmente

### Test de APIs

11. **API Clima**
    - [ ] Verificar que widget muestra temperatura
    - [ ] Verificar que se actualiza al recargar
    - [ ] Intentar con diferentes ciudades

12. **Timeout de Sesión**
    - [ ] Esperar SESSION_TIMEOUT (3600s)
    - [ ] Intentar acceder a dashboard
    - [ ] Verificar que redirige a login

## 🐛 Debugging

### En PHP

```php
// Modo debug
define('DEBUG_MODE', true);

// Logging
log_activity('ACCION', 'Detalles');

// Error trace
try {
    // código
} catch (Exception $e) {
    if (DEBUG_MODE) {
        var_dump($e);
    }
}
```

### En JavaScript

```javascript
// Console logging
console.log('Debug:', variable);

// Debugger
debugger;

// Network monitoring
// F12 > Network tab
```

### En MySQL

```sql
-- Ver queries lentas
SET GLOBAL log_queries_not_using_indexes = ON;

-- Ver actividades recientes
SELECT * FROM actividades_auditoria ORDER BY fecha_creacion DESC LIMIT 10;
```

## 📊 Métricas de Código

### Complejidad
- [ ] Funciones con < 25 líneas
- [ ] Métodos con < 3 parámetros
- [ ] Máximo 3 niveles de anidamiento

### Cobertura
- [ ] Tests para funciones críticas
- [ ] Coverage > 80%

### Performance
- [ ] Queries < 100ms
- [ ] Load page < 2s
- [ ] API response < 500ms

## 🚀 Deployment Checklist

Antes de llevar a producción:

- [ ] Todos los tests pasando
- [ ] DEBUG_MODE = false
- [ ] JWT_SECRET único y fuerte
- [ ] Base de datos respaldada
- [ ] HTTPS habilitado
- [ ] Headers de seguridad configurados
- [ ] Rate limiting activo
- [ ] Logs rotativos configurados
- [ ] Backups automáticos
- [ ] Monitoreo activo

## 📝 Notas de Desarrollo

### Convenciones de Código

```php
// Nombres de clases: PascalCase
class AuthService { }

// Nombres de métodos: camelCase
public function obtenerUsuario() { }

// Nombres de variables: snake_case
$usuario_id = 1;
$fecha_creacion = date('Y-m-d H:i:s');

// Constantes: UPPER_SNAKE_CASE
define('DB_HOST', 'localhost');

// Comentarios
/**
 * Descripción de método
 * @param string $email Email del usuario
 * @return bool
 */
```

### Variables Globales

```javascript
// JavaScript
const app = new TareasApp();
const auth = new AuthManager();

// Evitar
window.global = value;  // ❌
```

### Límites de Recursos

- Max upload: 10MB
- Max JSON: 1MB
- Timeout: 30s
- Rate limit: 100 req/hora

