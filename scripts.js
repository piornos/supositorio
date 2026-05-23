let estadoSeleccionado = "";
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

        let matchesText = palabras.every(palabra => {
            let pLimpia = palabra.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
            let tLimpio = textContent.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
            return tLimpio.includes(pLimpia);
        });

        let coincideEstado = (estadoSeleccionado === "") ||
            (estadoSeleccionado === "lila" && indicador && indicador.classList.contains('tipo-nota')) ||
            (estadoSeleccionado === "verde" && indicador && indicador.classList.contains('tipo-latam')) ||
            (estadoSeleccionado === "blanco" && indicador && indicador.classList.contains('tipo-espana')) ||
            (estadoSeleccionado === "azul" && indicador && indicador.classList.contains('tipo-jira')) ||
            (estadoSeleccionado === "negro" && fila.querySelector('.fav-marker.active'));

        let coincideMes = true;
        const valoresColores = ["lila", "verde", "blanco", "azul", "negro"];

        if (filterOption !== "" && !valoresColores.includes(filterOption)) {
            coincideMes = dateContent.includes(filterOption);
        }

        if (matchesText && coincideEstado && coincideMes) {
            fila.style.display = '';
            contadorVisibles++;

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

function convertirFecha(fechaString) {
    const partes = fechaString.split('/');
    if (partes.length !== 3) return new Date(0);
    return new Date(partes[2], partes[1] - 1, partes[0]);
}
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('searchInput');
    const filtroMesJS = document.getElementById('filtroMesJS');
    let timeoutBusqueda;

    const encenderResaltado = () => {
        if (searchInput && searchInput.value.trim().length > 0) {
            document.body.classList.add('buscando');
        } else {
            document.body.classList.remove('buscando');
        }
    };

    if (searchInput) {
        searchInput.addEventListener('input', () => {
            encenderResaltado();
            clearTimeout(timeoutBusqueda);
            timeoutBusqueda = setTimeout(() => {
                filtrarTabla();
            }, 250);
        });

        searchInput.addEventListener('focus', encenderResaltado);
    }

    if (filtroMesJS) {
        filtroMesJS.addEventListener('change', () => {
            encenderResaltado();
            filtrarTabla();
        });
    }

    document.addEventListener('click', (e) => {
        if (searchInput && !searchInput.contains(e.target) && e.target.id !== 'btnClear') {
            document.body.classList.remove('buscando');
        }
    });



    const thFecha = document.getElementById('thFecha');
    if (thFecha) {
        thFecha.addEventListener('click', function () {
            const elTbody = document.querySelector('#studentTable tbody');
            if (!elTbody) return;

            const rows = Array.from(elTbody.querySelectorAll('tr:not(.no-results-row)'));

            this.asc = !this.asc;
            const direccion = this.asc ? 1 : -1;

            rows.sort((a, b) => {
                const cellA = a.cells[7] ? a.cells[7].textContent.trim() : "";
                const cellB = b.cells[7] ? b.cells[7].textContent.trim() : "";

                const fA = convertirFecha(cellA);
                const fB = convertirFecha(cellB);

                if (fA < fB) return -1 * direccion;
                if (fA > fB) return 1 * direccion;
                return 0;
            });

            rows.forEach(row => elTbody.appendChild(row));
        });
    }

    const btnClear = document.getElementById('btnClear');
    if (btnClear) {
        btnClear.addEventListener('click', function (e) {
            e.preventDefault(); 
            limpiarTodo();
        });
    }

    filtrarTabla(); 
});

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
        const esNota = (chk && chk.checked) || (idf && idf.value === "NOTA");

        if (esNota) {
            nom.value = nom.value.toUpperCase();
            nom.style.textTransform = "uppercase"; 
            nom.addEventListener('input', forzarMayusculas);
        } else {
            nom.style.textTransform = "none";
            nom.removeEventListener('input', forzarMayusculas);
        }
    };

    if (chk) {
        chk.addEventListener('change', () => {
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

    aplicarMayusculasSiEsNota();
}

function abrirNuevo() {
    fetch('nuevo_registro.php')
        .then(r => r.text())
        .then(html => {
            document.getElementById('contenidoModalNuevo').innerHTML = html;
            conectarLogicaNota(); 
            new bootstrap.Modal(document.getElementById('modalNuevo')).show();
        });
}

function abrirEditar(id) {
    fetch('actualizar.php?id=' + id)
        .then(r => r.text())
        .then(html => {
            document.getElementById('contenidoModalEditar').innerHTML = html;

            const idInput = document.getElementById('inputID');
            if (idInput) {
                idInput.readOnly = true;
                idInput.classList.add('campo-anulado');
                idInput.title = "El ID no se puede modificar en edición";
            }

            setTimeout(() => {
                conectarLogicaNota();
            }, 50);

            new bootstrap.Modal(document.getElementById('modalEditar')).show();
        });
}
const tablaBody = document.querySelector('#studentTable tbody');
if (tablaBody) {
    tablaBody.addEventListener('click', function (e) {
        const row = e.target.closest('tr');
        if (!row || e.target.closest('a, button, .modal, .btn-copiar')) return;

        const estabaAbierta = row.classList.contains('abierta');

        tablaBody.querySelectorAll('tr').forEach(f => {
            f.classList.remove('abierta', 'fila-marcada');
        });

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

    if (searchInput) searchInput.value = '';
    if (filtroMesJS) filtroMesJS.value = '';
    if (typeof estadoSeleccionado !== 'undefined') {
        estadoSeleccionado = "";
    }

    document.body.classList.remove('buscando');

    document.querySelectorAll('#studentTable tr.abierta, #studentTable tr.fila-marcada').forEach(fila => {
        fila.classList.remove('abierta', 'fila-marcada');
    });
    const elTbody = document.querySelector('#studentTable tbody');
    if (elTbody) {
        const rows = Array.from(elTbody.querySelectorAll('tr:not(.no-results-row)'));

        rows.sort((a, b) => {
            const fechaA = a.cells[7] ? a.cells[7].textContent.trim() : "";
            const fechaB = b.cells[7] ? b.cells[7].textContent.trim() : "";

            const fA = convertirFecha(fechaA);
            const fB = convertirFecha(fechaB);

            return fB - fA;
        });

        rows.forEach(row => elTbody.appendChild(row));
    }

    const thFecha = document.getElementById('thFecha');
    if (thFecha) thFecha.asc = false; 
    if (typeof filtrarTabla === 'function') {
        filtrarTabla();
    }

    if (document.activeElement) {
        document.activeElement.blur();
    }
    const tabla = document.getElementById('studentTable');
    if (tabla) {
        tabla.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    window.scrollTo({
        top: 0,
        behavior: 'smooth' 
    });
}


function copiarTexto(texto, elemento) {
    navigator.clipboard.writeText(texto).then(() => {
        const iconoOriginal = elemento.innerHTML;
        elemento.innerHTML = '<span class="material-symbols-outlined" style="font-size: 1rem; color: #22c55e;">check</span>';

        const aviso = document.createElement('div');
        aviso.textContent = '¡Copiado!';
        aviso.className = 'aviso-copiado';

        document.body.appendChild(aviso);

        const rect = elemento.getBoundingClientRect();
        aviso.style.left = `${rect.left + (rect.width / 2) - 30}px`; 
        aviso.style.top = `${rect.top - 30}px`; 

        setTimeout(() => {
            aviso.classList.add('visible');
        }, 10);

        setTimeout(() => {
            aviso.classList.remove('visible');
            setTimeout(() => {
                aviso.remove();
                elemento.innerHTML = iconoOriginal; 
            }, 300);
        }, 1000);
    });
}
document.addEventListener('change', function (e) {
    const chkNota = document.getElementById('checkApunte'); 
    const chkJira = document.getElementById('checkJira');   /
    const tel = document.getElementById('inputTelefono');
    const idf = document.getElementById('inputID');

    if (e.target === chkNota && chkNota.checked) {
        if (chkJira) chkJira.checked = false;
    }
    if (e.target === chkJira && chkJira.checked) {
        if (chkNota) chkNota.checked = false;
    }

    if (chkNota && tel && idf) {
        if (chkNota.checked) {
            idf.value = "NOTA";
            tel.value = "-";
            idf.readOnly = true;
            tel.readOnly = true;
            idf.classList.add('campo-anulado');
            tel.classList.add('campo-anulado');
        } else {
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

    const pattern = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;

    if (url === "") {
        input.style.borderColor = "#e2e8f0";
        input.style.boxShadow = "none";
        feedback.style.display = "none";
        return;
    }

    if (pattern.test(url)) {
        input.style.borderColor = "#22c55e";
        input.style.boxShadow = "0 0 0 3px rgba(34, 197, 94, 0.1)";
        feedback.innerText = "✓ Enlace válido";
        feedback.style.color = "#16a34a";
        feedback.style.display = "block";
    } else {
        input.style.borderColor = "#ef4444"; 
        input.style.boxShadow = "0 0 0 3px rgba(239, 68, 68, 0.1)";
        feedback.innerText = "⚠ Por favor, introduce un enlace válido (ej: https://...)";
        feedback.style.color = "#dc2626";
        feedback.style.display = "block";
    }
});
if (window.location.search.includes('actualizado=1') || window.location.search.includes('eliminado=1')) {
    console.log("Limpiando caché de visualización...");
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

    if (savedSearch || savedMonth) {
        filtrarTabla();
    }
});

function filtrarPorEstado(valor) {
    if (estadoSeleccionado === valor) {
        estadoSeleccionado = "";
    } else {
        estadoSeleccionado = valor;
    }

    document.body.classList.add('buscando');
    filtrarTabla(); 
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

            if (res === 'success') {
                if (box) {
                    box.className = "alert alert-success";
                    box.innerHTML = "✅ Ajustes guardados correctamente.";
                    box.classList.remove('d-none');
                }
                setTimeout(() => {
                    window.location.href = 'supositorio.php'; 
                }, 800);

            } else if (res === 'sin_cambios') {
                if (box) {
                    box.className = "alert alert-info"; 
                    box.innerHTML = "ℹ️ No se realizaron cambios.";
                    box.classList.remove('d-none');
                }

            } else {
                if (box) {
                    box.className = "alert alert-danger"; 
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
                    location.reload(); 
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
                window.location.reload();
            } else {
                alert("Error al cambiar de entorno");
            }
        })
        .catch(error => console.error('Error:', error));
}

if (window.location.search.length > 0) {
    const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;

    window.history.replaceState({}, document.title, cleanUrl);
}
function toggleFavorito(elemento, idSistema) {
    event.stopPropagation();

    const esFavoritoActualmente = elemento.classList.contains('active');
    const nuevoEstado = esFavoritoActualmente ? 0 : 1;

    if (nuevoEstado === 1) {
        elemento.classList.add('active');
        elemento.innerText = 'lens';
        elemento.style.fontVariationSettings = "'FILL' 1";
    } else {
        elemento.classList.remove('active');
        elemento.innerText = 'radio_button_unchecked';
        elemento.style.fontVariationSettings = "'FILL' 0";
    }

    fetch('actualizar_favorito.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id_sistema=${idSistema}&estado=${nuevoEstado}`
    });

    if (estadoSeleccionado === "negro") {
        filtrarTabla();
    }
}

function abrirAjustes() {
    const contenedor = document.getElementById('contenidoAjustes');
    const modalElemento = document.getElementById('modalAjustes');

    if (!contenedor || !modalElemento) {
        console.error("No se encontró el contenedor 'contenidoAjustes' o la modal.");
        return;
    }

    fetch('obtener_ajustes.php')
        .then(response => {
            if (!response.ok) throw new Error('Error al cargar ajustes.php');
            return response.text();
        })
        .then(html => {
            contenedor.innerHTML = html;

            const modalBootstrap = new bootstrap.Modal(modalElemento);
            modalBootstrap.show();
        })
        .catch(error => {
            console.error('Error AJAX:', error);
            alert("No se pudo abrir el panel de personalización.");
        });
}
function seleccionarTema(elemento, bg, row) {
    document.body.style.setProperty('background', bg, 'important');

    document.getElementById('inputFondo').value = bg;
    document.getElementById('inputFilas').value = row;
    
    document.querySelectorAll('.card-tema').forEach(c => c.classList.remove('tema-active'));
    elemento.classList.add('tema-active');
}

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
                mensaje.innerHTML = '<span class="material-symbols-outlined" style="font-size: 1.2rem; margin-right: 8px; vertical-align: middle;">check_circle</span> Cambios guardados. Actualizando sesión...';
                mensaje.className = "alert alert-success mt-3 d-flex align-items-center justify-content-center";
                mensaje.classList.remove('d-none');

                setTimeout(() => { window.location.href = 'supositorio.php'; }, 1500);
            } else if (respuestaLimpia === '' || respuestaLimpia.includes('no_changes')) {
                mensaje.innerHTML = '<span class="material-symbols-outlined" style="font-size: 1.2rem; margin-right: 8px; vertical-align: middle;">info</span> No se realizaron cambios nuevos.';
                mensaje.className = "alert alert-info mt-3 d-flex align-items-center justify-content-center";
                mensaje.classList.remove('d-none');

                setTimeout(() => { mensaje.classList.add('d-none'); }, 1500);

            } else {
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