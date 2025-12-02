<?php
session_start();
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/src/middleware/AuthMiddleware.php';

// Verificar autenticación
\App\Middleware\AuthMiddleware::verificar();
\App\Middleware\AuthMiddleware::verificarTimeout();

$csrf_token = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gestor de Tareas</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>📋 Tareas</h2>
            </div>

            <nav class="sidebar-nav">
                <a href="#" class="nav-item active" data-filter="todas">
                    <span class="icon">📊</span> Todas las Tareas
                </a>
                <a href="#" class="nav-item" data-filter="pendiente">
                    <span class="icon">⏳</span> Pendientes
                </a>
                <a href="#" class="nav-item" data-filter="en_progreso">
                    <span class="icon">⚙️</span> En Progreso
                </a>
                <a href="#" class="nav-item" data-filter="completada">
                    <span class="icon">✅</span> Completadas
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">👤</div>
                    <div>
                        <p class="user-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
                        <small><?php echo htmlspecialchars($_SESSION['user_email']); ?></small>
                    </div>
                </div>
                <button id="logoutBtn" class="btn btn-secondary btn-block">Cerrar Sesión</button>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="app-header">
                <div class="header-left">
                    <h1>Mi Panel de Control</h1>
                </div>
                <div class="header-right">
                    <button id="toggleTheme" class="btn btn-icon">🌙</button>
                    <div class="weather-widget" id="weatherWidget">
                        <span>Cargando clima...</span>
                    </div>
                </div>
            </header>

            <!-- Estadísticas -->
            <section class="stats-section">
                <div class="stat-card">
                    <h3>Total</h3>
                    <p class="stat-value" id="statTotal">0</p>
                </div>
                <div class="stat-card">
                    <h3>Pendientes</h3>
                    <p class="stat-value" id="statPendiente">0</p>
                </div>
                <div class="stat-card">
                    <h3>En Progreso</h3>
                    <p class="stat-value" id="statEnProgreso">0</p>
                </div>
                <div class="stat-card">
                    <h3>Completadas</h3>
                    <p class="stat-value" id="statCompletada">0</p>
                </div>
            </section>

            <!-- Crear Nueva Tarea -->
            <section class="create-task-section">
                <h2>Nueva Tarea</h2>
                <form id="crearTareaForm" class="task-form">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" id="titulo" name="titulo" placeholder="Título de la tarea" required>
                        </div>
                        <div class="form-group">
                            <select id="prioridad" name="prioridad">
                                <option value="baja">Baja</option>
                                <option value="media" selected>Media</option>
                                <option value="alta">Alta</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="date" id="fecha_vencimiento" name="fecha_vencimiento">
                        </div>
                        <button type="submit" class="btn btn-primary">+ Crear</button>
                    </div>
                    
                    <div class="form-group">
                        <textarea id="descripcion" name="descripcion" placeholder="Descripción (opcional)"></textarea>
                    </div>
                </form>
            </section>

            <!-- Lista de Tareas -->
            <section class="tasks-section">
                <h2>Mis Tareas</h2>
                <div id="tareasList" class="tasks-list">
                    <p class="empty-state">Cargando tareas...</p>
                </div>
            </section>
        </main>
    </div>

    <div id="alert" class="alert alert-hidden" style="position: fixed; top: 20px; right: 20px; z-index: 1000;"></div>

    <script src="public/js/app.js?v=<?php echo time(); ?>"></script>
    <script>
        const app = new TareasApp();
        app.init();
    </script>
</body>
</html>
