# 📋 GESTOR DE TAREAS WEB
## Sistema de Gestión Empresarial con Seguridad Avanzada

---

### PORTADA

**INSTITUCIÓN**: Escuela Superior de Informática

**MATERIA**: SABER HACER - Unidad 3

**ESTUDIANTE**: Fernando Sandoval Iracheta

**DOCENTE**: [Nombre del Docente]

**FECHA**: 1 de Diciembre de 2025

**TEMA**: Aplicación Web de Gestión de Tareas con Web Services y Seguridad Empresarial

**REPOSITORIO**: https://github.com/Fernando7u710/SandovalIrachetaFernandoAzaelUnidad3

---

## TABLA DE CONTENIDOS

1. Descripción General
2. Funcionamiento del Sitio Web
3. Mecanismos de Seguridad Empleados
4. Web Services de Terceros
5. Web Services Propios
6. Conclusiones
7. Anexos

---

## 1. DESCRIPCIÓN GENERAL

### 1.1 Objetivo del Proyecto

Desarrollar una aplicación web profesional de gestión de tareas que implemente:
- ✅ Autenticación y autorización segura
- ✅ Web Services propios (API REST)
- ✅ Integración con Web Services de terceros
- ✅ Patrones de diseño empresariales
- ✅ Metodología ágil
- ✅ Arquitectura escalable

### 1.2 Tecnologías Utilizadas

| Componente | Tecnología |
|-----------|-----------|
| Backend | PHP 8.1 con Apache 2.4 |
| Base de Datos | MySQL 8.0 |
| Frontend | HTML5, CSS3, JavaScript ES6+ |
| Contenedorización | Docker & Docker Compose |
| API Propias | REST JSON |
| Seguridad | bcrypt, JWT, CSRF, Sessions |

### 1.3 Características Principales

- ✅ Sistema completo de autenticación
- ✅ Gestión CRUD de tareas
- ✅ Dashboard responsivo
- ✅ Tema oscuro/claro
- ✅ Validación de seguridad
- ✅ Auditoría de actividades
- ✅ Integración con APIs externas
- ✅ Optimización de rendimiento

---

## 2. FUNCIONAMIENTO DEL SITIO WEB

### 2.1 Flujo de Uso

#### Paso 1: Acceso a la Aplicación
- URL: `http://localhost`
- El usuario accede a la pantalla de login

#### Paso 2: Autenticación
- Email: `demo@example.com`
- Contraseña: `demo123456`
- Al hacer clic en "Iniciar Sesión", se autentica al usuario

**Pantalla de Login:**
- Formulario con validación client-side
- Campo email con validación
- Campo contraseña con requisitos de seguridad
- Botón de login y enlace a registro

#### Paso 3: Acceso al Dashboard
Después de autenticarse exitosamente, el usuario accede al dashboard donde puede:

**a) Ver Tareas Existentes**
- Lista de todas las tareas del usuario
- Información: título, descripción, estado, prioridad, fecha vencimiento
- Estados: Pendiente, En Progreso, Completada

**b) Crear Nuevas Tareas**
- Formulario con campos:
  - Título (requerido)
  - Descripción (opcional)
  - Prioridad: Alta, Media, Baja
  - Fecha de vencimiento
- Validación en cliente y servidor

**c) Filtrar Tareas**
- Todas las tareas
- Tareas pendientes
- Tareas en progreso
- Tareas completadas

**d) Actualizar Tareas**
- Cambiar estado a "En Progreso"
- Cambiar estado a "Completada"
- Actualización en tiempo real

**e) Eliminar Tareas**
- Confirmación de seguridad antes de eliminar
- Eliminación permanente de base de datos

**f) Ver Estadísticas**
- Total de tareas
- Tareas por estado
- Indicadores en tiempo real

**g) Ver Información del Clima**
- Temperatura actual
- Velocidad del viento
- Datos del servicio OpenWeather

### 2.2 Interfaz Visual

**Componentes Principales:**

