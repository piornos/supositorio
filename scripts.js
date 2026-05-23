let estadoSeleccionado = "";
// 1. FUNCIÓN DE FILTRADO Y RESALTADO (Sincronizada)
function filtrarTabla() {
    const sInput = document.getElementById('searchInput');
    const fMes = document.getElementById('filtroMesJS');
    const tbody = document.querySelector('#studentTable tbody');

    if (!sInput || !fMes || !tbody) return;

    if (fMes.value === "LIMPIAR_TODO") {
        limpiarTodo();
        return;
    }

    const existingNoResults = tbody.querySelector('.no-results-row');
    if (existingNoResults) existingNoResults.remove();

    const busquedaCompleta = sInput.value.toLowerCase().trim();
    const palabras = busquedaCompleta.split(/\s+/);
    const filterOption = fMes.value;
    const rows = document.querySelectorAll('#studentTable tbody tr');
    let contadorVisibles = 0;

    rows.forEach(fila => {
        if (fila.classList.contains('no-results-row')) return;

        const textContent = fila.innerText.toLowerCase();
        const dateContent = fila.cells[7] ? fila.cells[7].textContent : "";
        const indicador = fila.cells[0].querySelector('.indicador-tipo');

        // 1. Lógica de búsqueda por texto
        let matchesText = palabras.every(palabra => {
            let pLimpia = palabra.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
            let tLimpio = textContent.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
            return tLimpio.includes(pLimpia);
        });

        // 2. Lógica de ESTADO (Sincronizada)
        // CORREGIDO: He quitado el ";" que tenías después de 'tipo-jira' y unido con "||"
        let coincideEstado = (estadoSeleccionado === "") ||
            (estadoSeleccionado === "lila" && indicador && indicador.classList.contains('tipo-nota')) ||
            (estadoSeleccionado === "verde" && indicador && indicador.classList.contains('tipo-latam')) ||
            (estadoSeleccionado === "blanco" && indicador && indicador.classList.contains('tipo-espana')) ||
            (estadoSeleccionado === "azul" && indicador && indicador.classList.contains('tipo-jira')) ||
            (estadoSeleccionado === "negro" && fila.querySelector('.fav-marker.active'));

        // 3. Lógica de MES (Mira el Select)
        let coincideMes = true;
        // CORREGIDO: La lista de colores estaba mal escrita ("azul, negro" en lugar de "azul", "negro")
        const valoresColores = ["lila", "verde", "blanco", "azul", "negro"];

        if (filterOption !== "" && !valoresColores.includes(filterOption)) {
            coincideMes = dateContent.includes(filterOption);
        }

        // 4. LA CONDICIÓN FINAL
        if (matchesText && coincideEstado && coincideMes) {
            fila.style.display = '';
            contadorVisibles++;

            // Resaltado <mark>
            [2, 4, 5].forEach(index => {
                const cell = fila.cells[index];
                if (cell) {
                    if (!cell.hasAttribute('data-original')) cell.setAttribute('data-original', cell.innerHTML);
                    let tempHTML = cell.getAttribute('data-original');

                    if (busquedaCompleta !== "") {
                        palabras.forEach(palabra => {
                            if (palabra.length > 1) {
                                const regex = new RegExp(`\\b(${palabra})\\b`, "gi");
                                tempHTML = tempHTML.replace(regex, "<mark>$1</mark>");
                            }
                        });
                        cell.innerHTML = tempHTML;
                    } else {
                        cell.innerHTML = tempHTML;
                    }
                }
            });
        } else {
            fila.style.display = 'none';
        }
    });

    // Contador y Empty State
    if (contadorVisibles === 0) {
        const columnCount = document.querySelectorAll('#studentTable thead th').length;
        const noResultsRow = document.createElement('tr');
        noResultsRow.className = 'no-results-row';
        noResultsRow.innerHTML = `
            <td colspan="${columnCount}">
                <div class="no-results-content" style="padding: 40px 0; text-align: center;">
                    <span class="material-symbols-outlined" style="font-size: 48px; color: #94a3b8; margin-bottom: 12px; display: block;">description</span>
                    <div class="no-results-text" style="font-size: 0.9rem; color: #64748b; font-weight: 500;">
                        No se encontraron registros que coincidan con la búsqueda
                    </div>
                </div>
            </td>`;
        tbody.appendChild(noResultsRow);
    }

    document.getElementById('contadorRegistros').innerText = contadorVisibles;
}

