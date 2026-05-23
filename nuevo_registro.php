<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    exit;
}
?>
<style>
        .tema-inmune {
            background-color: #ffffff !important;
            color: #55575a !important;
            backdrop-filter: none !important;
            border: 1px solid #e2e8f0 !important;
        }

        .tema-inmune p,
        .tema-inmune span,
        .tema-inmune h1 {
            color: #55575a !important;
        }
        
</style>
<div class="custom-modal-body">
    <h3 style="font-weight: 400; font-size: 1.3rem; color: #1e293b; margin-bottom: 20px; text-align: center;">
        <label class="text-muted">Añadir Registro</label>
    </h3>
    <form action="insertar.php" method="POST" enctype="multipart/form-data">
        <div class="row g-2">
            <div class="col-4">
                <label class="text-muted">ID<span style="color: #dc2626;">*</span></label>
                <input type="text" name="ID" id="inputID" class="tema-inmune form-control" required>
            </div>
            <div class="col-8">
                <label class="text-muted">Nombre</label>
                <input type="text" class="tema-inmune form-control" name="nombre" id="inputNombre">
            </div>
        </div>

        <div style="display: flex; gap: 8px; margin-bottom: 15px;">
            <div class="tip-container" id="containerNotas" style="flex: 1; margin-bottom: 0;">
                <input type="checkbox" id="checkApunte"
                    onclick="
                if(this.checked) { 
                    document.getElementById('checkJira').checked = false;
                    document.getElementById('bloque-archivos').style.display = 'block';
                    document.getElementById('bloque-jira').style.display = 'none';
                    document.getElementById('inputJiraUrl').required = false;
                }
                toggleNotas(this);
            "
                    style="width: 14px; height: 14px; cursor: pointer;">
                <label for="checkApunte">Marcar como NOTA</label>
            </div>

            <div class="tip-container" id="containerJira" style="flex: 1; margin-bottom: 0;">
                <input type="checkbox" id="checkJira" name="jira"
                    onclick="
                if(this.checked) {
                    document.getElementById('checkApunte').checked = false;
                    if(typeof toggleNotas === 'function') { toggleNotas(document.getElementById('checkApunte')); }
                }
                document.getElementById('bloque-archivos').style.display = this.checked ? 'none' : 'block';
                document.getElementById('bloque-jira').style.display = this.checked ? 'block' : 'none';
                document.getElementById('inputJiraUrl').required = this.checked;
            "
                    style="width: 14px; height: 14px; cursor: pointer; accent-color: #2563eb;">
                <label for="checkJira">Marcar como JIRA</label>
            </div>
        </div>

        <div class="row g-2">
            <div class="col-4">
                <label class="text-muted">Teléfono<span style="color: #dc2626;">*</span></label>
                <input type="text"
                    class="tema-inmune form-control"
                    name="telefono"
                    id="inputTelefono"
                    inputmode="numeric"
                    pattern="[0-9+]+"
                    title="Por favor, introduce solo números o el símbolo +"
                    onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 43"
                    required>
            </div>
            <div class="col-8">
                <label class="text-muted">Consulta</label>
                <input type="text" class="tema-inmune form-control" name="consulta">
            </div>
        </div>

        <div class="mb-1">
            <label class="text-muted">Resolución</label>
            <textarea
                name="solucion"
                class="tema-inmune form-control"
                rows="3"
                style="resize: vertical; min-height: 80px;"></textarea>
        </div>

        <div id="bloque-archivos">
            <label class="text-muted">Archivos</label>
            <input type="file" name="adjunto[]" class="tema-inmune form-control" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx,.zip,.rar">
            <div style="margin-top: -8px; margin-bottom: 10px; margin-left: 15px;">
                <small style="font-size: 0.65rem; color: #94a3b8;">Permite selección múltiple.</small>
            </div>
        </div>

        <div id="bloque-jira" style="display: none;">
            <label class="text-muted" style="color: #64748b;">Enlace del Ticket JIRA</label>
            <input type="url" id="inputJiraUrl" name="jira_url"
                class="tema-inmune form-control"
                placeholder="Añade URL..."
                onblur="if(this.value.trim() !== '' && !/^https?:\/\"
                style="font-size: 0.85rem; border: 1px solid #e2e8f0;">
            <div id="jira-feedback" style="font-size: 0.7rem; margin-top: 4px; display: none;"></div>
            <div style="margin-top: -8px; margin-bottom: 10px; margin-left: 15px;">
                <small style="font-size: 0.65rem; color: #94a3b8;">Introduce la URL de JIRA para este registro.</small>
            </div>
        </div>

        <div class="row g-2 mt-3">
            <div class="col-6"><button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancelar</button></div>
            <div class="col-6"><button type="submit" class="btn-save">Guardar</button></div>
        </div>
    </form>
</div>

<script>
    function toggleNotas(checkbox) {
        var inputID = document.getElementById('inputID');
        var inputTel = document.getElementById('inputTelefono');
        if (checkbox.checked) {
            inputID.value = 'NOTA';
            inputTel.value = '-';
            inputID.classList.add('bg-light');
            inputTel.classList.add('bg-light');
        } else {
            inputID.value = '';
            inputTel.value = '';
            inputID.classList.remove('bg-light');
            inputTel.classList.remove('bg-light');
        }
    }
    document.querySelector('form').addEventListener('submit', function(e) {
        let resoluciónInput = this.querySelector('textarea[name="solucion"]');
        let texto = resoluciónInput.value.trim();

        if (texto.length > 0 && !texto.endsWith('.')) {
            resoluciónInput.value = texto + '.';
        }
    });
</script>