1. **Barra Superior**
   - Logo de la aplicación
   - Botón de logout
   - Toggle de tema oscuro/claro

2. **Navegación Lateral**
   - Filtros de tareas
   - Estadísticas
   - Widget de clima

3. **Área Principal**
   - Formulario de crear tarea
   - Lista de tareas filtradas
   - Tarjetas de tarea con acciones

4. **Diseño Responsivo**
   - Funciona en desktop
   - Funciona en tablets
   - Funciona en móviles

---

## 3. MECANISMOS DE SEGURIDAD EMPLEADOS

### 3.1 Autenticación

#### 3.1.1 Hash de Contraseñas con bcrypt

**Implementación:**
```php
// En AuthService.php
$hash = password_hash($password, PASSWORD_BCRYPT);
```

**Características:**
- Algoritmo: bcrypt (más seguro que SHA-256)
- Costo computacional: 10 iteraciones
- Sal incluida automáticamente
- Irreversible y único por contraseña

**Demostración en Código:**
- Archivo: `src/services/AuthService.php`
- Línea: Línea de hash
- Verificación: `password_verify($password, $hash)`

#### 3.1.2 Gestión de Sesiones

**Implementación:**
- Sesiones PHP con cookies seguras
- Timeout de sesión: 30 minutos
- Regeneración de ID de sesión
- Validación en cada solicitud

**Características:**
- Cookie PHPSESSID
- Almacenamiento seguro en servidor
- No se exponen datos sensibles
- Validación de timeout

**Código:**
```php
// En config/config.php
session_start();
$_SESSION['user_id'] = $usuario->getId();
$_SESSION['login_time'] = time();
```

#### 3.1.3 Validación de Credenciales

**Proceso:**
1. Usuario ingresa email y contraseña
2. Se busca usuario en base de datos por email
3. Se verifica contraseña contra hash almacenado
4. Si es correcta, se crea sesión
5. Si es incorrecta, se rechaza con mensaje genérico

**Seguridad:**
- No se expone si el email existe o no
- Protección contra enumeración de usuarios
- Logging de intentos fallidos

### 3.2 Autorización

#### 3.2.1 Middleware de Autenticación

**Implementación:**
```php
// En AuthMiddleware.php
public static function verificar() {
    if (!is_logged_in()) {
        // Retorna error 401
    }
}
```

**Características:**
- Verifica autenticación en cada endpoint
- Devuelve 401 si no está autenticado
- Redirige a login en web
- Retorna JSON en API

#### 3.2.2 Control de Acceso a Recursos

**Validación:**
- Usuario solo puede ver sus propias tareas
- Usuario solo puede modificar sus propias tareas
- Validación en servidor (no en cliente)

**Código:**
```php
// En TareaController.php
$usuario_id = getCurrentUserId();
// Solo retorna tareas donde usuario_id coincide
```

### 3.3 Protección Contra Ataques

#### 3.3.1 CSRF (Cross-Site Request Forgery)

**Mecanismo:**
- Tokens CSRF únicos por sesión
- Requeridos en formularios y AJAX
- Validación en servidor

**Implementación:**
```php
// Generar token
$csrf_token = generate_csrf_token();

// Validar token
verify_csrf_token($_POST['csrf_token']);
```

**Beneficio:**
- Previene solicitudes maliciosas desde otros sitios
- Token se regenera en cada sesión

#### 3.3.2 XSS (Cross-Site Scripting)

**Protecciones:**

1. **Escapado de HTML:**
```javascript
// En app.js
escaparHTML(texto) {
    const div = document.createElement('div');
    div.textContent = texto;
    return div.innerHTML;
}
```

2. **Content Security Policy:**
```php
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
```

3. **Validación de Input:**
- Todos los inputs se validan
- Caracteres especiales se escapan
- Scripts no se ejecutan en títulos/descripciones

#### 3.3.3 SQL Injection

**Protección mediante Prepared Statements:**
```php
// En models/Usuario.php
$stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
```

