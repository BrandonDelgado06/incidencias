<?php
// Configuración de conexión (valores por defecto de XAMPP)
$host = "localhost";
$user = "root";
$pass = "";
$db   = "incidencias_db";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conexion = new mysqli($host, $user, $pass, $db);
    $conexion->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    die("Error de conexión a la base de datos. Verifica que MySQL esté iniciado en XAMPP y que la base 'incidencias_db' exista.");
}

// Listas de valores permitidos
$LABORATORIOS = ["Laboratorio 1", "Laboratorio 2", "Laboratorio 3", "Laboratorio 4"];
$PRIORIDADES  = ["Baja", "Media", "Alta"];
$ESTADOS      = ["Pendiente", "En proceso", "Resuelta"];