@echo off
REM ================================================
REM Instalador de Gestor de Tareas - Windows Local
REM ================================================

echo.
echo ╔════════════════════════════════════════════════════════════╗
echo ║     Instalador - Gestor de Tareas Web v1.0.0              ║
echo ╚════════════════════════════════════════════════════════════╝
echo.

REM Verificar si está en el directorio correcto
if not exist "config\config.php" (
    echo Error: Ejecuta este script desde la raiz del proyecto
    pause
    exit /b 1
)

echo [1] Verificando requisitos...
echo.

REM Verificar PHP
php -v >nul 2>&1
if errorlevel 1 (
    echo [ERROR] PHP no está instalado o no está en el PATH
    echo Descargalo desde: https://windows.php.net/download/
    pause
    exit /b 1
)
echo [OK] PHP está instalado

REM Verificar MySQL
mysql --version >nul 2>&1
if errorlevel 1 (
    echo [WARNING] MySQL no encontrado en PATH
    echo Pero puedes usar MariaDB o MySQL desde otro lugar
)

echo.
echo [2] Configurando base de datos...
echo.
echo Ingresa los datos de conexión a MySQL:
set /p DB_USER="Usuario (default: root): "
set /p DB_PASS="Contraseña: "
set /p DB_HOST="Host (default: localhost): "

if "%DB_USER%"=="" set DB_USER=root
if "%DB_HOST%"=="" set DB_HOST=localhost

echo.
echo [3] Importando base de datos...
if "%DB_PASS%"=="" (
    mysql -u %DB_USER% -h %DB_HOST% < config\init_database.sql
) else (
    mysql -u %DB_USER% -p%DB_PASS% -h %DB_HOST% < config\init_database.sql
)

if errorlevel 1 (
    echo [ERROR] Error al importar la base de datos
    pause
    exit /b 1
)
echo [OK] Base de datos importada

echo.
echo [4] Configurando variables de entorno...
(
    echo DB_HOST=%DB_HOST%
    echo DB_USER=%DB_USER%
    echo DB_NAME=app_tareas
    echo WEB_ROOT=http://localhost/tareas
) > .env

echo [OK] Archivo .env creado

echo.
echo [5] Creando carpetas necesarias...
if not exist "logs" mkdir logs
if not exist "uploads" mkdir uploads
echo [OK] Carpetas creadas

echo.
echo ╔════════════════════════════════════════════════════════════╗
echo ║            Instalación completada exitosamente            ║
echo ╚════════════════════════════════════════════════════════════╝
echo.
echo PRÓXIMOS PASOS:
echo.
echo 1. Copiar archivos a tu servidor web (C:\xampp\htdocs\ etc)
echo.
echo 2. Acceder a:
echo    http://localhost/tareas/login.php
echo.
echo 3. Credenciales:
echo    Email: admin@example.com
echo    Contraseña: Admin123456
echo.
echo 4. O usar PHP built-in server:
echo    php -S localhost:8000
echo.
pause
