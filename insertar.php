<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

include("conexion.php");
$con = conectar();

$usuario_actual = $_SESSION['usuario'];
$entorno_actual = $_SESSION['entorno'] ?? 'general';
$vista_privada = ($entorno_actual === 'personal') ? $usuario_actual : 'general';

$id_recibido = mysqli_real_escape_string($con, $_POST['ID']);
$nombre      = mysqli_real_escape_string($con, $_POST['nombre']);
$telefono    = mysqli_real_escape_string($con, $_POST['telefono']);
$jira_url    = "";
$consulta    = mysqli_real_escape_string($con, $_POST['consulta']);
$solucion    = mysqli_real_escape_string($con, $_POST['solucion']);
$categoria   = mysqli_real_escape_string($con, $_POST['categoria'] ?? 'General');

if (isset($_POST['jira'])) {
    $id_final = $id_recibido;
    $jira_url = mysqli_real_escape_string($con, $_POST['jira_url']);
    $tel_final = empty($telefono) ? "NOTA" : $telefono;
} else {
    if ($id_recibido === 'NOTA' || $telefono === 'NOTA') {
        $id_final = "NOTA-" . date("His");
        $tel_final = "NOTA";
    } else {
        $id_final = $id_recibido;
        $tel_final = $telefono;
    }
}

$carpeta = "uploads/";
if (!file_exists($carpeta)) {
    mkdir($carpeta, 0777, true);
}

$nombres_imagenes = [];
if (!empty($_FILES['adjunto']['name'][0])) {
    foreach ($_FILES['adjunto']['name'] as $i => $nombre_original) {
        if ($_FILES['adjunto']['error'][$i] == 0) {
            $nombre_final = time() . "_" . $i . "_" . $nombre_original;
            $ruta_temporal = $_FILES['adjunto']['tmp_name'][$i];
            if (move_uploaded_file($ruta_temporal, $carpeta . $nombre_final)) {
                $nombres_imagenes[] = $nombre_final;
            }
        }
    }
}
$cadena_imagenes = implode(",", $nombres_imagenes);

$sql = "INSERT INTO supositorio (ID, nombre, categoria, telefono, consulta, solucion, adjunto, jira_url, vista_privada, fecha_registro) 
        VALUES ('$id_final', '$nombre', '$categoria', '$tel_final', '$consulta', '$solucion', '$cadena_imagenes', '$jira_url', '$vista_privada', NOW())";

$query = mysqli_query($con, $sql);

if ($query) {
    header("Location: supositorio.php?guardado=1&id_nuevo=" . urlencode($id_final));
    exit();
} else {
    echo "Error técnico: " . mysqli_error($con);
}
