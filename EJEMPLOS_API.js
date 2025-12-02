/**
 * Ejemplos de uso de la API
 * Cliente JavaScript para consumir los endpoints
 */

// ============================================
// EJEMPLO 1: Login y Autenticación
// ============================================

async function ejemploLogin() {
    try {
        const response = await fetch('api.php/auth/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'email=usuario@example.com&password=Usuario123456'
        });

        const data = await response.json();
        
        if (data.success) {
            console.log('Login exitoso:', data.data);
            localStorage.setItem('user', JSON.stringify(data.data));
        } else {
            console.error('Error:', data.message);
        }
    } catch (error) {
        console.error('Error de conexión:', error);
    }
}

// ============================================
// EJEMPLO 2: Registro de nuevo usuario
// ============================================

async function ejemploRegistro() {
    try {
        const response = await fetch('api.php/auth/registro', {
            method: 'POST',
            body: new FormData(document.getElementById('registroForm'))
        });

        const data = await response.json();
        
        if (data.success) {
            alert('Usuario registrado correctamente');
            window.location.href = 'login.php';
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// ============================================
// EJEMPLO 3: Obtener todas las tareas
// ============================================

async function ejemploObtenerTareas() {
    try {
        const response = await fetch('api.php/tareas/obtener', {
            method: 'GET',
            credentials: 'include' // Incluir cookies de sesión
        });

        const data = await response.json();
        
        if (data.success) {
            console.log('Tareas:', data.data);
            mostrarTareas(data.data);
        } else {
            console.error('Error:', data.message);
        }
    } catch (error) {
        console.error('Error de conexión:', error);
    }
}

// ============================================
// EJEMPLO 4: Filtrar tareas por estado
// ============================================

async function ejemploFiltrarTareas(estado) {
    try {
        const response = await fetch(`api.php/tareas/obtener?estado=${estado}`, {
            method: 'GET',
            credentials: 'include'
        });

        const data = await response.json();
        
        if (data.success) {
            console.log(`Tareas ${estado}:`, data.data);
            return data.data;
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// ============================================
// EJEMPLO 5: Crear nueva tarea
// ============================================

async function ejemploCrearTarea(titulo, descripcion, prioridad, fecha_vencimiento) {
    try {
        const formData = new FormData();
        formData.append('titulo', titulo);
        formData.append('descripcion', descripcion);
        formData.append('prioridad', prioridad);
        formData.append('fecha_vencimiento', fecha_vencimiento);
        formData.append('csrf_token', document.querySelector('input[name="csrf_token"]').value);

        const response = await fetch('api.php/tareas/crear', {
            method: 'POST',
            credentials: 'include',
            body: formData
        });

        const data = await response.json();
        
        if (data.success) {
            console.log('Tarea creada:', data.data);
            alert('Tarea creada exitosamente');
        } else {
            console.error('Error:', data.message);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// ============================================
// EJEMPLO 6: Actualizar tarea
// ============================================

async function ejemploActualizarTarea(tareaId, nuevoEstado) {
    try {
        const formData = new FormData();
        formData.append('id', tareaId);
        formData.append('estado', nuevoEstado);
        formData.append('csrf_token', document.querySelector('input[name="csrf_token"]').value);

        const response = await fetch('api.php/tareas/actualizar', {
            method: 'POST',
            credentials: 'include',
            body: formData
        });

        const data = await response.json();
        
        if (data.success) {
            console.log('Tarea actualizada');
        } else {
            console.error('Error:', data.message);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// ============================================
// EJEMPLO 7: Eliminar tarea
// ============================================

async function ejemploEliminarTarea(tareaId) {
    try {
        const formData = new FormData();
        formData.append('id', tareaId);
        formData.append('csrf_token', document.querySelector('input[name="csrf_token"]').value);

        const response = await fetch('api.php/tareas/eliminar', {
            method: 'POST',
            credentials: 'include',
            body: formData
        });

        const data = await response.json();
        
        if (data.success) {
            console.log('Tarea eliminada');
        } else {
            console.error('Error:', data.message);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// ============================================
// EJEMPLO 8: Obtener estadísticas
// ============================================

async function ejemploEstadisticas() {
    try {
        const response = await fetch('api.php/tareas/estadisticas', {
            method: 'GET',
            credentials: 'include'
        });

        const data = await response.json();
        
        if (data.success) {
            console.log('Estadísticas:', data.data);
            
            const stats = {};
            data.data.forEach(stat => {
                stats[stat.estado] = stat.cantidad;
            });
            
            console.log('Total pendientes:', stats.pendiente || 0);
            console.log('Total en progreso:', stats.en_progreso || 0);
            console.log('Total completadas:', stats.completada || 0);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// ============================================
// EJEMPLO 9: Obtener clima
// ============================================

async function ejemploClima(ciudad = 'Bogotá') {
    try {
        const response = await fetch(`api.php/external/clima?ciudad=${encodeURIComponent(ciudad)}`, {
            method: 'GET',
            credentials: 'include'
        });

        const data = await response.json();
        
        if (data.success) {
            const clima = data.data;
            console.log(`Clima en ${clima.ciudad}:`);
            console.log(`Temperatura: ${clima.temperatura}°C`);
            console.log(`Descripción: ${clima.descripcion}`);
            console.log(`Humedad: ${clima.humedad}%`);
            console.log(`Viento: ${clima.velocidad_viento} m/s`);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// ============================================
// EJEMPLO 10: Geocodificar dirección
// ============================================

async function ejemploGeocodificar(direccion) {
    try {
        const response = await fetch(`api.php/external/geocodificar?direccion=${encodeURIComponent(direccion)}`, {
            method: 'GET',
            credentials: 'include'
        });

        const data = await response.json();
        
        if (data.success) {
            const geo = data.data;
            console.log(`Dirección: ${geo.direccion}`);
            console.log(`Latitud: ${geo.latitud}`);
            console.log(`Longitud: ${geo.longitud}`);
            
            // Usar coordenadas en Google Maps, etc.
            const mapsUrl = `https://www.google.com/maps?q=${geo.latitud},${geo.longitud}`;
            console.log(`Ver en Maps: ${mapsUrl}`);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// ============================================
// EJEMPLO 11: Logout
// ============================================

async function ejemploLogout() {
    try {
        const response = await fetch('api.php/auth/logout', {
            method: 'POST',
            credentials: 'include'
        });

        const data = await response.json();
        
        if (data.success) {
            console.log('Sesión cerrada');
            localStorage.removeItem('user');
            window.location.href = 'login.php';
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// ============================================
// FUNCIONES AUXILIARES
// ============================================

function mostrarTareas(tareas) {
    tareas.forEach(tarea => {
        console.log(`
            ID: ${tarea.id}
            Título: ${tarea.titulo}
            Estado: ${tarea.estado}
            Prioridad: ${tarea.prioridad}
            Vencimiento: ${tarea.fecha_vencimiento}
        `);
    });
}

// ============================================
// USO EN HTML
// ============================================

/*
<button onclick="ejemploLogin()">Login</button>
<button onclick="ejemploObtenerTareas()">Obtener Tareas</button>
<button onclick="ejemploClima('Madrid')">Ver Clima</button>
<button onclick="ejemploEstadisticas()">Estadísticas</button>
*/

// ============================================
// LLAMADAS DESDE FETCH API
// ============================================

// Obtener usuario actual
fetch('api.php/auth/me', {
    credentials: 'include'
})
.then(res => res.json())
.then(data => console.log('Usuario:', data.data));

// Obtener salud de la API
fetch('api.php/health')
.then(res => res.json())
.then(data => console.log('API Status:', data));
