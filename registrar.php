<?php
require_once 'config/conexion.php';
require_once 'includes/auth.php';
$titulo = "Nueva incidencia";

$errores = [];
$datos = ['reportante' => '', 'laboratorio' => '', 'equipo' => '', 'descripcion' => '', 'prioridad' => 'Media'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($datos as $campo => $v) {
        $datos[$campo] = trim($_POST[$campo] ?? '');
    }
    if ($datos['reportante'] === '')  $errores[] = "El nombre de quien reporta es obligatorio.";
    if ($datos['laboratorio'] === '') $errores[] = "Selecciona un laboratorio.";
    elseif (!in_array($datos['laboratorio'], $LABORATORIOS, true)) $errores[] = "Laboratorio no válido.";
    if ($datos['equipo'] === '')      $errores[] = "El equipo afectado es obligatorio.";
    if ($datos['descripcion'] === '') $errores[] = "La descripción del problema es obligatoria.";
    if (!in_array($datos['prioridad'], $PRIORIDADES, true)) $errores[] = "Prioridad no válida.";

    if (!$errores) {
        $stmt = $conexion->prepare("INSERT INTO incidencias (reportante, laboratorio, equipo, descripcion, prioridad) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $datos['reportante'], $datos['laboratorio'], $datos['equipo'], $datos['descripcion'], $datos['prioridad']);
        $stmt->execute();
        mensaje('success', 'Incidencia registrada correctamente.');
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
        <h2 class="h4 mb-3">Registrar incidencia</h2>
        <?php if ($errores): ?>
          <div class="alert alert-danger"><ul class="mb-0">
            <?php foreach ($errores as $err): ?><li><?= e($err) ?></li><?php endforeach; ?>
          </ul></div>
        <?php endif; ?>
        <form method="post" novalidate>
          <div class="mb-3">
            <label class="form-label">Nombre de quien reporta *</label>
            <input type="text" name="reportante" class="form-control" maxlength="100" value="<?= e($datos['reportante']) ?>">
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Laboratorio *</label>
              <select name="laboratorio" class="form-select">
                <option value="">-- Selecciona --</option>
                <?php foreach ($LABORATORIOS as $l): ?>
                  <option value="<?= e($l) ?>" <?= $datos['laboratorio'] === $l ? 'selected' : '' ?>><?= e($l) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Equipo afectado *</label>
              <input type="text" name="equipo" class="form-control" maxlength="100" placeholder="Ej: PC-05, Proyector" value="<?= e($datos['equipo']) ?>">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Descripción del problema *</label>
            <textarea name="descripcion" rows="4" class="form-control"><?= e($datos['descripcion']) ?></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Prioridad *</label>
            <select name="prioridad" class="form-select">
              <?php foreach ($PRIORIDADES as $p): ?>
                <option value="<?= e($p) ?>" <?= $datos['prioridad'] === $p ? 'selected' : '' ?>><?= e($p) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Guardar incidencia</button>
          <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include 'includes/footer.php'; ?>