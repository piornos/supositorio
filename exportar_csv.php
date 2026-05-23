<?php
session_start(); // Fundamental para saber quién es el usuario
if (!isset($_SESSION['usuario'])) {
    exit("Acceso denegado");
}

include("conexion.php");
$con = conectar();

// Recopilamos datos de la sesión
$usuario_actual = $_SESSION['usuario'];
$rol_usuario = $_SESSION['rol'] ?? 'user';
$entorno = $_SESSION['entorno'] ?? 'general';

$fecha = date('d-m-Y');
$filename = "Exportacion_Supositorio_$fecha.csv";

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

$output = fopen('php://output', 'w');

// BOM para acentos y Ñ
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// 1. Títulos de las columnas
fputcsv($output, array('ID', 'Nombre', 'Teléfono', 'Consulta', 'Resolución', 'Fecha Registro'), ';');

// 2. Lógica de filtrado (Igual que en tu index.php)
if ($rol_usuario === 'admin') {
    // El ADMIN exporta todo
    $sql = "SELECT ID, nombre, telefono, consulta, solucion, fecha_registro FROM supositorio ORDER BY id_sistema DESC";
} else {
    // El USUARIO filtra según su entorno actual
    if ($entorno === 'personal') {
        $sql = "SELECT ID, nombre, telefono, consulta, solucion, fecha_registro FROM supositorio WHERE vista_privada = '$usuario_actual' ORDER BY id_sistema DESC";
    } else {
        $sql = "SELECT ID, nombre, telefono, consulta, solucion, fecha_registro FROM supositorio WHERE vista_privada = 'general' ORDER BY id_sistema DESC";
    }
}

$result = mysqli_query($con, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    // Limpieza de saltos de línea para que no rompa las celdas del CSV
    $row['consulta'] = str_replace(array("\r", "\n"), ' ', $row['consulta'] ?? '');
    $row['solucion'] = str_replace(array("\r", "\n"), ' ', $row['solucion'] ?? '');
    
    fputcsv($output, $row, ';');
}

fclose($output);
exit();
?>