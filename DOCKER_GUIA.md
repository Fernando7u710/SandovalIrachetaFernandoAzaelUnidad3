# 🚀 Ejecutar la Aplicación

## Con Docker (Recomendado)

### Requisitos
- Docker Desktop instalado
- Git
- Mínimo 2GB de RAM disponible

### Pasos

1. **Clonar el repositorio**
```bash
git clone https://github.com/Fernando7u710/SandovalIrachetaFernandoAzaelUnidad3.git
cd SandovalIrachetaFernandoAzaelUnidad3
```

2. **Iniciar los contenedores**
```bash
docker-compose up -d
```

3. **Esperar a que levante** (30-60 segundos)
```bash
docker-compose logs -f
```

4. **Acceder**
- 🌐 Aplicación: http://localhost
- 📊 phpMyAdmin: http://localhost:8081

### Credenciales de Prueba

**Usuario Demo:**
- Email: `demo@example.com`
- Contraseña: `demo123456`

**Base de Datos:**
- Usuario: `usuario`
- Contraseña: `contraseña123`
- DB: `tareas_db`

## Detener la Aplicación

```bash
docker-compose down
```

## Ver Logs

```bash
# Todos los servicios
docker-compose logs

# Solo PHP
docker-compose logs php

# Solo MySQL
docker-compose logs mysql

# Con seguimiento en vivo
docker-compose logs -f
```

## Resolver Problemas

### Puertos ya en uso
```bash
# Liberar puerto 80
docker-compose down
```

### Reiniciar servicios
```bash
docker-compose restart
```

### Reconstruir imágenes
```bash
docker-compose build --no-cache
docker-compose up -d
```

## Estructura de Carpetas

```
SandovalIrachetaFernandoAzaelUnidad3/
├── api.php                    # Enrutador de API REST
├── login.php                  # Página de login
├── registro.php               # Página de registro
├── dashboard.php              # Panel principal
├── docker-compose.yml         # Configuración Docker
├── Dockerfile                 # Imagen PHP/Apache
├── config/
│   ├── config.php            # Configuración global
│   └── database.php          # Conexión BD
├── src/
│   ├── controllers/          # Lógica de negocio
│   ├── models/              # Modelos de datos
│   ├── services/            # Servicios (Auth, API externas)
│   └── middleware/          # Middleware (Autenticación)
└── public/
    ├── css/style.css        # Estilos
    └── js/
        ├── auth.js          # Lógica de autenticación
        └── app.js           # Lógica del dashboard
```

## Funcionalidades Principales

✅ **Autenticación Segura**
- Login y registro con hash bcrypt
- Gestión de sesiones
- Protección CSRF

✅ **Gestión de Tareas**
- Crear, leer, actualizar, eliminar tareas
- Filtros por estado y prioridad
- Fechas de vencimiento
- Estadísticas en tiempo real

✅ **Seguridad**
- Validación de inputs
- Protección XSS
- Prepared statements (SQL injection protection)
- Auditoría de actividades

✅ **Integraciones Externas**
- OpenWeather API - Datos del clima
- Open-Meteo API - Pronóstico de tiempo
- Nominatim - Geocodificación

## Tecnologías

- **Backend**: PHP 8.1 + Apache 2.4
- **Base de Datos**: MySQL 8.0
- **Frontend**: HTML5 + CSS3 + JavaScript ES6+
- **DevOps**: Docker + Docker Compose
- **Patrones**: MVC, Singleton, Active Record, Factory

## Soporte

Para reportar issues, usar el sistema de issues de GitHub.

## Licencia

Este proyecto es de código abierto para propósitos educativos.
