# Dashboard Académico con PHP + Bootstrap

Este proyecto es un **dashboard académico interactivo**, desarrollado con **HTML, CSS (Bootstrap 5)**, **JavaScript** y **PHP básico**. Permite visualizar tareas, exámenes, gráficas de desempeño, cargar datos desde hojas de cálculo y validar formularios para agregar nuevas tareas o exámenes.

---

## Funcionalidades Principales

-  Visualización de tarjetas de **tareas** y **exámenes**.
-  Dos modales (uno para tareas y otro para exámenes) que replican tarjetas existentes.
-  Formularios con **validación PHP** (no guardan datos, solo validan campos requeridos).
-  Gráficas de Google Charts:
  - Asistencia General
  - Tareas Entregadas
  - Exámenes Aprobados
  - Performance General
-  Carga automática de datos desde Google Sheets usando `fetch()` o `google.visualization.Query()`.
-  Estilo visual moderno con bordes redondeados y scroll personalizados.
-  Diseño responsive usando Bootstrap 5.

---

## Estructura del Proyecto
/ (raíz)
│
├── index.php ← Página principal (con HTML + PHP integrado)
├── css/
│ └── style.css ← Estilos personalizados
└── README.md ← Este archivo

## 🛠️ Tecnologías Utilizadas

- **Bootstrap 5**
- **PHP básico (validación de formularios)**
- **JavaScript vanilla (DOM, eventos, fetch)**
- **Google Charts (gráficas dinámicas)**
- **Google Sheets como fuente externa de datos**

---

## ⚙️ Requisitos

- Servidor local con soporte PHP (como [XAMPP](https://www.apachefriends.org/) o [Laragon](https://laragon.org/))
- Conexión a internet (para cargar recursos externos como hojas de cálculo y librerías)

---

##  Notas Importantes

-  El formulario **no guarda datos** en base de datos ni archivo. Solo valida que todos los campos estén completos.
-  Las tarjetas que aparecen en los modales son **clonadas** directamente del HTML original usando JavaScript.
-  El código está preparado para agregar funcionalidad real (guardar datos en una DB, enviar correos, etc.)
-  El código está comentado para facilitar su comprensión y modificación por parte de estudiantes.
- Puedes duplicar la estructura de tareas/exámenes para nuevas secciones si se requiere.

---

##  Próximos pasos sugeridos

-  Agregar almacenamiento en **base de datos** (MySQL).
-  Enviar notificaciones por correo al agregar tareas.
-  Incluir **sistema de login** para usuarios y roles.
-  Documentar el código como guía para otros estudiantes (instrucción esperada en el proyecto).