**Características:**
- Parámetros separados de consulta SQL
- Imposible inyectar código SQL
- Validación automática de tipos

#### 3.3.4 Validación y Sanitización

**Sanitización de Inputs:**
```php
// En SecurityService.php
public static function sanitizar($data, $type = 'text') {
    switch($type) {
        case 'email':
            return filter_var($data, FILTER_SANITIZE_EMAIL);
        case 'number':
            return intval($data);
        default:
            return htmlspecialchars($data, ENT_QUOTES);
    }
}
```

### 3.4 Seguridad de Comunicación

#### 3.4.1 Headers HTTP de Seguridad
```php
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
```

#### 3.4.2 HTTPS (en producción)
- SSL/TLS requerido
- Certificados válidos
- Redirección HTTP a HTTPS

### 3.5 Auditoría y Logging

**Registro de Actividades:**
```php
// En AuthService.php
$this->registrarActividad('LOGIN', 'usuarios', $usuario_id);
$this->registrarActividad('TAREA_CREAR', 'tareas', $tarea_id);
```

**Información Registrada:**
- Tipo de actividad
- Usuario responsable
- Fecha y hora
- IP de origen (si aplica)
- Resultado (éxito/error)

---

## 4. WEB SERVICES DE TERCEROS

### 4.1 OpenWeather API

#### 4.1.1 Descripción
Servicio que proporciona información meteorológica en tiempo real de cualquier ubicación del mundo.

#### 4.1.2 Implementación

**Endpoint Consumido:**
```
https://api.openweathermap.org/data/2.5/weather?q=Mexico%20City&appid=KEY&units=metric
```

**Parámetros:**
- `q`: Ciudad (Mexico City)
- `appid`: API key de OpenWeather
- `units`: Unidades (metric para Celsius)

**Respuesta JSON:**
```json
{
  "main": {
    "temp": 25.5,
    "humidity": 60,
    "pressure": 1013
  },
  "wind": {
    "speed": 3.2
  },
  "weather": [
    {
      "main": "Clouds",
      "description": "scattered clouds"
    }
  ]
}
```

#### 4.1.3 Código de Integración

**Archivo:** `src/services/ExternalAPIService.php`

```php
public static function obtenerClima() {
    $ciudad = 'Mexico City';
    $apikey = getenv('OPENWEATHER_KEY');
    $url = "https://api.openweathermap.org/data/2.5/weather?q={$ciudad}&appid={$apikey}&units=metric";
    
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    
    return [
        'temperatura' => $data['main']['temp'],
        'velocidad_viento' => $data['wind']['speed'],
        'descripcion' => $data['weather'][0]['main']
    ];
}
```

#### 4.1.4 Endpoint Propio que Consume

**URL:** `GET /api/external/clima-general`

**Respuesta:**
```json
{
  "success": true,
  "data": {
    "temperatura": 25.5,
    "velocidad_viento": 3.2,
    "descripcion": "Scattered clouds"
  },
  "message": "Clima obtenido",
  "timestamp": 1764652881
}
```

#### 4.1.5 Uso en Dashboard

**JavaScript:**
```javascript
async cargarClima() {
    const response = await fetch(`${this.baseUrl}/external/clima-general`, {
        credentials: 'include'
    });
    const data = await response.json();
    
    if (data.success) {
        document.getElementById('weatherWidget').innerHTML = `
            <span>🌡️ ${data.data.temperatura}°C</span>
            <span>💨 ${data.data.velocidad_viento} km/h</span>
        `;
    }
}
```

**Pantalla:**
- Widget en esquina superior derecha del dashboard
- Muestra temperatura en Celsius
- Muestra velocidad del viento en km/h
- Se actualiza al cargar la página

### 4.2 Open-Meteo API (Fallback)

#### 4.2.1 Descripción
Servicio alternativo gratuito sin requerimiento de API key para datos meteorológicos.

#### 4.2.2 Endpoint
```
https://api.open-meteo.com/v1/forecast?latitude=19.4326&longitude=-99.1332&current=temperature_2m,weather_code,wind_speed_10m
```

