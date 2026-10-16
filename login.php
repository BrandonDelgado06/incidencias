<?php
require_once 'config/conexion.php';
require_once 'includes/auth.php';
$titulo = "Iniciar sesión";

if (esAdmin()) {
    header("Location: index.php");
    exit;
}

$error = "";
$usuario = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario  = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($usuario === '' || $password === '') {
        $error = "Ingresa usuario y contraseña.";
    } else {
        $stmt = $conexion->prepare("SELECT id, nombre, password FROM usuarios WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $u = $stmt->get_result()->fetch_assoc();

        if ($u && password_verify($password, $u['password'])) {
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = $u['id'];
            $_SESSION['usuario_nombre'] = $u['nombre'];
            mensaje('success', 'Bienvenido, ' . $u['nombre'] . '.');
            header("Location: index.php");
            exit;
        }
        $error = "Usuario o contraseña incorrectos.";
    }
}
include 'includes/header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card">
      <div class="card-body">
        <h2 class="h4 mb-3">Acceso administrador</h2>
        <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
        <form method="post" novalidate>
          <div class="mb-3">
            <label class="form-label">Usuario</label>
            <input type="text" name="usuario" class="form-control" value="<?= e($usuario) ?>" autofocus>
          </div>
          <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control">
          </div>
          <button type="submit" class="btn btn-primary w-100">Ingresar</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include 'includes/footer.php'; ?>