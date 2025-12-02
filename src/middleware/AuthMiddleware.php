<?php
/**
 * Middleware de Autenticación
 * Valida que el usuario esté autenticado
 */

namespace App\Middleware;

require_once __DIR__ . '/../../config/config.php';

class AuthMiddleware {
    
    /**
     * Verificar autenticación
     */
    public static function verificar() {
        if (!is_logged_in()) {
            // Si es una solicitud API, devolver JSON
            if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false || 
                strpos($_SERVER['REQUEST_URI'], 'api.php') !== false) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'No autenticado', 'code' => 401]);
                exit;
            }
            // Si no es API, redirigir a login
            header('Location: ' . WEB_ROOT . '/login.php');
            exit;
        }
    }

    /**
     * Verificar autenticación para API
     */
    public static function verificarAPI() {
        $headers = apache_request_headers();
        
        if (!isset($headers['Authorization'])) {
            api_response(false, null, 'Token no proporcionado', 401);
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);
        
        $auth_service = new \App\Services\AuthService();
        $payload = $auth_service->verificarJWT($token);

        if (!$payload) {
            api_response(false, null, 'Token inválido o expirado', 401);
        }

        return $payload;
    }

    /**
     * Verificar rol
     */
    public static function verificarRol($rol_requerido) {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== $rol_requerido) {
            if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
                api_response(false, null, 'Acceso denegado', 403);
            } else {
                die('Acceso denegado');
            }
        }
    }

    /**
     * Verificar timeout de sesión
     */
    public static function verificarTimeout() {
        if (isset($_SESSION['login_time'])) {
            $elapsed = time() - $_SESSION['login_time'];
            
            if ($elapsed > SESSION_TIMEOUT) {
                session_destroy();
                header('Location: ' . WEB_ROOT . '/login.php?expired=1');
                exit;
            }

            // Renovar tiempo de sesión
            $_SESSION['login_time'] = time();
        }
    }

    /**
     * Verificar CSRF Token
     */
    public static function verificarCSRF() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            
            if (!verify_csrf_token($token)) {
                die('Token CSRF inválido');
            }
        }
    }
}
?>