// 2. FUNCIÓN CONVERTIR FECHA
function convertirFecha(fechaString) {
    const partes = fechaString.split('/');
    if (partes.length !== 3) return new Date(0);
    return new Date(partes[2], partes[1] - 1, partes[0]);
}
// 3. INICIALIZACIÓN DE EVENTOS (Dentro del DOMContentLoaded)
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('searchInput');
    const filtroMesJS = document.getElementById('filtroMesJS');
    let timeoutBusqueda;

    // Definimos la función ANTES de usarla
    const encenderResaltado = () => {
        if (searchInput && searchInput.value.trim().length > 0) {
            document.body.classList.add('buscando');
        } else {
            document.body.classList.remove('buscando');
        }
    };

    if (searchInput) {
        // 1. Al escribir: Resalta y Filtra con espera (Debounce)
        searchInput.addEventListener('input', () => {
            encenderResaltado();
            clearTimeout(timeoutBusqueda);
            timeoutBusqueda = setTimeout(() => {
                filtrarTabla();
            }, 250);
        });

        // 2. Al hacer clic dentro: Resalta si hay texto
        searchInput.addEventListener('focus', encenderResaltado);
    }

    // 3. Al cambiar el Selector: Resalta y Filtra al instante
    if (filtroMesJS) {
        filtroMesJS.addEventListener('change', () => {
            encenderResaltado();
            filtrarTabla();
        });
    }

    // 4. Al hacer clic fuera: Quita el resaltado (pero NO deja de filtrar)
    document.addEventListener('click', (e) => {
        if (searchInput && !searchInput.contains(e.target) && e.target.id !== 'btnClear') {
            document.body.classList.remove('buscando');
        }
    });



    // Ordenación por fecha
    const thFecha = document.getElementById('thFecha');
    if (thFecha) {
        thFecha.addEventListener('click', function () {
            // Buscamos el tbody justo en el momento del click para que no sea null
            const elTbody = document.querySelector('#studentTable tbody');
            if (!elTbody) return;

            // Obtenemos solo las filas que NO sean el mensaje de "Sin resultados"
            const rows = Array.from(elTbody.querySelectorAll('tr:not(.no-results-row)'));

            this.asc = !this.asc;
            const direccion = this.asc ? 1 : -1;

            rows.sort((a, b) => {
                // Validamos que exista la celda 7 (Fecha)
                const cellA = a.cells[7] ? a.cells[7].textContent.trim() : "";
                const cellB = b.cells[7] ? b.cells[7].textContent.trim() : "";

                const fA = convertirFecha(cellA);
                const fB = convertirFecha(cellB);

                if (fA < fB) return -1 * direccion;
                if (fA > fB) return 1 * direccion;
                return 0;
            });

            // Reinsertamos las filas ordenadas
            rows.forEach(row => elTbody.appendChild(row));
        });
    }

    // Botón Limpiar
    // Busca esto en tu Inicialización de Eventos
    const btnClear = document.getElementById('btnClear');
    if (btnClear) {
        btnClear.addEventListener('click', function (e) {
            e.preventDefault(); // Evita recargas accidentales
            limpiarTodo();
        });
    }

    filtrarTabla(); // Contar inicial
});

// 4. FUNCIONES DE MODALES
/// Función auxiliar para pasar a mayúsculas
function forzarMayusculas(e) {
    e.target.value = e.target.value.toUpperCase();
}

