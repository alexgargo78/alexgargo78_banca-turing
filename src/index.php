<?php
// index.php — Banca Turing (Listado + botón para alta en nuevo.php)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conexion = mysqli_connect("mysql-alexgargo78.alwaysdata.net", "432730_", "Lequio.78", "alexgargo78_banca-turing");
$conexion->set_charset("utf8mb4");

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
    header("Location: index.php?msg=del_ok");
    exit;
  }

  if ($accion === "actualizar") {
    if ($dni === "" || $nombre === "" || $direccion === "" || $telefono === "") {
      header("Location: index.php?msg=err&text=Faltan+campos+obligatorios");
      exit;
    }
    if ($dni !== $dniAntiguo) {
      $stmt = $conexion->prepare("SELECT 1 FROM cliente WHERE dni = ?");
      $stmt->bind_param("s", $dni);
      $stmt->execute(); $stmt->store_result();
      if ($stmt->num_rows > 0) {
        header("Location: index.php?msg=err&text=DNI+duplicado+en+actualizacion");
        exit;
      }
      $stmt = $conexion->prepare("UPDATE cliente SET dni=?, nombre=?, direccion=?, telefono=? WHERE dni=?");
      $stmt->bind_param("sssss", $dni, $nombre, $direccion, $telefono, $dniAntiguo);
      $stmt->execute();
      header("Location: index.php?msg=upd_ok");
      exit;
    } else {
      $stmt = $conexion->prepare("UPDATE cliente SET nombre=?, direccion=?, telefono=? WHERE dni=?");
      $stmt->bind_param("ssss", $nombre, $direccion, $telefono, $dniAntiguo);
      $stmt->execute();
      header("Location: index.php?msg=upd_ok");
      exit;
    }
  }
} catch (Throwable $e) {
  header("Location: index.php?msg=err&text=" . urlencode($e->getMessage()));
  exit;
}

// Listado
$result = $conexion->query("SELECT dni, nombre, direccion, telefono FROM cliente ORDER BY nombre");
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
<div class="container p-4">
  <div class="card p-3">
    <h1 class="text-center mb-3">Banca Turing</h1>

    <!-- Botón para ir a nuevo.php -->
    <div class="mb-3">
      <a href="añadir_cliente.php" class="btn btn-success">
        <i class="bi bi-plus"></i> Añadir nuevo cliente
      </a>
    </div>

    <?php if ($mensaje): ?>
      <div class="alert alert-<?= htmlspecialchars($tipoMsg) ?>"><?= $mensaje ?></div>
    <?php endif; ?>

    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>DNI</th>
          <th>Nombre</th>
          <th>Dirección</th>
          <th>Teléfono</th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['dni']) ?></td>
          <td><?= htmlspecialchars($row['nombre']) ?></td>
          <td><?= htmlspecialchars($row['direccion']) ?></td>
          <td><?= htmlspecialchars($row['telefono']) ?></td>
          <td>
            <form action="index.php" method="post">
              <input type="hidden" name="accion" value="eliminar">
              <input type="hidden" name="dni" value="<?= htmlspecialchars($row['dni']) ?>">
              <button class="btn btn-danger" type="submit">
                <i class="bi bi-trash"></i> Borrar
              </button>
            </form>
          </td>
          <td>
            <form action="index.php" method="post">
              <input type="hidden" name="accion" value="modificar">
              <input type="hidden" name="dni" value="<?= htmlspecialchars($row['dni']) ?>">
              <button class="btn btn-primary" type="submit">
                <i class="bi bi-pencil"></i> Modificar
              </button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>

