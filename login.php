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
    <title>Login - Gestor de Tareas</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <div class="container auth-container">
        <div class="auth-box">
            <h1>Gestor de Tareas</h1>
            <p class="subtitle">Sistema de Gestión Web con Seguridad Empresarial</p>

            <form id="loginForm" method="POST" action="api/auth/login" class="auth-form">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="tu@email.com" required>
                    <small class="error" id="emailError"></small>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" placeholder="Contraseña" required>
                    <small class="error" id="passwordError"></small>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
            </form>

            <div class="auth-divider">ó</div>

            <p class="auth-link">¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>

            <div id="alert" class="alert alert-hidden"></div>
        </div>
    </div>

    <script src="public/js/auth.js?v=<?php echo time(); ?>"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const alert = document.getElementById('alert');

            try {
                const response = await fetch('api/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`,
                    credentials: 'include'
                });

                const data = await response.json();

                if (data.success) {
                    alert.className = 'alert alert-success';
                    alert.textContent = 'Login exitoso. Redirigiendo...';
                    setTimeout(() => {
                        window.location.href = 'dashboard.php';
                    }, 1500);
                } else {
                    alert.className = 'alert alert-error';
                    alert.textContent = data.message || 'Error en el login';
                }

            } catch (error) {
                alert.className = 'alert alert-error';
                alert.textContent = 'Error de conexión: ' + error.message;
            }
        });
    </script>
</body>
</html>
