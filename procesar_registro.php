<?php
include("conexion.php");
$con = conectar();

$usuarioInput = mysqli_real_escape_string($con, $_POST['usuario'] ?? '');
$passwordInput = $_POST['password'] ?? ''; 
$rol = 'user'; 

if (!empty($usuarioInput) && !empty($passwordInput)) {
    
    $passwordHash = password_hash($passwordInput, PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO usuarios (usuario, password, rol, color_fondo, color_botones, foto_perfil, primer_acceso, ultimo_entorno) 
                VALUES ('$usuarioInput', '$passwordHash', '$rol', '#e2e8f0', '#475569', '', 0, 'general')";
        
        if (mysqli_query($con, $sql)) {
            header("Location: index.php?registro=ok");
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            header("Location: registro.php?error=duplicado");
            exit();
        } else {
            echo "Error inesperado: " . $e->getMessage();
            exit();
        }
    }
} else {
    header("Location: registro.php?error=vacio");
    exit();
}
?>