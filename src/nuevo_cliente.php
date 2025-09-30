<?php
// nuevo.php — Formulario de alta
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conexion = mysqli_connect("mysql-alexgargo78.alwaysdata.net", "432730_", "Lequio.78", "alexgargo78_banca-turing");
$conexion->set_charset("utf8mb4");

$mensaje = ""; $tipoMsg = "warning";

$dni       = $_POST["dni"]       ?? "";
$nombre    = $_POST["nombre"]    ?? "";
$direccion = $_POST["direccion"] ?? "";
$telefono  = $_POST["telefono"]  ?? "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  try {
    if ($dni === "" || $nombre === "" || $direccion === "" || $telefono === "") {
      $mensaje = "❌ Rellena todos los campos.";
      $tipoMsg = "danger";
    } else {
      $stmt = $conexion->prepare("SELECT 1 FROM cliente WHERE dni=?");
      $stmt->bind_param("s", $dni);
      $stmt->execute(); $stmt->store_result();
      if ($stmt->num_rows > 0) {
        $mensaje = "⚠️ El DNI ya existe.";
        $tipoMsg = "warning";
      } else {
        $stmt = $conexion->prepare("INSERT INTO cliente (dni, nombre, direccion, telefono) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $dni, $nombre, $direccion, $telefono);
        if ($stmt->execute()) {
          header("Location: index.php?msg=add_ok");
          exit;
        } else {
          $mensaje = "❌ Error al insertar.";
          $tipoMsg = "danger";
        }
      }
    }
  } catch (Throwable $e) {
    $mensaje = "❌ " . htmlspecialchars($e->getMessage());
    $tipoMsg = "danger";
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Añadir cliente</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<div class="container p-4">
  <div class="card p-4">
    <h1 class="mb-3">Añadir nuevo cliente</h1>

    <?php if ($mensaje): ?>
      <div class="alert alert-<?= htmlspecialchars($tipoMsg) ?>"><?= $mensaje ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="mb-3">
        <label class="form-label">DNI</label>
        <input class="form-control" type="text" name="dni" value="<?= htmlspecialchars($dni) ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input class="form-control" type="text" name="nombre" value="<?= htmlspecialchars($nombre) ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Dirección</label>
        <input class="form-control" type="text" name="direccion" value="<?= htmlspecialchars($direccion) ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Teléfono</label>
        <input class="form-control" type="text" name="telefono" value="<?= htmlspecialchars($telefono) ?>" required>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-success"><i class="bi bi-check-lg"></i> Guardar</button>
        <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>

