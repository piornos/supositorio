<?php
session_start();
include("conexion.php");
$con = conectar();
$usuario = $_SESSION['usuario'];

$sql = "SELECT color_fondo, color_filas FROM usuarios WHERE usuario = '$usuario'";
$res = mysqli_query($con, $sql);
$u = mysqli_fetch_assoc($res);
$fondo_actual = $u['color_fondo'] ?? '#e2e8f0';
$filas_actual = $u['color_filas'] ?? '#ffffff';
?>

<div id="mensajeAjustes" class="alert d-none" style="font-size: 0.85rem; border-radius: 8px; margin-bottom: 15px;"></div>

<form id="formColores">
    <label class="form-label small fw-bold text-muted mb-3" style="letter-spacing: 1px; text-transform: uppercase; font-size: 0.7rem;">Estilos Disponibles</label>

    <div style="max-height: 320px; overflow-y: auto; padding-right: 8px; margin-bottom: 20px;">
        <div class="row g-3">
            <?php
            $temas = [
                ['nombre' => 'Ártico', 'bg' => '#e2e8f0', 'row' => '#ffffff', 'desc' => 'Clásico'],
                ['nombre' => 'Noche', 'bg' => '#0f172a', 'row' => '#1e293b', 'desc' => 'Deep Blue'],
                ['nombre' => 'Sepia', 'bg' => '#f4ecd8', 'row' => '#fdfaf1', 'desc' => 'Papel'],
                ['nombre' => 'Zinc', 'bg' => '#18181b', 'row' => '#27272a', 'desc' => 'Industrial'],
                ['nombre' => 'Nord', 'bg' => '#2e3440', 'row' => '#3b4252', 'desc' => 'Polar'],
                ['nombre' => 'Obsidiana', 'bg' => '#000000', 'row' => '#0a0a0a', 'desc' => 'Puro'],
                ['nombre' => 'Arcilla', 'bg' => '#fff7ed', 'row' => '#ffffff', 'desc' => 'Cálido'],
                ['nombre' => 'Drácula', 'bg' => '#282a36', 'row' => '#44475a', 'desc' => 'Vampiro'],
                ['nombre' => 'Esmaltado', 'bg' => '#f1f5f9', 'row' => '#ffffff', 'desc' => 'Limpio'],
                ['nombre' => 'Menta', 'bg' => '#ecfdf5', 'row' => '#ffffff', 'desc' => 'Fresco'],
                ['nombre' => 'Café', 'bg' => '#1c1917', 'row' => '#292524', 'desc' => 'Tostado'],

                ['nombre' => 'Aurora', 'bg' => 'linear-gradient(135deg, #0f172a 0%, #213d8c 100%)', 'row' => 'rgba(255,255,255,0.05)', 'desc' => 'Gradiente'],
                ['nombre' => 'Atardecer', 'bg' => 'linear-gradient(135deg, #1e293b 0%, #4c1d95 100%)', 'row' => 'rgba(255,255,255,0.05)', 'desc' => 'Místico'],
                ['nombre' => 'Bosque', 'bg' => 'linear-gradient(135deg, #064e3b 0%, #022c22 100%)', 'row' => 'rgba(255,255,255,0.05)', 'desc' => 'Orgánico'],
                ['nombre' => 'Amanecer', 'bg' => 'linear-gradient(135deg, #ffffff 0%, #d9e2ee 100%)', 'row' => '#ffffff', 'desc' => 'Suave'],
                ['nombre' => 'Vértigo', 'bg' => 'linear-gradient(135deg, #171f30 0%, #000000 100%)', 'row' => '#1f2937', 'desc' => 'Elegante'],
                ['nombre' => 'Seda', 'bg' => 'linear-gradient(135deg, #efeae6 0%, #949495 100%)', 'row' => '#f8f9fa', 'desc' => 'Texturizado'],
                ['nombre' => 'Ciberpunk', 'bg' => 'linear-gradient(135deg, #2e1065 0%, #000000 100%)', 'row' => 'rgba(216,180,254,0.05)', 'desc' => 'Neón'],
                ['nombre' => 'Océano', 'bg' => 'linear-gradient(135deg, #083344 0%, #164e63 100%)', 'row' => 'rgba(255,255,255,0.03)', 'desc' => 'Profundo'],
                ['nombre' => 'Cosmos', 'bg' => 'linear-gradient(135deg, #020617 0%, #312e81 100%)', 'row' => 'rgba(255,255,255,0.05)', 'desc' => 'Espacial'],
                ['nombre' => 'Flamingo', 'bg' => 'linear-gradient(135deg, #4c0519 0%, #831843 100%)', 'row' => 'rgba(255,255,255,0.05)', 'desc' => 'Cálido'],
                ['nombre' => 'Bruma', 'bg' => 'linear-gradient(135deg, #312e81 0%, #581c87 100%)', 'row' => 'rgba(255,255,255,0.05)', 'desc' => 'Nocturno'],

                ['nombre' => 'Matriz', 'bg' => '#000000 radial-gradient(#00ff41 0.5px, transparent 0.5px)', 'row' => 'rgba(0,0,0,0.7)', 'desc' => 'Terminal'],
                ['nombre' => 'Ladrillo', 'bg' => 'linear-gradient(30deg, #444 12%, transparent 12.5%, transparent 87%, #444 87.5%, #444), linear-gradient(150deg, #444 12%, transparent 12.5%, transparent 87%, #444 87.5%, #444), linear-gradient(30deg, #444 12%, transparent 12.5%, transparent 87%, #444 87.5%, #444), linear-gradient(150deg, #444 12%, transparent 12.5%, transparent 87%, #444 87.5%, #444), linear-gradient(60deg, #999 25%, transparent 25.5%, transparent 75%, #999 75%, #999), linear-gradient(60deg, #999 25%, transparent 25.5%, transparent 75%, #999 75%, #999)', 'row' => 'rgba(255,255,255,0.1)', 'desc' => 'Geométrico'],
                ['nombre' => 'Diagonal', 'bg' => 'repeating-linear-gradient(45deg, #2b2d42, #2b2d42 10px, #1d1e33 10px, #1d1e33 20px)', 'row' => 'rgba(255,255,255,0.05)', 'desc' => 'Rayado'],
                ['nombre' => 'Jeans', 'bg' => 'linear-gradient(90deg, rgba(50,100,150,0.5) 50%, transparent 50%), linear-gradient(rgba(50,100,150,0.5) 50%, transparent 50%)', 'row' => 'rgba(255,255,255,0.1)', 'desc' => 'Tejido'],
                [
                    'nombre' => 'Cebra Dark',
                    'bg' => 'repeating-linear-gradient(45deg, #000 0, #000 20px, #111 20px, #111 40px)',
                    'row' => 'rgba(255,255,255,0.05)',
                    'desc' => 'Rayado Pro'
                ],
                [
                    'nombre' => 'Radiactivo',
                    'bg' => '#000000 repeating-radial-gradient(circle at 50% 50%, #1a2e05 0%, #000 10%)',
                    'row' => 'rgba(163,230,53,0.05)',
                    'desc' => 'Ondas'
                ],
                [
                    'nombre' => 'Escamas',
                    'bg' => 'radial-gradient(circle at 100% 150%, #222 24%, #333 25%, #333 28%, #222 29%, #222 36%, #333 36%, #333 40%, transparent 40%), radial-gradient(circle at 0 150%, #222 24%, #333 25%, #333 28%, #222 29%, #222 36%, #333 36%, #333 40%, transparent 40%), radial-gradient(circle at 50% 100%, #333 10%, #222 11%, #222 23%, #333 24%, #333 30%, #222 31%, #222 43%, #333 44%, #333 50%, transparent 50%)',
                    'row' => 'rgba(0,0,0,0.5)',
                    'desc' => 'Armadura'
                ],
            ];

            foreach ($temas as $t):
                $active = ($fondo_actual == $t['bg']) ? 'tema-active' : '';
            ?>
                <div class="col-6">
                    <div class="card-tema <?php echo $active; ?>" onclick="seleccionarTema(this, '<?php echo $t['bg']; ?>', '<?php echo $t['row']; ?>')">
                        <div class="preview-ventana" style="background: <?php echo $t['bg']; ?>;">
                            <div class="preview-fila" style="background: <?php echo $t['row']; ?>; border: 1px solid rgba(128,128,128,0.1);"></div>
                            <div class="preview-fila" style="background: <?php echo $t['row']; ?>; border: 1px solid rgba(128,128,128,0.1);"></div>
                            <div class="preview-fila" style="background: <?php echo $t['row']; ?>; border: 1px solid rgba(128,128,128,0.1);"></div>
                        </div>
                        <div class="p-2 text-center border-top bg-white">
                            <div class="fw-bold" style="font-size: 0.75rem; color: #334155;"><?php echo $t['nombre']; ?></div>
                            <div class="text-muted" style="font-size: 0.6rem;"><?php echo $t['desc']; ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <input type="hidden" name="color_fondo" id="inputFondo" value="<?php echo htmlspecialchars($fondo_actual); ?>">
    <input type="hidden" name="color_filas" id="inputFilas" value="<?php echo htmlspecialchars($filas_actual); ?>">
             <label class="form-label small fw-bold text-muted mb-3" style="letter-spacing: 1px; text-transform: uppercase; font-size: 0.7rem;">Acceso</label>