#### 4.2.3 Implementación
Se usa como fallback si OpenWeather API falla.

### 4.3 Nominatim API (Geocodificación)

#### 4.3.1 Descripción
Servicio de OpenStreetMap para convertir coordenadas a direcciones.

#### 4.3.2 Implementación Futura
Se preparó para integrar ubicación de usuario (no implementado aún en esta versión).

---

## 5. WEB SERVICES PROPIOS

### 5.1 Arquitectura de API

**Patrón:** REST con JSON

**Base URL:** `/api`

**Autenticación:** Sesiones PHP + Middleware

**Respuesta Estándar:**
```json
{
  "success": true,
  "data": {},
  "message": "Descripción",
  "timestamp": 1234567890
}
```

### 5.2 Endpoints de Autenticación

#### 5.2.1 POST /api/auth/login

**Propósito:** Autenticar usuario

**Parámetros:**
- `email` (string, requerido)
- `password` (string, requerido)

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "data": {
    "user_id": 3,
    "name": "Usuario Demo",
    "email": "demo@example.com",
    "role": "user"
  },
  "message": "Login exitoso"
}
```

**Respuesta Error (401):**
```json
{
  "success": false,
  "message": "Credenciales inválidas"
}
```

**Implementación:**
- Archivo: `src/controllers/AuthController.php`
- Método: `login()`
- Validación: Email y contraseña requeridos
- Seguridad: bcrypt password_verify

#### 5.2.2 POST /api/auth/registro

**Propósito:** Registrar nuevo usuario

**Parámetros:**
- `nombre` (string, requerido)
- `email` (string, requerido, único)
- `password` (string, requerido, mín 8 caracteres)
- `password_confirm` (string, requerido, debe coincidir)

**Validaciones:**
- Email único en base de datos
- Contraseña mínimo 8 caracteres
- Las contraseñas deben coincidir
- Email con formato válido

**Respuesta Exitosa (201):**
```json
{
  "success": true,
  "data": {
    "user_id": 4,
    "name": "Nuevo Usuario",
    "email": "nuevo@example.com"
  },
  "message": "Registro exitoso"
}
```

#### 5.2.3 POST /api/auth/logout

**Propósito:** Cerrar sesión del usuario

**Autenticación:** Requerida

**Respuesta:**
```json
{
  "success": true,
  "message": "Logout exitoso"
}
```

#### 5.2.4 GET /api/auth/me

**Propósito:** Obtener datos del usuario autenticado

**Autenticación:** Requerida

**Respuesta:**
```json
{
  "success": true,
  "data": {
    "user_id": 3,
    "name": "Usuario Demo",
    "email": "demo@example.com"
  }
}
```

### 5.3 Endpoints de Tareas

#### 5.3.1 GET /api/tareas/obtener

**Propósito:** Obtener todas las tareas del usuario

**Autenticación:** Requerida

**Parámetros (Query String Opcionales):**
- `estado` (filter: pendiente, en_progreso, completada)
- `prioridad` (filter: alta, media, baja)

**Respuesta:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "usuario_id": 3,
      "titulo": "Tarea de ejemplo 1",
      "descripcion": "Esta es tu primera tarea",
      "estado": "pendiente",
      "prioridad": "alta",
      "fecha_vencimiento": "2025-12-09",
      "fecha_creacion": "2025-12-02 05:04:05",
      "fecha_actualizacion": "2025-12-02 05:04:05"
    }
  ],
  "message": "Tareas obtenidas"
}
```

**Implementación:**
- Archivo: `src/controllers/TareaController.php`
- Método: `obtenerTodas()`
- Seguridad: Solo retorna tareas del usuario autenticado

#### 5.3.2 GET /api/tareas/obtener-una

**Propósito:** Obtener una tarea específica

**Autenticación:** Requerida

**Parámetros:**
- `id` (integer, requerido)

**Validación:**
- La tarea debe perteneccer al usuario autenticado

