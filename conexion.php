<?php
require_once 'config.php';

function conectar()
{
    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if (!$con) {
        die("Fallo la conexión: " . mysqli_connect_error());
    }

    mysqli_set_charset($con, "utf8");
    mysqli_query($con, "SET time_zone = '+01:00'");
    
    return $con;
}