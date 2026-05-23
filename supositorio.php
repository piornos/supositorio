<?php
session_start();
date_default_timezone_set('Europe/Madrid');
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

include("conexion.php");
$con = conectar();
$usuario_actual = $_SESSION['usuario'];
// Traemos el rol y las preferencias de una sola vez
// En la parte superior de supositorio.php
$res_user_data = mysqli_query($con, "SELECT rol, primer_acceso, ultimo_entorno, color_fondo, color_filas, color_botones FROM usuarios WHERE usuario = '$usuario_actual'");
$user_data = mysqli_fetch_assoc($res_user_data);
if (!$user_data) {
    session_destroy();
    header("Location: index.php");
    exit();
}
// Sincronizamos la memoria de la DB con la Sesión si no están actualizadas
$_SESSION['color_fondo'] = $user_data['color_fondo'];
$_SESSION['color_filas'] = $user_data['color_filas'];
$_SESSION['color_botones'] = $user_data['color_botones'] ?? '#475569';
$rol_usuario = $user_data['rol'] ?? 'user';
$mostrar_bienvenida = ($user_data['primer_acceso'] == 0);

// --- 1. PROCESAR CAMBIO (Bienvenida o Ajustes) ---
if (isset($_GET['cambiar_entorno'])) {
    $nuevo = $_GET['cambiar_entorno'];
    $_SESSION['entorno'] = $nuevo;
    mysqli_query($con, "UPDATE usuarios SET primer_acceso = 1, ultimo_entorno = '$nuevo' WHERE usuario = '$usuario_actual'");
    header("Location: supositorio.php");
    exit();
}

// --- 2. SINCRONIZAR ENTORNO ---
$entorno_en_db = $user_data['ultimo_entorno'] ?? 'general';

if (!isset($_SESSION['entorno']) || $_SESSION['entorno'] !== $entorno_en_db) {
    $_SESSION['entorno'] = $entorno_en_db;
}

$entorno = $_SESSION['entorno'];

// Paso B: Sincronizar Sesión con Base de Datos
// Consultamos qué hay en la DB ahora mismo
$res_memoria = mysqli_query($con, "SELECT ultimo_entorno FROM usuarios WHERE usuario = '$usuario_actual'");
$fila_memoria = mysqli_fetch_assoc($res_memoria);
$entorno_en_db = $fila_memoria['ultimo_entorno'] ?? 'general';


// Paso C: La variable final que usa el resto del código
$entorno = $_SESSION['entorno'];

// --- 3. LÓGICA DE CONSULTA SQL ---
if ($rol_usuario === 'admin') {
    if ($entorno === 'personal') {
        $sql = "SELECT * FROM supositorio WHERE vista_privada = '$usuario_actual' ORDER BY id_sistema DESC";
    } else {
        $sql = "SELECT * FROM supositorio ORDER BY id_sistema DESC";
    }
} else {
    if ($entorno === 'personal') {
        $sql = "SELECT * FROM supositorio WHERE vista_privada = '$usuario_actual' ORDER BY id_sistema DESC";
    } else {
        $sql = "SELECT * FROM supositorio WHERE vista_privada = 'general' ORDER BY id_sistema DESC";
    }
}
$query = mysqli_query($con, $sql);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Supositorio</title>

    <!-- 1. Favicon (Emoji 💊 girado) -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 x=%2250%%22 font-size=%2280%22 text-anchor=%22middle%22 transform=%22scale(-1, 1) translate(-100, 0)%22>💊</text></svg>">

    <!-- 2. Fuentes de Google (Unificadas en un solo enlace para mayor velocidad) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;600&family=Inter:wght@400;600;700&family=Poppins:wght@500;700&display=swap" rel="stylesheet">

    <!-- 3. Iconos (Material Symbols) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,400,0,0" />

    <!-- 4. Frameworks (Bootstrap) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- 5. Tu archivo CSS externo (Va al final para que tus cambios tengan prioridad) -->
    <link rel="stylesheet" href="estilos.css">
    <style>
        /* 1. FONDO DINÁMICO */
        body {
            background: <?php echo $_SESSION['color_fondo']; ?> !important;
            background-attachment: fixed !important;
            background-size: cover !important;
            min-height: 100vh;
            /* Esto obliga al fondo a cubrir TODA la pantalla siempre */
            margin: 0;
            flex-direction: column;
            transition: background 0.3s ease;
        }

        .flex-grow-content {
            flex: 1 0 auto;
        }

        /* 2. FILAS Y EFECTO CEBRA */
        .table td {
    background-color: #ffffff !important; /* O el color base que prefieras */
            vertical-align: middle;
            transition: all 0.2s ease;
        }