<div class="p-3 rounded-3 mb-3" style="border: 1px solid rgba(255,255,255,0.1); background-color: transparent;">

    <input type="password" name="pass_actual" class="form-control form-control-sm mb-2" 
           placeholder="Contraseña actual" 
           style="background-color: white !important; color: black !important; opacity: 1 !important;">

    <div class="row g-2">
        <div class="col-6">
            <input type="password" name="pass_nueva" class="form-control form-control-sm" 
                   placeholder="Nueva Contraseña" 
                   style="background-color: white !important; color: black !important; opacity: 1 !important;">
        </div>
        <div class="col-6">
            <input type="password" name="pass_confirmar" class="form-control form-control-sm" 
                   placeholder="Repetir Contraseña" 
                   style="background-color: white !important; color: black !important; opacity: 1 !important;">
        </div>
    </div>
</div>


    <div class="row g-2">
        <div class="col-6"><button type="button" class="btn-cancel" data-bs-dismiss="modal" style="width: 100%;">Cerrar</button></div>
        <div class="col-6"><button type="button" onclick="ejecutarGuardado(); return false;" class="btn-save" style="width: 100%;">Aplicar</button></div>
    </div>
</form>

<style>
    div::-webkit-scrollbar {
        width: 4px;
    }

    div::-webkit-scrollbar-track {
        background: transparent;
    }

    div::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .card-tema {
        border: 2px solid #f1f5f9;
        border-radius: 12px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #fff;
    }

    .card-tema:hover {
        transform: translateY(-3px);
        border-color: #cbd5e1;
    }

    .tema-active {
        border-color: #475569 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .preview-ventana {
        height: 50px;
        padding: 8px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .preview-fila {
        height: 8px;
        width: 100%;
        border-radius: 2px;
    }
</style>