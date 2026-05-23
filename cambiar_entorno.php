<?php
session_start();
include("conexion.php");
$con = conectar();

header('Content-Type: application/json');

if (isset($_POST['entorno']) && isset($_SESSION['usuario'])) {
    
    $usuario = $_SESSION['usuario'];
    $nuevoEntorno = ($_POST['entorno'] === 'personal') ? 'personal' : 'general';
    
    $_SESSION['entorno'] = $nuevoEntorno;

    $sql = "UPDATE usuarios SET ultimo_entorno = '$nuevoEntorno', primer_acceso = 1 WHERE usuario = '$usuario'";
    
    if (mysqli_query($con, $sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($con)]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No hay sesión o datos']);
}
exit;