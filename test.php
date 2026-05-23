<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
$rol_usuario = $_SESSION['rol'] ?? 'user';

include("conexion.php");
$con = conectar();

// Mantenemos tu ordenación original
$sql = "SELECT * FROM supositorio ORDER BY id_sistema DESC";
$query = mysqli_query($con, $sql);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Supositorio - Estabilidad Total</title>

    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 x=%2250%%22 font-size=%2280%22 text-anchor=%22middle%22 transform=%22scale(-1, 1) translate(-100, 0)%22>💊</text></svg>">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,400,0,0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-blue: #475569;
            --hover-blue: #334155;
            --bg-body: #f0f2f5;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        /* 1. Evitamos que el body salte al abrir la modal */
        body.modal-open {
            position: fixed !important;
            overflow-y: scroll !important;
            width: 100% !important;
            /* Esto mantiene la posición actual de la pantalla */
            top: calc(-1 * var(--scroll-y));
        }

        /* 2. Aseguramos que el buscador no se ensanche */
        .search-container,
        .custom-card {
            max-width: 1400px !important;
            margin-right: auto !important;
            margin-left: auto !important;
        }

        /* 3. Evitamos el padding que inyecta Bootstrap por JS */
        body {
            padding-right: 0 !important;
        }

        /* 1. RESET ESTRUCTURAL: El secreto de la web que me pasaste */
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100vh;
            width: 100vw;
            overflow: hidden;
            /* Prohibido el scroll en el body */
            background-color: var(--bg-body);
            font-family: 'Inter', sans-serif;
            color: var(--text-main);
        }

        /* Contenedor que divide la pantalla en 2 partes: Arriba (Fija) y Abajo (Scroll) */
        .app-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        /* 2. CAPA SUPERIOR (Panel Admin + Buscador) - NUNCA SE MUEVE */
        .capa-superior {
            flex-shrink: 0;
            background: var(--bg-body);
            z-index: 100;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 10px;
        }

        /* 3. CAPA DE DATOS (La Tabla) - SÓLO ESTO TIENE SCROLL */
        .capa-datos {
            flex-grow: 1;
            overflow-y: scroll;
            /* El scrollbar vive aquí siempre */
            padding: 10px 0 30px 0;
        }

        .bloque-centrado {
            width: 95%;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* TUS ESTILOS ORIGINALES DE TARJETAS */
        .custom-card {
            background: #ffffff;
            border-radius: 4px;
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03);
            padding: 0.5rem 1rem !important;
            margin-top: 10px;
        }

        /* TUS ESTILOS DE FILAS */
        .fila-extranjera {
            background-color: #e2efda !important;
        }

        .fila-azul-especial {
            background-color: #f3e8ff !important;
            color: #000000 !important;
        }

        #studentTable tbody tr.fila-marcada,
        #studentTable tbody tr.fila-marcada td {
            background-color: #fef9c3 !important;
            color: #854d0e !important;
        }

        #studentTable tbody tr:hover {
            background-color: #eff6ff !important;
            cursor: pointer;
        }

        mark {
            background-color: #fef08a;
            color: #854d0e;
            padding: 0px 2px;
            border-radius: 2px;
        }

        .table-responsive-scroll {
            background: white;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        /* Estilo para el Modal (Independiente de Bootstrap para evitar saltos) */
        .modal-blindado {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .modal-caja {
            background: white;
            width: 90%;
            max-width: 800px;
            border-radius: 12px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
        }
    </style>
</head>

<body>



    <div class="app-container">

        <header class="capa-superior">
            <div class="bloque-centrado">

                <div class="d-flex justify-content-end align-items-center py-1" style="font-size: 0.75rem;">
                    <span class="text-muted">Cuenta de: <strong><?php echo $_SESSION['usuario']; ?></strong></span>
                    <div style="width: 1px; height: 12px; background: #e2e8f0; margin: 0 10px;"></div>
                    <a href="logout.php" class="text-danger text-decoration-none fw-bold">Salir</a>
                </div>

                <?php if ($rol_usuario === 'admin'): ?>
                    <div class="card custom-card border-warning border mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="fw-bold mb-0 text-secondary" style="font-size: 0.75rem;">Panel Admin</h6>
                            <a href="descargar_plantilla.php" class="btn btn-outline-secondary btn-sm" style="font-size: 0.65rem; padding: 1px 8px;">Plantilla CSV</a>
                        </div>
                        <form action="importar_excel.php" method="POST" enctype="multipart/form-data" class="row g-2 align-items-center">
                            <div class="col-9">
                                <input type="file" name="archivo_excel" class="form-control form-control-sm" accept=".csv" required>
                            </div>
                            <div class="col-3">
                                <button type="submit" class="btn btn-dark btn-sm w-100 py-1" style="font-size: 0.75rem;">Importar</button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>

                <div class="search-container">
                    <div class="card custom-card">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-0"><span class="material-symbols-outlined" style="font-size: 1.1rem;">search</span></span>
                            <input type="text" id="searchInput" class="form-control border-0 shadow-none" placeholder="Buscar..." style="height: 28px; font-size: 0.8rem;">
                            <select id="filtroMesJS" class="form-select border-0 shadow-none text-muted" style="max-width: 130px; font-size: 0.8rem; height: 28px; border-left: 1px solid #eee !important;">
                                <option value="">Filtrar</option>
                                <option value="lila">Notas</option>
                                <option value="verde">Latam</option>
                                <option value="blanco">España</option>
                                <option disabled style="background:#f1f5f9; font-weight:bold;">· Meses ·</option>
                                <option value="/01/">Enero</option>
                                <option value="/02/">Febrero</option>
                                <option value="/03/">Marzo</option>
                                <option value="/04/">Abril</option>
                                <option value="/05/">Mayo</option>
                                <option value="/06/">Junio</option>
                                <option value="/07/">Julio</option>
                                <option value="/08/">Agosto</option>
                                <option value="/09/">Septiembre</option>
                                <option value="/10/">Octubre</option>
                                <option value="/11/">Noviembre</option>
                                <option value="/12/">Diciembre</option>
                            </select>
                            <button class="btn btn-sm text-muted border-0" id="btnClear">✖</button>
                        </div>
                    </div>
                </div>

                <div class="mt-2 d-flex justify-content-between align-items-center">
                    <a href="javascript:void(0)" onclick="abrirModal('nuevo')" class="btn btn-dark btn-sm px-3" style="background-color: #475569; font-size: 0.75rem;">+ Añadir Registro</a>

                    <div class="d-flex gap-3 align-items-center">
                        <div class="d-flex align-items-center">
                            <div style="width:10px;height:10px;background:#fef9c3;margin-right:4px;border-radius:2px;"></div><span style="font-size:0.65rem;">Marcado</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div style="width:10px;height:10px;background:#f3e8ff;margin-right:4px;border-radius:2px;"></div><span style="font-size:0.65rem;">Notas</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div style="width:10px;height:10px;background:#e2efda;margin-right:4px;border-radius:2px;"></div><span style="font-size:0.65rem;">Latam</span>
                        </div>
                    </div>

                    <div class="text-muted" style="font-size: 0.75rem;">Registros: <span id="contadorRegistros" class="fw-bold">0</span></div>
                </div>
            </div>
        </header>

        <main class="capa-datos">
            <div class="bloque-centrado">
                <div class="table-responsive-scroll shadow-sm">
                    <table class="table mb-0" id="studentTable">
                        <thead class="table-light sticky-top">
                            <tr style="font-size: 0.75rem; color: #64748b;">
                                <th style="width: 70px;">ID</th>
                                <th>Nombre</th>
                                <th>Teléfono</th>
                                <th>Consulta</th>
                                <th>Resolución</th>
                                <th class="text-center">Adj.</th>
                                <th id="thFecha" style="cursor:pointer">Fecha ↕</th>
                                <?php if ($rol_usuario === 'admin'): ?><th>Acciones</th><?php endif; ?>
                            </tr>
                        </thead>
                        <tbody style="font-size: 0.85rem;">
                            <?php while ($row = mysqli_fetch_array($query)): ?>
                                <tr class="<?php echo (strpos($row['ID'], 'NOTA-') === 0) ? 'fila-azul-especial' : ''; ?>" onclick="marcarFila(this)">
                                    <td class="fw-bold text-center">
                                        <?php if (strpos($row['ID'], 'NOTA-') === 0): ?>
                                            <div style="width:24px;height:24px;background:#f3e8ff;color:#7e22ce;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.7rem;border:1px solid #e9d5ff;margin:0 auto;">N</div>
                                        <?php else: ?>
                                            <?php echo $row['ID']; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $row['nombre']; ?></td>
                                    <td><?php echo ($row['telefono'] == 'NOTA' || $row['telefono'] == '1') ? '' : $row['telefono']; ?></td>
                                    <td class="fw-bold"><?php echo $row['consulta']; ?></td>
                                    <td><?php echo $row['solucion']; ?></td>
                                    <td class="text-center"><?php echo !empty($row['adjunto']) ? '📎' : '-'; ?></td>
                                    <td class="text-muted small"><?php echo date('d/m/Y', strtotime($row['fecha_registro'])); ?></td>
                                    <?php if ($rol_usuario === 'admin'): ?>
                                        <td class="text-end">
                                            <div class="d-flex gap-1 justify-content-end">
                                                <button onclick="abrirModal('editar', <?php echo $row['id_sistema']; ?>)" class="btn btn-light btn-sm py-0 px-2" style="font-size:0.7rem;">Editar</button>
                                                <a href="delete.php?id=<?php echo $row['id_sistema']; ?>" class="btn btn-light btn-sm py-0 px-2 text-danger" style="font-size:0.7rem;" onclick="return confirm('¿Eliminar?')">Borrar</a>
                                            </div>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <div id="modalMaestro" class="modal-blindado" onclick="if(event.target==this) cerrarModal()">
        <div class="modal-caja">
            <div id="contenidoDinamicoModal"></div>
        </div>
    </div>

    <script>
        // 1. GESTIÓN DE MODALES (Aislada para que nada se mueva)
        function abrirModal(tipo, id = null) {
            let url = tipo === 'nuevo' ? 'nuevo_registro.php' : 'actualizar.php?id=' + id;
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('contenidoDinamicoModal').innerHTML = html;
                    document.getElementById('modalMaestro').style.display = 'flex';
                });
        }

        function cerrarModal() {
            document.getElementById('modalMaestro').style.display = 'none';
            document.getElementById('contenidoDinamicoModal').innerHTML = '';
        }

        // 2. TU LÓGICA DE FILTRADO COMPLETA
        function filtrarTabla() {
            let filterText = document.getElementById('searchInput').value.toLowerCase().trim();
            let filterOption = document.getElementById('filtroMesJS').value;
            let rows = document.querySelector('#studentTable tbody').rows;
            let count = 0;

            for (let row of rows) {
                let text = row.innerText.toLowerCase();
                let date = row.cells[6].textContent;
                let matchesText = text.includes(filterText);
                let matchesFilter = true;

                if (filterOption !== "") {
                    if (filterOption === "lila") matchesFilter = row.classList.contains('fila-azul-especial');
                    else if (filterOption === "verde") matchesFilter = row.classList.contains('fila-extranjera');
                    else if (filterOption === "blanco") matchesFilter = !row.className.includes('fila-');
                    else matchesFilter = date.includes(filterOption);
                }

                if (matchesText && matchesFilter) {
                    row.style.display = '';
                    count++;
                    // Resaltar texto si hay búsqueda
                    if (filterText !== "") {
                        [1, 3, 4].forEach(i => {
                            let cell = row.cells[i];
                            let regex = new RegExp(`(${filterText})`, "gi");
                            cell.innerHTML = cell.innerText.replace(regex, "<mark>$1</mark>");
                        });
                    }
                } else {
                    row.style.display = 'none';
                }
            }
            document.getElementById('contadorRegistros').innerText = count;
        }

        // 3. EVENTOS
        document.getElementById('searchInput').addEventListener('keyup', filtrarTabla);
        document.getElementById('filtroMesJS').addEventListener('change', filtrarTabla);
        document.getElementById('btnClear').addEventListener('click', () => {
            document.getElementById('searchInput').value = '';
            document.getElementById('filtroMesJS').value = '';
            filtrarTabla();
        });

        function marcarFila(el) {
            document.querySelectorAll('tr.fila-marcada').forEach(r => r.classList.remove('fila-marcada'));
            el.classList.toggle('fila-marcada');
        }

        // Ejecutar al cargar
        window.onload = function() {
            filtrarTabla();
            verificarColoresAutomaticos();
        };

        function verificarColoresAutomaticos() {
            document.querySelectorAll('#studentTable tbody tr').forEach(row => {
                let tel = row.cells[2].innerText.trim();
                if (tel !== "" && !tel.startsWith('6') && !tel.startsWith('7') && !tel.startsWith('9') && !tel.startsWith('+34')) {
                    row.classList.add('fila-extranjera');
                }
            });
        }
        // Guardamos la posición del scroll antes de abrir la modal
        window.addEventListener('scroll', () => {
            document.documentElement.style.setProperty('--scroll-y', `${window.scrollY}px`);
        });

        // Cuando se abre cualquier modal
        document.addEventListener('show.bs.modal', function() {
            const scrollY = document.documentElement.style.getPropertyValue('--scroll-y');
            const body = document.body;
            // El body ya no se moverá ni un píxel
        });

        // Cuando se cierra (Recuperar estado original)
        document.addEventListener('hidden.bs.modal', function() {
            const scrollY = document.body.style.top;
            document.body.style.position = '';
            document.body.style.top = '';
            window.scrollTo(0, parseInt(scrollY || '0') * -1);

            // Limpieza total
            document.body.removeAttribute("style");
        });
    </script>

</body>

</html>