# 🐳 Ejecutar la Aplicación en Docker

## Requisitos Previos

✅ **Docker Desktop instalado** - Versión 29.0.1 o superior detectada en tu sistema

## Método 1: Docker Desktop (Recomendado - GUI)

### Paso 1: Iniciar Docker Desktop
1. Haz clic en el **menú Inicio de Windows**
2. Busca "Docker Desktop"
3. Haz clic para abrir la aplicación
4. **Espera 30-60 segundos** a que se inicie el daemon

### Paso 2: Verificar conexión a Docker
Abre PowerShell en el directorio del proyecto y ejecuta:
```powershell
docker ps
```
Deberías ver una salida similar a:
```
CONTAINER ID   IMAGE     COMMAND   CREATED   STATUS    PORTS     NAMES
```

### Paso 3: Iniciar los contenedores
```powershell
docker-compose up -d
```

Espera a que se complete la inicialización (aproximadamente 30-45 segundos)

### Paso 4: Verificar que los servicios estén corriendo
```powershell
docker-compose ps
```

Deberías ver 3 contenedores corriendo:
- `app_tareas_db` (MySQL)
- `app_tareas_web` (PHP/Apache)
- `phpmyadmin` (phpMyAdmin)

## Paso 5: Acceder a la Aplicación

### 🌐 Aplicación Principal
- **URL**: http://localhost/login.php
- **Usuario demo**: demo@example.com
- **Contraseña demo**: DemoPassword123!

### 📊 Panel de Administración (phpMyAdmin)
- **URL**: http://localhost:8081
- **Usuario**: usuario
- **Contraseña**: contraseña123
- **Base de datos**: app_tareas

### 🏥 Estado de Salud de la API
- **URL**: http://localhost/api.php?action=health
- Verifica que todos los servicios estén funcionando

## Método 2: Línea de Comandos (Sin GUI)

### Windows PowerShell
```powershell
# 1. Navega al directorio del proyecto
cd "c:\Users\fersa\OneDrive\Imágenes\Documentos\SABER HACER UNIDAD3"

# 2. Inicia Docker Desktop desde PowerShell
Start-Process "C:\Program Files\Docker\Docker\Docker.exe"

# 3. Espera 45 segundos
Start-Sleep -Seconds 45

# 4. Inicia los contenedores
docker-compose up -d

# 5. Verifica el estado
docker-compose ps
```

### Windows CMD (Símbolo del sistema)
```cmd
cd "c:\Users\fersa\OneDrive\Imágenes\Documentos\SABER HACER UNIDAD3"
docker-compose up -d
docker-compose ps
```

## Verificación Completa

Ejecuta este script para verificar que todo funciona:
```powershell
# Verificar Docker daemon
docker ps

# Listar contenedores corriendo
docker-compose ps

# Ver logs del servidor web
docker-compose logs php

# Ver logs de MySQL
docker-compose logs mysql

# Probar el endpoint de salud
Invoke-RestMethod -Uri "http://localhost/api.php?action=health" | ConvertTo-Json
```

## Detener los Contenedores

```powershell
docker-compose down
```

Esto detendrá y eliminará los contenedores, pero conservará los datos en MySQL.

## Detener y Eliminar Todo (Incluida la Base de Datos)

```powershell
docker-compose down -v
```

⚠️ **Advertencia**: Esto elimina la base de datos. Solo hazlo si quieres empezar de cero.

## Solucionar Problemas

### Problema: "Docker daemon is not running"
**Solución**:
1. Haz clic en el menú Inicio
2. Busca y abre "Docker Desktop"
3. Espera 45 segundos a que inicie
4. Intenta de nuevo

### Problema: "Port 80 already in use"
**Solución**:
```powershell
# Encuentra qué está usando el puerto 80
netstat -ano | findstr :80

# Detén los contenedores anteriores
docker-compose down

# O cambia el puerto en docker-compose.yml
# Cambia "- '80:80'" por "- '8080:80'"
```

### Problema: "Cannot connect to MySQL"
**Solución**:
```powershell
# Ver logs de MySQL
docker-compose logs mysql

# Reiniciar solo MySQL
docker-compose restart mysql

# Esperar a que se inicie completamente
Start-Sleep -Seconds 15
```

### Problema: "502 Bad Gateway"
**Solución**:
```powershell
# Ver logs del servidor PHP
docker-compose logs php

# Reiniciar PHP
docker-compose restart php
```

## Información de Conexión

### Dentro de los Contenedores:
- **Base de datos**: `mysql` (hostname)
- **Puerto MySQL**: 3306
- **Usuario**: usuario
- **Contraseña**: contraseña123
- **Base de datos**: app_tareas

### Desde tu Computadora:
- **Base de datos**: localhost o 127.0.0.1
- **Puerto MySQL**: 3306
- **Usuario**: usuario
- **Contraseña**: contraseña123
- **Base de datos**: app_tareas

## Características Disponibles

✅ Autenticación segura con JWT
✅ Gestión completa de tareas (CRUD)
✅ Sistema de etiquetas y categorización
✅ Estadísticas y filtrado avanzado
✅ Integración con APIs externas (Clima, Geocodificación)
✅ Panel de administración phpMyAdmin
✅ Base de datos MySQL completa
✅ Servidor Apache con mod_rewrite

## API Endpoints

```
POST   /api.php?action=auth/login
POST   /api.php?action=auth/registro
POST   /api.php?action=auth/logout
GET    /api.php?action=auth/me
GET    /api.php?action=tareas/all
GET    /api.php?action=tareas/get&id=1
POST   /api.php?action=tareas/create
PUT    /api.php?action=tareas/update&id=1
DELETE /api.php?action=tareas/delete&id=1
GET    /api.php?action=external/weather
GET    /api.php?action=health
```

## Documentación Adicional

- 📖 **README.md** - Descripción general del proyecto
- 🔧 **DEVELOPMENT.md** - Guía de desarrollo
- 📋 **GIT_WORKFLOW.md** - Flujo de trabajo con Git
- 🚀 **RESUMEN_EJECUTIVO.md** - Resumen ejecutivo

---

**¿Necesitas ayuda?** Consulta los archivos de documentación en el directorio raíz del proyecto.
