<?php
require_once 'config/conexion.php';
require_once 'includes/auth.php';
requerirLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $stmt = $conexion->prepare("DELETE FROM incidencias WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        mensaje('success', "Incidencia #$id eliminada correctamente.");
    }
}
header("Location: index.php");
exit;