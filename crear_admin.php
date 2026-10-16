<?php
// EJECUTAR UNA SOLA VEZ desde el navegador: http://localhost/incidencias/crear_admin.php
require_once 'config/conexion.php';

$usuario = "admin";
$clave   = "admin123";
$nombre  = "Administrador";

$stmt = $conexion->prepare("SELECT id FROM usuarios WHERE usuario = ?");
$stmt->bind_param("s", $usuario);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {
    echo "El usuario administrador ya existe. Ya puedes eliminar este archivo.";
} else {
    $hash = password_hash($clave, PASSWORD_DEFAULT);
    $ins = $conexion->prepare("INSERT INTO usuarios (usuario, password, nombre) VALUES (?, ?, ?)");
    $ins->bind_param("sss", $usuario, $hash, $nombre);
    $ins->execute();
    echo "Administrador creado. Usuario: admin | Contraseña: admin123. <a href='login.php'>Ir al login</a>";
}