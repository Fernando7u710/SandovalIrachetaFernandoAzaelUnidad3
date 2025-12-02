/**
 * Aplicación Principal - Gestor de Tareas
 * Lógica del dashboard y gestión de tareas
 */

class TareasApp {
    constructor() {
        this.baseUrl = '/api';
        this.tareas = [];
        this.filtroActivo = 'todas';
        this.darkMode = localStorage.getItem('darkMode') === 'true';
    }

    /**
     * Inicializar aplicación
     */
    async init() {
        this.aplicarTemaOscuro();
        await this.cargarTareas();
        await this.cargarEstadisticas();
        await this.cargarClima();
        this.setupEventos();
        this.actualizarInterfaz();
    }

    /**
     * Configurar eventos
     */
    setupEventos() {
        // Logout
        document.getElementById('logoutBtn').addEventListener('click', () => this.logout());

        // Crear tarea
        document.getElementById('crearTareaForm').addEventListener('submit', (e) => this.crearTarea(e));

        // Filtros
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
                item.classList.add('active');
                this.filtroActivo = item.getAttribute('data-filter');
                this.actualizarInterfaz();
            });
        });

        // Toggle tema oscuro
        document.getElementById('toggleTheme').addEventListener('click', () => this.toggleTema());
    }

    /**
     * Cargar tareas del servidor
     */
    async cargarTareas() {
        try {
            const response = await fetch(`${this.baseUrl}/tareas/obtener`, {
                credentials: 'include'
            });
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();

            if (data.success) {
                this.tareas = data.data || [];
            } else {
                this.mostrarAlerta('Error al cargar tareas: ' + (data.message || ''), 'error');
            }
        } catch (error) {
            console.error('Error cargando tareas:', error);
            this.mostrarAlerta('Error de conexión al cargar tareas', 'error');
        }
    }

    /**
     * Cargar estadísticas
     */
    async cargarEstadisticas() {
        try {
            const response = await fetch(`${this.baseUrl}/tareas/estadisticas`, {
                credentials: 'include'
            });
            const data = await response.json();

            if (data.success) {
                const estadisticas = data.data || [];
                
                // Calcular totales
                let total = 0;
                const stats = {};
                
                estadisticas.forEach(stat => {
                    stats[stat.estado] = stat.cantidad;
                    total += stat.cantidad;
                });

                document.getElementById('statTotal').textContent = total;
                document.getElementById('statPendiente').textContent = stats['pendiente'] || 0;
                document.getElementById('statEnProgreso').textContent = stats['en_progreso'] || 0;
                document.getElementById('statCompletada').textContent = stats['completada'] || 0;
            }
        } catch (error) {
            console.error('Error al cargar estadísticas:', error);
        }
    }

    /**
     * Cargar clima actual
     */
    async cargarClima() {
        try {
            const response = await fetch(`${this.baseUrl}/external/clima-general`, {
                credentials: 'include'
            });
            const data = await response.json();

            if (data.success) {
                const clima = data.data;
                const widget = document.getElementById('weatherWidget');
                if (widget) {
                    widget.innerHTML = `
                        <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                            <span>🌡️ ${clima.temperatura}°C</span>
                            <span>💨 ${clima.velocidad_viento} km/h</span>
                        </div>
                    `;
                }
            }
        } catch (error) {
            console.error('Error al cargar clima:', error);
        }
    }

    /**
     * Crear nueva tarea
     */
    async crearTarea(e) {
        e.preventDefault();

        const titulo = document.getElementById('titulo').value;
        const descripcion = document.getElementById('descripcion').value;
        const prioridad = document.getElementById('prioridad').value;
        const fecha_vencimiento = document.getElementById('fecha_vencimiento').value;
        const csrf_token = document.querySelector('input[name="csrf_token"]').value;

        if (!titulo) {
            this.mostrarAlerta('El título es requerido', 'error');
            return;
        }

        try {
            const formData = new FormData();
            formData.append('titulo', titulo);
            formData.append('descripcion', descripcion);
            formData.append('prioridad', prioridad);
            formData.append('fecha_vencimiento', fecha_vencimiento);
            formData.append('csrf_token', csrf_token);

            const response = await fetch(`${this.baseUrl}/tareas/crear`, {
                method: 'POST',
                body: formData,
                credentials: 'include'
            });

            const data = await response.json();

            if (data.success) {
                this.mostrarAlerta('Tarea creada exitosamente', 'success');
                document.getElementById('crearTareaForm').reset();
                await this.cargarTareas();
                await this.cargarEstadisticas();
                this.actualizarInterfaz();
            } else {
                this.mostrarAlerta(data.message || 'Error al crear tarea', 'error');
            }
        } catch (error) {
            this.mostrarAlerta('Error de conexión', 'error');
            console.error('Error:', error);
        }
    }

    /**
     * Actualizar tarea
     */
    async actualizarTarea(id, estado) {
        try {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('estado', estado);
            formData.append('csrf_token', document.querySelector('input[name="csrf_token"]').value);

            const response = await fetch(`${this.baseUrl}/tareas/actualizar`, {
                method: 'POST',
                body: formData,
                credentials: 'include'
            });

            const data = await response.json();

            if (data.success) {
                this.mostrarAlerta('Tarea actualizada', 'success');
                await this.cargarTareas();
                await this.cargarEstadisticas();
                this.actualizarInterfaz();
            }
        } catch (error) {
            this.mostrarAlerta('Error al actualizar tarea', 'error');
        }
    }

    /**
     * Eliminar tarea
     */
    async eliminarTarea(id) {
        if (!confirm('¿Estás seguro de que deseas eliminar esta tarea?')) {
            return;
        }

        try {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('csrf_token', document.querySelector('input[name="csrf_token"]').value);

            const response = await fetch(`${this.baseUrl}/tareas/eliminar`, {
                method: 'POST',
                body: formData,
                credentials: 'include'
            });

            const data = await response.json();

            if (data.success) {
                this.mostrarAlerta('Tarea eliminada', 'success');
                await this.cargarTareas();
                await this.cargarEstadisticas();
                this.actualizarInterfaz();
            }
        } catch (error) {
            this.mostrarAlerta('Error al eliminar tarea', 'error');
        }
    }

    /**
     * Actualizar interfaz según filtro activo
     */
    actualizarInterfaz() {
        const tareasList = document.getElementById('tareasList');
        let tareasFiltradas = this.tareas;

        // Aplicar filtro
        if (this.filtroActivo !== 'todas') {
            tareasFiltradas = this.tareas.filter(t => t.estado === this.filtroActivo);
        }

        // Renderizar tareas
        if (tareasFiltradas.length === 0) {
            tareasList.innerHTML = '<p class="empty-state">No hay tareas para mostrar</p>';
        } else {
            tareasList.innerHTML = tareasFiltradas.map(tarea => this.renderTarea(tarea)).join('');
        }

        // Agregar eventos a botones
        document.querySelectorAll('.task-actions button').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const action = e.target.getAttribute('data-action');
                const tareaId = parseInt(e.target.getAttribute('data-id'));

                if (action === 'completar') {
                    this.actualizarTarea(tareaId, 'completada');
                } else if (action === 'eliminar') {
                    this.eliminarTarea(tareaId);
                } else if (action === 'en-progreso') {
                    this.actualizarTarea(tareaId, 'en_progreso');
                }
            });
        });
    }

    /**
     * Renderizar tarjeta de tarea
     */
    renderTarea(tarea) {
        const fechaVencimiento = tarea.fecha_vencimiento ? new Date(tarea.fecha_vencimiento).toLocaleDateString('es-ES') : 'Sin fecha';
        
        let botones = '';
        if (tarea.estado === 'pendiente') {
            botones = `
                <button class="btn btn-success" data-action="en-progreso" data-id="${tarea.id}">En Progreso</button>
                <button class="btn btn-primary" data-action="completar" data-id="${tarea.id}">Completar</button>
            `;
        } else if (tarea.estado === 'en_progreso') {
            botones = `
                <button class="btn btn-primary" data-action="completar" data-id="${tarea.id}">Completar</button>
            `;
        }

        botones += `<button class="btn btn-danger" data-action="eliminar" data-id="${tarea.id}">Eliminar</button>`;

        return `
            <div class="task-card ${tarea.prioridad}">
                <div class="task-header">
                    <h3 class="task-title ${tarea.estado === 'completada' ? 'completed' : ''}">${this.escaparHTML(tarea.titulo)}</h3>
                    <span class="task-badge badge-${tarea.prioridad}">${tarea.prioridad.toUpperCase()}</span>
                </div>
                ${tarea.descripcion ? `<p>${this.escaparHTML(tarea.descripcion)}</p>` : ''}
                <div class="task-meta">
                    <span>📌 ${tarea.estado.replace('_', ' ').toUpperCase()}</span>
                    <span>📅 ${fechaVencimiento}</span>
                </div>
                <div class="task-actions">
                    ${botones}
                </div>
            </div>
        `;
    }

    /**
     * Logout
     */
    async logout() {
        try {
            const response = await fetch(`${this.baseUrl}/auth/logout`, {
                method: 'POST',
                credentials: 'include'
            });

            const data = await response.json();

            if (data.success) {
                window.location.href = 'login.php';
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    /**
     * Mostrar alerta
     */
    mostrarAlerta(mensaje, tipo = 'info') {
        const alert = document.getElementById('alert');
        alert.textContent = mensaje;
        alert.className = `alert alert-${tipo}`;

        setTimeout(() => {
            alert.classList.add('alert-hidden');
        }, 4000);
    }

    /**
     * Toggle tema oscuro
     */
    toggleTema() {
        this.darkMode = !this.darkMode;
        this.aplicarTemaOscuro();
        localStorage.setItem('darkMode', this.darkMode);
    }

    /**
     * Aplicar tema oscuro
     */
    aplicarTemaOscuro() {
        if (this.darkMode) {
            document.body.classList.add('dark-mode');
        } else {
            document.body.classList.remove('dark-mode');
        }
    }

    /**
     * Escapar HTML para evitar XSS
     */
    escaparHTML(texto) {
        const div = document.createElement('div');
        div.textContent = texto;
        return div.innerHTML;
    }
}
