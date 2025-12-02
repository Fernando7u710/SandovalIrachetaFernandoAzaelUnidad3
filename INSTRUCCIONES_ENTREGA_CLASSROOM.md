# 📤 INSTRUCCIONES DE ENTREGA EN CLASSROOM

## ✅ CHECKLIST DE ENTREGA

Antes de entregar, verifica que tu entrega contenga:

### 📄 Documento PDF Principal
- [ ] Portada con datos del estudiante
- [ ] Título y objetivo del proyecto
- [ ] Tabla de contenidos
- [ ] Todas las secciones solicitadas

### 📸 Capturas de Pantalla del Funcionamiento
- [ ] Pantalla de login
- [ ] Dashboard principal
- [ ] Lista de tareas
- [ ] Formulario crear tarea
- [ ] Filtros funcionando
- [ ] Estadísticas
- [ ] Widget clima
- [ ] Tema oscuro/claro

### 🔐 Descripción de Mecanismos de Seguridad
- [ ] Autenticación (bcrypt)
- [ ] Gestión de sesiones
- [ ] Protección CSRF
- [ ] Protección XSS
- [ ] Protección SQL Injection
- [ ] Validación y sanitización
- [ ] Headers HTTP de seguridad
- [ ] Auditoría de actividades
- [ ] Ejemplos de código o capturas demostrando cada uno

### 🌐 Web Services de Terceros
- [ ] API utilizada (OpenWeather)
- [ ] Endpoint consumido
- [ ] Respuesta JSON ejemplo
- [ ] Visualización en la aplicación
- [ ] Prueba en dashboard mostrando el clima

### 🔌 Web Services Propios
- [ ] Listado de todos los endpoints (9+)
- [ ] Descripción de cada uno
- [ ] Parámetros y respuestas
- [ ] Ejemplos JSON
- [ ] Estructura de base de datos
- [ ] Pruebas de funcionamiento

### 🔗 Enlace del Repositorio
- [ ] URL funcional y accesible
- [ ] Repositorio público
- [ ] Código completo
- [ ] Documentación incluida
- [ ] README visible

---

## 📋 PASOS PARA ENTREGAR

### Paso 1: Preparar el Documento

**A. Si usas el archivo HTML:**
1. Abre el archivo `ENTREGA.html` en tu navegador
2. Presiona `Ctrl + P` (Imprimir)
3. Selecciona "Guardar como PDF"
4. Nombre: `Entrega_Gestor_Tareas.pdf`

**B. Si usas el archivo Markdown:**
1. Abre `ENTREGA_PDF.md`
2. Usa convertidor Markdown a PDF
3. Guarda como PDF

### Paso 2: Verificar el PDF

- Abre el PDF generado
- Verifica que todas las secciones estén presentes
- Comprueba que las imágenes y código se vean correctamente
- Asegúrate que el documento sea legible

### Paso 3: Preparar Archivos Adicionales

```
Crea una carpeta con:
├── Entrega_Gestor_Tareas.pdf      ← PRINCIPAL
├── RESUMEN_ENTREGA.txt             (opcional)
└── Link_GitHub.txt                 (contiene URL del repo)
```

### Paso 4: Ir a Google Classroom

1. Abre la tarea de entrega
2. Selecciona "Agregar archivo"
3. Sube `Entrega_Gestor_Tareas.pdf`
4. Opcionalmente sube `RESUMEN_ENTREGA.txt`

### Paso 5: Agregar Información Adicional

En el campo de comentarios o descripción, incluye:

```
INFORMACIÓN DE ENTREGA:

Estudiante: Fernando Sandoval Iracheta
Materia: SABER HACER - Unidad 3
Fecha: 1 de Diciembre de 2025

REPOSITORIO FUNCIONAL:
https://github.com/Fernando7u710/SandovalIrachetaFernandoAzaelUnidad3

CÓMO PROBAR:
1. git clone https://github.com/Fernando7u710/SandovalIrachetaFernandoAzaelUnidad3.git
2. docker-compose up -d
3. Accede a http://localhost
4. Credenciales: demo@example.com / demo123456

CARACTERÍSTICAS IMPLEMENTADAS:
✓ Autenticación segura con bcrypt
✓ API REST con 9+ endpoints
✓ Integración OpenWeather API
✓ Protección CSRF, XSS, SQL Injection
✓ Dashboard responsivo
✓ Tema oscuro/claro
✓ Auditoría de actividades
✓ Patrones de diseño (MVC, Singleton, etc.)
```

### Paso 6: Enviar la Tarea

1. Haz clic en "Enviar"
2. Confirma que deseas entregar
3. Verifica el estado: "Entregado"

---

## 📱 CONTENIDO QUE DEBERÍA VER EL DOCENTE

