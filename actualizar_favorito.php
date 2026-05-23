<?php
include("conexion.php");
$con = conectar();

// Usamos id_sistema porque es lo que envías en el body del fetch
if (isset($_POST['id_sistema']) && isset($_POST['estado'])) {
    $id = intval($_POST['id_sistema']);
    $estado = intval($_POST['estado']);
    
    $sql = "UPDATE supositorio SET es_favorito = $estado WHERE id_sistema = $id";
    
    if (mysqli_query($con, $sql)) {
        echo "ok";
    } else {
        echo "error";
    }
}
?>