<?php
$filename = "Plantilla_supositorio.csv";

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

$output = fopen('php://output', 'w');

fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

fputcsv($output, array('ID', 'Nombre', 'Telefono', 'Consulta', 'Solucion'), ';');

fclose($output);
exit();
?>