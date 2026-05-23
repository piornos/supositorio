<?php
session_start();
include("conexion.php");
$con = conectar();

// 1. Asegurarnos de que el ID llega y es numérico
if (isset($_GET['id'])) {
    $id_tecnico = mysqli_real_escape_string($con, $_GET['id']);

    // 2. Buscar datos para el mensaje y borrar archivos
    $busqueda = mysqli_query($con, "SELECT ID, adjunto FROM supositorio WHERE id_sistema='$id_tecnico'");
    $fila = mysqli_fetch_array($busqueda);

    if ($fila) {
        $id_visual = $fila['ID'];
        
        // Borrar archivos físicos
        if (!empty($fila['adjunto'])) {
            $archivos = explode(',', $fila['adjunto']);
            foreach ($archivos as $a) {
                @unlink("uploads/" . trim($a));
            }
        }

        // 3. BORRAR DE LA BASE DE DATOS (Sin condiciones extra)
        $sql_delete = "DELETE FROM supositorio WHERE id_sistema = '$id_tecnico'";
        
        if (mysqli_query($con, $sql_delete)) {
            // REDIRECCIÓN CRÍTICA: Volvemos a supositorio.php
            header("Location: supositorio.php?eliminado=1&id_borrado=" . urlencode($id_visual));
            exit();
        }
    }
}
header("Location: supositorio.php?error=1");
exit();