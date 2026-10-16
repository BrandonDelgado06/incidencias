<?php
session_start();
session_unset();
session_destroy();
session_start();
$_SESSION['msg'] = ['info', 'Sesión cerrada correctamente.'];
header("Location: index.php");
exit;