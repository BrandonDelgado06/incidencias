<?php
require_once 'config/conexion.php';
require_once 'includes/auth.php';
requerirLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$id     = (int)($_POST['id'] ?? 0);
$estado = trim($_POST['estado'] ?? '');
$volver = trim($_POST['volver'] ?? '');

if ($id > 0 && in_array($estado, $ESTADOS, true)) {
    $stmt = $conexion->prepare("UPDATE incidencias SET estado = ? WHERE id = ?");
    $stmt->bind_param("si", $estado, $id);
    $stmt->execute();
    mensaje('success', "Estado de la incidencia #$id cambiado a \"$estado\".");
} else {
    mensaje('danger', 'Datos no válidos para cambiar el estado.');
}

// Conservar solo filtros permitidos
parse_str($volver, $filtros);
$permitidos = array_intersect_key($filtros, array_flip(['laboratorio', 'estado', 'prioridad']));
$destino = "index.php" . ($permitidos ? "?" . http_build_query($permitidos) : "");
header("Location: $destino");
exit;