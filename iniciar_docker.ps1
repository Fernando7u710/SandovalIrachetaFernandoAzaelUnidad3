#!/usr/bin/env pwsh
# Script para ejecutar la aplicación en Docker automáticamente
# Uso: .\iniciar_docker.ps1

Write-Host "╔════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║     Gestor de Tareas - Iniciador Docker          ║" -ForegroundColor Cyan
Write-Host "╚════════════════════════════════════════════════════╝" -ForegroundColor Cyan
Write-Host ""

# Función para mostrar mensajes de estado
function Write-Status {
    param([string]$message, [string]$status)
    $color = @{"success" = "Green"; "error" = "Red"; "info" = "Yellow"; "waiting" = "Cyan"}
    Write-Host "[$(Get-Date -Format 'HH:mm:ss')]" -NoNewline -ForegroundColor Gray
    Write-Host " $message" -NoNewline
    if ($status) {
        Write-Host " [$status]" -ForegroundColor $color[$status]
    } else {
        Write-Host ""
    }
}

# Paso 1: Verificar si Docker está instalado
Write-Status "Verificando instalación de Docker..." "waiting"
try {
    $dockerVersion = & docker --version
    Write-Status "Docker detectado: $dockerVersion" "success"
} catch {
    Write-Status "ERROR: Docker no está instalado" "error"
    Write-Host ""
    Write-Host "Por favor instala Docker Desktop desde: https://www.docker.com/products/docker-desktop" -ForegroundColor Red
    exit 1
}

# Paso 2: Verificar si Docker daemon está corriendo
Write-Status "Verificando daemon de Docker..." "waiting"
$dockerRunning = $false
try {
    & docker ps | Out-Null
    $dockerRunning = $true
    Write-Status "Docker daemon está corriendo" "success"
} catch {
    Write-Status "Docker daemon NO está corriendo - Iniciando..." "waiting"
    
    # Intentar iniciar Docker Desktop
    $dockerPath = "C:\Program Files\Docker\Docker\Docker.exe"
    if (Test-Path $dockerPath) {
        Write-Host "  → Iniciando Docker Desktop..." -ForegroundColor Yellow
        & $dockerPath
        Write-Status "Esperando a que Docker se inicie (45 segundos)..." "waiting"
        Start-Sleep -Seconds 45
        
        # Verificar nuevamente
        try {
            & docker ps | Out-Null
            $dockerRunning = $true
            Write-Status "Docker daemon ahora está corriendo" "success"
        } catch {
            Write-Status "ERROR: No se pudo conectar con Docker" "error"
            Write-Host ""
            Write-Host "Por favor:" -ForegroundColor Red
            Write-Host "  1. Abre Docker Desktop manualmente desde el menú Inicio" -ForegroundColor Red
            Write-Host "  2. Espera 45 segundos a que se inicie" -ForegroundColor Red
            Write-Host "  3. Ejecuta nuevamente este script" -ForegroundColor Red
            exit 1
        }
    } else {
        Write-Status "ERROR: Docker Desktop no encontrado en la ruta esperada" "error"
        exit 1
    }
}

# Paso 3: Navegar al directorio del proyecto
Write-Status "Verificando directorio del proyecto..." "waiting"
$projectDir = Split-Path -Parent $MyInvocation.MyCommand.Path
if (-not (Test-Path "$projectDir\docker-compose.yml")) {
    Write-Status "ERROR: docker-compose.yml no encontrado" "error"
    exit 1
}
Write-Status "Directorio del proyecto: $projectDir" "success"

# Paso 4: Detener contenedores anteriores si existen
Write-Status "Deteniendo contenedores anteriores (si existen)..." "waiting"
try {
    & docker-compose -f "$projectDir\docker-compose.yml" down 2>$null
    Write-Status "Contenedores anteriores detenidos" "success"
} catch {
    Write-Status "No hay contenedores anteriores para detener" "info"
}

# Paso 5: Iniciar contenedores
Write-Host ""
Write-Status "Iniciando contenedores Docker..." "waiting"
Write-Host ""

