<?php
session_start();
date_default_timezone_set('Europe/Madrid');
include("conexion.php");
$con = conectar();

$id_sistema     = $_POST['id_sistema']; 
$id_visible_raw = $_POST['ID'];
$nombre_raw     = $_POST['nombre'];
$telefono_raw   = $_POST['telefono'];
$consulta_raw   = $_POST['consulta'];
$solucion_raw   = $_POST['solucion'];
$jira_url_raw   = isset($_POST['jira_url']) ? $_POST['jira_url'] : "";

$sql_old = "SELECT * FROM supositorio WHERE id_sistema = '$id_sistema'";
$res_old = mysqli_query($con, $sql_old);
$old = mysqli_fetch_assoc($res_old);

$imagenes_finales = !empty($old['adjunto']) ? array_filter(explode(",", $old['adjunto'])) : [];

if (!empty($_POST['eliminar_fotos'])) {
    foreach ($_POST['eliminar_fotos'] as $foto_borrar) {
        $foto_borrar = trim($foto_borrar);
        if (($key = array_search($foto_borrar, $imagenes_finales)) !== false) {
            unset($imagenes_finales[$key]);
            if (file_exists("uploads/" . $foto_borrar)) {
                unlink("uploads/" . $foto_borrar);
            }
        }
    }
}

if (!empty($_FILES['adjunto']['name'][0])) {
    foreach ($_FILES['adjunto']['name'] as $i => $nombre_original) {
        if ($_FILES['adjunto']['error'][$i] == 0) {
            $nombre_final = time() . "_" . $i . "_" . preg_replace("/\s+/", "_", $nombre_original);
            if (move_uploaded_file($_FILES['adjunto']['tmp_name'][$i], "uploads/" . $nombre_final)) {
                $imagenes_finales[] = $nombre_final;
            }
        }
    }
}
$cadena_fotos_nueva = implode(",", array_values(array_filter($imagenes_finales)));

$cambio_detectado = false;

function sonDiferentes($nuevo, $viejo)
{
    $n = trim(str_replace("\r", "", strval($nuevo)));
    $v = trim(str_replace("\r", "", strval($viejo)));
    return $n !== $v;
}

if (sonDiferentes($id_visible_raw, $old['ID']))       $cambio_detectado = true;
if (sonDiferentes($nombre_raw,     $old['nombre']))   $cambio_detectado = true;
if (sonDiferentes($telefono_raw,   $old['telefono'])) $cambio_detectado = true;
if (sonDiferentes($consulta_raw,   $old['consulta'])) $cambio_detectado = true;
if (sonDiferentes($solucion_raw,   $old['solucion'])) $cambio_detectado = true;
if (sonDiferentes($jira_url_raw,   $old['jira_url'])) $cambio_detectado = true;
if (trim($cadena_fotos_nueva) !== trim($old['adjunto'] ?? '')) $cambio_detectado = true;

if ($cambio_detectado) {
    $id_visible = mysqli_real_escape_string($con, $id_visible_raw);
    $nombre     = mysqli_real_escape_string($con, $nombre_raw);
    $telefono   = mysqli_real_escape_string($con, $telefono_raw);
    $consulta   = mysqli_real_escape_string($con, $consulta_raw);
    $solucion   = mysqli_real_escape_string($con, $solucion_raw);
    $jira_url   = mysqli_real_escape_string($con, $jira_url_raw);
    $adjunto    = mysqli_real_escape_string($con, $cadena_fotos_nueva);

    $sql = "UPDATE supositorio SET 
            ID = '$id_visible', 
            nombre = '$nombre', 
            telefono = '$telefono', 
            consulta = '$consulta', 
            solucion = '$solucion',
            jira_url = '$jira_url',
            adjunto = '$adjunto',
            fecha_actualizacion = NOW() 
            WHERE id_sistema = '$id_sistema'";

    if (mysqli_query($con, $sql)) {
        header("Location: supositorio.php?actualizado=1&id_editado=" . urlencode($id_visible));
    } else {
        echo "Error en la consulta: " . mysqli_error($con);
    }
} else {
    header("Location: supositorio.php?actualizado=sin_cambios");
}
exit();
