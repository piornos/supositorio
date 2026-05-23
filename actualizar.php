<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    exit("Acceso denegado");
}

include("conexion.php");
$con = conectar();

$id = mysqli_real_escape_string($con, $_GET['id']);

$sql = "SELECT * FROM supositorio WHERE id_sistema='$id'";
$query = mysqli_query($con, $sql);
$row = mysqli_fetch_array($query);

$esNota = (trim($row['ID']) === 'NOTA' || strpos($row['ID'], 'NOTA-') === 0 || $row['telefono'] === '-');
$claseGris = $esNota ? 'campo-anulado' : '';
$esReadOnly = $esNota ? 'readonly' : '';
$url_jira_actual = trim($row['jira_url'] ?? '');
$esJira = !empty($url_jira_actual);
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
    <h3 style="font-weight: 400; font-size: 1.3rem; color: #1e293b; margin-bottom: 25px; text-align: center; letter-spacing: -0.5px;">
        <label class="text-muted">Editar Registro</label>
    </h3>
    <form action="update.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_sistema" value="<?php echo $row['id_sistema']; ?>">

        <div class="row g-2">
            <div class="col-4">
                <label class="text-muted">ID</label>
                <input type="text" name="ID" id="inputID" class="tema-inmune form-control <?php echo $claseGris; ?>"
                    value="<?php echo htmlspecialchars($row['ID'], ENT_QUOTES, 'UTF-8'); ?>" <?php echo $esReadOnly; ?>>
            </div>
            <div class="col-8">
                <label class="text-muted">Nombre</label>
                <input type="text" class="tema-inmune form-control" name="nombre"
                    value="<?php echo htmlspecialchars($row['nombre'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
        </div>

        <div class="row g-2">
            <div class="col-5">
                <label class="text-muted">Teléfono <span style="color: #ef4444;">*</span></label>
                <input type="text"
                    name="telefono"
                    id="inputTelefono"
                    class="tema-inmune tema-inmune form-control <?php echo $claseGris; ?>"
                    value="<?php echo htmlspecialchars($row['telefono'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    <?php echo $esReadOnly; ?>
                    inputmode="numeric"
                    onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 43"
                    required>
            </div>
            <div class="col-7">
                <label class="text-muted">Consulta</label>
                <input type="text" class="tema-inmune form-control" name="consulta"
                    value="<?php echo htmlspecialchars($row['consulta'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
        </div>

        <div class="mb-2">
            <label class="text-muted">Resolución</label>
            <textarea name="solucion" class="tema-inmune form-control" rows="3" style="resize: vertical; min-height: 80px;"><?php echo htmlspecialchars($row['solucion'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
        </div>

        <?php if ($esJira): ?>
            <div class="mb-3">
                <label class="text-muted">Ticket JIRA</label>
                <input type="text" name="jira_url" class="form-control" value="<?php echo htmlspecialchars($url_jira_actual, ENT_QUOTES, 'UTF-8'); ?>">
            </div>
        <?php else: ?>
            <div class="mb-3 px-2">
                <label class="text-muted">Adjuntos</label>
                <div id="lista-adjuntos" class="mb-2">
                    <?php
                    if (!empty($row['adjunto'])) {
                        $imagenes = explode(",", $row['adjunto']);
                        foreach ($imagenes as $img) {
                            $img = trim($img);
                            if (!empty($img)) {
                                echo '<div class="contenedor-adjunto">
                                    <span style="font-size: 0.7rem; color: #475569;">' . htmlspecialchars($img) . '</span>
                                    <input type="checkbox" name="eliminar_fotos[]" value="' . htmlspecialchars($img) . '" style="display: none;">
                                    <label onclick="this.parentElement.style.display=\'none\'; this.previousElementSibling.checked=true;" 
                                           style="margin-left: 8px; cursor: pointer; color: #ef4444; font-weight: bold; font-size: 1rem;">
                                           &times;
                                    </label>
                                </div>';
                            }
                        }
                    }
                    ?>
                </div>
                <input type="file" name="adjunto[]" class="tema-inmune form-control" multiple>
            </div>
        <?php endif; ?>

        <div class="row g-3 mt-3">
            <div class="col-6">
                <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancelar</button>
            </div>
            <div class="col-6">
                <button type="submit" class="btn-save">Actualizar</button>
            </div>
        </div>
    </form>
</div>