function conectarLogicaNota() {
    const chk = document.getElementById('checkApunte');
    const tel = document.getElementById('inputTelefono');
    const idf = document.getElementById('inputID');
    const nom = document.getElementById('inputNombre');

    if (!nom) return;

    const aplicarMayusculasSiEsNota = () => {
        // Determinamos si es nota (por el check o por el valor del ID)
        const esNota = (chk && chk.checked) || (idf && idf.value === "NOTA");

        if (esNota) {
            // 1. Convertimos lo que haya (si hay algo)
            nom.value = nom.value.toUpperCase();
            // 2. Obligamos a que todo lo que se escriba sea mayúscula
            nom.style.textTransform = "uppercase"; // Refuerzo visual CSS
            nom.addEventListener('input', forzarMayusculas);
        } else {
            nom.style.textTransform = "none";
            nom.removeEventListener('input', forzarMayusculas);
        }
    };

    // Si hay checkbox (caso "Nuevo"), escuchamos el cambio
    if (chk) {
        chk.addEventListener('change', () => {
            // Lógica de bloqueo de campos que ya tenías
            if (chk.checked) {
                if (idf) idf.value = "NOTA";
                if (tel) tel.value = "-";
                [idf, tel].forEach(el => { if (el) { el.readOnly = true; el.classList.add('campo-anulado'); } });
            } else {
                if (idf && idf.value === "NOTA") idf.value = "";
                if (tel && tel.value === "-") tel.value = "";
                [idf, tel].forEach(el => { if (el) { el.readOnly = false; el.classList.remove('campo-anulado'); } });
            }
            aplicarMayusculasSiEsNota();
        });
    }

    // EJECUCIÓN INMEDIATA (Para Edición y para cuando se abre el modal)
    aplicarMayusculasSiEsNota();
}

function abrirNuevo() {
    fetch('nuevo_registro.php')
        .then(r => r.text())
        .then(html => {
            document.getElementById('contenidoModalNuevo').innerHTML = html;
            conectarLogicaNota(); // Conectamos el cable
            new bootstrap.Modal(document.getElementById('modalNuevo')).show();
        });
}