/* 2. DEFINIMOS LOS COLORES FIJOS POR TIPO (Independientes del tema) */
/* Usamos !important para asegurarnos de que ganen al blanco base */
.hover-jira td { 
    background-color: #bbcff9 !important; /* Azul Jira siempre */
}
.hover-nota td { 
    background-color: #d8b4fe !important; /* Lila Notas siempre */
}
.hover-latam td { 
    background-color: #bbe2a3 !important; /* Verde Latam siempre */
}
.hover-espana td { 
    background-color: #ffedd5 !important; /* Naranja España siempre */
}
        /* Efecto Cebra: Oscurece ligeramente las filas impares automáticamente */
        .table tr:nth-child(odd) td {
            filter: brightness(0.96) !important;
        }

        /* Hover: Resalta la fila al pasar el ratón */
        .table tr:hover td {
            filter: brightness(0.90) !important;
        }

        /* 3. BOLA DE FAVORITOS (Fav-Marker) */
        .fav-marker {
            cursor: pointer;
            font-size: 0.8rem;
            color: #94a3b8;
            /* Gris cuando está apagada */
            transition: all 0.2s ease;
            display: inline-flex;
        }

        .fav-marker.active {
            color: #e81a1a !important;
            /* NEGRO cuando es favorito */
            font-variation-settings: 'FILL' 1 !important;
            /* Rellena el icono si es Material Symbol */
            transform: scale(1.1);
        }

        .fav-marker:hover {
            transform: scale(1.3);
            color: #475569;
        }

        /* 4. CONTRASTE AUTOMÁTICO (Cristal) */
        <?php
        $f = $_SESSION['color_fondo'];
        if (strpos($f, '#0') === 0 || strpos($f, 'linear-gradient') !== false):
        ?>.bg-white,
        .card,
        .modal-content,
        .search-container>div,
        .main-content>div {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
        }

        .table {
            color: #ffffff !important;
        }

        .text-muted {
            color: #cbd5e1 !important;
        }

        .btn-close {
            filter: invert(1);
        }

        /* X del modal en blanco */
        <?php endif; ?>

        /* 5. COLORES DE CATEGORÍAS (Indicadores laterales) */
        .indicador-tipo {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 800;
            margin: 0 auto;
        }

        .tipo-nota {
            background-color: #d8b4fe !important;
            color: #200d2d !important;
        }

        .tipo-jira {
            background-color: #bbcff9 !important;
            color: #1e3a8a !important;
        }

        .tipo-latam {
            background-color: #bbe2a3 !important;
            color: #092a16 !important;
        }

        .tipo-espana {
            background-color: #ffedd5 !important;
            color: #7c2d12 !important;
        }

        .tipo-favorito {
            background-color: #000000 !important;
            color: #ffffff !important;
        }

        /* 6. BOTONES */
        .btn-custom,
        .btn-admin-pill,
        #btnGuardarColores {
            background-color: <?php echo $_SESSION['color_botones']; ?> !important;
            color: white !important;
            border: none;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .tema-inmune {
            background-color: #ffffff !important;
            /* Siempre fondo blanco */
            color: #55575a !important;
            /* Siempre texto azul oscuro/negro */
            backdrop-filter: none !important;
            /* Quita el efecto borroso si existía */
            border: 1px solid #e2e8f0 !important;
            /* Borde gris suave */
        }

        /* También hay que blindar los textos dentro de esa zona */
        .tema-inmune p,
        .tema-inmune span,
        .tema-inmune h1 {
            color: #55575a !important;
        }

        /* ELIMINAR EFECTOS EN FILAS DE "SIN RESULTADOS" */
        /* Usamos el ID #studentTable para ser más fuertes que el archivo externo */

        #studentTable tbody tr.no-results-row:hover td,
        #studentTable tbody tr.fila-sin-hover:hover td {
            filter: none !important;
            cursor: default !important;
            background-color: <?php echo $_SESSION['color_filas']; ?> !important;
            box-shadow: none !important;
        }

        /* Bloqueo total del ratón para todo lo que haya dentro de esas filas */
        .no-results-row,
        .no-results-row *,
        .fila-sin-hover,
        .fila-sin-hover * {
            cursor: default !important;
        }

        .mensaje-vacio {
            color: inherit;
            /* Heredará el color del tema automáticamente */
            opacity: 0.9;
            /* Le da un toque suave sin llegar a ser gris */
        }
    </style>
</head>

