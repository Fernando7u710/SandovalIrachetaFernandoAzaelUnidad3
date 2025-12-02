FROM php:8.1-apache

# Actualizar e instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    libssl-dev \
    default-libmysqlclient-dev \
    && rm -rf /var/lib/apt/lists/*

# Habilitar módulos necesarios
RUN a2enmod rewrite headers ssl

# Instalar extensiones PHP
RUN docker-php-ext-install -j$(nproc) mysqli pdo pdo_mysql curl

# Copiar configuración de Apache
COPY .htaccess /var/www/html/.htaccess

# Crear carpetas necesarias
RUN mkdir -p /var/www/html/logs /var/www/html/uploads
RUN chmod 755 /var/www/html/logs /var/www/html/uploads

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html

# Exponer puerto 80
EXPOSE 80 443

# Ejecutar Apache
CMD ["apache2-foreground"]