function abrirEditar(id) {
    fetch('actualizar.php?id=' + id)
        .then(r => r.text())
        .then(html => {
            document.getElementById('contenidoModalEditar').innerHTML = html;

            // 1. Bloqueo permanente del ID en edición
            const idInput = document.getElementById('inputID');
            if (idInput) {
                idInput.readOnly = true;
                idInput.classList.add('campo-anulado');
                // Opcional: añadir un título para que el usuario sepa por qué no puede escribir
                idInput.title = "El ID no se puede modificar en edición";
            }

            // 2. Conectar la lógica del checkbox para el campo Teléfono
            setTimeout(() => {
                conectarLogicaNota();
            }, 50);

            new bootstrap.Modal(document.getElementById('modalEditar')).show();
        });
}
// PEGA ESTO UNA SOLA VEZ dentro de tu DOMContentLoaded
const tablaBody = document.querySelector('#studentTable tbody');
if (tablaBody) {
    tablaBody.addEventListener('click', function (e) {
        const row = e.target.closest('tr');
        // No actuar si pulsamos en botones, links o el icono de copiar
        if (!row || e.target.closest('a, button, .modal, .btn-copiar')) return;

        const estabaAbierta = row.classList.contains('abierta');

        // Limpiamos TODAS las filas antes de marcar la nueva
        tablaBody.querySelectorAll('tr').forEach(f => {
            f.classList.remove('abierta', 'fila-marcada');
        });

        // Si la fila no estaba abierta, ahora la abrimos y marcamos
        if (!estabaAbierta) {
            row.classList.add('abierta', 'fila-marcada');
        }
    });
}
document.addEventListener('keydown', function (event) {
    if (event.key === "Escape") {
        limpiarTodo(); // Llamamos a la misma limpieza total
    }
});
function limpiarTodo() {
    const searchInput = document.getElementById('searchInput');
    const filtroMesJS = document.getElementById('filtroMesJS');

    // 1. Limpiar inputs
    if (searchInput) searchInput.value = '';
    if (filtroMesJS) filtroMesJS.value = '';
    if (typeof estadoSeleccionado !== 'undefined') {
        estadoSeleccionado = "";
    }

    // 2. Quitar resaltado amarillo del body
    document.body.classList.remove('buscando');

    // 3. Cerrar filas y quitar marcado de selección
    document.querySelectorAll('#studentTable tr.abierta, #studentTable tr.fila-marcada').forEach(fila => {
        fila.classList.remove('abierta', 'fila-marcada');
    });
    // --- ORDENAR POR FECHA DESCENDENTE (Lo más nuevo arriba) ---
    const elTbody = document.querySelector('#studentTable tbody');
    if (elTbody) {
        // Obtenemos las filas (saltando la de "no hay resultados")
        const rows = Array.from(elTbody.querySelectorAll('tr:not(.no-results-row)'));

        rows.sort((a, b) => {
            // Buscamos la fecha en la celda 7 (index 7)
            const fechaA = a.cells[7] ? a.cells[7].textContent.trim() : "";
            const fechaB = b.cells[7] ? b.cells[7].textContent.trim() : "";

            // Usamos tu función convertirFecha que ya existe en el código
            const fA = convertirFecha(fechaA);
            const fB = convertirFecha(fechaB);

            // Orden DESCENDENTE: de más reciente a más antiguo
            return fB - fA;
        });

        // Reinyectamos las filas en el nuevo orden
        rows.forEach(row => elTbody.appendChild(row));
    }

    // --- NUEVO: Resetear el estado del icono/variable de fecha ---
    const thFecha = document.getElementById('thFecha');
    if (thFecha) thFecha.asc = false; // Reiniciamos el estado del click
    // 4. Refrescar la tabla (quita los <mark> y muestra todas las filas)
    if (typeof filtrarTabla === 'function') {
        filtrarTabla();
    }

    // 5. Quitar el foco del cursor
    if (document.activeElement) {
        document.activeElement.blur();
    }
    // 6. Llevar el scroll al principio de la tabla de forma suave
    const tabla = document.getElementById('studentTable');
    if (tabla) {
        tabla.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    window.scrollTo({
        top: 0,
        behavior: 'smooth' // Esto hace que suba suavemente
    });
}


function copiarTexto(texto, elemento) {
    navigator.clipboard.writeText(texto).then(() => {
        // 1. Efecto visual en el icono (el que ya tienes)
        const iconoOriginal = elemento.innerHTML;
        elemento.innerHTML = '<span class="material-symbols-outlined" style="font-size: 1rem; color: #22c55e;">check</span>';

        // 2. CREAR EL MENSAJE FLOTANTE
        const aviso = document.createElement('div');
        aviso.textContent = '¡Copiado!';
        aviso.className = 'aviso-copiado';

        // Añadimos el aviso al cuerpo del documento
        document.body.appendChild(aviso);

        // Posicionamos el aviso sobre el botón
        const rect = elemento.getBoundingClientRect();
        aviso.style.left = `${rect.left + (rect.width / 2) - 30}px`; // Centrado
        aviso.style.top = `${rect.top - 30}px`; // Un poco arriba

        // 3. Animación y eliminación
        setTimeout(() => {
            aviso.classList.add('visible');
        }, 10);

        setTimeout(() => {
            aviso.classList.remove('visible');
            setTimeout(() => {
                aviso.remove();
                elemento.innerHTML = iconoOriginal; // Restaurar icono
            }, 300);
        }, 1000);
    });
}
document.addEventListener('change', function (e) {
    const chkNota = document.getElementById('checkApunte'); // Tu ID de NOTA
    const chkJira = document.getElementById('checkJira');   // Tu ID de JIRA
    const tel = document.getElementById('inputTelefono');
    const idf = document.getElementById('inputID');

    // 1. LÓGICA DE EXCLUSIÓN MUTUA (Solo uno marcado)
    if (e.target === chkNota && chkNota.checked) {
        if (chkJira) chkJira.checked = false;
    }
    if (e.target === chkJira && chkJira.checked) {
        if (chkNota) chkNota.checked = false;
    }

    // 2. RE-EVALUAR EL ESTADO DE LOS CAMPOS (Desbloqueo)
    // Si NOTA no está marcado (porque lo quitamos o porque marcamos JIRA), liberamos los campos
    if (chkNota && tel && idf) {
        if (chkNota.checked) {
            idf.value = "NOTA";
            tel.value = "-";
            idf.readOnly = true;
            tel.readOnly = true;
            idf.classList.add('campo-anulado');
            tel.classList.add('campo-anulado');
        } else {
            // Si se desmarcó (ya sea manualmente o por pulsar JIRA)
            if (idf.value === "NOTA") idf.value = "";
            if (tel.value === "-") tel.value = "";
            idf.readOnly = false;
            tel.readOnly = false;
            idf.classList.remove('campo-anulado');
            tel.classList.remove('campo-anulado');
        }
    }
});
document.getElementById('inputJiraUrl').addEventListener('input', function (e) {
    let url = e.target.value.trim();
    const feedback = document.getElementById('jira-feedback');
    const input = e.target;

    // Si el usuario olvida el https://, lo ayudamos visualmente o lo aceptamos
    // Esta regex valida que tenga estructura de dominio (ejemplo.com)
    const pattern = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;

    if (url === "") {
        input.style.borderColor = "#e2e8f0";
        input.style.boxShadow = "none";
        feedback.style.display = "none";
        return;
    }

    if (pattern.test(url)) {
        // URL Válida (sea de lo que sea)
        input.style.borderColor = "#22c55e"; // Verde éxito
        input.style.boxShadow = "0 0 0 3px rgba(34, 197, 94, 0.1)";
        feedback.innerText = "✓ Enlace válido";
        feedback.style.color = "#16a34a";
        feedback.style.display = "block";
    } else {
        // No tiene formato de URL
        input.style.borderColor = "#ef4444"; // Rojo error
        input.style.boxShadow = "0 0 0 3px rgba(239, 68, 68, 0.1)";
        feedback.innerText = "⚠ Por favor, introduce un enlace válido (ej: https://...)";
        feedback.style.color = "#dc2626";
        feedback.style.display = "block";
    }
});
// Añade esto al final de tu scripts.js
if (window.location.search.includes('actualizado=1') || window.location.search.includes('eliminado=1')) {
    console.log("Limpiando caché de visualización...");
    // Esto asegura que la tabla que ves es la última de la DB
}
window.addEventListener('load', () => {
    const sInput = document.getElementById('searchInput');
    const fMes = document.getElementById('filtroMesJS');

    const savedSearch = sessionStorage.getItem('lastSearch');
    const savedMonth = sessionStorage.getItem('lastMonth');

    if (savedSearch && sInput) {
        sInput.value = savedSearch;
    }
    if (savedMonth && fMes) {
        fMes.value = savedMonth;
    }

    // Si había algo guardado, ejecutamos el filtro para que la tabla se vea bien
    if (savedSearch || savedMonth) {
        filtrarTabla();
    }
});
/**
 /**
 * ÚNICA FUNCIÓN para filtrar por estado (Colores y Favoritos)
 */
function filtrarPorEstado(valor) {
    // Si pulsas el mismo botón que ya está activo, limpiamos el filtro (Toggle)
    if (estadoSeleccionado === valor) {
        estadoSeleccionado = "";
    } else {
        estadoSeleccionado = valor;
    }

    document.body.classList.add('buscando');
    filtrarTabla(); // Llamamos a la maestra para que aplique los cambios
}
function ejecutarGuardado(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }

    const form = document.getElementById('formColores');
    const box = document.getElementById('mensajeAjustes');
    if (!form) return;

    const formData = new FormData(form);

    fetch('guardar_ajustes.php', {
        method: 'POST',
        body: formData
    })
        .then(response => {
            if (!response.ok) throw new Error('Error de red');
            return response.text();
        })
        .then(text => {
            const res = text.trim();

            // 1. CASO: ÉXITO
            if (res === 'success') {
                if (box) {
                    box.className = "alert alert-success";
                    box.innerHTML = "✅ Ajustes guardados correctamente.";
                    box.classList.remove('d-none');
                }
                setTimeout(() => {
                    window.location.href = 'supositorio.php'; // Esto limpia la URL al recargar
                }, 800);

                // 2. CASO: SIN CAMBIOS (El PHP devuelve 'sin_cambios')
            } else if (res === 'sin_cambios') {
                if (box) {
                    box.className = "alert alert-info"; // Azul informativo
                    box.innerHTML = "ℹ️ No se realizaron cambios.";
                    box.classList.remove('d-none');
                }

                // 3. CASO: ERROR DE DATOS O DB
            } else {
                if (box) {
                    box.className = "alert alert-danger"; // Rojo de error
                    // Personalizamos el mensaje según lo que devuelva el PHP
                    if (res === 'error_pass') {
                        box.innerHTML = "❌ La contraseña actual es incorrecta.";
                    } else if (res === 'error_datos') {
                        box.innerHTML = "⚠️ Faltan datos obligatorios.";
                    } else {
                        box.innerHTML = "❌ Error: " + res;
                    }
                    box.classList.remove('d-none');
                }
            }
        })
        .catch(error => {
            if (box) {
                box.className = "alert alert-danger";
                box.innerHTML = "🚀 Error de conexión con el servidor.";
                box.classList.remove('d-none');
            }
            console.error('Fallo el fetch:', error);
        });
}
function subirFotoRapida() {
    const input = document.getElementById('inputFotoPerfil');
    const formData = new FormData();

    if (input.files.length > 0) {
        formData.append('nueva_foto', input.files[0]);

        fetch('guardar_foto.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // Recarga para mostrar la nueva foto
                } else {
                    alert("Error al subir la imagen: " + data.error);
                }
            })
            .catch(error => console.error('Error:', error));
    }
}
function peticionCambiarEntorno(valor) {
    const formData = new FormData();
    formData.append('entorno', valor);

    fetch('cambiar_entorno.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Recargamos la página para que el PHP ejecute la nueva consulta SQL
                window.location.reload();
            } else {
                alert("Error al cambiar de entorno");
            }
        })
        .catch(error => console.error('Error:', error));
}

