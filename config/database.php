<?php
/**
 * Conexión a Base de Datos
 * Patrón Singleton
 */

require_once __DIR__ . '/config.php';

class Database {
    private static $instance = null;
    private $connection;
    private $last_query = '';
    private $query_log = [];

    private function __construct() {
        try {
            $this->connection = new mysqli(
                DB_HOST,
                DB_USER,
                DB_PASS,
                DB_NAME,
                DB_PORT
            );

            if ($this->connection->connect_error) {
                throw new Exception('Error de conexión: ' . $this->connection->connect_error);
            }

            $this->connection->set_charset('utf8mb4');
        } catch (Exception $e) {
            if (DEBUG_MODE) {
                die('Error de Base de Datos: ' . $e->getMessage());
            } else {
                die('Error en la aplicación. Intente más tarde.');
            }
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    public function query($sql) {
        $this->last_query = $sql;
        if (DEBUG_MODE) {
            $this->query_log[] = $sql;
        }
        return $this->connection->query($sql);
    }

    public function prepare($sql) {
        $this->last_query = $sql;
        return $this->connection->prepare($sql);
    }

    public function escape($string) {
        return $this->connection->real_escape_string($string);
    }

    public function getLastError() {
        return $this->connection->error;
    }

    public function getQueryLog() {
        return $this->query_log;
    }

    public function close() {
        if ($this->connection) {
            $this->connection->close();
        }
    }

    public function __clone() {}
    public function __wakeup() {}
}

// Inicializar base de datos
$db = Database::getInstance();
?>
