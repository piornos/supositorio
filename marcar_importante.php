<?php
include("conexion.php");
$con = conectar();

if (isset($_POST['id']) && isset($_POST['estado'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $estado = mysqli_real_escape_string($con, $_POST['estado']);
    
    $sql = "UPDATE supositorio SET importante = '$estado' WHERE id_sistema = '$id'";
    mysqli_query($con, $sql);
}
?>