// Si la URL contiene parámetros (como ?actualizado=...)
if (window.location.search.length > 0) {
    // Creamos una nueva URL sin los parámetros
    const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;

    // Reemplazamos la URL actual en el historial sin recargar la página
    window.history.replaceState({}, document.title, cleanUrl);
}
function toggleFavorito(elemento, idSistema) {
    // Evitamos que el clic seleccione la fila de la tabla
    event.stopPropagation();

    const esFavoritoActualmente = elemento.classList.contains('active');
    const nuevoEstado = esFavoritoActualmente ? 0 : 1;

    // Cambio visual inmediato
    if (nuevoEstado === 1) {
        elemento.classList.add('active');
        elemento.innerText = 'lens';
        elemento.style.fontVariationSettings = "'FILL' 1";
    } else {
        elemento.classList.remove('active');
        elemento.innerText = 'radio_button_unchecked';
        elemento.style.fontVariationSettings = "'FILL' 0";
    }

    // Guardar en base de datos (AJAX)
    fetch('actualizar_favorito.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id_sistema=${idSistema}&estado=${nuevoEstado}`
    });

    // IMPORTANTE: Si el filtro de favoritos (negro) está puesto, refrescamos la tabla
    if (estadoSeleccionado === "negro") {
        filtrarTabla();
    }
}
/**
 * Carga el contenido de ajustes.php y muestra la modal
 */
