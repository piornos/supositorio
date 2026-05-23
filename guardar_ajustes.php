<?php
ob_start();
session_start();
include("conexion.php");
$con = conectar();

header('Content-Type: text/plain');

if (!isset($_SESSION['usuario'])) {
    exit("error_sesion");
}
$usuario = $_SESSION['usuario'];

$fondo  = mysqli_real_escape_string($con, $_POST['color_fondo'] ?? '');
$filas  = mysqli_real_escape_string($con, $_POST['color_filas'] ?? '');

$pass_a = $_POST['pass_actual'] ?? ''; 
$pass_n = $_POST['pass_nueva'] ?? '';
$pass_c = $_POST['pass_confirmar'] ?? '';

if (empty($fondo) || empty($filas)) {
    exit("error_datos");
}

$res_check = mysqli_query($con, "SELECT password, color_fondo, color_filas FROM usuarios WHERE usuario = '$usuario'");
$actual = mysqli_fetch_assoc($res_check);

$cambios = false;
$update_pass = "";

if ($fondo !== $actual['color_fondo'] || $filas !== $actual['color_filas']) {
    $cambios = true;
}

if (!empty($pass_n)) {
    if (!password_verify($pass_a, $actual['password'])) {
        exit("error_pass_actual"); 
    }
    if ($pass_n === $pass_c) {
        $pass_hash = password_hash($pass_n, PASSWORD_DEFAULT);
        $update_pass = ", password = '$pass_hash'";
        $cambios = true;
    } else {
        exit("error_confirmacion");
    }
}

if ($cambios) 
    $sql_update = "UPDATE usuarios SET color_fondo = '$fondo', color_filas = '$filas' $update_pass WHERE usuario = '$usuario'";

if (mysqli_query($con, $sql_update)) {
        $_SESSION['color_fondo'] = $_POST['color_fondo']; 
        $_SESSION['color_filas'] = $_POST['color_filas'];
        
        if (ob_get_length()) ob_end_clean();
        
        echo "success";
        exit; 
    } else {
        if (ob_get_length()) ob_end_clean();
        echo "error_db: " . mysqli_error($con);
        exit;
    }