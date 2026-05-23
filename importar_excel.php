<?php
session_start();
include("conexion.php");
$con = conectar();

mysqli_report(MYSQLI_REPORT_OFF);

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    die("Acceso denegado.");
}

if (isset($_FILES['archivo_excel']) && $_FILES['archivo_excel']['error'] == 0) {

    $archivo = $_FILES['archivo_excel']['tmp_name'];
    $nuevos = 0;
    $actualizados = 0;

    if (($handle = fopen($archivo, "r")) !== FALSE) {

        $primeraLinea = fgets($handle);
        $separador = (strpos($primeraLinea, ';') !== false) ? ';' : ',';
        rewind($handle);

        fgetcsv($handle, 1000, $separador); // Saltar cabecera

        while (($datos = fgetcsv($handle, 1000, $separador)) !== FALSE) {
            if (count($datos) < 3) continue; // Validación mínima de columnas

            $id_original = trim($datos[0] ?? '');
            $nombre      = mysqli_real_escape_string($con, $datos[1] ?? '');
            $telefono    = trim($datos[2] ?? '');
            $consulta    = mysqli_real_escape_string($con, $datos[3] ?? '');
            $solucion    = mysqli_real_escape_string($con, $datos[4] ?? '');

            // --- LÓGICA DE AUTO-GENERACIÓN DE NOTAS ---
            if (strtoupper($telefono) === 'NOTA') {
                // Buscamos el último ID que empiece por NOTA- en la base de datos
                $res_n = mysqli_query($con, "SELECT ID FROM supositorio WHERE ID LIKE 'NOTA-%' ORDER BY id_sistema DESC LIMIT 1");
                $ultima = mysqli_fetch_assoc($res_n);

                if ($ultima) {
                    // Extraemos el número, le sumamos 1 y creamos el nuevo ID
                    $num = (int)str_replace('NOTA-', '', $ultima['ID']);
                    $id_final = "NOTA-" . ($num + 1);
                } else {
                    // Si es la primera nota de la historia
                    $id_final = "NOTA-1";
                }
            } else {
                // Si no es nota, usamos el ID que viene en el CSV
                $id_final = mysqli_real_escape_string($con, $id_original);
            }

            $telefono_db = mysqli_real_escape_string($con, $telefono);

            // Consulta con el ID calculado
            $sql = "INSERT INTO supositorio (ID, nombre, telefono, consulta, solucion) 
                    VALUES ('$id_final', '$nombre', '$telefono_db', '$consulta', '$solucion')
                    ON DUPLICATE KEY UPDATE 
                    nombre = '$nombre', 
                    telefono = '$telefono_db', 
                    consulta = '$consulta', 
                    solucion = '$solucion'";

            if (mysqli_query($con, $sql)) {
                $filas = mysqli_affected_rows($con);
                if ($filas == 1) $nuevos++;
                elseif ($filas == 2) $actualizados++;
            }
        }
        fclose($handle);

        header("Location: supositorio.php?nuevos=$nuevos&actualizados=$actualizados");
        exit();
    }
} else {
    header("Location: supositorio.php?error=subida");
    exit();
}
