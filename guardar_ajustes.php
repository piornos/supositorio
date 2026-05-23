<?php
ob_start();
session_start();
include("conexion.php");
$con = conectar();

// Cambiamos a JSON para que el JavaScript pueda leer mejor los errores si quieres, 
// pero mantengo el formato texto para no romper tu lógica actual.
header('Content-Type: text/plain');

if (!isset($_SESSION['usuario'])) {
    exit("error_sesion");
}
$usuario = $_SESSION['usuario'];

// Recogemos colores
$fondo  = mysqli_real_escape_string($con, $_POST['color_fondo'] ?? '');
$filas  = mysqli_real_escape_string($con, $_POST['color_filas'] ?? '');

// Recogemos Passwords
$pass_a = $_POST['pass_actual'] ?? ''; // Necesaria para validar el cambio
$pass_n = $_POST['pass_nueva'] ?? '';
$pass_c = $_POST['pass_confirmar'] ?? '';

if (empty($fondo) || empty($filas)) {
    exit("error_datos");
}

// Consultamos datos actuales para comparar
$res_check = mysqli_query($con, "SELECT password, color_fondo, color_filas FROM usuarios WHERE usuario = '$usuario'");
$actual = mysqli_fetch_assoc($res_check);

$cambios = false;
$update_pass = "";

// LÓGICA DE COLORES
if ($fondo !== $actual['color_fondo'] || $filas !== $actual['color_filas']) {
    $cambios = true;
}

// LÓGICA DE CONTRASEÑA (Con validación de actual)
if (!empty($pass_n)) {
    // 1. Verificar que la actual sea correcta
    if (!password_verify($pass_a, $actual['password'])) {
        exit("error_pass_actual"); // Error si la contraseña vieja no coincide
    }
    // 2. Verificar que la nueva y confirmación coincidan
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
        // 1. Sincronizamos la sesión con los valores ORIGINALES de POST
        $_SESSION['color_fondo'] = $_POST['color_fondo']; 
        $_SESSION['color_filas'] = $_POST['color_filas'];
        
        // 2. Limpiamos cualquier salida previa (espacios o warnings)
        if (ob_get_length()) ob_end_clean();
        
        // 3. Enviamos SOLO la palabra success
        echo "success";
        exit; // Esto evita que se envíe cualquier espacio en blanco que haya al final del archivo
    } else {
        if (ob_get_length()) ob_end_clean();
        echo "error_db: " . mysqli_error($con);
        exit;
    }