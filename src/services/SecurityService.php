<?php
/**
 * Servicio de Validación y Seguridad
 */

namespace App\Services;

require_once __DIR__ . '/../../config/config.php';

class SecurityService {
    
    /**
     * Sanitizar entrada
     */
    public static function sanitizar($input, $type = 'string') {
        switch ($type) {
            case 'email':
                return filter_var($input, FILTER_SANITIZE_EMAIL);
            case 'int':
                return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
            case 'float':
                return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT);
            case 'url':
                return filter_var($input, FILTER_SANITIZE_URL);
            case 'string':
            default:
                return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        }
    }

    /**
     * Validar entrada
     */
    public static function validar($input, $type = 'string') {
        switch ($type) {
            case 'email':
                return filter_var($input, FILTER_VALIDATE_EMAIL) !== false;
            case 'int':
                return filter_var($input, FILTER_VALIDATE_INT) !== false;
            case 'float':
                return filter_var($input, FILTER_VALIDATE_FLOAT) !== false;
            case 'url':
                return filter_var($input, FILTER_VALIDATE_URL) !== false;
            case 'string':
            default:
                return !empty($input) && is_string($input);
        }
    }

    /**
     * Prevenir XSS
     */
    public static function escaparXSS($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Prevenir SQL Injection (usar prepared statements en lugar de esto)
     */
    public static function escaparSQL($data) {
        $db = \Database::getInstance()->getConnection();
        return $db->real_escape_string($data);
    }

    /**
     * Verificar velocidad de solicitudes (Rate Limiting)
     */
    public static function verificarRateLimit($identifier, $max_requests = 100, $window = 3600) {
        $key = "rate_limit_" . md5($identifier);
        
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = [
                'requests' => 1,
                'start_time' => time()
            ];
            return true;
        }

        $elapsed = time() - $_SESSION[$key]['start_time'];
        
        if ($elapsed > $window) {
            $_SESSION[$key] = [
                'requests' => 1,
                'start_time' => time()
            ];
            return true;
        }

        $_SESSION[$key]['requests']++;
        
        return $_SESSION[$key]['requests'] <= $max_requests;
    }

    /**
     * Generar contraseña segura
     */
    public static function generarContrasena($length = 12) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        $password = '';
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }
        
        return $password;
    }

    /**
     * Encriptar datos
     */
    public static function encriptar($data, $key = null) {
        $key = $key ?? JWT_SECRET;
        $cipher = 'AES-256-CBC';
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
        $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    /**
     * Desencriptar datos
     */
    public static function desencriptar($data, $key = null) {
        $key = $key ?? JWT_SECRET;
        $cipher = 'AES-256-CBC';
        $data = base64_decode($data);
        $iv = substr($data, 0, openssl_cipher_iv_length($cipher));
        $encrypted = substr($data, openssl_cipher_iv_length($cipher));
        return openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
    }
}
?>
