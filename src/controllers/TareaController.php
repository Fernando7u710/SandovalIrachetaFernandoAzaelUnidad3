<?php
/**
 * Controlador de Tareas
 * API REST para gestión de tareas
 */

namespace App\Controllers;

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Tarea.php';
require_once __DIR__ . '/../services/SecurityService.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class TareaController {
    private $tarea_model;
    private $security_service;

    public function __construct() {
        // Verificar autenticación
        \App\Middleware\AuthMiddleware::verificar();
        
        $this->tarea_model = new \App\Models\Tarea();
        $this->security_service = new \App\Services\SecurityService();
    }

    /**
     * Obtener todas las tareas del usuario
     */
    public function obtenerTodas() {
        try {
            $usuario_id = getCurrentUserId();
            
            $filtros = [];
            if (isset($_GET['estado'])) {
                $filtros['estado'] = $_GET['estado'];
            }
            if (isset($_GET['prioridad'])) {
                $filtros['prioridad'] = $_GET['prioridad'];
            }

            $tareas = $this->tarea_model->obtenerPorUsuario($usuario_id, $filtros);
            api_response(true, $tareas, 'Tareas obtenidas');

        } catch (\Exception $e) {
            api_response(false, null, $e->getMessage(), 500);
        }
    }

    /**
     * Obtener una tarea específica
     */
    public function obtenerUna() {
        try {
            $usuario_id = getCurrentUserId();
            $tarea_id = intval($_GET['id'] ?? 0);

            if ($tarea_id <= 0) {
                api_response(false, null, 'ID inválido', 400);
            }

            if (!$this->tarea_model->obtenerPorId($tarea_id, $usuario_id)) {
                api_response(false, null, 'Tarea no encontrada', 404);
            }

            api_response(true, $this->tarea_model, 'Tarea obtenida');

        } catch (\Exception $e) {
            api_response(false, null, $e->getMessage(), 500);
        }
    }

    /**
     * Crear nueva tarea
     */
    public function crear() {
        try {
            \App\Middleware\AuthMiddleware::verificarCSRF();

            $usuario_id = getCurrentUserId();
            $titulo = $this->security_service::sanitizar($_POST['titulo'] ?? '');
            $descripcion = $this->security_service::sanitizar($_POST['descripcion'] ?? '');
            $prioridad = $_POST['prioridad'] ?? 'media';
            $fecha_vencimiento = $_POST['fecha_vencimiento'] ?? null;

            if (!$titulo) {
                api_response(false, null, 'Título requerido', 400);
            }

            $this->tarea_model->crear($usuario_id, $titulo, $descripcion, $prioridad, $fecha_vencimiento);

            log_activity('CREAR_TAREA', "Tarea ID: " . $this->tarea_model->getId());

            api_response(true, ['id' => $this->tarea_model->getId()], 'Tarea creada exitosamente', 201);

        } catch (\Exception $e) {
            api_response(false, null, $e->getMessage(), 500);
        }
    }

    /**
     * Actualizar tarea
     */
    public function actualizar() {
        try {
            \App\Middleware\AuthMiddleware::verificarCSRF();

            $usuario_id = getCurrentUserId();
            $tarea_id = intval($_POST['id'] ?? 0);

            if ($tarea_id <= 0) {
                api_response(false, null, 'ID inválido', 400);
            }

            if (!$this->tarea_model->obtenerPorId($tarea_id, $usuario_id)) {
                api_response(false, null, 'Tarea no encontrada', 404);
            }

            if (isset($_POST['titulo'])) {
                $this->tarea_model->setTitulo($this->security_service::sanitizar($_POST['titulo']));
            }
            if (isset($_POST['descripcion'])) {
                $this->tarea_model->setDescripcion($this->security_service::sanitizar($_POST['descripcion']));
            }
            if (isset($_POST['estado'])) {
                $this->tarea_model->setEstado($_POST['estado']);
            }
            if (isset($_POST['prioridad'])) {
                $this->tarea_model->setPrioridad($_POST['prioridad']);
            }
            if (isset($_POST['fecha_vencimiento'])) {
                $this->tarea_model->setFechaVencimiento($_POST['fecha_vencimiento']);
            }

            $this->tarea_model->actualizar();

            log_activity('ACTUALIZAR_TAREA', "Tarea ID: $tarea_id");

            api_response(true, null, 'Tarea actualizada exitosamente');

        } catch (\Exception $e) {
            api_response(false, null, $e->getMessage(), 500);
        }
    }

    /**
     * Eliminar tarea
     */
    public function eliminar() {
        try {
            \App\Middleware\AuthMiddleware::verificarCSRF();

            $usuario_id = getCurrentUserId();
            $tarea_id = intval($_POST['id'] ?? $_GET['id'] ?? 0);

            if ($tarea_id <= 0) {
                api_response(false, null, 'ID inválido', 400);
            }

            if (!$this->tarea_model->obtenerPorId($tarea_id, $usuario_id)) {
                api_response(false, null, 'Tarea no encontrada', 404);
            }

            $this->tarea_model->eliminar($tarea_id, $usuario_id);

            log_activity('ELIMINAR_TAREA', "Tarea ID: $tarea_id");

            api_response(true, null, 'Tarea eliminada exitosamente');

        } catch (\Exception $e) {
            api_response(false, null, $e->getMessage(), 500);
        }
    }

    /**
     * Obtener estadísticas
     */
    public function obtenerEstadisticas() {
        try {
            $usuario_id = getCurrentUserId();
            $estadisticas = $this->tarea_model->obtenerEstadisticas($usuario_id);
            api_response(true, $estadisticas, 'Estadísticas obtenidas');

        } catch (\Exception $e) {
            api_response(false, null, $e->getMessage(), 500);
        }
    }
}
?>
