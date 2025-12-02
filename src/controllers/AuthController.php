<?php
/**
 * Controlador de Autenticación
 * Maneja login, registro y logout
 */

namespace App\Controllers;

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../services/AuthService.php';
require_once __DIR__ . '/../services/SecurityService.php';

class AuthController {
    private $auth_service;
    private $security_service;

    public function __construct() {
        $this->auth_service = new \App\Services\AuthService();
        $this->security_service = new \App\Services\SecurityService();
    }

    /**
     * Procesar login
     */
    public function login() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                api_response(false, null, 'Método no permitido', 405);
            }

            $email = $this->security_service::sanitizar($_POST['email'] ?? '', 'email');
            $password = $_POST['password'] ?? '';

            if (!$email || !$password) {
                api_response(false, null, 'Email y contraseña requeridos', 400);
            }

            $result = $this->auth_service->login($email, $password);
            api_response(true, $result, 'Login exitoso');

        } catch (\Exception $e) {
            api_response(false, null, $e->getMessage(), 401);
        }
    }

    /**
     * Procesar registro
     */
    public function registro() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                api_response(false, null, 'Método no permitido', 405);
            }

            $nombre = $this->security_service::sanitizar($_POST['nombre'] ?? '');
            $email = $this->security_service::sanitizar($_POST['email'] ?? '', 'email');
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';

            if (!$nombre || !$email || !$password) {
                api_response(false, null, 'Todos los campos son requeridos', 400);
            }

            $result = $this->auth_service->registrar($nombre, $email, $password, $password_confirm);
            api_response(true, $result, 'Registro exitoso');

        } catch (\Exception $e) {
            api_response(false, null, $e->getMessage(), 400);
        }
    }

    /**
     * Procesar logout
     */
    public function logout() {
        try {
            $this->auth_service->logout();
            api_response(true, null, 'Logout exitoso');

        } catch (\Exception $e) {
            api_response(false, null, $e->getMessage(), 500);
        }
    }

    /**
     * Obtener usuario actual
     */
    public function obtenerUsuario() {
        try {
            if (!is_logged_in()) {
                api_response(false, null, 'No autenticado', 401);
            }

            api_response(true, [
                'user_id' => getCurrentUserId(),
                'name' => $_SESSION['user_name'] ?? '',
                'email' => $_SESSION['user_email'] ?? '',
                'role' => $_SESSION['user_role'] ?? ''
            ], 'Usuario obtenido');

        } catch (\Exception $e) {
            api_response(false, null, $e->getMessage(), 500);
        }
    }
}
?>
