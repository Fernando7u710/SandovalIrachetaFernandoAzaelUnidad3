#!/usr/bin/env bash
# ================================================
# 🚀 GUÍA RÁPIDA DE INICIO
# Gestor de Tareas Web - Aplicación Empresarial
# ================================================

echo "╔════════════════════════════════════════════════════════════╗"
echo "║     📋 GESTOR DE TAREAS - GUÍA RÁPIDA DE INICIO            ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""

# Colores
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Función para imprimir secciones
print_section() {
    echo ""
    echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
    echo -e "${GREEN}$1${NC}"
    echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
    echo ""
}

# Función para imprimir pasos
print_step() {
    echo -e "${YELLOW}→ $1${NC}"
}

# ================================================
# OPCIÓN 1: INSTALACIÓN LOCAL
# ================================================

install_local() {
    print_section "📦 INSTALACIÓN LOCAL"
    
    print_step "Paso 1: Verifica requisitos"
    echo "  • PHP >= 7.4"
    echo "  • MySQL >= 5.7"
    echo "  • Apache con mod_rewrite"
    
    print_step "Paso 2: Importa la base de datos"
    echo "  mysql -u root < config/init_database.sql"
    echo ""
    read -p "  ¿Deseas importar la BD ahora? (s/n): " confirm
    if [[ $confirm == "s" ]]; then
        mysql -u root < config/init_database.sql
        echo -e "${GREEN}✓ Base de datos importada${NC}"
    fi
    
    print_step "Paso 3: Configura credenciales"
    echo "  Edita: config/config.php"
    echo "  Actualiza: DB_HOST, DB_USER, DB_PASS"
    
    print_step "Paso 4: Accede a la aplicación"
    echo -e "${GREEN}  http://localhost/tareas/login.php${NC}"
    
    print_step "Paso 5: Credenciales de prueba"
    echo "  Email: admin@example.com"
    echo "  Contraseña: Admin123456"
}

# ================================================
# OPCIÓN 2: INSTALACIÓN CON DOCKER
# ================================================

install_docker() {
    print_section "🐳 INSTALACIÓN CON DOCKER"
    
    print_step "Requisitos"
    echo "  • Docker Desktop instalado"
    echo "  • Docker Compose"
    
    print_step "Comandos"
    echo ""
    echo "  # Iniciar contenedores"
    echo "  docker-compose up -d"
    echo ""
    echo "  # Ver logs"
    echo "  docker-compose logs -f"
    echo ""
    echo "  # Acceder a la aplicación"
    echo "  http://localhost"
    echo ""
    echo "  # PhpMyAdmin"
    echo "  http://localhost:8080"
    echo ""
    echo "  # Detener contenedores"
    echo "  docker-compose down"
    
    read -p "¿Deseas ejecutar docker-compose up -d? (s/n): " confirm
    if [[ $confirm == "s" ]]; then
        docker-compose up -d
        echo -e "${GREEN}✓ Contenedores iniciados${NC}"
        sleep 3
        echo "  Espera a que MySQL esté listo (5-10 segundos)..."
        sleep 10
        echo -e "${GREEN}✓ Accede a http://localhost${NC}"
    fi
}

# ================================================
# FUNCIONES DE UTILIDAD
# ================================================

show_structure() {
    print_section "📁 ESTRUCTURA DEL PROYECTO"
    echo "
    proyecto/
    ├── 📄 login.php                 (Página de login)
    ├── 📄 registro.php              (Registro de usuarios)
    ├── 📄 dashboard.php             (Panel principal)
    ├── 📄 api.php                   (Enrutador API)
    ├── 📄 index.html                (Página de inicio)
    │
    ├── 📁 config/
    │   ├── config.php              (Configuración)
    │   ├── database.php            (Conexión BD)
    │   └── init_database.sql       (Script SQL)
    │
    ├── 📁 src/
    │   ├── controllers/            (Controladores)
    │   ├── models/                 (Modelos)
    │   ├── services/               (Servicios)
    │   └── middleware/             (Middleware)
    │
    └── 📁 public/
        ├── css/style.css           (Estilos)
        └── js/                     (Scripts)
    "
}

show_endpoints() {
    print_section "📡 ENDPOINTS DE API"
    echo "
    AUTENTICACIÓN:
      POST   /api.php/auth/login
      POST   /api.php/auth/registro
      POST   /api.php/auth/logout
      GET    /api.php/auth/me

    TAREAS:
      GET    /api.php/tareas/obtener
      GET    /api.php/tareas/obtener-una?id=1
      POST   /api.php/tareas/crear
      POST   /api.php/tareas/actualizar
      POST   /api.php/tareas/eliminar
      GET    /api.php/tareas/estadisticas

    APIs EXTERNAS:
      GET    /api.php/external/clima?ciudad=Bogotá
      GET    /api.php/external/geocodificar?direccion=...
      GET    /api.php/external/clima-general

    SALUD:
      GET    /api.php/health
    "
}

