<?php
require_once 'config/conexion.php';
$titulo = "Listado de incidencias";

// Filtros (GET)
$lab       = trim($_GET['laboratorio'] ?? '');
$estado    = trim($_GET['estado'] ?? '');
$prioridad = trim($_GET['prioridad'] ?? '');

$where = []; $tipos = ""; $params = [];
if ($lab !== '')       { $where[] = "laboratorio = ?"; $tipos .= "s"; $params[] = $lab; }
if ($estado !== '')    { $where[] = "estado = ?";      $tipos .= "s"; $params[] = $estado; }
if ($prioridad !== '') { $where[] = "prioridad = ?";   $tipos .= "s"; $params[] = $prioridad; }

$sql = "SELECT * FROM incidencias";
if ($where) { $sql .= " WHERE " . implode(" AND ", $where); }
$sql .= " ORDER BY fecha_registro DESC, id DESC";

$stmt = $conexion->prepare($sql);
if ($params) { $stmt->bind_param($tipos, ...$params); }
$stmt->execute();
$resultado = $stmt->get_result();

function badgePrioridad($p) {
    return ['Alta' => 'danger', 'Media' => 'warning text-dark', 'Baja' => 'success'][$p] ?? 'secondary';
}
function badgeEstado($s) {
    return ['Pendiente' => 'secondary', 'En proceso' => 'info text-dark', 'Resuelta' => 'success'][$s] ?? 'secondary';
}

// Parámetros de filtro para conservarlos al cambiar estado
$qs = http_build_query(array_filter(['laboratorio' => $lab, 'estado' => $estado, 'prioridad' => $prioridad]));

include 'includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="h4 mb-0">Incidencias registradas</h2>
  <a href="registrar.php" class="btn btn-primary">+ Nueva incidencia</a>
</div>

<div class="card mb-4">
  <div class="card-body">
    <form method="get" class="row g-2 align-items-end">
      <div class="col-md-3">
        <label class="form-label">Laboratorio</label>
        <select name="laboratorio" class="form-select">
          <option value="">Todos</option>
          <?php foreach ($LABORATORIOS as $l): ?>
            <option value="<?= e($l) ?>" <?= $lab === $l ? 'selected' : '' ?>><?= e($l) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Estado</label>
        <select name="estado" class="form-select">
          <option value="">Todos</option>
          <?php foreach ($ESTADOS as $s): ?>
            <option value="<?= e($s) ?>" <?= $estado === $s ? 'selected' : '' ?>><?= e($s) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Prioridad</label>
        <select name="prioridad" class="form-select">
          <option value="">Todas</option>
          <?php foreach ($PRIORIDADES as $p): ?>
            <option value="<?= e($p) ?>" <?= $prioridad === $p ? 'selected' : '' ?>><?= e($p) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
        <a href="index.php" class="btn btn-outline-secondary w-100">Limpiar</a>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="table-responsive">
    <table class="table table-hover tabla-incidencias mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th><th>Fecha de registro</th><th>Reporta</th><th>Laboratorio</th>
          <th>Equipo</th><th>Descripción</th><th>Prioridad</th><th>Estado</th>
          <?php if (esAdmin()): ?><th>Acciones</th><?php endif; ?>
        </tr>
      </thead>
      <tbody>
      <?php if ($resultado->num_rows === 0): ?>
        <tr><td colspan="<?= esAdmin() ? 9 : 8 ?>" class="text-center text-muted py-4">No hay incidencias para mostrar.</td></tr>
      <?php endif; ?>
      <?php while ($fila = $resultado->fetch_assoc()): ?>
        <tr>
          <td><?= (int)$fila['id'] ?></td>
          <td><?= e(date('d/m/Y H:i', strtotime($fila['fecha_registro']))) ?></td>
          <td><?= e($fila['reportante']) ?></td>
          <td><?= e($fila['laboratorio']) ?></td>
          <td><?= e($fila['equipo']) ?></td>
          <td class="descripcion"><?= e($fila['descripcion']) ?></td>
          <td><span class="badge bg-<?= badgePrioridad($fila['prioridad']) ?>"><?= e($fila['prioridad']) ?></span></td>
          <td>
            <?php if (esAdmin()): ?>
              <form action="cambiar_estado.php" method="post" class="d-flex gap-1">
                <input type="hidden" name="id" value="<?= (int)$fila['id'] ?>">
                <input type="hidden" name="volver" value="<?= e($qs) ?>">
                <select name="estado" class="form-select form-select-sm" onchange="this.form.submit()">
                  <?php foreach ($ESTADOS as $s): ?>
                    <option value="<?= e($s) ?>" <?= $fila['estado'] === $s ? 'selected' : '' ?>><?= e($s) ?></option>
                  <?php endforeach; ?>
                </select>
              </form>
            <?php else: ?>
              <span class="badge bg-<?= badgeEstado($fila['estado']) ?>"><?= e($fila['estado']) ?></span>
            <?php endif; ?>
          </td>
          <?php if (esAdmin()): ?>
          <td class="text-nowrap">
            <a href="editar.php?id=<?= (int)$fila['id'] ?>" class="btn btn-sm btn-outline-primary">Editar</a>
            <form action="eliminar.php" method="post" class="d-inline"
                  onsubmit="return confirm('¿Seguro que deseas eliminar la incidencia #<?= (int)$fila['id'] ?>? Esta acción no se puede deshacer.');">
              <input type="hidden" name="id" value="<?= (int)$fila['id'] ?>">
              <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
            </form>
          </td>
          <?php endif; ?>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include 'includes/footer.php'; ?>