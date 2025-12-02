# 📄 Cómo Convertir ENTREGA.html a PDF

## Opción 1: Desde el Navegador (Más Fácil)

### Google Chrome / Microsoft Edge

1. **Abrir el archivo:**
   - Presiona `Ctrl + O` en el navegador
   - Selecciona `ENTREGA.html`
   - O arrastra el archivo al navegador

2. **Convertir a PDF:**
   - Presiona `Ctrl + P` (o Archivo → Imprimir)
   - Selecciona "Guardar como PDF" en el destino
   - Nombre: `Entrega_Gestor_Tareas.pdf`
   - Haz clic en "Guardar"

### Firefox

1. **Abrir:** `Ctrl + O` → Selecciona `ENTREGA.html`

2. **Imprimir a PDF:**
   - Presiona `Ctrl + P`
   - Destino: "Guardar como PDF"
   - Haz clic en "Guardar"

### Safari (macOS)

1. **Abrir:** `Cmd + O` → Selecciona `ENTREGA.html`

2. **Imprimir:**
   - Presiona `Cmd + P`
   - Click en "PDF" → "Guardar como PDF"

---

## Opción 2: Usando Pandoc (Línea de Comandos)

### Instalación

```bash
# En Windows (usando Chocolatey)
choco install pandoc

# O descargar desde: https://pandoc.org/installing.html
```

### Conversión

```bash
# Navega a la carpeta del proyecto
cd "c:\Users\fersa\OneDrive\Imágenes\Documentos\SABER HACER UNIDAD3"

# Convierte HTML a PDF
pandoc ENTREGA.html -o Entrega_Gestor_Tareas.pdf --css public/css/style.css
```

---

## Opción 3: Usando wkhtmltopdf

### Instalación

```bash
# Windows
choco install wkhtmltopdf

# O descargar desde: https://wkhtmltopdf.org/
```

### Conversión

```bash
cd "c:\Users\fersa\OneDrive\Imágenes\Documentos\SABER HACER UNIDAD3"

wkhtmltopdf ENTREGA.html Entrega_Gestor_Tareas.pdf
```

---

## Opción 4: Convertidores Online

1. **Visita:** https://convertio.co/es/html-pdf/
2. **Arrastra** el archivo `ENTREGA.html`
3. **Espera** a que se procese
4. **Descarga** el PDF

---

## Recomendación Final

**✅ Usa la Opción 1 (Navegador)** - Es la más sencilla y no requiere instalaciones adicionales:

1. Abre `ENTREGA.html` en Chrome
2. Presiona `Ctrl + P`
3. Selecciona "Guardar como PDF"
4. ¡Listo! Tienes tu PDF

---

## Nombre del Archivo Sugerido

```
Entrega_Gestor_Tareas_FernandoSandovalIracheta.pdf
```

O simplemente:

```
ENTREGA_FINAL.pdf
```

---

## Verificación

Después de generar el PDF, verifica que incluya:

- ✅ Portada con datos del estudiante
- ✅ Tabla de contenidos
- ✅ Descripción general
- ✅ Funcionamiento del sitio
- ✅ Mecanismos de seguridad
- ✅ Web Services terceros (OpenWeather)
- ✅ Web Services propios (API REST)
- ✅ Conclusiones
- ✅ Enlace al repositorio funcional

---

## Tamaño Esperado del PDF

- Aproximadamente 2-3 MB
- 15-20 páginas
- Todas las secciones incluidas

---

**¡Listo para entregar en Classroom!** 📚
