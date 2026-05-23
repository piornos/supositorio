<?php
session_start();
// Solo el admin puede hacer backups
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    exit("Acceso denegado");
}

include("conexion.php");
$con = conectar();

$tablas = array();
$result = mysqli_query($con, "SHOW TABLES");
while ($row = mysqli_fetch_row($result)) {
    $tablas[] = $row[0];
}

$sql_script = "";
foreach ($tablas as $tabla) {
    $result = mysqli_query($con, "SHOW CREATE TABLE $tabla");
    $row = mysqli_fetch_row($result);
    $sql_script .= "\n\n" . $row[1] . ";\n\n";

    $result = mysqli_query($con, "SELECT * FROM $tabla");
    $num_campos = mysqli_num_fields($result);

    for ($i = 0; $i < $num_campos; $i++) {
        while ($row = mysqli_fetch_row($result)) {
            $sql_script .= "INSERT INTO $tabla VALUES(";
            for ($j = 0; $j < $num_campos; $j++) {
                $row[$j] = $row[$j] === NULL ? 'NULL' : '"' . mysqli_real_escape_string($con, $row[$j]) . '"';
                $sql_script .= $row[$j];
                if ($j < ($num_campos - 1)) $sql_script .= ",";
            }
            $sql_script .= ");\n";
        }
    }
}

// Configuración de descarga
$nombre_archivo = 'backup_sistema_' . date("Y-m-d_H-i-s") . '.sql';
header('Content-Type: application/octet-stream');
header('Content-Transfer-Encoding: Binary');
header('Content-disposition: attachment; filename="' . $nombre_archivo . '"');
echo $sql_script;
exit;
?>