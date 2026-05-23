<?php
session_start();
include("conexion.php");
$con = conectar();
$usuario = $_SESSION['usuario'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['nueva_foto'])) {
    $dir = "uploads/";
    if (!is_dir($dir)) mkdir($dir, 0777, true);

    $ext = pathinfo($_FILES['nueva_foto']['name'], PATHINFO_EXTENSION);
    $nombreArchivo = "perfil_" . $usuario . "_" . time() . "." . $ext;
    
    if (move_uploaded_file($_FILES['nueva_foto']['tmp_name'], $dir . $nombreArchivo)) {
        $sql = "UPDATE usuarios SET foto_perfil = '$nombreArchivo' WHERE usuario = '$usuario'";
        
        if (mysqli_query($con, $sql)) {
            $_SESSION['foto_perfil'] = $nombreArchivo; 
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al guardar en BD']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'No se pudo mover el archivo']);
    }
    exit;
}