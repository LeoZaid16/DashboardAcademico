<?php
$mensaje = "";
$mostrarModalConfirmacion = false;

// Validar formulario al enviar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $inicio = trim($_POST["inicio"] ?? "");
    $vencimiento = trim($_POST["vencimiento"] ?? "");

    if ($nombre && $descripcion && $inicio && $vencimiento) {
        $mensaje = "Se ha agregado correctamente.";
        $mostrarModalConfirmacion = true;
    } else {
        $mensaje = "⚠️ Todos los campos son obligatorios.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- BOOTSRAP CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- CSS STYLESHEET -->
    <link rel="stylesheet" href="css/style.css">
    <!-- BOOTSTRAP ICONS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- GOOGLE CHARTS -->
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        let AsistenciaGeneral = 0;
    </script>

    <script>
        google.charts.load('current', { packages: ['corechart'] });
        google.charts.setOnLoadCallback(drawAsistencia);

        function drawAsistencia() {
            const query = new google.visualization.Query(
                'https://docs.google.com/spreadsheets/d/1xDNzg1fvKdYhfsAzApNR7EyVFkeY2C4dPgxpVqrR39U/gviz/tq?sheet=Hoja1'
            );

            query.setQuery('SELECT *');
            query.send(function (response) {
                if (response.isError()) {
                    console.error('Error al cargar datos:', response.getMessage());
                    return;
                }

                const data = response.getDataTable();

                let total = 0;
                let posibles = 0;

                for (let i = 0; i < data.getNumberOfRows(); i++) {
                    for (let j = 1; j <= 15; j++) {
                        const val = data.getValue(i, j);
                        if (val === 1 || val === "1" || val === "✓") total++;
                        if (val !== null && val !== '') posibles++;
                    }
                }

                const porcentaje = posibles ? Math.round((total / posibles) * 100) : 0;
                AsistenciaGeneral = porcentaje;

                const label = document.getElementById('asistenciaLabel');
                if (label) label.innerText = `Asistencia (${porcentaje}%)`;
                const asistenciaTexto = document.getElementById('asistenciaTexto');
                if (asistenciaTexto) asistenciaTexto.innerText = `Asistencia (${porcentaje}%)`;


                const chartData = google.visualization.arrayToDataTable([
                    ['Estado', 'Cantidad'],
                    ['Asistencias', total],
                    ['Inasistencias', posibles - total]
                ]);

                const options = {
                    backgroundColor: 'transparent',
                    pieHole: 0.4,
                    legend: 'none',
                    chartArea: { width: '100%', height: '80%' },
                    colors: ['#274c4c', '#d0d7d7']
                };

                const chart = new google.visualization.PieChart(document.getElementById('graficaAsistencia'));
                chart.draw(chartData, options);
            });
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const urlCSV = "https://docs.google.com/spreadsheets/d/e/2PACX-1vSQDcNurIGDRopy1cTKfHbT7mlGlbPT_lNz9rLzJRHbDmiQt5mzAP4yfKnCkRdVznftejQKFpcMkR8H/pub?gid=0&single=true&output=csv";

            fetch(urlCSV)
                .then(res => res.text())
                .then(data => {
                    const rows = data.trim().split("\n").slice(1); // omite encabezado
                    const tbody = document.getElementById("tablaDatos");

                    rows.forEach(row => {
                        const cols = row.split(",");
                        const tr = document.createElement("tr");

                        cols.forEach(col => {
                            const td = document.createElement("td");
                            td.textContent = col;
                            tr.appendChild(td);
                        });

                        tbody.appendChild(tr);
                    });
                })
                .catch(error => {
                    console.error("Error al cargar CSV:", error);
                });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const urlCSV = "https://docs.google.com/spreadsheets/d/e/2PACX-1vSQDcNurIGDRopy1cTKfHbT7mlGlbPT_lNz9rLzJRHbDmiQt5mzAP4yfKnCkRdVznftejQKFpcMkR8H/pub?gid=1992436241&single=true&output=csv";

            fetch(urlCSV)
                .then(res => res.text())
                .then(data => {
                    const rows = data.trim().split("\n").slice(1); // omite encabezado
                    const tbody = document.getElementById("tablaAlumnos");

                    rows.forEach(row => {
                        const cols = row.split(",");
                        const tr = document.createElement("tr");

                        cols.forEach(col => {
                            const td = document.createElement("td");
                            td.textContent = col.trim(); // elimina espacios sobrantes
                            tr.appendChild(td);
                        });

                        tbody.appendChild(tr);
                    });
                })
                .catch(error => {
                    console.error("Error al cargar CSV:", error);
                });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const urlTSV = "https://docs.google.com/spreadsheets/d/e/2PACX-1vSQDcNurIGDRopy1cTKfHbT7mlGlbPT_lNz9rLzJRHbDmiQt5mzAP4yfKnCkRdVznftejQKFpcMkR8H/pub?gid=678384186&single=true&output=tsv";

            fetch(urlTSV)
                .then(res => res.text())
                .then(data => {
                    const rows = data.trim().split("\n").slice(1); // omitimos encabezado
                    let sumaExamenes = 0, totalExamenes = 0;
                    let sumaTareas = 0, totalTareas = 0;

                    rows.forEach(row => {
                        const cols = row.split("\t");
                        const exAprobados = parseInt(cols[1]);
                        const exTotales = parseInt(cols[2]);
                        const tareasEntregadas = parseInt(cols[3]);
                        const tareasTotales = parseInt(cols[4]);

                        if (!isNaN(exAprobados)) sumaExamenes += exAprobados;
                        if (!isNaN(exTotales)) totalExamenes += exTotales;
                        if (!isNaN(tareasEntregadas)) sumaTareas += tareasEntregadas;
                        if (!isNaN(tareasTotales)) totalTareas += tareasTotales;
                    });

                    const porcentajeExamenes = totalExamenes ? (sumaExamenes / totalExamenes) * 100 : 0;
                    const porcentajeTareas = totalTareas ? (sumaTareas / totalTareas) * 100 : 0;

                    const performanceGeneral = ((porcentajeExamenes + porcentajeTareas + AsistenciaGeneral) / 3);

                    document.getElementById("porcentajeExamenes").innerText = `Exámenes Aprobados (${porcentajeExamenes.toFixed(0)}%)`;
                    document.getElementById("porcentajeTareas").innerText = `Tareas Entregadas (${porcentajeTareas.toFixed(0)}%)`;
                    document.getElementById("performanceGeneral").innerText = `Performance General (${performanceGeneral.toFixed(0)}%)`;

                    //GRAFICA DE PERFORMANCE
                    google.charts.setOnLoadCallback(() => {
                        const chartData = google.visualization.arrayToDataTable([
                            ['Tipo', 'Valor'],
                            ['Performance', performanceGeneral],
                            ['Resto', 100 - performanceGeneral]
                        ]);

                        const options = {
                            backgroundColor: 'transparent',
                            pieHole: 0,
                            legend: 'none',
                            chartArea: { width: '100%', height: '80%' },
                            colors: ['#274c4c', '#d0d7d7']
                        };

                        const chart = new google.visualization.PieChart(document.getElementById('graficaPerformance'));
                        chart.draw(chartData, options);
                    });

                });
        })
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Cuando se abre el modal, insertamos las tarjetas
            const modal = document.getElementById("modaltareas");
            modal.addEventListener("show.bs.modal", () => {
                const tareas = document.querySelectorAll(".tarjeta-tarea");
                const contenedorModal = document.getElementById("modalTareasLista");
                contenedorModal.innerHTML = ""; // Limpiamos
                tareas.forEach(t => {
                    contenedorModal.appendChild(t.cloneNode(true));
                });
            });

            // Validación del formulario
            const form = document.getElementById("formTarea");
            const btn = document.getElementById("btnAgregar");
            const mensaje = document.getElementById("mensajeConfirmacion");

        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Muestra tarjetas en el modal de exámenes
            const modalExamenes = document.getElementById("modalexamenes");
            modalExamenes.addEventListener("show.bs.modal", () => {
                const examenes = document.querySelectorAll(".tarjeta-examen");
                const contenedorModal = document.getElementById("modalExamenesLista");
                contenedorModal.innerHTML = ""; // Limpiar
                examenes.forEach(examen => {
                    // Evitar que se clone la tarjeta "Agregar examen"
                    if (!examen.querySelector(".bi-plus-lg")) {
                        contenedorModal.appendChild(examen.cloneNode(true));
                    }
                });
            });

            // Validación simple del formulario con JavaScript
            const form = document.getElementById("formExamen");
            const btn = document.getElementById("btnAgregarExamen");
            const mensaje = document.getElementById("mensajeExamen");
        });
    </script>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-md fixed-top bg-navbar ">
        <div class="container">
            <a class="navbar-brand" href="#"><img src="assets/logo/academia.png" alt="Logo" draggable="false"
                    height="50" /></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon bi bi-list align-content-center fs-1 "></span>
            </button>
            <!-- LINKS NAVBAR -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul
                    class="navbar-nav ms-auto fs-6 align-self-center fw-bolder d-flex gap-2 align-items-center gap-md-4">
                    <li class="">
                        <a href="#overview">
                            <span>Overview</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="#tareas-examenes">
                            <span>Trabajos y Examenes</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="#asistencia">
                            <span>Asistencia</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="#alumnos">
                            <span>Alumnos</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- DATOS OVERVIEW -->
    <div class="container section-bg mb-5 p-4 rounded" id="overview">
        <div class="row">
            <!-- LADO IZQUIERDO (DATOS) -->
            <div class="col-12 col-lg-8 d-inline-flex flex-column justify-content-center ps-3 ps-lg-5">
                <!-- PRIMERA FILA -->
                <div class="row">
                    <!-- ASISTENCIA GENERAL -->
                    <div class="col-12 col-md-6 my-3 mt-md-0">
                        <div class="content-bg p-3 rounded text-center h-100">
                            <p class="fw-bolder">Asistencia General</p>
                            <p class="fw-bold" id="asistenciaTexto">Asistencia 0/0 (0%)</p>
                        </div>
                    </div>
                    <!-- EXÁMENES REALIZADOS -->
                    <div class="col-12 col-md-6 my-3 mt-md-0">
                        <div class="content-bg p-3 rounded text-center h-100">
                            <p class="fw-bolder">Exámenes Realizados</p>
                            <p class="fw-bold" id="porcentajeExamenes">Exámenes 0/0 (0%)</p>
                        </div>
                    </div>
                </div>
                <!-- SEGUNDA FILA -->
                <div class="row">
                    <!-- TAREAS Y TRABAJOS -->
                    <div class="col-12 col-md-6 my-3 mt-md-0">
                        <div class="content-bg p-3 rounded text-center h-100">
                            <p class="fw-bolder">Tareas y Trabajos</p>
                            <p class="fw-bold" id="porcentajeTareas">Tareas completadas 0/0 (0%)</p>
                        </div>
                    </div>
                    <!-- PERFORMANCE GENERAL -->
                    <div class="col-12 col-md-6 my-3 mt-md-0">
                        <div class="content-bg p-3 rounded text-center h-100">
                            <p class="fw-bolder">Performance General</p>
                            <p class="fw-bold" id="performanceGeneral">Performance (0%)</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4 d-flex flex-column align-items-center justify-content-center">
                <div id="graficaPerformance" style="width: 100%; max-width: 300px; height: 300px;"></div>
            </div>
        </div>
    </div>

    <!-- MODAL TAREAS -->
    <div class="modal fade" id="modaltareas" tabindex="-1" aria-labelledby="modalTareasLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content section-bg rounded-4 p-3">
                <div class="modal-body">
                    <div class="row">
                        <!-- Lista tareas -->
                        <div class="col-lg-6 pe-3 scroll-wrapper text-start" id="modalTareasLista">
                            <!-- Aquí insertaremos dinámicamente las tarjetas existentes -->
                        </div>

                        <!-- Formulario -->
                        <div class="col-lg-6 mt-4 mt-lg-0">
                            <form method="POST" action="#modaltareas">
                                <div class="mb-2">
                                    <label class="form-label fw-bold">Nombre:</label>
                                    <input type="text" name="nombre" class="form-control rounded-3" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-bold">Descripción:</label>
                                    <textarea name="descripcion" class="form-control rounded-3" rows="2"
                                        required></textarea>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-bold">Fecha inicio:</label>
                                    <input type="date" name="inicio" class="form-control rounded-3" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Vencimiento:</label>
                                    <input type="date" name="vencimiento" class="form-control rounded-3" required>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-custom rounded-3">Agregar</button>
                                </div>
                                <?php if ($mensaje): ?>
                                    <div class="mt-3 text-white fw-bold"><?= $mensaje ?></div>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EXÁMENES -->
    <div class="modal fade" id="modalexamenes" tabindex="-1" aria-labelledby="modalExamenesLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content section-bg rounded-4 p-3">
                <div class="modal-body">
                    <div class="row">
                        <!-- Tarjetas de exámenes -->
                        <div class="col-lg-6 pe-3 scroll-wrapper text-start" id="modalExamenesLista">
                            <!-- Aquí se insertan dinámicamente -->
                        </div>

                        <!-- Formulario -->
                        <div class="col-lg-6 mt-4 mt-lg-0">
                            <form method="POST" action="#modalexamenes">
                                <div class="mb-2">
                                    <label class="form-label fw-bold">Nombre:</label>
                                    <input type="text" name="nombre" class="form-control rounded-3" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-bold">Descripción:</label>
                                    <textarea name="descripcion" class="form-control rounded-3" rows="2"
                                        required></textarea>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-bold">Fecha inicio:</label>
                                    <input type="date" name="inicio" class="form-control rounded-3" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Vencimiento:</label>
                                    <input type="date" name="vencimiento" class="form-control rounded-3" required>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-custom rounded-3">Agregar</button>
                                </div>
                                <?php if ($mensaje): ?>
                                    <div class="mt-3 text-white fw-bold"><?= $mensaje ?></div>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL CONFIRMACIÓN -->
    <div class="modal fade" id="modalConfirmacion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 p-4 content-bg">
                <div class="text-center">
                    <p class="fw-bolder">Se ha agregado correctamente</p>
                    <button class="btn btn-custom rounded-3 mt-3" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- TARJETAS TAREAS Y EXAMENES -->
    <div class="container my-5" id="tareas-examenes">
        <div class="row justify-content-between">
            <!-- SECCIÓN TAREAS -->
            <div class="col-12 col-lg-5 section-bg">
                <div class="scroll-wrapper">
                    <div class="scroll-inner">
                        <!-- Tarjetas -->
                        <div class="card content-bg my-2 rounded-4 tarjeta-tarea" data-bs-toggle="modal"
                            data-bs-target="#modaltareas" role="button">
                            <div class="card-body">
                                <h6 class="card-title fw-bolder">Portafolio de evidencias U5</h6>
                                <p class="card-text">Sube tu portafolio completo con capturas y documentación de la
                                    unidad 5.</p>
                            </div>
                        </div>
                        <div class="card content-bg my-2 rounded-4 tarjeta-tarea" data-bs-toggle="modal"
                            data-bs-target="#modaltareas" role="button">
                            <div class="card-body">
                                <h6 class="card-title fw-bolder">Práctica laberinto - Lego</h6>
                                <p class="card-text">Entrega un video y PDF evidenciando el funcionamiento del
                                    laberinto.</p>
                            </div>
                        </div>
                        <div class="card content-bg my-2 rounded-4 tarjeta-tarea" data-bs-toggle="modal"
                            data-bs-target="#modaltareas" role="button">
                            <div class="card-body">
                                <h6 class="card-title fw-bolder">Diseño de interfaz U4</h6>
                                <p class="card-text">Sube el diseño en Figma exportado a PDF con interacciones.</p>
                            </div>
                        </div>
                        <div class="card content-bg my-2 rounded-4 tarjeta-tarea" data-bs-toggle="modal"
                            data-bs-target="#modaltareas" role="button">
                            <div class="card-body">
                                <h6 class="card-title fw-bolder">Mockup funcional</h6>
                                <p class="card-text">Desarrolla un mockup navegable con mínimo 3 pantallas enlazadas.
                                </p>
                            </div>
                        </div>
                        <div class="card content-bg my-2 rounded-4 tarjeta-tarea" data-bs-toggle="modal"
                            data-bs-target="#modaltareas" role="button">
                            <div class="card-body">
                                <h6 class="card-title fw-bolder">Práctica de sensores</h6>
                                <p class="card-text">Sube el esquema y video demostrando el uso de sensores básicos.</p>
                            </div>
                        </div>
                        <div class="card content-bg my-2 rounded-4 tarjeta-tarea" data-bs-toggle="modal"
                            data-bs-target="#modaltareas" role="button">
                            <div class="card-body">
                                <h6 class="card-title fw-bolder">Video resumen U3</h6>
                                <p class="card-text">Graba un video de 2 minutos explicando conceptos clave de la unidad
                                    3.</p>
                            </div>
                        </div>
                        <div class="card content-bg my-2 rounded-4 tarjeta-tarea" data-bs-toggle="modal"
                            data-bs-target="#modaltareas" role="button">
                            <div class="card-body">
                                <h6 class="card-title fw-bolder">Formulario en HTML</h6>
                                <p class="card-text">Entrega un formulario funcional con validación de campos.</p>
                            </div>
                        </div>
                        <div class="card content-bg my-2 rounded-4 tarjeta-tarea" data-bs-toggle="modal"
                            data-bs-target="#modaltareas" role="button">
                            <div class="card-body">
                                <h6 class="card-title fw-bolder">Presentación final</h6>
                                <p class="card-text">Presenta tu proyecto final con capturas, resultados y conclusiones.
                                </p>
                            </div>
                        </div>
                        <div class="card my-2 rounded-4 content-bg" data-bs-toggle="modal" data-bs-target="#modaltareas"
                            role="button">
                            <div class="card-body">
                                <h6 class="card-title fw-bolder">Agregar tarea</h6>
                                <div class="card-text d-flex justify-content-between">
                                    <p class=" fw-medium">Agregar tarea
                                    </p>
                                    <i class="bi bi-plus-lg fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN EXÁMENES -->
            <div class="col-12 col-lg-5 section-bg mt-4 mt-lg-0">
                <div class="scroll-wrapper">
                    <div class="scroll-inner">
                        <!-- Tarjetas -->
                        <div class="card content-bg my-2 rounded-4 tarjeta-examen" data-bs-toggle="modal"
                            data-bs-target="#modalexamenes" role="button">
                            <div class="card-body">
                                <h6 class="card-title fw-bolder">Examen unidad 5</h6>
                                <p class="card-text">Evaluación sobre diseño adaptativo y maquetación avanzada con CSS.
                                </p>
                            </div>
                        </div>
                        <div class="card content-bg my-2 rounded-4 tarjeta-examen" data-bs-toggle="modal"
                            data-bs-target="#modalexamenes" role="button">
                            <div class="card-body">
                                <h6 class="card-title fw-bolder">Examen unidad 4</h6>
                                <p class="card-text">Prueba escrita sobre principios de diseño visual y accesibilidad
                                    web.</p>
                            </div>
                        </div>
                        <div class="card content-bg my-2 rounded-4 tarjeta-examen" data-bs-toggle="modal"
                            data-bs-target="#modalexamenes" role="button">
                            <div class="card-body">
                                <h6 class="card-title fw-bolder">Examen unidad 3</h6>
                                <p class="card-text">Examen práctico sobre estructuras HTML y formularios interactivos.
                                </p>
                            </div>
                        </div>
                        <div class="card content-bg my-2 rounded-4 tarjeta-examen" data-bs-toggle="modal"
                            data-bs-target="#modalexamenes" role="button">
                            <div class="card-body">
                                <h6 class="card-title fw-bolder">Examen unidad 2</h6>
                                <p class="card-text">Cuestionario sobre lógica de programación en JavaScript.</p>
                            </div>
                        </div>
                        <div class="card content-bg my-2 rounded-4 tarjeta-examen" data-bs-toggle="modal"
                            data-bs-target="#modalexamenes" role="button">
                            <div class="card-body">
                                <h6 class="card-title fw-bolder">Examen unidad 1</h6>
                                <p class="card-text">Evaluación teórica sobre conceptos básicos de desarrollo web.</p>
                            </div>
                        </div>
                        <div class="card content-bg my-2 rounded-4 tarjeta-examen" data-bs-toggle="modal"
                            data-bs-target="#modalexamenes" role="button">
                            <div class="card-body">
                                <h6 class="card-title fw-bolder">Evaluación Diagnóstica</h6>
                                <p class="card-text">Prueba inicial para conocer tus conocimientos previos del curso.
                                </p>
                            </div>
                        </div>
                        <div class="card my-2 rounded-4 content-bg" data-bs-toggle="modal"
                            data-bs-target="#modalexamenes" role="button">
                            <div class="card-body">
                                <h6 class="card-title fw-bolder">Agregar examen</h6>
                                <div class="card-text d-flex justify-content-between">
                                    <p class=" fw-medium">Agregar examen
                                    </p>
                                    <i class="bi bi-plus-lg fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCION ASISTENCIA -->
    <div class=" container section-bg p-4 rounded mb-5" id="asistencia">
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="table-responsive scroll-custom">
                    <table class="table table-bordered table-hover table-format text-center align-middle ">
                        <thead>
                            <tr>
                                <th>Alumno</th>
                                <th>S1</th>
                                <th>S2</th>
                                <th>S3</th>
                                <th>S4</th>
                                <th>S5</th>
                                <th>S6</th>
                                <th>S7</th>
                                <th>S8</th>
                                <th>S9</th>
                                <th>S10</th>
                                <th>S11</th>
                                <th>S12</th>
                                <th>S13</th>
                                <th>S14</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="tablaDatos"></tbody>
                    </table>
                </div>
            </div>
            <!-- Gráfica -->
            <div class="col-12 col-lg-4 d-flex flex-column align-items-center justify-content-center text-center">
                <div id="graficaAsistencia" style="width: 100%; max-width: 300px; height: 300px;"></div>
                <div id="asistenciaLabel" class="content-bg rounded px-3 py-2 mt-0 fw-bold d-inline-block">
                    Asistencia (calculando...)
                </div>
            </div>
        </div>
    </div>

    <!-- TABLA DE ALUMNOS -->
    <div class="container section-bg p-4 rounded mb-5 d-flex justify-content-center" id="alumnos">
        <!-- CONTENEDOR DE LA TABLA -->
        <div class="table-responsive w-100">
            <table class="table table-bordered table-hover table-format text-center align-middle">
                <thead>
                    <tr>
                        <th>Grupo</th>
                        <th>Nombre</th>
                        <th>Asistencias</th>
                        <th>Tareas entregadas</th>
                        <th>Examenes aprobados</th>
                        <th>Estado General</th>
                        <!-- agrega más encabezados si es necesario -->
                    </tr>
                </thead>
                <tbody id="tablaAlumnos">
                    <!-- Aquí se insertarán las filas -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="container-fluid bg-footer mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex gap-4 justify-content-center fs-1">
                    <a href="https://github.com/">
                        <i class="bi bi-github color-icon"></i>
                    </a>
                    <a href="https://www.linkedin.com/">
                        <i class="bi bi-linkedin color-icon"></i>
                    </a>
                    <a href="https://www.facebook.com/">
                        <i class="bi bi-facebook color-icon"></i>
                    </a>
                    <a href="https://www.instagram.com/">
                        <i class="bi bi-instagram color-icon"></i>
                    </a>
                </div>
            </div>
            <div class="row mt-3">
                <div
                    class="col-12 d-flex flex-column flex-md-row gap-2 gap-md-5 justify-content-center text-center fs-5">
                    <a href="#overview">
                        <span>Overview</span>
                    </a>
                    <a href="#tareas-examenes">
                        <span>Trabajos y Examenes</span>
                    </a>
                    <a href="#asistencia">
                        <span>Asistencia</span>
                    </a>
                    <a href="#alumnos">
                        <span>Alumnos</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid copyright-bg py-3">
        <div class="container text-center">
            <span class="fs-6 fw-light">Copyright ©2025; Dashboard Académico. </span>
            <br>
            <span>Todos los derechos reservados.</span>
        </div>
    </div>

    <!-- BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <?php if ($mostrarModalConfirmacion): ?>
        <script>
            const modal = new bootstrap.Modal(document.getElementById('modalConfirmacion'));
            modal.show();
        </script>
    <?php endif; ?>
</body>

</html>