#### 5.3.3 POST /api/tareas/crear

**Propósito:** Crear nueva tarea

**Autenticación:** Requerida

**Parámetros:**
- `titulo` (string, requerido)
- `descripcion` (string, opcional)
- `prioridad` (string: alta, media, baja)
- `fecha_vencimiento` (date, opcional)
- `csrf_token` (string, requerido)

**Validaciones:**
- Título no puede estar vacío
- Prioridad debe ser válida
- Fecha no puede ser en el pasado
- CSRF token válido

**Respuesta (201):**
```json
{
  "success": true,
  "data": {
    "id": 5,
    "titulo": "Nueva tarea",
    "descripcion": "Descripción",
    "estado": "pendiente",
    "prioridad": "media",
    "fecha_vencimiento": "2025-12-10"
  },
  "message": "Tarea creada exitosamente"
}
```

#### 5.3.4 POST /api/tareas/actualizar

**Propósito:** Actualizar estado o datos de tarea

**Autenticación:** Requerida

**Parámetros:**
- `id` (integer, requerido)
- `estado` (string: pendiente, en_progreso, completada)
- `csrf_token` (string, requerido)

**Validaciones:**
- Tarea debe existir
- Tarea debe perteneccer al usuario
- Estado debe ser válido

**Respuesta (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "estado": "en_progreso"
  },
  "message": "Tarea actualizada"
}
```

#### 5.3.5 POST /api/tareas/eliminar

**Propósito:** Eliminar una tarea

**Autenticación:** Requerida

**Parámetros:**
- `id` (integer, requerido)
- `csrf_token` (string, requerido)

**Validaciones:**
- Tarea debe existir
- Tarea debe perteneccer al usuario
- Confirmación del usuario (client-side)

**Respuesta (200):**
```json
{
  "success": true,
  "message": "Tarea eliminada"
}
```

#### 5.3.6 GET /api/tareas/estadisticas

**Propósito:** Obtener estadísticas de tareas

**Autenticación:** Requerida

**Respuesta:**
```json
{
  "success": true,
  "data": [
    {
      "estado": "pendiente",
      "cantidad": 2
    },
    {
      "estado": "en_progreso",
      "cantidad": 1
    },
    {
      "estado": "completada",
      "cantidad": 1
    }
  ],
  "message": "Estadísticas obtenidas"
}
```

### 5.4 Flujo de Solicitudes API

**Proceso de una solicitud AJAX:**

```
1. Cliente (JavaScript en navegador)
   ↓
2. Fetch con credentials: 'include' (envía cookies)
   ↓
3. Servidor recibe solicitud
   ↓
4. session_start() carga sesión del usuario
   ↓
5. AuthMiddleware verifica si usuario está logueado
   ↓
6. Si OK → Procesa solicitud
   Si NO → Retorna 401 JSON
   ↓
7. Controlador procesa lógica
   ↓
8. Modelo realiza cambios en BD
   ↓
9. Respuesta JSON al cliente
   ↓
10. JavaScript procesa respuesta
```

### 5.5 Estructura de Base de Datos

**Tabla: usuarios**
```sql
CREATE TABLE usuarios (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  rol VARCHAR(50) DEFAULT 'user',
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fecha_ultimo_acceso TIMESTAMP
);
```

**Tabla: tareas**
```sql
CREATE TABLE tareas (
  id INT PRIMARY KEY AUTO_INCREMENT,
  usuario_id INT NOT NULL,
  titulo VARCHAR(255) NOT NULL,
  descripcion TEXT,
  estado ENUM('pendiente', 'en_progreso', 'completada'),
  prioridad ENUM('alta', 'media', 'baja'),
  fecha_vencimiento DATE,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);
