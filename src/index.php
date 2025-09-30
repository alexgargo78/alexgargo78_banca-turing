<?php
// index.php — Banca Turing (Listado + botón para alta en nuevo.php)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conexion = mysqli_connect("mysql-alexgargo78.alwaysdata.net", "432730_", "Lequio.78", "alexgargo78_banca-turing");
$conexion->set_charset("utf8mb4");

/* ----------------------------
   Ordenación por columnas (GET/POST)
----------------------------- */
$permitidas = ['dni','nombre','direccion','telefono'];
$ordenParam = $_GET['orden'] ?? $_POST['orden'] ?? 'nombre';
$dirParam   = $_GET['dir']   ?? $_POST['dir']   ?? 'asc';

$orden = in_array($ordenParam, $permitidas, true) ? $ordenParam : 'nombre';
$dir   = strtolower($dirParam) === 'desc' ? 'desc' : 'asc'; // default asc

// para conservar los parámetros de orden en redirecciones
$queryOrden = "orden={$orden}&dir={$dir}";

/* ----------------------------
   Mensajes por querystring
----------------------------- */

$mensaje = ""; $tipoMsg = "info";
if (!empty($_GET["msg"])) {
  switch ($_GET["msg"]) {
    case "add_ok":  $mensaje="✅ Cliente añadido correctamente."; $tipoMsg="success"; break;
    case "dni_dup": $mensaje="⚠️ El DNI ya existe. No se ha insertado."; $tipoMsg="warning"; break;
    case "err":     $mensaje="❌ " . htmlspecialchars($_GET["text"] ?? "Se produjo un error."); $tipoMsg="danger"; break;
    case "del_ok":  $mensaje="🗑️ Cliente eliminado."; $tipoMsg="secondary"; break;
    case "upd_ok":  $mensaje="✅ Cliente actualizado."; $tipoMsg="success"; break;
  }
}

// Acciones de eliminar / modificar / actualizar
$accion      = $_POST["accion"]      ?? "";
$dni         = $_POST["dni"]         ?? "";
$nombre      = $_POST["nombre"]      ?? "";
$direccion   = $_POST["direccion"]   ?? "";
$telefono    = $_POST["telefono"]    ?? "";
$dniAntiguo  = $_POST["dniAntiguo"]  ?? "";

try {
  if ($accion === "eliminar") {
    $stmt = $conexion->prepare("DELETE FROM cliente WHERE dni = ?");
    $stmt->bind_param("s", $dni);
    $stmt->execute();
    header("Location: index.php?msg=del_ok&$queryOrden");
    exit;
  }

  if ($accion === "actualizar") {
    if ($dni === "" || $nombre === "" || $direccion === "" || $telefono === "") {
      header("Location: index.php?msg=err&text=Faltan+campos+obligatorios&$queryOrden");
      exit;
    }

    if ($dni !== $dniAntiguo) {
      $check = $conexion->prepare("SELECT 1 FROM cliente WHERE dni = ?");
      $check->bind_param("s", $dni);
      $check->execute(); $check->store_result();
      if ($check->num_rows > 0) {
        header("Location: index.php?msg=err&text=DNI+duplicado+en+actualizacion&$queryOrden");
        exit;
      }
      $upd = $conexion->prepare("UPDATE cliente SET dni=?, nombre=?, direccion=?, telefono=? WHERE dni=?");
      $upd->bind_param("sssss", $dni, $nombre, $direccion, $telefono, $dniAntiguo);
      $upd->execute();
      header("Location: index.php?msg=upd_ok&$queryOrden");
      exit;
    } else {
      $upd = $conexion->prepare("UPDATE cliente SET nombre=?, direccion=?, telefono=? WHERE dni=?");
      $upd->bind_param("ssss", $nombre, $direccion, $telefono, $dniAntiguo);
      $upd->execute();
      header("Location: index.php?msg=upd_ok&$queryOrden");
      exit;
    }
  }
} catch (Throwable $e) {
  header("Location: index.php?msg=err&text=" . urlencode($e->getMessage()) . "&$queryOrden");
  exit;
}

/* ----------------------------
   Consulta con ORDER BY seguro
----------------------------- */
$sql = "SELECT dni, nombre, direccion, telefono FROM cliente ORDER BY $orden $dir";
$result = $conexion->query($sql);

