<?php
session_start();
// Habilitamos errores para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

include("conexion.php");
$con = conectar();

$usuarioInput = mysqli_real_escape_string($con, $_POST['usuario'] ?? '');
$passwordInput = $_POST['password'] ?? '';

if (!empty($usuarioInput) && !empty($passwordInput)) {

    // Buscamos al usuario solo por su nombre
    $sql = "SELECT * FROM usuarios WHERE usuario = '$usuarioInput'";
    $resultado = mysqli_query($con, $sql);

    if ($fila = mysqli_fetch_assoc($resultado)) {
        
        // --- CAMBIO CLAVE: Verificación Dual ---
        // Esto permite que las contraseñas antiguas (texto plano) y las nuevas (Hash) funcionen
        $valido = password_verify($passwordInput, $fila['password']) || ($passwordInput === $fila['password']);

        if ($valido) {
            // 1. Datos básicos de identidad
            $_SESSION['usuario'] = $fila['usuario'];
            $_SESSION['rol'] = $fila['rol'] ?? 'user';

            // 2. Inicializar el entorno
            $_SESSION['entorno'] = 'general';

            // 3. Preferencias de Interfaz
            // Cargamos todo en la sesión para que el icono y colores se vean al instante
            $_SESSION['color_fondo'] = !empty($fila['color_fondo']) ? $fila['color_fondo'] : '#e2e8f0';
            $_SESSION['color_botones'] = !empty($fila['color_botones']) ? $fila['color_botones'] : '#475569';
            $_SESSION['foto_perfil'] = $fila['foto_perfil'] ?? '';
            $_SESSION['tema_preferido'] = $fila['tema_preferido'] ?? 'light';
            
            header("Location: supositorio.php");
            exit();
        } else {
            header("Location: index.php?error=clave");
            exit();
        }
    } else {
        header("Location: index.php?error=no_existe");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}