-- Insertar usuario de prueba
-- Contraseña: demo123456
INSERT INTO usuarios (nombre, email, contrasena, rol, estado) 
VALUES ('Usuario Demo', 'demo@example.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36DDqKlm', 'user', 'activo')
ON DUPLICATE KEY UPDATE contrasena='$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36DDqKlm';

-- Insertar algunos datos de prueba adicionales
INSERT INTO tareas (usuario_id, titulo, descripcion, estado, prioridad, fecha_vencimiento)
SELECT id, 'Tarea de ejemplo 1', 'Esta es tu primera tarea', 'pendiente', 'alta', DATE_ADD(NOW(), INTERVAL 7 DAY)
FROM usuarios WHERE email = 'demo@example.com'
ON DUPLICATE KEY UPDATE titulo=VALUES(titulo);

INSERT INTO tareas (usuario_id, titulo, descripcion, estado, prioridad, fecha_vencimiento)
SELECT id, 'Tarea completada', 'Esta tarea ya está completada', 'completada', 'media', DATE_ADD(NOW(), INTERVAL 3 DAY)
FROM usuarios WHERE email = 'demo@example.com'
ON DUPLICATE KEY UPDATE titulo=VALUES(titulo);
