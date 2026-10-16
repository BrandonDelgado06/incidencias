<?php require_once __DIR__ . '/auth.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($titulo ?? 'Incidencias') ?> | Soporte Técnico</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/estilos.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="index.php">🖥️ Soporte Técnico</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menu">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Incidencias</a></li>
        <li class="nav-item"><a class="nav-link" href="registrar.php">Nueva incidencia</a></li>
      </ul>
      <ul class="navbar-nav">
        <?php if (esAdmin()): ?>
          <li class="nav-item"><span class="navbar-text me-3">Admin: <?= e($_SESSION['usuario_nombre']) ?></span></li>
          <li class="nav-item"><a class="btn btn-light btn-sm" href="logout.php">Cerrar sesión</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="btn btn-light btn-sm" href="login.php">Acceso administrador</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<main class="container my-4">
<?php if (isset($_SESSION['msg'])): ?>
  <div class="alert alert-<?= e($_SESSION['msg'][0]) ?> alert-dismissible fade show" role="alert">
    <?= e($_SESSION['msg'][1]) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  <?php unset($_SESSION['msg']); ?>
<?php endif; ?>