<body>
    <div class="flex-grow-content"> <!-- Añade esta línea aquí -->
        <?php if ($mostrar_bienvenida): ?>
            <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(8px); z-index: 20000; display: flex; align-items: center; justify-content: center; padding: 20px;">
                <div style="background: #ffffff; padding: 40px; border-radius: 16px; text-align: center; max-width: 450px; width: 100%; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); border: 1px solid rgba(255,255,255,0.1);">

                    <!-- Icono con una pequeña animación o sombra suave -->
                    <div style="background: #f1f5f9; width: 80px; height: 80px; line-height: 80px; border-radius: 50%; margin: 0 auto 25px; font-size: 2.5rem; display: flex; align-items: center; justify-content: center; box-shadow: inset 0 2px 4px rgba(0,0,0,0.06);">
                        💊
                    </div>

                    <h2 style="font-family: 'Geist', sans-serif; font-weight: 700; color: #0f172a; margin-bottom: 12px; letter-spacing: -0.025em;">
                        Bienvenido, <?php echo htmlspecialchars($usuario_actual); ?>
                    </h2>

                    <p style="font-family: 'Inter', sans-serif; color: #64748b; font-size: 0.95rem; line-height: 1.5; margin-bottom: 32px;">
                        Parece que es tu primera vez por aquí.<br>Selecciona tu área de trabajo predeterminada:
                    </p>

                    <div style="display: grid; grid-template-columns: 1fr; gap: 12px;">

                        <!-- Opción General -->
                        <a href="?cambiar_entorno=general"
                            style="display: flex; align-items: center; justify-content: space-between; text-decoration: none; background: #ffffff; border: 2px solid #e2e8f0; padding: 18px 24px; border-radius: 12px; transition: all 0.2s ease; group"
                            onmouseover="this.style.borderColor='#94a3b8'; this.style.background='#f8fafc'"
                            onmouseout="this.style.borderColor='#e2e8f0'; this.style.background='#ffffff'">
                            <div style="text-align: left;">
                                <span style="display: block; font-weight: 700; color: #1e293b; font-size: 0.9rem;">Entorno General</span>
                                <span style="display: block; font-size: 0.75rem; color: #94a3b8; font-weight: 400;">Colaboración con el equipo</span>
                            </div>
                            <span class="material-symbols-outlined" style="color: #cbd5e1;">public</span>
                        </a>

                        <!-- Opción Personal -->
                        <a href="?cambiar_entorno=personal"
                            style="display: flex; align-items: center; justify-content: space-between; text-decoration: none; background: #475569; border: 2px solid #475569; padding: 18px 24px; border-radius: 12px; transition: all 0.2s ease;"
                            onmouseover="this.style.background='#334155'; this.style.borderColor='#334155'"
                            onmouseout="this.style.background='#475569'; this.style.borderColor='#475569'">
                            <div style="text-align: left;">
                                <span style="display: block; font-weight: 700; color: #ffffff; font-size: 0.9rem;">Entorno Personal</span>
                                <span style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.7); font-weight: 400;">Tu espacio privado y notas</span>
                            </div>
                            <span class="material-symbols-outlined" style="color: rgba(255,255,255,0.5);">lock</span>
                        </a>

                    </div>

                    <p style="margin-top: 25px; font-size: 0.75rem; color: #94a3b8;">
                        Podrás cambiar esto más tarde en los ajustes.
                    </p>
                </div>
            </div>
        <?php endif; ?>
        <div class="container-fluid wrapper-espaciado">
            <div class="row justify-content-center">
                <div class="tema_inmune mb-3">
                    <div style="width: 96%; max-width: 100%; margin: 0 auto; display: flex; justify-content: flex-end;">
                        <div class="d-flex align-items-center tema-inmune shadow-sm px-3 py-1" style="border-radius: 20px; border: 1px solid #e2e8f0;">
                            <!-- BOTÓN DE CAMBIO RÁPIDO DE ENTORNO -->
                            <div class="dropdown">
                                <a href="?cambiar_entorno=<?php echo ($entorno === 'general') ? 'personal' : 'general'; ?>"
                                    class="d-flex align-items-center text-decoration-none"
                                    style="color: <?php echo ($entorno === 'personal') ? '#8b5cf6' : '#64748b'; ?>; transition: all 0.2s;"
                                    title="Cambiar a entorno <?php echo ($entorno === 'general') ? 'Personal' : 'General'; ?>">

                                    <span class="material-symbols-outlined" style="font-size: 1.2rem;">
                                        <?php echo ($entorno === 'general') ? 'public' : 'lock'; ?>
                                    </span>
                                    <span style="font-size: 0.65rem; font-weight: 700; margin-left: 4px; text-transform: uppercase;">
                                        <?php echo ($entorno === 'general') ? 'Gral' : 'Pers'; ?>
                                    </span>
                                </a>
                            </div>
                            <!-- Separador -->
                            <div style="width: 1px; height: 15px; background: #e2e8f0; margin: 0 12px;"></div>
                            <!-- Botón de Ruedecita (Ajustes) -->
                            <div class="dropdown">
                                <a href="javascript:void(0)" onclick="abrirAjustes()" class="d-flex align-items-center text-decoration-none" style="color: #64748b; transition: color 0.2s;">
                                    <span class="material-symbols-outlined" style="font-size: 1.1rem;">settings</span>
                                </a>
                            </div>
                            <!-- Separador -->
                            <div style="width: 1px; height: 15px; background: #e2e8f0; margin: 0 12px;"></div>

                            <!-- Avatar Clickable -->
                            <div class="position-relative" style="cursor: pointer;" onclick="document.getElementById('inputFotoPerfil').click();" title="Cambiar foto de perfil">
                                <?php
                                // Verificamos que la sesión exista, no esté vacía y el archivo realmente exista
                                $foto = $_SESSION['foto_perfil'] ?? '';
                                $ruta_foto = "uploads/" . $foto;

                                if (!empty($foto) && file_exists($ruta_foto)): ?>
                                    <img src="<?php echo $ruta_foto; ?>"
                                        style="width: 26px; height: 26px; border-radius: 50%; object-fit: cover; border: 1px solid #e2e8f0;">
                                <?php else: ?>
                                    <!-- Forzamos un color más visible para descartar que se camufle con el fondo -->
                                    <span class="material-symbols-outlined" style="font-size: 1.4rem; color: #747b84 !important; vertical-align: middle;">account_circle</span>
                                <?php endif; ?>

                                <!-- Input oculto corregido -->
                                <form id="formFotoPerfil" style="display:none;" method="POST" enctype="multipart/form-data">
                                    <input type="file" id="inputFotoPerfil" name="nueva_foto" accept="image/*" onchange="subirFotoRapida()">
                                </form>
                            </div>

                            <!-- Separador y Nombre -->
                            <div style="width: 1px; height: 15px; background: #e2e8f0; margin: 0 12px;"></div>
                            <span style="font-size: 0.75rem; color: #475569;">
                                <strong><?php echo $_SESSION['usuario']; ?></strong>
                            </span>

                            <div style="width: 1px; height: 15px; background: #e2e8f0; margin: 0 15px;"></div>

                            <a href="logout.php" style="font-size: 0.75rem; color: #ef4444; text-decoration: none; font-weight: 500; padding: 2px 8px; border-radius: 4px; transition: background 0.2s;" onmouseover="this.style.color='#1e293b';" onmouseout="this.style.color='#ef4444'">
                                Salir
                            </a>
                        </div>
                    </div>
                </div>
                <div class="search-container mb-3" style="width: 96%; max-width: 100%; margin: 0 auto;">
                    <div class="tema-inmune" style="
        display: flex; 
        align-items: center; 
        height: 34px; 
        background: #fff !important;
        border: 1px solid #e2e8f0; 
        border-radius: 50px; 
        padding: 0 15px; 
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    ">

                        <span class="material-symbols-outlined" style="font-size: 1.1rem; color: #94a3b8; user-select: none;">search</span>

                        <input type="text" id="searchInput" placeholder="Buscar..." style="
            flex: 1;
            border: none;
            outline: none;
            background: transparent;
            padding-left: 10px;
            font-size: 0.85rem;
            color: #475569 !important;
            height: 100%;
        ">

                        <div style="width: 1px; height: 16px; background: #e2e8f0; margin: 0 12px;"></div>

                        <select id="filtroMesJS" style="
            border: none;
            outline: none;
            background: transparent;
            font-size: 0.75rem;
            color: #64748b;
            cursor: pointer;
            height: 100%;
            padding-right: 5px;
        ">
                            <option value="LIMPIAR_TODO" hidden>x Limpiar</option>
                            <option value="" selected>Filtrar</option>
                            <option disabled style="background-color: #f1f5f9; color: #475569; font-weight: bold;" hidden>· Estado ·</option>
                            <option value="lila" hidden>Notas</option>
                            <option value="azul" hidden>Jira</option>
                            <option value="verde" hidden>Latam</option>
                            <option value="blanco" hidden>España</option>
                            <option value="negro" hidden>Favoritos</option>
                            <option disabled style="background-color: #f1f5f9; color: #475569; font-weight: bold;">· Meses ·</option>
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
                    </div>
                </div>
                <div style="width: 96%; max-width: 100%; margin: 5px auto; display: grid; grid-template-columns: 1fr auto 1fr; align-items: center;">

                    <div style="display: flex; justify-content: flex-start; align-items: center; gap: 12px;">
                        <a href="javascript:void(0)" onclick="abrirNuevo()" class="btn-custom">
                            + Añadir Registro
                        </a>

                        <div style="cursor: default; display: flex; align-items: center; gap: 6px; opacity: 0.7;">
                            <kbd style="
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            box-shadow: 0 2px 0 #cbd5e1;
            color: #475569;
            display: inline-block;
            font-size: 0.65rem;
            font-weight: 700;
            line-height: 1;
            padding: 2px 4px;
            font-family: sans-serif;
        ">Esc</kbd>

                            <span class="text-muted" style="font-size: 0.75rem; color: #64748b;">para limpiar</span>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-3 justify-content-center align-items-center">
                        <div class="d-flex align-items-center leyenda-item" onclick="limpiarTodo()" title="Limpiar todos los filtros">
                            <div style="width: 14px; height: 14px; background-color: #e2e8f0; border: none; border-radius: 50%; margin-right: 6px; display: flex; align-items: center; justify-content: center;">
                                <span style="font-size: 8px; color: #64748b; font-weight: bold;">✕</span>
                            </div>
                            <span style="font-size: 0.7rem; color: #64748b; font-weight: 600;">Limpiar</span>
                        </div>

                        <div style="width: 0px; height: 12px; background: #f1f5f9; margin: 0 5px;"></div>

                        <div class="d-flex align-items-center leyenda-item" onclick="filtrarPorEstado('lila')">
                            <div style="width: 14px; height: 14px; background-color: #d8b4fe; border: none; border-radius: 50%; margin-right: 6px;"></div>
                            <span style="font-size: 0.7rem; color: #64748b;">Notas</span>
                        </div>

                        <div class="d-flex align-items-center leyenda-item" onclick="filtrarPorEstado('azul')">
                            <div style="width: 14px; height: 14px; background-color: #bbcff9; border: none; border-radius: 50%; margin-right: 6px;"></div>
                            <span style="font-size: 0.7rem; color: #64748b;">Jira</span>
                        </div>

                        <div class="d-flex align-items-center leyenda-item" onclick="filtrarPorEstado('verde')">
                            <div style="width: 14px; height: 14px; background-color: #bbe2a3; border: none; border-radius: 50%; margin-right: 6px;"></div>
                            <span style="font-size: 0.7rem; color: #64748b;">Latam</span>
                        </div>

                        <div class="d-flex align-items-center leyenda-item" onclick="filtrarPorEstado('blanco')">
                            <div style="width: 14px; height: 14px; background-color: #ffedd5; border: none; border-radius: 50%; margin-right: 6px;"></div>
                            <span style="font-size: 0.7rem; color: #64748b;">España</span>
                        </div>
                        <div class="d-flex align-items-center leyenda-item" onclick="filtrarPorEstado('negro')">
                            <div style="width: 14px; height: 14px; background-color: #e58787; border: none; border-radius: 50%; margin-right: 6px;"></div>
                            <span style="font-size: 0.7rem; color: #64748b;">Favoritos</span>
                        </div>
                    </div>
                    <div style="display: flex; justify-content: flex-end; align-items: center;">
                        <span class="text-muted" style="font-size: 0.75rem;">
                            Registros: <span id="contadorRegistros" class="fw-bold">0</span>
                        </span>
                    </div>

                </div>

                <div></div>
            </div>
            <?php if (isset($_GET['guardado']) || isset($_GET['actualizado']) || isset($_GET['eliminado']) || isset($_GET['error'])):
                // Determinamos la clase de Bootstrap según el mensaje
                $clase_bootstrap = 'alert-success'; // Por defecto verde
                if (isset($_GET['error']) || isset($_GET['eliminado'])) $clase_bootstrap = 'alert-danger';
                if (isset($_GET['actualizado']) && $_GET['actualizado'] == 'sin_cambios') $clase_bootstrap = 'alert-info';
            ?>
                <div class="alert alerta-temporal <?php echo $clase_bootstrap; ?> shadow-sm text-center mb-3"
                    style="width: 96%; margin: 10px auto; border-radius: 8px; font-size: 0.85rem; border: none; opacity: 1 !important;">
                    <strong style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <?php
                        // Icono dinámico según el tipo
                        $icono = 'check_circle';
                        if ($clase_bootstrap === 'alert-danger') $icono = 'error';
                        if ($clase_bootstrap === 'alert-info') $icono = 'info';

                        echo '<span class="material-symbols-outlined" style="font-size: 1.2rem;">' . $icono . '</span>';

                        if (isset($_GET['guardado'])) {
                            echo "¡Registro [ " . htmlspecialchars($_GET['id_nuevo'] ?? "") . " ] guardado con éxito!";
                        } elseif (isset($_GET['actualizado'])) {
                            if ($_GET['actualizado'] == 'sin_cambios') {
                                echo "No se realizaron cambios en el registro.";
                            } else {
                                echo "¡Registro [ " . htmlspecialchars($_GET['id_editado'] ?? "") . " ] actualizado!";
                            }
                        } elseif (isset($_GET['eliminado'])) {
                            echo "Registro [ " . htmlspecialchars($_GET['id_borrado'] ?? "") . " ] eliminado correctamente.";
                        } elseif (isset($_GET['error'])) {
                            echo ($_GET['error'] == 'duplicado') ? "Error: El ID [ " . htmlspecialchars($_GET['id_error'] ?? "") . " ] ya existe." : "Hubo un error al procesar la solicitud.";
                        }
                        ?>
                    </strong>
                </div>
            <?php endif; ?>


            <?php if (isset($_GET['nuevos']) || isset($_GET['actualizados'])): ?>
                <div class="alerta-temporal shadow-sm text-center mb-2" style="padding: 10px; border-radius: 8px; background-color: #ffedd5; color: #9a3412; border: 1px solid #fed7aa; font-size: 0.85rem;">
                    <img src="/img/cara.png" class="emoji-img">
                    <strong>Importación:</strong>
                    <span style="color: #166534; font-weight: bold;"><?php echo (int)$_GET['nuevos']; ?> nuevos</span>
                    |
                    <span style="color: #1e40af; font-weight: bold;"><?php echo (int)($_GET['actualizados'] ?? 0); ?> actualizados/revisados</span>

                    <?php if (!empty($_GET['omitidos_ids'])): ?>
                        <br><small>Omitidos (error): <?php echo htmlspecialchars($_GET['omitidos_ids']); ?></small>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div id="tablaContenedor">
        <div class="table-responsive-scroll shadow-sm">
            <table class="tema-inmune table" id="studentTable">
                <thead>
                    <tr>
                        <th style="width: 50px;"></th>
                        <th style="width: 80px;">ID</th>
                        <th style="width: 120px; min-width: 120px;">Nombre</th>
                        <th style="white-space: nowrap;">Teléfono</th>
                        <th style="width: 20%;">Consulta</th>
                        <th style="width: 50%;">Resolución</th>
                        <th class="text-center" style="width: 70px;">Adj.</th>
                        <th id="thFecha" style="width: 100px; cursor: pointer;">Fecha ↕</th>
                        <th style="width: 110px;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($query) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($query)):
                            $esJira = false;
                            $esNota = false;
                            $esEspanol = false;
                            $clase_indicador = '';
                            $tipo_label = '';
                            $tel_limpio = trim($row['telefono']);
                            $id_limpio = trim($row['ID']);
                            $url_jira = trim($row['jira_url'] ?? '');

                            $esJira = !empty($url_jira);
                            $esNota = !$esJira && ($id_limpio === 'NOTA' || strpos($id_limpio, 'NOTA-') === 0 || $tel_limpio === 'NOTA');

                            $esEspanol = false;
                            if (!$esJira && !$esNota) {
                                $esEspanol = preg_match('/^(6|7|9|\+34|0034)/', $tel_limpio);
                            }

                            if ($esJira) {
                                $clase_hover = 'hover-jira';
                                $tipo_label = 'J';
                                $clase_indicador = 'tipo-jira';
                            } elseif ($esNota) {
                                $clase_hover = 'hover-nota';
                                $tipo_label = 'N';
                                $clase_indicador = 'tipo-nota';
                            } else {
                                $clase_hover = $esEspanol ? 'hover-espana' : 'hover-latam';
                                $tipo_label = $esEspanol ? 'ES' : 'LT';
                                $clase_indicador = $esEspanol ? 'tipo-espana' : 'tipo-latam';
                            }
                        ?>
                            <tr class="tema-inmune<?php echo $clase_hover; ?>"> <!-- He añadido el TR inicial para que la clase hover funcione -->
                                <td class=" text-center" style="vertical-align: middle; position: relative;">
                                    <?php
                                    $hoy = date('Y-m-d');
                                    $fechaDB = !empty($row['fecha_actualizacion']) ? date('Y-m-d', strtotime($row['fecha_actualizacion'])) : '';
                                    if ($fechaDB === $hoy):
                                    ?>
                                        <span title="Modificado hoy" style="display: none;"></span>
                                    <?php endif; ?>

                                    <div class="indicador-tipo <?php echo $clase_indicador; ?>">
                                        <?php echo $tipo_label; ?>
                                    </div>
                                </td>

                                <td class="text-center fw-bold" style="vertical-align: middle;">
                                    <div class="d-flex flex-column align-items-center justify-content-center" style="line-height: 1.1;">

                                        <!-- Contenedor de ID y botón Copiar -->
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <?php if ($esNota): ?>
                                                <span style="font-weight: 800; color: #1e293b; font-size: 0.85rem;">NOTA</span>
                                            <?php else: ?>
                                                <span><?php echo htmlspecialchars($row['ID']); ?></span>
                                                <a href="javascript:void(0)"
                                                    class="btn-copiar text-muted"
                                                    onclick="event.stopPropagation(); copiarTexto('<?php echo $row['ID']; ?>', this)"
                                                    title="Copiar ID">
                                                    <span class="material-symbols-outlined" style="font-size: 1rem;">content_copy</span>
                                                </a>
                                            <?php endif; ?>
                                        </div>

                                        <!-- CONTENEDOR DE LA BOLITA (Altura 0 para no mover la fila) -->
                                        <!-- AQUÍ VA EL CÓDIGO NUEVO -->
                                        <div style="height: 0; display: flex; justify-content: center; width: 100%;">
                                            <span class="material-symbols-outlined fav-marker <?php echo ($row['es_favorito'] == 1) ? 'active' : ''; ?>"
                                                onclick="toggleFavorito(this, <?php echo $row['id_sistema']; ?>)"
                                                style="cursor: pointer; font-size: 0.7rem; position: relative; top: 2px; <?php echo ($row['es_favorito'] == 1) ? "font-variation-settings: 'FILL' 1; transform: scale(0.7);" : ""; ?>">
                                                <?php echo ($row['es_favorito'] == 1) ? 'lens' : 'radio_button_unchecked'; ?>
                                            </span>
                                        </div>

                                        <!-- Info de Admin -->
                                        <?php if ($rol_usuario === 'admin' && ($row['vista_privada'] ?? 'general') !== 'general'): ?>
                                            <div style="font-size: 0.55rem; color: #94a3b8; font-weight: 400; margin-top: 10px; display: flex; align-items: center; gap: 2px; text-transform: uppercase;">
                                                <span class="material-symbols-outlined" style="font-size: 0.65rem;">person</span>
                                                <?php echo htmlspecialchars($row['vista_privada']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="text-center celda-nombre" style="vertical-align: middle;">
                                    <?php
                                    if (empty($row['nombre'])) {
                                        echo '<span class="dato-vacio fw-bold">-</span>';
                                    } else {
                                        echo htmlspecialchars($row['nombre']);
                                    }
                                    ?>
                                </td>
                                <td class="text-center" style="vertical-align: middle;"> <!-- Cambiado a text-center -->
                                    <?php
                                    $mostrarGuion = ($tel_limpio === 'NOTA' || ($esNota && empty($tel_limpio)) || empty($tel_limpio));

                                    // Forzamos a que siempre esté centrado, haya o no teléfono
                                    $claseFlex = 'justify-content-center';
                                    ?>
                                    <div class="d-flex align-items-center <?php echo $claseFlex; ?> gap-2">
                                        <?php if ($mostrarGuion): ?>
                                            <span class="dato-vacio fw-bold">-</span>
                                        <?php else: ?>
                                            <span><?php echo htmlspecialchars($row['telefono']); ?></span>
                                            <a href="javascript:void(0)"
                                                class="btn-copiar text-muted"
                                                onclick="event.stopPropagation(); copiarTexto('<?php echo $row['telefono']; ?>', this)"
                                                title="Copiar teléfono">
                                                <span class="material-symbols-outlined" style="font-size: 0.9rem;">content_copy</span>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <td class="text-start fw-bold" style="vertical-align: middle;">
                                    <div class="celda-acordeon texto-mayuscula">
                                        <?php echo htmlspecialchars($row['consulta']); ?>
                                    </div>
                                </td>
                                <td style="vertical-align: top !important; padding-top: 0px !important; padding-bottom: 4px !important; border-top: 1px solid #e2e8f0;">
                                    <div class="celda-acordeon" style="white-space: pre-line !important; line-height: 1.1 !important; padding-top: 4px !important; margin: 0 !important;"><?php
                                                                                                                                                                                            $texto = trim(string: $row['solucion']);
                                                                                                                                                                                            $pattern = '/(https?:\/\/[^\s]+|www\.[^\s]+)/i';

                                                                                                                                                                                            echo preg_replace_callback($pattern, function ($m) {
                                                                                                                                                                                                $url_m = $m[0];
                                                                                                                                                                                                $url_f = (strpos($url_m, 'http') === 0) ? $url_m : 'http://' . $url_m;

                                                                                                                                                                                                $html = '<a href="' . htmlspecialchars($url_f) . '" target="_blank" style="color: #2563eb; text-decoration: underline; line-height: 1.1;">' . htmlspecialchars($url_m) . '</a>';
                                                                                                                                                                                                $html .= '<span class="btn-copiar text-muted" 
                            onclick="event.stopPropagation(); copiarTexto(\'' . addslashes($url_f) . '\', this)" 
                            style="cursor: pointer; display: inline-block; width: 14px; margin-left: 3px; vertical-align: middle; line-height: 0;">
                            <span class="material-symbols-outlined" style="font-size: 0.9rem; display: inline-block;">content_copy</span>
                        </span>';

                                                                                                                                                                                                return $html;
                                                                                                                                                                                            }, htmlspecialchars($texto));
                                                                                                                                                                                            ?></div>
                                </td>
                                <td class="celda-adjunto text-center" style="vertical-align: middle;">
                                    <div class="d-flex justify-content-center gap-2">
                                        <?php if (!empty($row['jira_url'])): ?>
                                            <a href="<?php echo htmlspecialchars($row['jira_url']); ?>" target="_blank"
                                                style="text-decoration: none; color: #2563eb;" title="Ver Ticket"
                                                onclick="event.stopPropagation();">
                                                <span class="material-symbols-outlined" style="font-size: 1.2rem; font-weight: bold;">link</span>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (!empty($row['adjunto'])): ?>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#modal_<?php echo $row['id_sistema']; ?>"
                                                style="text-decoration: none; color: #64748b;">
                                                <span class="material-symbols-outlined" style="font-size: 1.2rem;">attach_file</span>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (empty($row['jira_url']) && empty($row['adjunto'])): ?>
                                            <span class="sin-adjunto">-</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="tema-inmune text-muted text-center" style="vertical-align: middle; font-size: 0.55rem;">
                                    <?php echo date('d/m/Y', strtotime($row['fecha_registro'])); ?>
                                </td>
                                <td class="text-end" style="vertical-align: middle;">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="javascript:void(0)"
                                            onclick="event.stopPropagation(); abrirEditar(<?php echo $row['id_sistema']; ?>)"
                                            class="action-icon edit" title="Editar">
                                            <span class="material-symbols-outlined">edit</span>
                                        </a>

                                        <a href="delete.php?id=<?php echo $row['id_sistema']; ?>"
                                            class="action-icon delete"
                                            onclick="event.stopPropagation(); return confirm('¿Seguro?')" title="Eliminar">
                                            <span class="material-symbols-outlined">delete</span>
                                        </a>
                                    </div>
                                </td>
                                <?php if ($rol_usuario === 'admin'): ?>
                                    <td class="text-center">
                                        <?php if ($row['vista_privada'] === 'general'): ?>
                                            <span class="badge bg-info">General</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">
                                                Privado: <?php echo htmlspecialchars($row['vista_privada']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endwhile; ?>

                    <?php else: ?>
                        <!-- ESTO ES LO ÚNICO QUE SE AÑADE: EL MENSAJE DIFERENTE -->
                        <tr>
                        <tr class="fila-sin-hover">
                            <td colspan="10" class="text-center py-5">
                                <?php if ($entorno === 'personal'): ?>
                                    <!-- He quitado el color fijo #475569 para que use el del sistema -->
                                    <div class="mensaje-vacio">
                                        <span class="material-symbols-outlined" style="font-size: 3.5rem; opacity: 0.3; margin-bottom: 15px; color: #b51f79;">folder_shared</span>
                                        <h5 class="fw-bold">Tu entorno personal está vacío</h5>
                                        <p class="mb-0">Aquí solo tú y el administrador podréis ver lo que guardes.</p>
                                        <p class="small">Haz clic en <strong>+ Añadir Registro</strong> para crear el primero.</p>
                                    </div>
                                <?php else: ?>
                                    <div class="py-4 text-muted">
                                        <span class="material-symbols-outlined" style="font-size: 3rem; opacity: 0.3; color: #cbd5e1;">search_off</span>
                                        <p class="mt-2">No se encontraron registros en el sistema general.</p>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>

                    <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>
    </div>
    </div> <!-- Cierra el div aquí, justo antes del panel -->
    <div class="card custom-card mt-3 border-warning py-1" style="width: 96%; margin: 15px auto; border-radius: 6px !important;">
        <div class="card-body py-1">
            <div class="d-flex flex-row align-items-center justify-content-between" style="min-height: 32px;">

                <div class="d-flex align-items-center gap-2">
                    <h6 class="text-muted fw-bold mb-0 text-secondary me-2" style="white-space: nowrap; font-size: 0.75rem;">
                        <?php echo ($rol_usuario === 'admin') ? 'Panel Admin' : 'Sesión de ' . htmlspecialchars($_SESSION['usuario']); ?>
                    </h6>

                    <!-- BOTONES DE ADMINISTRACIÓN (Solo para Admin) -->
                    <?php if ($rol_usuario === 'admin'): ?>
                        <a href="descargar_plantilla.php" class="btn-admin-pill">Plantilla CSV</a>
                        <form action="importar_excel.php" method="POST" enctype="multipart/form-data" class="m-0">
                            <input type="file" name="archivo_excel" id="inputModerno" accept=".csv" style="display: none;" onchange="this.form.submit()">
                            <button type="button" class="btn-admin-pill" onclick="document.getElementById('inputModerno').click()">
                                <span class="material-symbols-outlined">upload</span> Importar CSV
                            </button>
                        </form>
                        <a href="exportar_csv.php" class="btn-admin-pill">
                            <span class="material-symbols-outlined">download</span> Exportar Todo
                        </a>
                        <a href="respaldo.php" class="btn-admin-pill">
                            <span class="material-symbols-outlined">database</span> Respaldo
                        </a>

                        <!-- BOTÓN DE EXPORTACIÓN PERSONAL (Solo si está en entorno personal y NO es admin) -->
                    <?php elseif ($entorno === 'personal'): ?>
                        <a href="exportar_csv.php" class="btn-admin-pill">
                            <span class="material-symbols-outlined">download</span> Exportar listado
                        </a>
                    <?php endif; ?>
                </div>

                <div class="d-flex align-items-center gap-3">
                </div>
            </div>
        </div>
    </div>
    <?php
    mysqli_data_seek($query, 0);
    while ($row = mysqli_fetch_assoc($query)):
        if (!empty($row['adjunto'])):
            $imagenes = explode(",", $row['adjunto']);
            $id_modal = "modal_" . $row['id_sistema'];
    ?>
            <div class="modal fade" id="<?php echo $id_modal; ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content" style="border-radius: 6px; border: none; box-shadow: 0 15px 40px rgba(0,0,0,0.2);">

                        <div class="modal-header" style="border: none; padding: 20px 20px 5px 20px;">
                            <h5 class="text-mutedmodal-title" style="font-family: 'Inter', sans-serif; font-weight: 600; color: #1e293b; font-size: 1rem; display: flex; align-items: center; gap: 8px;">
                                <span class="text-muted material-symbols-outlined" style="font-size: 1.2rem; color: #64748b;">folder_open</span>
                                Adjuntos
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.7rem;"></button>
                        </div>

                        <div class="modal-body" style="padding: 15px 20px 25px 20px;">
                            <div class="d-grid gap-2">
                                <?php
                                foreach ($imagenes as $img):
                                    $img = trim($img);
                                    if (empty($img)) continue;

                                    // CORRECCIÓN AQUÍ: PATHINFO_EXTENSION (sin el guion bajo central)
                                    $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

                                    $icono = 'insert_drive_file';
                                    $color_icono = '#64748b';

                                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                                        $icono = 'image';
                                        $color_icono = '#3b82f6';
                                    } elseif ($ext == 'pdf') {
                                        $icono = 'picture_as_pdf';
                                        $color_icono = '#ef4444';
                                    } elseif (in_array($ext, ['xlsx', 'csv', 'xls'])) {
                                        $icono = 'table_chart';
                                        $color_icono = '#10b981';
                                    }
                                ?>
                                    <a href="uploads/<?php echo $img; ?>" target="_blank"
                                        class="d-flex align-items-center justify-content-between p-2 px-3"
                                        style="text-decoration: none; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; transition: all 0.2s;">

                                        <div class="d-flex align-items-center gap-3" style="min-width: 0;">
                                            <span class="material-symbols-outlined" style="color: <?php echo $color_icono; ?>; font-size: 1.3rem;">
                                                <?php echo $icono; ?>
                                            </span>
                                            <span style="font-size: 0.8rem; color: #475569; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 140px;">
                                                <?php echo htmlspecialchars($img); ?>
                                            </span>
                                        </div>

                                        <span class="material-symbols-outlined" style="font-size: 1rem; color: #94a3b8;">open_in_new</span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <?php
        endif;
    endwhile;
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts.js"></script>
    <div class="modal fade" id="modalNuevo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" id="contenidoModalNuevo" style="border-radius: 6px; border: none; box-shadow: 0 20px 50px rgba(0,0,0,0.2);">
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" id="contenidoModalEditar" style="border-radius: 6px; border: none; box-shadow: 0 20px 50px rgba(0,0,0,0.2);">
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalAjustes" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <!-- Ajustamos el header para centrar el contenido -->
                <div class="modal-header" style="border-bottom: 1px solid #f1f5f9; display: flex; justify-content: center; position: relative; padding: 1.5rem 1rem;">

                    <!-- El título ahora ocupa todo el ancho con text-align center -->
                    <h5 style="font-weight: 400; font-size: 1.3rem; color: #1e293b; margin-bottom: 20px; text-align: center;">
                        <label class="text-muted">Personalización</label>
                    </h5>

                    <!-- El botón de cerrar se posiciona a la derecha sin afectar al centro del título -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="position: absolute; right: 1rem; top: 1.5rem; margin: 0;"></button>
                </div>

                <div class="modal-body" id="contenidoAjustes">
                    <!-- El contenido se cargará aquí vía AJAX -->
                </div>
            </div>
        </div>
    </div>
</body>

</html>