#!/bin/bash
# Script de instalación de la aplicación

echo "==================================="
echo "Instalador - Gestor de Tareas"
echo "==================================="

# Crear directorios necesarios
echo "Creando directorios..."
mkdir -p logs
mkdir -p uploads
chmod 755 logs
chmod 755 uploads

# Importar base de datos
echo "¿Deseas importar la base de datos? (s/n)"
read -r response
if [ "$response" = "s" ]; then
    echo "Ingresa usuario MySQL:"
    read -r db_user
    echo "Ingresa contraseña MySQL:"
    read -s db_pass
    echo "Ingresa host MySQL (default: localhost):"
    read -r db_host
    db_host=${db_host:-localhost}
    
    mysql -u "$db_user" -p"$db_pass" -h "$db_host" < config/init_database.sql
    echo "✓ Base de datos importada"
fi

# Configuración
echo ""
echo "Configurando aplicación..."
echo "Ingresa base de datos (default: app_tareas):"
read -r db_name
db_name=${db_name:-app_tareas}

echo "Ingresa usuario BD (default: root):"
read -r db_user
db_user=${db_user:-root}

echo "Ingresa API Key OpenWeather (opcional):"
read -r weather_key

# Crear archivo .env
cat > .env << EOF
DB_HOST=localhost
DB_USER=$db_user
DB_NAME=$db_name
WEATHER_API_KEY=$weather_key
EOF

echo "✓ Configuración completada"
echo ""
echo "==================================="
echo "Instalación finalizada!"
echo "==================================="
echo ""
echo "Accede a: http://localhost/tareas/login.php"
echo "Usuario: admin@example.com"
echo "Contraseña: Admin123456"
