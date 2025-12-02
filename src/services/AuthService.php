<?php
/**
 * Servicio de Autenticación
 * Manejo de sesiones y JWT
 */

namespace App\Services;

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Usuario.php';

class AuthService {
    private $usuario_model;
    private $db;

    public function __construct() {
        $this->usuario_model = new \App\Models\Usuario();
        $this->db = \Database::getInstance()->getConnection();
    }

    /**
     * Login de usuario
     */
    public function login($email, $password) {
        try {
            if (!$this->usuario_model->obtenerPorEmail($email)) {
                throw new \Exception('Credenciales inválidas');
            }

            if (!$this->usuario_model->verificarContrasena($password)) {
                throw new \Exception('Credenciales inválidas');
            }

            // Crear sesión
            $this->usuario_model->actualizarUltimoAcceso();
            
            $_SESSION['user_id'] = $this->usuario_model->getId();
            $_SESSION['user_email'] = $this->usuario_model->getEmail();
            $_SESSION['user_role'] = $this->usuario_model->getRol();
            $_SESSION['user_name'] = $this->usuario_model->getNombre();
            $_SESSION['login_time'] = time();
            $_SESSION['csrf_token'] = generate_csrf_token();

            // Registrar en auditoría
            $this->registrarActividad('LOGIN', 'usuarios', $this->usuario_model->getId());

            return [
                'success' => true,
                'user_id' => $this->usuario_model->getId(),
                'name' => $this->usuario_model->getNombre(),
                'email' => $this->usuario_model->getEmail(),
                'role' => $this->usuario_model->getRol()
            ];

        } catch (\Exception $e) {
            log_activity('LOGIN_FAILED', "Email: $email");
            throw $e;
        }
    }

    /**
     * Registrar nuevo usuario
     */
    public function registrar($nombre, $email, $password, $password_confirm) {
        try {
            if ($password !== $password_confirm) {
                throw new \Exception('Las contraseñas no coinciden');
            }

            $this->usuario_model->registrar($nombre, $email, $password);

            log_activity('REGISTRO_EXITOSO', "Email: $email");

            return [
                'success' => true,
                'message' => 'Usuario registrado exitosamente',
                'user_id' => $this->usuario_model->getId()
            ];

        } catch (\Exception $e) {
            log_activity('REGISTRO_FALLIDO', "Email: $email | Error: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Logout
     */
    public function logout() {
        $user_id = $_SESSION['user_id'] ?? null;
        
        session_destroy();
        
        if ($user_id) {
            log_activity('LOGOUT', "User ID: $user_id");
        }

        return ['success' => true];
    }

    /**
     * Generar JWT Token
     */
    public function generarJWT($user_id) {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        
        $payload = json_encode([
            'user_id' => $user_id,
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24) // 24 horas
        ]);

        $base64_header = rtrim(strtr(base64_encode($header), '+/', '-_'), '=');
        $base64_payload = rtrim(strtr(base64_encode($payload), '+/', '-_'), '=');

        $signature = hash_hmac('sha256', "$base64_header.$base64_payload", JWT_SECRET, true);
        $base64_signature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        return "$base64_header.$base64_payload.$base64_signature";
    }

    /**
     * Verificar JWT Token
     */
    public function verificarJWT($token) {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            return false;
        }

        list($base64_header, $base64_payload, $base64_signature) = $parts;

        $signature = hash_hmac('sha256', "$base64_header.$base64_payload", JWT_SECRET, true);
        $expected_signature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        if (!hash_equals($base64_signature, $expected_signature)) {
            return false;
        }

        $payload = json_decode(base64_decode(strtr($base64_payload, '-_', '+/')), true);

        if ($payload['exp'] < time()) {
            return false;
        }

        return $payload;
    }

    /**
     * Registrar actividad en auditoría
     */
    private function registrarActividad($accion, $tabla, $registro_id, $cambios = null) {
        $usuario_id = $_SESSION['user_id'] ?? null;
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        $stmt = $this->db->prepare("INSERT INTO actividades_auditoria (usuario_id, accion, tabla, registro_id, cambios, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $cambios_json = $cambios ? json_encode($cambios) : null;
        $stmt->bind_param('issiiss', $usuario_id, $accion, $tabla, $registro_id, $cambios_json, $ip, $user_agent);
        $stmt->execute();
    }
}
?>