cd $projectDir
try {
    & docker-compose up -d
    Write-Status "Contenedores iniciados" "success"
} catch {
    Write-Status "ERROR: No se pudieron iniciar los contenedores" "error"
    Write-Host "Error: $_" -ForegroundColor Red
    exit 1
}

# Paso 6: Esperar a que MySQL esté listo
Write-Host ""
Write-Status "Esperando a que MySQL se inicie completamente..." "waiting"
Start-Sleep -Seconds 10

$maxAttempts = 30
$attempt = 0
$mysqlReady = $false

while ($attempt -lt $maxAttempts -and -not $mysqlReady) {
    try {
        $status = & docker-compose ps mysql 2>$null | Select-String "healthy"
        if ($status) {
            $mysqlReady = $true
            Write-Status "MySQL está listo" "success"
        } else {
            $attempt++
            Write-Host "  → Intento $attempt/$maxAttempts..." -ForegroundColor Gray
            Start-Sleep -Seconds 2
        }
    } catch {
        $attempt++
        Start-Sleep -Seconds 2
    }
}

if (-not $mysqlReady) {
    Write-Status "ADVERTENCIA: MySQL puede no estar completamente listo" "error"
    Write-Host "  → Continuando de todas formas..." -ForegroundColor Yellow
}

# Paso 7: Mostrar estado de los contenedores
Write-Host ""
Write-Status "Estado de los contenedores:" "info"
Write-Host ""
& docker-compose ps | Format-Table -AutoSize

# Paso 8: Información de acceso
Write-Host ""
Write-Host "╔════════════════════════════════════════════════════╗" -ForegroundColor Green
Write-Host "║          ✓ Aplicación lista para usar             ║" -ForegroundColor Green
Write-Host "╚════════════════════════════════════════════════════╝" -ForegroundColor Green
Write-Host ""

Write-Host "📱 ACCESO A LA APLICACIÓN:" -ForegroundColor Cyan
Write-Host ""
Write-Host "  🌐 Aplicación Principal:" -ForegroundColor Yellow
Write-Host "     URL: " -NoNewline; Write-Host "http://localhost/login.php" -ForegroundColor Green
Write-Host "     Usuario demo: demo@example.com" -ForegroundColor Gray
Write-Host "     Contraseña demo: DemoPassword123!" -ForegroundColor Gray
Write-Host ""

Write-Host "  📊 phpMyAdmin (Base de datos):" -ForegroundColor Yellow
Write-Host "     URL: " -NoNewline; Write-Host "http://localhost:8081" -ForegroundColor Green
Write-Host "     Usuario: usuario" -ForegroundColor Gray
Write-Host "     Contraseña: contraseña123" -ForegroundColor Gray
Write-Host ""

Write-Host "  🏥 API Health Check:" -ForegroundColor Yellow
Write-Host "     URL: " -NoNewline; Write-Host "http://localhost/api.php?action=health" -ForegroundColor Green
Write-Host ""

Write-Host "📝 COMANDOS ÚTILES:" -ForegroundColor Cyan
Write-Host ""
Write-Host "  Ver logs:" -ForegroundColor Yellow
Write-Host "    docker-compose logs -f php" -ForegroundColor Gray
Write-Host ""
Write-Host "  Detener contenedores:" -ForegroundColor Yellow
Write-Host "    docker-compose down" -ForegroundColor Gray
Write-Host ""
Write-Host "  Reiniciar servicios:" -ForegroundColor Yellow
Write-Host "    docker-compose restart" -ForegroundColor Gray
Write-Host ""

Write-Host "📖 DOCUMENTACIÓN:" -ForegroundColor Cyan
Write-Host ""
Write-Host "  • EJECUTAR_EN_DOCKER.md - Guía completa de Docker" -ForegroundColor Gray
Write-Host "  • README.md - Descripción general del proyecto" -ForegroundColor Gray
Write-Host "  • DEVELOPMENT.md - Guía de desarrollo" -ForegroundColor Gray
Write-Host ""

Write-Host "Presiona cualquier tecla para salir de este script..." -ForegroundColor Yellow
$null = Read-Host