/* ----------------------------
   Helper para cabeceras sortables
----------------------------- */
function th_sort(string $label, string $col, string $ordenActual, string $dirActual): string {
  $isActive = ($col === $ordenActual);
  // Alterna dirección si se vuelve a pulsar la misma columna
  $newDir = ($isActive && $dirActual === 'asc') ? 'desc' : 'asc';
  $arrow = '';
  if ($isActive) $arrow = $dirActual === 'asc' ? ' ▲' : ' ▼';
  $href = "index.php?orden={$col}&dir={$newDir}";
  return "<a href=\"$href\" class=\"text-white text-decoration-none\">$label$arrow</a>";
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Banca Turing</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <div class="container p-4" id="principal">
        <div class="card p-3 glass-card">
            <h1 class="mb-3 text-center">Banca Turing</h1>

            <div class="text-center mb-3">
                <a href="nuevo_cliente.php" class="btn btn-success">
                    <i class="bi bi-plus"></i> Añadir nuevo cliente
                </a>
            </div>

            <?php if ($mensaje): ?>
            <div class="alert alert-<?= htmlspecialchars($tipoMsg) ?>"><?= $mensaje ?></div>
            <?php endif; ?>

            <table class="table table-striped align-middle glass-table">
                <thead>
                    <tr>
                        <th><?= th_sort('DNI',       'dni',       $orden, $dir) ?></th>
                        <th><?= th_sort('Nombre',    'nombre',    $orden, $dir) ?></th>
                        <th><?= th_sort('Dirección', 'direccion', $orden, $dir) ?></th>
                        <th><?= th_sort('Teléfono',  'telefono',  $orden, $dir) ?></th>
                        <th style="width:1%"></th>
                        <th style="width:1%"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <?php if ($accion === "modificar" && $dni === $row['dni']): ?>
                    <?php $formId = 'f'.md5($row['dni']); ?>
                    <!-- Formulario "fantasma" para asociar inputs por form="id" -->
                    <form id="<?= $formId ?>" action="index.php?<?= $queryOrden ?>" method="post"></form>
                    <tr>
                        <td>
                            <input class="form-control" type="text" name="dni"
                                value="<?= htmlspecialchars($row['dni']) ?>" form="<?= $formId ?>">
                            <input type="hidden" name="accion" value="actualizar" form="<?= $formId ?>">
                            <input type="hidden" name="dniAntiguo" value="<?= htmlspecialchars($row['dni']) ?>"
                                form="<?= $formId ?>">
                            <input type="hidden" name="orden" value="<?= htmlspecialchars($orden) ?>"
                                form="<?= $formId ?>">
                            <input type="hidden" name="dir" value="<?= htmlspecialchars($dir) ?>" form="<?= $formId ?>">
                        </td>
                        <td><input class="form-control" type="text" name="nombre"
                                value="<?= htmlspecialchars($row['nombre']) ?>" form="<?= $formId ?>"></td>
                        <td><input class="form-control" type="text" name="direccion"
                                value="<?= htmlspecialchars($row['direccion']) ?>" form="<?= $formId ?>"></td>
                        <td><input class="form-control" type="text" name="telefono"
                                value="<?= htmlspecialchars($row['telefono']) ?>" form="<?= $formId ?>"></td>
                        <td>
                            <button class="btn btn-success btn-sm w-100" type="submit" form="<?= $formId ?>">
                                <i class="bi bi-check-lg"></i> Guardar
                            </button>
                        </td>
                        <td>
                            <a href="index.php?<?= $queryOrden ?>" class="btn btn-secondary btn-sm w-100">
                                <i class="bi bi-x-lg"></i> Cancelar
                            </a>
                        </td>
                    </tr>
                    <?php else: ?>
                    <!-- Fila normal -->
                    <tr>
                        <td><?= htmlspecialchars($row['dni']) ?></td>
                        <td><?= htmlspecialchars($row['nombre']) ?></td>
                        <td><?= htmlspecialchars($row['direccion']) ?></td>
                        <td><?= htmlspecialchars($row['telefono']) ?></td>
                        <td>
                            <form action="index.php?<?= $queryOrden ?>" method="post" class="m-0">
                                <input type="hidden" name="accion" value="eliminar">
                                <input type="hidden" name="dni" value="<?= htmlspecialchars($row['dni']) ?>">
                                <input type="hidden" name="orden" value="<?= htmlspecialchars($orden) ?>">
                                <input type="hidden" name="dir" value="<?= htmlspecialchars($dir) ?>">
                                <button class="btn btn-danger btn-sm w-100" type="submit">
                                    <i class="bi bi-trash"></i> Borrar
                                </button>
                            </form>
                        </td>
                        <td>
                            <form action="index.php?<?= $queryOrden ?>" method="post" class="m-0">
                                <input type="hidden" name="accion" value="modificar">
                                <input type="hidden" name="dni" value="<?= htmlspecialchars($row['dni']) ?>">
                                <input type="hidden" name="orden" value="<?= htmlspecialchars($orden) ?>">
                                <input type="hidden" name="dir" value="<?= htmlspecialchars($dir) ?>">
                                <button class="btn btn-primary btn-sm w-100" type="submit">
                                    <i class="bi bi-pencil"></i> Modificar
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>