<?php
require_once 'config/conexion.php';
require_once 'includes/auth.php';
requerirLogin();
$titulo = "Editar incidencia";

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$stmt = $conexion->prepare("SELECT * FROM incidencias WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$incidencia = $stmt->get_result()->fetch_assoc();
if (!$incidencia) {
    mensaje('danger', 'La incidencia no existe.');
    header("Location: index.php");
    exit;
}

$errores = [];
$datos = $incidencia;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (['reportante', 'laboratorio', 'equipo', 'descripcion', 'prioridad', 'estado'] as $campo) {
        $datos[$campo] = trim($_POST[$campo] ?? '');
    }
    if ($datos['reportante'] === '')  $errores[] = "El nombre de quien reporta es obligatorio.";
    if (!in_array($datos['laboratorio'], $LABORATORIOS, true)) $errores[] = "Selecciona un laboratorio válido.";
    if ($datos['equipo'] === '')      $errores[] = "El equipo afectado es obligatorio.";
    if ($datos['descripcion'] === '') $errores[] = "La descripción del problema es obligatoria.";
    if (!in_array($datos['prioridad'], $PRIORIDADES, true)) $errores[] = "Prioridad no válida.";
    if (!in_array($datos['estado'], $ESTADOS, true))        $errores[] = "Estado no válido.";

    if (!$errores) {
        $stmt = $conexion->prepare("UPDATE incidencias SET reportante=?, laboratorio=?, equipo=?, descripcion=?, prioridad=?, estado=? WHERE id=?");
        $stmt->bind_param("ssssssi", $datos['reportante'], $datos['laboratorio'], $datos['equipo'], $datos['descripcion'], $datos['prioridad'], $datos['estado'], $id);
        $stmt->execute();
        mensaje('success', 'Incidencia actualizada correctamente.');
        header("Location: index.php");
        exit;
    }
}
include 'includes/header.php';
?>
<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-body">
        <h2 class="h4 mb-1">Editar incidencia #<?= (int)$id ?></h2>
        <p class="text-muted">Registrada el <?= e(date('d/m/Y H:i', strtotime($incidencia['fecha_registro']))) ?></p>
        <?php if ($errores): ?>
          <div class="alert alert-danger"><ul class="mb-0">
            <?php foreach ($errores as $err): ?><li><?= e($err) ?></li><?php endforeach; ?>
          </ul></div>
        <?php endif; ?>
        <form method="post" novalidate>
          <input type="hidden" name="id" value="<?= (int)$id ?>">
          <div class="mb-3">
            <label class="form-label">Nombre de quien reporta *</label>
            <input type="text" name="reportante" class="form-control" maxlength="100" value="<?= e($datos['reportante']) ?>">
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Laboratorio *</label>
              <select name="laboratorio" class="form-select">
                <?php foreach ($LABORATORIOS as $l): ?>
                  <option value="<?= e($l) ?>" <?= $datos['laboratorio'] === $l ? 'selected' : '' ?>><?= e($l) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Equipo afectado *</label>
              <input type="text" name="equipo" class="form-control" maxlength="100" value="<?= e($datos['equipo']) ?>">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Descripción del problema *</label>
            <textarea name="descripcion" rows="4" class="form-control"><?= e($datos['descripcion']) ?></textarea>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Prioridad *</label>
              <select name="prioridad" class="form-select">
                <?php foreach ($PRIORIDADES as $p): ?>
                  <option value="<?= e($p) ?>" <?= $datos['prioridad'] === $p ? 'selected' : '' ?>><?= e($p) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Estado *</label>
              <select name="estado" class="form-select">
                <?php foreach ($ESTADOS as $s): ?>
                  <option value="<?= e($s) ?>" <?= $datos['estado'] === $s ? 'selected' : '' ?>><?= e($s) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <button type="submit" class="btn btn-primary">Guardar cambios</button>
          <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include 'includes/footer.php'; ?>