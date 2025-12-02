<?php
/**
 * Modelo de Usuario
 * Patrón Active Record
 */

namespace App\Models;

require_once __DIR__ . '/../../config/database.php';

class Usuario {
    private $db;
    private $id;
    private $nombre;
    private $email;
    private $rol;
    private $estado;
    private $fecha_registro;

    public function __construct() {
        $this->db = \Database::getInstance()->getConnection();
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getEmail() { return $this->email; }
    public function getRol() { return $this->rol; }
    public function getEstado() { return $this->estado; }
    public function getFechaRegistro() { return $this->fecha_registro; }

    // Setters
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setEmail($email) { $this->email = $email; }
    public function setRol($rol) { $this->rol = $rol; }
    public function setEstado($estado) { $this->estado = $estado; }

    /**
     * Registrar nuevo usuario
     */
    public function registrar($nombre, $email, $contrasena) {
        if (!$this->validarEmail($email)) {
            throw new \Exception('Email inválido');
        }

        if (!$this->validarContrasena($contrasena)) {
            throw new \Exception('Contraseña débil');
        }

        $stmt = $this->db->prepare("INSERT INTO usuarios (nombre, email, contrasena, rol, estado) VALUES (?, ?, ?, 'user', 'activo')");
        
        if (!$stmt) {
            throw new \Exception('Error en la consulta: ' . $this->db->error);
        }

        $hash = password_hash($contrasena, PASSWORD_BCRYPT);
        $stmt->bind_param('sss', $nombre, $email, $hash);

        if (!$stmt->execute()) {
            throw new \Exception('El email ya está registrado');
        }

        $this->id = $this->db->insert_id;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->rol = 'user';
        $this->estado = 'activo';

        return true;
    }

    /**
     * Obtener usuario por email
     */
    public function obtenerPorEmail($email) {
        $stmt = $this->db->prepare("SELECT id, nombre, email, rol, estado, fecha_registro FROM usuarios WHERE email = ? AND estado = 'activo'");
        
        if (!$stmt) {
            throw new \Exception('Error en la consulta');
        }

        $stmt->bind_param('s', $email);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();

        if ($resultado) {
            $this->id = $resultado['id'];
            $this->nombre = $resultado['nombre'];
            $this->email = $resultado['email'];
            $this->rol = $resultado['rol'];
            $this->estado = $resultado['estado'];
            $this->fecha_registro = $resultado['fecha_registro'];
            return true;
        }

        return false;
    }

    /**
     * Obtener usuario por ID
     */
    public function obtenerPorId($id) {
        $stmt = $this->db->prepare("SELECT id, nombre, email, rol, estado, fecha_registro FROM usuarios WHERE id = ? AND estado = 'activo'");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();

        if ($resultado) {
            $this->id = $resultado['id'];
            $this->nombre = $resultado['nombre'];
            $this->email = $resultado['email'];
            $this->rol = $resultado['rol'];
            $this->estado = $resultado['estado'];
            $this->fecha_registro = $resultado['fecha_registro'];
            return true;
        }

        return false;
    }

    /**
     * Verificar contraseña
     */
    public function verificarContrasena($contrasena) {
        $stmt = $this->db->prepare("SELECT contrasena FROM usuarios WHERE id = ?");
        $stmt->bind_param('i', $this->id);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();

        if ($resultado) {
            return password_verify($contrasena, $resultado['contrasena']);
        }

        return false;
    }

    /**
     * Validar email
     */
    private function validarEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validar contraseña (mínimo 8 caracteres, incluir números y letras)
     */
    private function validarContrasena($contrasena) {
        return strlen($contrasena) >= 8 && preg_match('/[0-9]/', $contrasena) && preg_match('/[a-zA-Z]/', $contrasena);
    }

    /**
     * Actualizar último acceso
     */
    public function actualizarUltimoAcceso() {
        $stmt = $this->db->prepare("UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = ?");
        $stmt->bind_param('i', $this->id);
        return $stmt->execute();
    }

    /**
     * Obtener todos los usuarios (solo admin)
     */
    public function obtenerTodos() {
        $result = $this->db->query("SELECT id, nombre, email, rol, estado, fecha_registro FROM usuarios ORDER BY fecha_registro DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
