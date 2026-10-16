<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function esAdmin() {
    return isset($_SESSION['usuario_id']);
}

function requerirLogin() {
    if (!esAdmin()) {
        $_SESSION['msg'] = ['danger', 'Debes iniciar sesión para realizar esta acción.'];
        header("Location: login.php");
        exit;
    }
}

function mensaje($tipo, $texto) {
    $_SESSION['msg'] = [$tipo, $texto];
}

function e($valor) {
    return htmlspecialchars($valor ?? '', ENT_QUOTES, 'UTF-8');
}