<?php
session_start();
include("conexion.php");
$con = conectar();

header('Content-Type: application/json');

// 1. Verificamos que el usuario esté logueado y llegue el dato
if (isset($_POST['entorno']) && isset($_SESSION['usuario'])) {
    
    $usuario = $_SESSION['usuario'];
    $nuevoEntorno = ($_POST['entorno'] === 'personal') ? 'personal' : 'general';
    
    // 2. Actualizamos la SESIÓN (para el cambio inmediato)
    $_SESSION['entorno'] = $nuevoEntorno;

    // 3. Actualizamos la BASE DE DATOS (para la memoria a largo plazo)
    // He añadido 'primer_acceso = 1' por si acaso cambiara desde ajustes antes de ver la bienvenida
    $sql = "UPDATE usuarios SET ultimo_entorno = '$nuevoEntorno', primer_acceso = 1 WHERE usuario = '$usuario'";
    
    if (mysqli_query($con, $sql)) {
        echo json_encode(['success' => true]);
    } else {
        // Si hay un error en la SQL, lo veremos en la consola del navegador
        echo json_encode(['success' => false, 'error' => mysqli_error($con)]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No hay sesión o datos']);
}
exit;