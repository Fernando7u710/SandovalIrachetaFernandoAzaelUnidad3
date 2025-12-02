<?php
/**
 * Enrutador de API
 * Maneja todas las solicitudes a la API
 */

// Iniciar sesión primero, antes de cualquier output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/src/controllers/AuthController.php';
require_once __DIR__ . '/src/controllers/TareaController.php';
require_once __DIR__ . '/src/services/ExternalAPIService.php';
require_once __DIR__ . '/src/middleware/AuthMiddleware.php';

header('Content-Type: application/json');

// Parsear ruta
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Extraer segmentos de la ruta
$segments = array_filter(explode('/', $path));
$segments = array_values($segments); // Reiniciar índices

// Determinar endpoint y action
// Si viene como /api/auth/login: segments = ['api', 'auth', 'login']
// Si viene como api.php/auth/login: segments = ['api.php', 'auth', 'login']
$endpoint = '';
$action = '';

if (count($segments) >= 2) {
    // Saltar api o api.php
    $endpoint = $segments[1] ?? '';
    $action = $segments[2] ?? '';
}

try {
    // Rutas de autenticación (sin requerimiento de login)
    if ($endpoint === 'auth') {
        $controller = new \App\Controllers\AuthController();

        switch ($action) {
            case 'login':
                $controller->login();
                break;
            case 'registro':
                $controller->registro();
                break;
            case 'logout':
                \App\Middleware\AuthMiddleware::verificar();
                $controller->logout();
                break;
            case 'me':
                \App\Middleware\AuthMiddleware::verificar();
                $controller->obtenerUsuario();
                break;
            default:
                api_response(false, null, 'Endpoint no encontrado', 404);
        }
    }

    // Rutas de tareas (requiere autenticación)
    elseif ($endpoint === 'tareas') {
        \App\Middleware\AuthMiddleware::verificar();
        \App\Middleware\AuthMiddleware::verificarTimeout();
        
        $controller = new \App\Controllers\TareaController();

        switch ($action) {
            case 'obtener':
                $controller->obtenerTodas();
                break;
            case 'obtener-una':
                $controller->obtenerUna();
                break;
            case 'crear':
                $controller->crear();
                break;
            case 'actualizar':
                $controller->actualizar();
                break;
            case 'eliminar':
                $controller->eliminar();
                break;
            case 'estadisticas':
                $controller->obtenerEstadisticas();
                break;
            default:
                api_response(false, null, 'Acción no encontrada', 404);
        }
    }

    // Rutas de APIs externas
    elseif ($endpoint === 'external') {
        \App\Middleware\AuthMiddleware::verificar();
        
        $external_service = new \App\Services\ExternalAPIService();

        switch ($action) {
            case 'clima':
                $ciudad = $_GET['ciudad'] ?? 'Bogotá';
                $resultado = $external_service->obtenerClima($ciudad);
                api_response(true, $resultado, 'Clima obtenido');
                break;
            
            case 'geocodificar':
                $direccion = $_GET['direccion'] ?? '';
                if (!$direccion) {
                    api_response(false, null, 'Dirección requerida', 400);
                }
                $resultado = $external_service->geocodificar($direccion);
                api_response(true, $resultado, 'Geocodificación completada');
                break;

            case 'clima-general':
                $resultado = $external_service->obtenerClimaGeneral();
                api_response(true, $resultado, 'Clima general obtenido');
                break;

            default:
                api_response(false, null, 'Endpoint externo no encontrado', 404);
        }
    }

    // Health check
    elseif ($endpoint === 'health') {
        api_response(true, ['status' => 'ok'], 'API funcionando correctamente');
    }

    else {
        api_response(false, null, 'Endpoint no encontrado', 404);
    }

} catch (\Exception $e) {
    if (DEBUG_MODE) {
        api_response(false, null, $e->getMessage(), 500);
    } else {
        api_response(false, null, 'Error interno del servidor', 500);
    }
}
?>
