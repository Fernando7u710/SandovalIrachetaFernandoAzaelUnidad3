# 📋 Gestor de Tareas Web - Aplicación Empresarial

## 📌 Descripción del Proyecto

Aplicación web desarrollada con **HTML, PHP y JavaScript** que implementa un sistema completo de gestión de tareas con seguridad empresarial, Web Services propios y de terceros, siguiendo metodología ágil y patrones de diseño.

### Características Principales

✅ **Autenticación Segura**
- Sistema de login y registro con hash bcrypt
- Gestión de sesiones con timeout
- Tokens CSRF para protección
- JWT para APIs

✅ **Seguridad**
- Validación y sanitización de entradas
- Protección contra XSS, SQL Injection
- Headers HTTP de seguridad
- Encriptación de datos sensibles
- Auditoría de actividades

✅ **Web Services Propios**
- API REST para gestión de tareas (CRUD)
- Endpoints de autenticación
- Estadísticas en tiempo real

✅ **Web Services de Terceros**
- OpenWeather API (clima)
- Nominatim API (geocodificación)
- Open-Meteo API (clima sin clave)

✅ **Interfaz Moderna**
- Dashboard responsive
- Tema oscuro/claro
- Diseño intuitivo y profesional
- Animaciones suaves

## 🏗️ Arquitectura y Patrones

### Patrones de Diseño Implementados

1. **Singleton** - Base de datos única instancia
2. **Active Record** - Modelos con lógica de persistencia
3. **MVC** - Separación de responsabilidades
4. **Factory** - Creación de servicios
5. **Observer** - Eventos del formulario

### Estructura del Proyecto

```
proyecto/
├── api.php                      # Enrutador principal de API
├── login.php                    # Página de login
├── registro.php                 # Página de registro
├── dashboard.php                # Panel principal
├── config/
│   ├── config.php              # Configuración global
│   ├── database.php            # Conexión BD (Singleton)
│   └── init_database.sql       # Script SQL
├── src/
│   ├── controllers/
│   │   ├── AuthController.php  # Control autenticación
│   │   └── TareaController.php # Control tareas
│   ├── models/
│   │   ├── Usuario.php         # Modelo usuario
│   │   └── Tarea.php           # Modelo tarea
│   ├── services/
│   │   ├── AuthService.php     # Servicio de auth
│   │   ├── ExternalAPIService.php  # APIs externas
│   │   └── SecurityService.php # Seguridad
│   └── middleware/
│       └── AuthMiddleware.php  # Middleware autenticación
└── public/
    ├── css/style.css           # Estilos
    └── js/
        ├── auth.js             # Lógica autenticación
        └── app.js              # Lógica principal
```

## 🚀 Instalación y Configuración

### Requisitos Previos

- PHP >= 7.4
- MySQL/MariaDB
- Apache con módulos: mod_rewrite, mod_headers
- Extensiones PHP: curl, pdo, json

### Pasos de Instalación

1. **Clonar o descargar el proyecto**
```bash
cd /ruta/del/proyecto
```

2. **Crear base de datos**
```bash
# Importar script SQL
mysql -u root < config/init_database.sql
```

3. **Configurar credenciales**
```php
# Editar config/config.php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'contraseña');
define('DB_NAME', 'app_tareas');
```

4. **Configurar APIs externas**
```php
# En config/config.php
define('WEATHER_API_KEY', 'tu_api_key_openweathermap');
```

5. **Crear carpeta de logs**
```bash
mkdir logs
chmod 755 logs
```

6. **Acceder a la aplicación**
```
http://localhost/tareas/login.php
```

### Credenciales de Prueba

```
Email: admin@example.com
Password: Admin123456

Email: usuario@example.com
Password: Usuario123456
```

## 📡 Web Services

### API Propia - Endpoints

#### Autenticación
```bash
# Login
POST /api.php/auth/login
Content-Type: application/x-www-form-urlencoded
email=user@example.com&password=123456

# Registro
POST /api.php/auth/registro
nombre=Juan&email=juan@example.com&password=123456&password_confirm=123456

# Logout
POST /api.php/auth/logout

# Obtener usuario actual
GET /api.php/auth/me
```

#### Tareas
```bash
# Obtener todas las tareas
GET /api.php/tareas/obtener

# Filtrar por estado
GET /api.php/tareas/obtener?estado=pendiente

# Obtener una tarea
GET /api.php/tareas/obtener-una?id=1

# Crear tarea
POST /api.php/tareas/crear
titulo=Mi tarea&descripcion=Descripción&prioridad=media&fecha_vencimiento=2025-12-31

# Actualizar tarea
POST /api.php/tareas/actualizar
id=1&estado=completada&titulo=Actualizado

# Eliminar tarea
POST /api.php/tareas/eliminar
id=1

# Obtener estadísticas
GET /api.php/tareas/estadisticas
```

### APIs Externas Integradas

#### OpenWeather API
```bash
GET /api.php/external/clima?ciudad=Bogotá
```

#### Nominatim (Geocodificación)
```bash
GET /api.php/external/geocodificar?direccion=Calle 50 Bogotá
```

#### Open-Meteo (Sin clave)
```bash
GET /api.php/external/clima-general
```

## 🔐 Mecanismos de Seguridad