show_files() {
    print_section "📄 ARCHIVOS PRINCIPALES"
    echo "
    DOCUMENTACIÓN:
      • README.md               (Documentación completa)
      • DEVELOPMENT.md          (Guía de desarrollo)
      • GIT_WORKFLOW.md         (Workflow Git)
      • RESUMEN_EJECUTIVO.md    (Resumen del proyecto)
      • EJEMPLOS_API.js         (Ejemplos de API)

    INSTALACIÓN:
      • install.sh              (Script de instalación)
      • docker-compose.yml      (Configuración Docker)
      • Dockerfile              (Imagen Docker)
      • .htaccess               (Configuración Apache)
      • .gitignore              (Archivo Git)
    "
}

show_testing() {
    print_section "🧪 TESTING MANUAL"
    echo "
    1. AUTENTICACIÓN
       • Ir a /registro.php
       • Crear nueva cuenta
       • Ir a /login.php
       • Iniciar sesión

    2. TAREAS
       • Crear una tarea
       • Actualizar estado
       • Filtrar por estado
       • Eliminar tarea

    3. APIs EXTERNAS
       • Ver clima en widget
       • Verificar actualización

    4. SEGURIDAD
       • Intentar CSRF injection
       • Intentar XSS injection
       • Intentar SQL injection
       • Verificar timeout
    "
}

show_troubleshooting() {
    print_section "🔧 SOLUCIÓN DE PROBLEMAS"
    echo "
    ERROR: Database connection refused
    → Verifica MySQL está corriendo
    → Actualiza credenciales en config/config.php

    ERROR: 404 en API
    → Habilita mod_rewrite en Apache
    → Verifica .htaccess está en el directorio raíz

    ERROR: CORS issues
    → Revisa headers en .htaccess
    → Verifica Access-Control-Allow-Origin

    ERROR: Contraseña débil
    → Mínimo 8 caracteres
    → Incluir números y letras

    ERROR: Sesión expirada
    → El timeout es de 1 hora
    → Recarga la página para renovar
    "
}

show_git_setup() {
    print_section "🔀 CONFIGURAR GIT"
    echo "
    # Inicializar repositorio
    git init

    # Configurar usuario
    git config user.name 'Tu Nombre'
    git config user.email 'tu@email.com'

    # Agregar archivos
    git add .

    # Primer commit
    git commit -m 'Initial commit: Gestor de Tareas'

    # Conectar remoto (opcional)
    git remote add origin https://github.com/usuario/repo.git
    git push -u origin main
    "
}

show_credentials() {
    print_section "🔐 CREDENCIALES POR DEFECTO"
    echo ""
    echo "  USUARIO ADMINISTRADOR:"
    echo "  Email:     admin@example.com"
    echo "  Contraseña: Admin123456"
    echo ""
    echo "  USUARIO REGULAR:"
    echo "  Email:     usuario@example.com"
    echo "  Contraseña: Usuario123456"
    echo ""
    echo -e "${YELLOW}  ⚠️  Cambia estas credenciales en producción${NC}"
    echo ""
}

# ================================================
# MENÚ PRINCIPAL
# ================================================

show_menu() {
    echo ""
    echo -e "${BLUE}¿Qué deseas hacer?${NC}"
    echo ""
    echo "1) Instalación local (Apache + MySQL)"
    echo "2) Instalación con Docker"
    echo "3) Ver estructura del proyecto"
    echo "4) Ver endpoints de API"
    echo "5) Ver archivos principales"
    echo "6) Guía de testing"
    echo "7) Solución de problemas"
    echo "8) Configurar Git"
    echo "9) Ver credenciales"
    echo "0) Salir"
    echo ""
    read -p "Selecciona una opción (0-9): " option
}

# ================================================
# BUCLE PRINCIPAL
# ================================================

main() {
    while true; do
        show_menu
        
        case $option in
            1)
                install_local
                ;;
            2)
                install_docker
                ;;
            3)
                show_structure
                ;;
            4)
                show_endpoints
                ;;
            5)
                show_files
                ;;
            6)
                show_testing
                ;;
            7)
                show_troubleshooting
                ;;
            8)
                show_git_setup
                ;;
            9)
                show_credentials
                ;;
            0)
                echo ""
                echo -e "${GREEN}¡Hasta luego!${NC}"
                echo ""
                exit 0
                ;;
            *)
                echo -e "${RED}Opción inválida${NC}"
                ;;
        esac
        
        read -p "Presiona Enter para continuar..."
    done
}

# Ejecutar
main