```

---

## 6. CONCLUSIONES

### 6.1 Objetivos Logrados

✅ **Autenticación Segura**
- Sistema de login/registro con bcrypt
- Gestión de sesiones con timeout
- Middleware de autenticación

✅ **Web Services Propios**
- API REST completa con 6 endpoints
- Gestión CRUD de tareas
- Validación en servidor
- Respuestas consistentes en JSON

✅ **Web Services de Terceros**
- Integración OpenWeather API
- Fallback a Open-Meteo
- Preparación para Nominatim

✅ **Seguridad Empresarial**
- Protección CSRF
- Protección XSS
- Protección SQL Injection
- Validación y sanitización
- Auditoría de actividades
- Headers de seguridad

✅ **Patrones de Diseño**
- Patrón MVC
- Singleton (Base de Datos)
- Active Record (Modelos)
- Factory (Servicios)

✅ **Metodología Ágil**
- Iteraciones de desarrollo
- Pruebas continuadas
- Refactoring y mejoras
- Documentación

### 6.2 Tecnologías Empleadas

- **Backend**: PHP 8.1 con Apache 2.4
- **BD**: MySQL 8.0
- **Frontend**: HTML5, CSS3, JavaScript ES6+
- **DevOps**: Docker & Docker Compose
- **Control de Versiones**: Git & GitHub

### 6.3 Mejoras Futuras

- [ ] Implementar autenticación OAuth2
- [ ] Agregar notificaciones por email
- [ ] Sistema de roles y permisos
- [ ] Colaboración entre usuarios
- [ ] Exportar tareas a PDF/Excel
- [ ] App móvil nativa
- [ ] Integración con Slack/Teams
- [ ] Análisis de productividad

### 6.4 Repositorio

**URL**: https://github.com/Fernando7u710/SandovalIrachetaFernandoAzaelUnidad3

**Estado**: ✅ Funcional y en producción

**Acceso**: Público

**Documentación**: 
- README.md
- CHANGELOG.md
- DOCKER_GUIA.md
- Comentarios en código

---

## 7. ANEXOS

### 7.1 Instrucciones de Instalación

**Con Docker (Recomendado):**
```bash
git clone https://github.com/Fernando7u710/SandovalIrachetaFernandoAzaelUnidad3.git
cd SandovalIrachetaFernandoAzaelUnidad3
docker-compose up -d
```

**Acceso:**
- URL: `http://localhost`
- Email: `demo@example.com`
- Contraseña: `demo123456`

### 7.2 Archivos Principales

| Archivo | Propósito |
|---------|-----------|
| `api.php` | Enrutador principal de API |
| `login.php` | Página de login |
| `dashboard.php` | Panel principal |
| `src/controllers/AuthController.php` | Lógica de autenticación |
| `src/controllers/TareaController.php` | Lógica de tareas |
| `src/services/AuthService.php` | Servicio de autenticación |
| `src/services/ExternalAPIService.php` | Integración de APIs externas |
| `public/js/app.js` | Lógica del dashboard |
| `public/js/auth.js` | Lógica de autenticación JS |
| `docker-compose.yml` | Configuración de contenedores |

### 7.3 Credenciales de Prueba

**Usuario Demo:**
- Email: `demo@example.com`
- Contraseña: `demo123456`
- Rol: usuario

**Base de Datos (phpMyAdmin):**
- URL: `http://localhost:8080`
- Usuario: `root`
- Contraseña: (sin contraseña)

### 7.4 Código de Ejemplo

**Crear una tarea desde JavaScript:**
```javascript
const app = new TareasApp();
await app.crearTarea({
    titulo: 'Mi nueva tarea',
    descripcion: 'Descripción',
    prioridad: 'alta',
    fecha_vencimiento: '2025-12-10'
});
```

**Consumir API con curl:**
```bash
# Login
curl -X POST http://localhost/api/auth/login \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "email=demo@example.com&password=demo123456" \
  -c cookies.txt

# Obtener tareas
curl -X GET http://localhost/api/tareas/obtener \
  -H "Content-Type: application/json" \
  -b cookies.txt
```

---

**Fin del Documento**

Fecha de Generación: 1 de Diciembre de 2025
Estudiante: Fernando Sandoval Iracheta
Institución: Escuela Superior de Informática
