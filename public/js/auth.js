/**
 * Funciones de Autenticación
 * Manejo de login y registro
 */

class AuthManager {
    constructor() {
        this.baseUrl = '/api';
        this.user = null;
    }

    /**
     * Realizar login
     */
    async login(email, password) {
        try {
            const formData = new FormData();
            formData.append('email', email);
            formData.append('password', password);

            const response = await fetch(`${this.baseUrl}/auth/login`, {
                method: 'POST',
                body: formData,
                credentials: 'include'
            });

            const data = await response.json();

            if (data.success) {
                this.user = data.data;
                localStorage.setItem('user', JSON.stringify(this.user));
                return data;
            } else {
                throw new Error(data.message || 'Error en login');
            }
        } catch (error) {
            throw error;
        }
    }

    /**
     * Realizar registro
     */
    async registro(nombre, email, password, passwordConfirm) {
        try {
            const formData = new FormData();
            formData.append('nombre', nombre);
            formData.append('email', email);
            formData.append('password', password);
            formData.append('password_confirm', passwordConfirm);

            const response = await fetch(`${this.baseUrl}/auth/registro`, {
                method: 'POST',
                body: formData,
                credentials: 'include'
            });

            const data = await response.json();

            if (data.success) {
                return data;
            } else {
                throw new Error(data.message || 'Error en registro');
            }
        } catch (error) {
            throw error;
        }
    }

    /**
     * Realizar logout
     */
    async logout() {
        try {
            const response = await fetch(`${this.baseUrl}/auth/logout`, {
                method: 'POST',
                credentials: 'include'
            });

            const data = await response.json();

            if (data.success) {
                this.user = null;
                localStorage.removeItem('user');
                return data;
            } else {
                throw new Error(data.message || 'Error en logout');
            }
        } catch (error) {
            throw error;
        }
    }

    /**
     * Obtener usuario actual
     */
    async obtenerUsuario() {
        try {
            const response = await fetch(`${this.baseUrl}/auth/me`, {
                credentials: 'include'
            });
            const data = await response.json();

            if (data.success) {
                this.user = data.data;
                return this.user;
            } else {
                throw new Error('No autenticado');
            }
        } catch (error) {
            return null;
        }
    }

    /**
     * Verificar si está autenticado
     */
    isAuthenticated() {
        return this.user !== null;
    }
}

// Instancia global
const authManager = new AuthManager();