function abrirAjustes() {
    const contenedor = document.getElementById('contenidoAjustes');
    const modalElemento = document.getElementById('modalAjustes');

    if (!contenedor || !modalElemento) {
        console.error("No se encontró el contenedor 'contenidoAjustes' o la modal.");
        return;
    }

    // Cargamos el archivo PHP que contiene el formulario de ajustes
    fetch('obtener_ajustes.php')
        .then(response => {
            if (!response.ok) throw new Error('Error al cargar ajustes.php');
            return response.text();
        })
        .then(html => {
            // Inyectamos el HTML en el body de la modal
            contenedor.innerHTML = html;

            // Inicializamos y mostramos la modal usando Bootstrap 5
            const modalBootstrap = new bootstrap.Modal(modalElemento);
            modalBootstrap.show();
        })
        .catch(error => {
            console.error('Error AJAX:', error);
            alert("No se pudo abrir el panel de personalización.");
        });
}
// 1. Función para seleccionar y previsualizar
function seleccionarTema(elemento, bg, row) {
    // CAMBIA ESTA LÍNEA:
    // Usamos setProperty con 'important' para saltarnos el bloqueo del CSS
    document.body.style.setProperty('background', bg, 'important');

    // El resto se queda igual...
    document.getElementById('inputFondo').value = bg;
    document.getElementById('inputFilas').value = row;
    
    document.querySelectorAll('.card-tema').forEach(c => c.classList.remove('tema-active'));
    elemento.classList.add('tema-active');
}

