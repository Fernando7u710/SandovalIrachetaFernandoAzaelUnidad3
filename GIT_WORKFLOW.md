### Introducción al Proyecto

Este documento describe cómo iniciar un repositorio Git para el proyecto de Gestor de Tareas.

## Pasos para Inicializar Git

### 1. Inicializar repositorio local

```bash
cd /ruta/del/proyecto
git init
```

### 2. Configurar usuario

```bash
git config user.name "Tu Nombre"
git config user.email "tu.email@example.com"
```

### 3. Agregar archivos

```bash
git add .
```

### 4. Primer commit

```bash
git commit -m "Initial commit: Gestor de Tareas con seguridad empresarial"
```

### 5. Crear repositorio remoto

En GitHub, GitLab o Gitea, crear nuevo repositorio vacío.

### 6. Agregar remote

```bash
git remote add origin https://github.com/usuario/app-tareas.git
git branch -M main
git push -u origin main
```

## Flujo de Trabajo en Equipo

### Crear rama para una característica

```bash
git checkout -b feature/nueva-caracteristica
```

### Hacer cambios y commit

```bash
git add .
git commit -m "feat: descripción de cambio"
```

### Enviar cambios

```bash
git push origin feature/nueva-caracteristica
```

### Pull Request en GitHub

1. Ir a GitHub
2. Crear Pull Request
3. Describir cambios
4. Solicitar revisión
5. Merge cuando sea aprobado

## Convenciones de Commits

```
feat: Nueva característica
fix: Corrección de bug
docs: Cambios en documentación
style: Formato, sin cambios lógicos
refactor: Restructuración de código
test: Agregar tests
chore: Tareas de mantenimiento
```

## Ejemplo

```bash
# Crear rama
git checkout -b feature/auth-login

# Hacer cambios
# ... editar archivos ...

# Commit
git add src/controllers/AuthController.php
git commit -m "feat: implementar sistema de login seguro"

# Push
git push origin feature/auth-login

# En GitHub: crear Pull Request
```

## Proteger rama main

En GitHub/GitLab:
1. Ir a Settings > Branches
2. Seleccionar main
3. Activar "Require pull request reviews"
4. Activar "Require status checks"