### En el PDF:

**Sección 1: Descripción General**
- Objetivo del proyecto
- Tecnologías utilizadas
- Características principales

**Sección 2: Funcionamiento**
- Flujo de usuario paso a paso
- Pantallas y componentes
- Interfaz visual

**Sección 3: Seguridad** ⭐ IMPORTANTE
- Autenticación con bcrypt
- Gestión de sesiones
- Protección CSRF con ejemplos
- Protección XSS con ejemplos
- Protección SQL Injection
- Validación y sanitización
- Headers HTTP
- Auditoría

**Sección 4: Web Services Terceros** ⭐ IMPORTANTE
- API: OpenWeather
- Endpoints consumidos
- Respuesta JSON
- Visualización en dashboard

**Sección 5: Web Services Propios** ⭐ IMPORTANTE
- Listado de endpoints
- POST /api/auth/login
- POST /api/auth/registro
- GET /api/tareas/obtener
- POST /api/tareas/crear
- POST /api/tareas/actualizar
- POST /api/tareas/eliminar
- GET /api/tareas/estadisticas
- Ejemplos de request/response
- Estructura de BD

**Sección 6: Conclusiones**
- Objetivos logrados
- Tecnologías empleadas
- Enlace al repositorio

---

## 🔍 VERIFICACIÓN FINAL

Antes de hacer clic en "Entregar", verifica:

- [ ] El PDF tiene todas las secciones
- [ ] El documento es legible (no tiene caracteres raros)
- [ ] Todas las imágenes se ven bien
- [ ] El código se ve formateado
- [ ] El enlace al repositorio es correcto
- [ ] El repositorio es accesible (público)
- [ ] La aplicación funciona en Docker

---

## ⚠️ NOTAS IMPORTANTES

1. **Portada:**
   - Debe tener tu nombre completo
   - Fecha de entrega
   - Título del proyecto
   - Institución

2. **Mecanismos de Seguridad:**
   - Cada uno debe tener descripción
   - Incluir fragmentos de código
   - Explicar cómo funciona
   - Mostrar dónde se implementa

3. **Web Services:**
   - Mostrar endpoints reales
   - Incluir ejemplos JSON
   - Explicar qué hacen
   - Mostrar respuestas exitosas

4. **Repositorio:**
   - Debe ser público
   - Debe estar funcional
   - Debe tener README
   - Debe tener código bien organizado

5. **Documentación:**
   - Debe ser profesional
   - Buena ortografía
   - Bien estructurada
   - Fácil de leer

---

## 📞 PREGUNTAS FRECUENTES

**P: ¿Puedo cambiar el nombre del PDF?**
R: Sí, pero asegúrate que sea descriptivo. Ejemplo: `Proyecto_Gestor_Tareas_Fernando.pdf`

**P: ¿Debo incluir capturas de pantalla?**
R: El documento HTML incluye referencias. Si quieres agregar más, utiliza `ENTREGA_PDF.md`

**P: ¿Qué pasa si el repositorio no es público?**
R: El docente no podrá ver el código. Asegúrate que sea accesible.

**P: ¿Puedo entregar un ZIP con todos los archivos?**
R: Sí, pero incluye el PDF principal. Estructura sugerida:
```
Entrega_Final.zip
├── Entrega_Gestor_Tareas.pdf
├── RESUMEN_ENTREGA.txt
└── Link_GitHub.txt
```

**P: ¿Cuál es el tamaño máximo del PDF?**
R: Classroom permite hasta 100MB. El PDF tendrá ~2-3MB.

**P: ¿Necesito agregar más capturas de pantalla?**
R: No es obligatorio. El documento HTML ya tiene referencias a todas las funcionalidades.

---

## 📊 RÚBRICA DE EVALUACIÓN ESPERADA

| Aspecto | Puntos | ¿Incluido? |
|---------|--------|-----------|
| Portada y datos | 5 | ✓ |
| Funcionamiento | 10 | ✓ |
| Seguridad | 20 | ✓ |
| Web Services Terceros | 15 | ✓ |
| Web Services Propios | 25 | ✓ |
| Repositorio Funcional | 15 | ✓ |
| Documentación | 10 | ✓ |
| **TOTAL** | **100** | **✓** |

---

## 🎯 RESUMEN FINAL

**Lo que entregas:**
1. ✅ PDF profesional con portada
2. ✅ Descripción de seguridad con ejemplos
3. ✅ Web Services terceros demostrados
4. ✅ Web Services propios con endpoints
5. ✅ Enlace a repositorio público y funcional

**¡Listo para entregar en Classroom!** 🚀

---

**Generado:** 1 de Diciembre de 2025
**Para:** Escuela Superior de Informática
**Materia:** SABER HACER - Unidad 3