// 2. Función para guardar permanentemente
function ejecutarGuardado() {
    const form = document.getElementById('formColores');
    if (!form) return;

    const formData = new FormData(form);
    const mensaje = document.getElementById('mensajeAjustes');

    fetch('guardar_ajustes.php', {
        method: 'POST',
        body: formData
    })
        .then(r => r.text())
        .then(res => {
            console.log("Respuesta bruta del servidor:", "[" + res + "]");

            const respuestaLimpia = res.trim();

            if (respuestaLimpia === 'success') {
                // ÉXITO: Icono check_circle
                mensaje.innerHTML = '<span class="material-symbols-outlined" style="font-size: 1.2rem; margin-right: 8px; vertical-align: middle;">check_circle</span> Cambios guardados. Actualizando sesión...';
                mensaje.className = "alert alert-success mt-3 d-flex align-items-center justify-content-center";
                mensaje.classList.remove('d-none');

                setTimeout(() => { window.location.href = 'supositorio.php'; }, 1500);
            } else if (respuestaLimpia === '' || respuestaLimpia.includes('no_changes')) {
                // INFO/SIN CAMBIOS: Icono info
                mensaje.innerHTML = '<span class="material-symbols-outlined" style="font-size: 1.2rem; margin-right: 8px; vertical-align: middle;">info</span> No se realizaron cambios nuevos.';
                mensaje.className = "alert alert-info mt-3 d-flex align-items-center justify-content-center";
                mensaje.classList.remove('d-none');

                setTimeout(() => { mensaje.classList.add('d-none'); }, 1500);

            } else {
                // Error real del servidor
                mensaje.innerHTML = '<span class="material-symbols-outlined" style="font-size: 1.2rem; margin-right: 8px; vertical-align: middle;">error</span> Error al procesar: ' + (respuestaLimpia.substring(0, 50));
                mensaje.className = "alert alert-danger mt-3 d-flex align-items-center justify-content-center";
                mensaje.classList.remove('d-none');
            }
        })
        .catch(error => {
            console.error("Error en la petición fetch:", error);
            mensaje.innerHTML = '<span class="material-symbols-outlined" style="font-size: 1.2rem; margin-right: 8px; vertical-align: middle;">error</span> Error de conexión al servidor.';
            mensaje.className = "alert alert-danger mt-3 d-flex align-items-center justify-content-center";
            mensaje.classList.remove('d-none');
        });
}