### 1. Autenticación
- ✅ Hash bcrypt para contraseñas
- ✅ Sesiones seguras con timeout
- ✅ JWT tokens para APIs

### 2. Autorización
- ✅ Control de roles (admin/user)
- ✅ Verificación de propiedad de recursos
- ✅ Middleware de autenticación

### 3. Validación
- ✅ Validación de emails
- ✅ Validación de contraseñas (mínimo 8 caracteres)
- ✅ Sanitización de entradas (HTML, SQL)
- ✅ Validación de tipos de datos

### 4. Protección Web
- ✅ CSRF tokens en formularios
- ✅ XSS protection (escapado de HTML)
- ✅ SQL Injection prevention (prepared statements)
- ✅ Rate limiting por IP

### 5. Headers de Seguridad
- ✅ Content-Security-Policy
- ✅ X-Frame-Options: SAMEORIGIN
- ✅ X-Content-Type-Options: nosniff
- ✅ Strict-Transport-Security

### 6. Auditoría
- ✅ Log de actividades de usuarios
- ✅ Registro de cambios en datos
- ✅ Tracking de IPs
- ✅ User agent logging

## 📊 Base de Datos

### Tablas Principales

**usuarios**
- id: Identificador único
- nombre: Nombre del usuario
- email: Email único
- contrasena: Hash bcrypt
- rol: admin/user
- estado: activo/inactivo
- fecha_registro: Timestamp
- ultimo_acceso: Última conexión

**tareas**
- id: Identificador único
- usuario_id: FK a usuarios
- titulo: Título de la tarea
- descripcion: Descripción
- estado: pendiente/en_progreso/completada/cancelada
- prioridad: baja/media/alta
- fecha_vencimiento: Fecha límite
- fecha_creacion: Cuando se creó
- fecha_actualizacion: Última modificación

**etiquetas**
- id: Identificador único
- usuario_id: FK a usuarios
- nombre: Nombre de etiqueta
- color: Color hexadecimal

**actividades_auditoria**
- id: Identificador único
- usuario_id: FK a usuarios
- accion: Tipo de acción
- tabla: Tabla afectada
- cambios: JSON con cambios
- fecha_creacion: Cuándo ocurrió
- ip_address: IP del usuario

## 🧪 Pruebas

### Casos de Uso Principales

1. **Autenticación**
   - Registro de nuevo usuario ✓
   - Login con email/contraseña ✓
   - Logout seguro ✓
   - Timeout de sesión ✓

2. **Gestión de Tareas**
   - Crear tarea nueva ✓
   - Listar todas las tareas ✓
   - Filtrar por estado/prioridad ✓
   - Actualizar tarea ✓
   - Eliminar tarea ✓

3. **Seguridad**
   - Validación de contraseña débil ✓
   - Protección CSRF ✓
   - XSS protection ✓
   - Auditoría de actividades ✓

4. **APIs Externas**
   - Obtener clima actual ✓
   - Geocodificar dirección ✓
   - Caché de resultados ✓

## 🎓 Metodología Ágil

### Sprint Planning
- Historias de usuario definidas
- Criterios de aceptación claros
- Estimación en puntos

### Desarrollo
- Commits pequeños y frecuentes
- Revisión de código
- Testing continuo

### Documentación
- README actualizado
- Comentarios de código
- Ejemplos de uso

## 📋 Checklist de Requisitos

- ✅ Aplicación Web funcional
- ✅ HTML5 semántico
- ✅ PHP orientado a objetos
- ✅ JavaScript vanilla (sin dependencias)
- ✅ Mecanismos de seguridad avanzados
- ✅ Web Services propios (API REST)
- ✅ Web Services de terceros (OpenWeather, Nominatim)
- ✅ Base de datos relacional
- ✅ Autenticación y autorización
- ✅ Interfaz responsive
- ✅ Tema oscuro
- ✅ Auditoría y logging
- ✅ Validación y sanitización
- ✅ Patrones de diseño
- ✅ Documentación completa

## 🔄 Flujo de Trabajo

### Usuario Nuevo
1. Ir a `/registro.php`
2. Registrar cuenta (email, nombre, contraseña)
3. Sistema valida y crea usuario
4. Redirige a login

### Usuario Existente
1. Ir a `/login.php`
2. Ingresa credenciales
3. Sistema valida y crea sesión
4. Redirige a `/dashboard.php`

### Gestión de Tareas
1. En dashboard, crear nueva tarea
2. Completar formulario (título, descripción, prioridad, fecha)
3. Envía a API `/tareas/crear`
4. Tarea se guarda en BD
5. Lista se actualiza en tiempo real

## 🤝 Contribución

Para contribuir al proyecto:
1. Hacer fork del repositorio
2. Crear rama para la característica
3. Realizar cambios
4. Enviar pull request

## 📄 Licencia

Este proyecto es de código abierto bajo licencia MIT.

## 👨‍💼 Autor

**Desarrollo Académico**
- Unidad 3: Aplicación Web Empresarial
- Metodología Ágil
- Arquitectura y Patrones de Diseño

## 📞 Soporte

Para reportar bugs o sugerir mejoras, abrir un issue en el repositorio.

---

**Última actualización:** Diciembre 2025
**Versión:** 1.0.0
**Estado:** Producción

