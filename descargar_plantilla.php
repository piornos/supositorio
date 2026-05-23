<?php
// Nombre del archivo
$filename = "Plantilla_supositorio.csv";

// Configurar cabeceras
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

// Abrir salida
$output = fopen('php://output', 'w');

// 1. EL PASO MÁS IMPORTANTE: Enviar el BOM UTF-8
// Esto le dice a Excel: "Hey, esto es UTF-8, no rompas las columnas".
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// 2. Escribir los encabezados usando ";" como delimitador
// Cambiamos la coma por el punto y coma para que Excel lo entienda a la primera.
fputcsv($output, array('ID', 'Nombre', 'Telefono', 'Consulta', 'Solucion'), ';');

// Cerrar
fclose($output);
exit();
?>