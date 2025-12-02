<?php
/**
 * Modelo de Tarea
 * Patrón Active Record
 */

namespace App\Models;

require_once __DIR__ . '/../../config/database.php';

class Tarea {
    private $db;
    private $id;
    private $usuario_id;
    private $titulo;
    private $descripcion;
    private $estado;
    private $prioridad;
    private $fecha_vencimiento;
    private $fecha_creacion;
    private $etiquetas = [];

    public function __construct() {
        $this->db = \Database::getInstance()->getConnection();
    }

    // Getters
    public function getId() { return $this->id; }
    public function getUsuarioId() { return $this->usuario_id; }
    public function getTitulo() { return $this->titulo; }
    public function getDescripcion() { return $this->descripcion; }
    public function getEstado() { return $this->estado; }
    public function getPrioridad() { return $this->prioridad; }
    public function getFechaVencimiento() { return $this->fecha_vencimiento; }
    public function getFechaCreacion() { return $this->fecha_creacion; }
    public function getEtiquetas() { return $this->etiquetas; }

    // Setters
    public function setTitulo($titulo) { $this->titulo = $titulo; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
    public function setEstado($estado) { $this->estado = $estado; }
    public function setPrioridad($prioridad) { $this->prioridad = $prioridad; }
    public function setFechaVencimiento($fecha) { $this->fecha_vencimiento = $fecha; }

    /**
     * Crear nueva tarea
     */
    public function crear($usuario_id, $titulo, $descripcion, $prioridad = 'media', $fecha_vencimiento = null) {
        if (empty($titulo)) {
            throw new \Exception('El título es requerido');
        }

        $stmt = $this->db->prepare("INSERT INTO tareas (usuario_id, titulo, descripcion, prioridad, fecha_vencimiento, estado) VALUES (?, ?, ?, ?, ?, 'pendiente')");
        
        if (!$stmt) {
            throw new \Exception('Error en la consulta: ' . $this->db->error);
        }

        $stmt->bind_param('issss', $usuario_id, $titulo, $descripcion, $prioridad, $fecha_vencimiento);

        if (!$stmt->execute()) {
            throw new \Exception('Error al crear la tarea');
        }

        $this->id = $this->db->insert_id;
        $this->usuario_id = $usuario_id;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->prioridad = $prioridad;
        $this->fecha_vencimiento = $fecha_vencimiento;
        $this->estado = 'pendiente';
        $this->fecha_creacion = date('Y-m-d H:i:s');

        return true;
    }

    /**
     * Obtener tarea por ID
     */
    public function obtenerPorId($id, $usuario_id) {
        $stmt = $this->db->prepare("SELECT * FROM tareas WHERE id = ? AND usuario_id = ?");
        $stmt->bind_param('ii', $id, $usuario_id);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();

        if ($resultado) {
            $this->id = $resultado['id'];
            $this->usuario_id = $resultado['usuario_id'];
            $this->titulo = $resultado['titulo'];
            $this->descripcion = $resultado['descripcion'];
            $this->estado = $resultado['estado'];
            $this->prioridad = $resultado['prioridad'];
            $this->fecha_vencimiento = $resultado['fecha_vencimiento'];
            $this->fecha_creacion = $resultado['fecha_creacion'];
            $this->cargarEtiquetas();
            return true;
        }

        return false;
    }

    /**
     * Obtener tareas del usuario
     */
    public function obtenerPorUsuario($usuario_id, $filtros = []) {
        $query = "SELECT * FROM tareas WHERE usuario_id = ?";
        $types = 'i';
        $params = [$usuario_id];

        if (!empty($filtros['estado'])) {
            $query .= " AND estado = ?";
            $types .= 's';
            $params[] = $filtros['estado'];
        }

        if (!empty($filtros['prioridad'])) {
            $query .= " AND prioridad = ?";
            $types .= 's';
            $params[] = $filtros['prioridad'];
        }

        $query .= " ORDER BY fecha_vencimiento ASC, prioridad DESC";

        $stmt = $this->db->prepare($query);
        
        if (!$stmt) {
            throw new \Exception('Error en la consulta');
        }

        // Pasar parámetros por referencia correctamente
        if (!empty($params)) {
            $bind_params = [&$types];
            foreach ($params as &$param) {
                $bind_params[] = &$param;
            }
            call_user_func_array([$stmt, 'bind_param'], $bind_params);
        }
        
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Actualizar tarea
     */
    public function actualizar() {
        $stmt = $this->db->prepare("UPDATE tareas SET titulo = ?, descripcion = ?, estado = ?, prioridad = ?, fecha_vencimiento = ? WHERE id = ? AND usuario_id = ?");
        
        if (!$stmt) {
            throw new \Exception('Error en la consulta');
        }

        $stmt->bind_param('sssssii', $this->titulo, $this->descripcion, $this->estado, $this->prioridad, $this->fecha_vencimiento, $this->id, $this->usuario_id);

        if (!$stmt->execute()) {
            throw new \Exception('Error al actualizar la tarea');
        }

        return true;
    }

    /**
     * Eliminar tarea
     */
    public function eliminar($id, $usuario_id) {
        $stmt = $this->db->prepare("DELETE FROM tareas WHERE id = ? AND usuario_id = ?");
        $stmt->bind_param('ii', $id, $usuario_id);
        return $stmt->execute();
    }

    /**
     * Cargar etiquetas de la tarea
     */
    private function cargarEtiquetas() {
        $stmt = $this->db->prepare("SELECT e.id, e.nombre, e.color FROM etiquetas e JOIN tarea_etiqueta te ON e.id = te.etiqueta_id WHERE te.tarea_id = ?");
        $stmt->bind_param('i', $this->id);
        $stmt->execute();
        $this->etiquetas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtener estadísticas del usuario
     */
    public function obtenerEstadisticas($usuario_id) {
        $stmt = $this->db->prepare("SELECT estado, COUNT(*) as cantidad FROM tareas WHERE usuario_id = ? GROUP BY estado");
        $stmt->bind_param('i', $usuario_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
