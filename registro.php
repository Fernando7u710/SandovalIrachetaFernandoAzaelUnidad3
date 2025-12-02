<?php
session_start();
require_once __DIR__ . '/config/config.php';

// Si ya está autenticado, redirigir al dashboard
if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Gestor de Tareas</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <div class="container auth-container">
        <div class="auth-box">
            <h1>Crear Cuenta</h1>
            <p class="subtitle">Únete a nuestro sistema de gestión</p>

            <form id="registroForm" method="POST" action="api/auth/registro" class="auth-form">
                <div class="form-group">
                    <label for="nombre">Nombre Completo:</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" required>
                    <small class="error" id="nombreError"></small>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="tu@email.com" required>
                    <small class="error" id="emailError"></small>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" placeholder="Mínimo 8 caracteres" required>
                    <small class="info">Debe incluir números y letras</small>
                    <small class="error" id="passwordError"></small>
                </div>

                <div class="form-group">
                    <label for="password_confirm">Confirmar Contraseña:</label>
                    <input type="password" id="password_confirm" name="password_confirm" placeholder="Repite la contraseña" required>
                    <small class="error" id="passwordConfirmError"></small>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
            </form>

            <div class="auth-divider">ó</div>

            <p class="auth-link">¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>

            <div id="alert" class="alert alert-hidden"></div>
        </div>
    </div>

    <script src="public/js/auth.js?v=<?php echo time(); ?>"></script>
    <script>
        document.getElementById('registroForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const nombre = document.getElementById('nombre').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const password_confirm = document.getElementById('password_confirm').value;
            const alert = document.getElementById('alert');

            // Validaciones básicas
            if (password.length < 8) {
                alert.className = 'alert alert-error';
                alert.textContent = 'La contraseña debe tener mínimo 8 caracteres';
                return;
            }

            if (password !== password_confirm) {
                alert.className = 'alert alert-error';
                alert.textContent = 'Las contraseñas no coinciden';
                return;
            }

            try {
                const response = await fetch('api/auth/registro', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `nombre=${encodeURIComponent(nombre)}&email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}&password_confirm=${encodeURIComponent(password_confirm)}`,
                    credentials: 'include'
                });

                const data = await response.json();

                if (data.success) {
                    alert.className = 'alert alert-success';
                    alert.textContent = 'Registro exitoso. Redirigiendo al login...';
                    setTimeout(() => {
                        window.location.href = 'login.php';
                    }, 1500);
                } else {
                    alert.className = 'alert alert-error';
                    alert.textContent = data.message || 'Error en el registro';
                }

            } catch (error) {
                alert.className = 'alert alert-error';
                alert.textContent = 'Error de conexión: ' + error.message;
            }
        });
    </script>
</body>
</html>
