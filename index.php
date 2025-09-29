<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
    <title>Banca Turing</title>
</head>

<body>
    <div id="principal">

        <div class="card">

            <h1 class="text-center">Banca Turing</h1>

            <?php
            
            
            $accion = $_POST["accion"] ?? "";
            $dni = $_POST["dni"] ?? "";
            $nombre = $_POST["nombre"] ?? "";
            $direccion = $_POST["direccion"] ?? "";
            $telefono = $_POST["telefono"] ?? "";
            $dniAntiguo = $_POST["dniAntiguo"] ?? "";
            $conexion = mysqli_connect("mysql-alexgargo78.alwaysdata.net", "432730_", "Lequio.78", "alexgargo78_banca-turing"); 

        
               

                // Insertar cliente //

              if ($accion == "agregar") {
            // Verificar si ya existe un cliente con ese DNI
                    $check = mysqli_query($conexion, "SELECT dni FROM cliente WHERE dni='$dni'");
                    if (mysqli_num_rows($check) > 0) {
                        echo "<div class='alert alert-warning'>⚠️ El cliente con DNI $dni ya existe.</div>";
                    } else {
                        $insercion = "INSERT INTO cliente VALUES ('$dni', '$nombre', '$direccion', '$telefono')";
                        if (mysqli_query($conexion, $insercion)) {
                            echo "<div class='alert alert-success'>✅ Cliente añadido correctamente.</div>";
                        } else {
                            echo "<div class='alert alert-danger'>❌ Error al añadir el cliente.</div>";
                        }
                    }
                }

                // Borrar cliente // 

                if ($accion == "eliminar") {
                    $dni = $_POST["dni"];
                    $borrado = "DELETE FROM cliente WHERE dni='$dni'";
                    mysqli_query($conexion, $borrado);
                }
                // Modificar cliente con DNI antiguo //

                if ($accion == "actualizar") {

                    // UPDATE CLIENTE set DNI='123', NOMBRE='Juan', DIRECCION='Calle Nueva 456', TELEFONO='600654321' WHERE DNI='1234';
                  
                $actualizacion = "UPDATE cliente SET dni='$dni', nombre='$nombre', direccion='$direccion', telefono='$telefono' WHERE dni='$dniAntiguo'";
                    mysqli_query($conexion, $actualizacion);
                }


            
            // Listado de clientes// 

            $consulta = mysqli_query($conexion, "SELECT dni, nombre, direccion, telefono FROM cliente ORDER BY nombre");

            ?>

            <table class="table table-striped">
                <tr>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th></th>
                    <th></th>
                </tr>
                <?php
                
                while ($registro = mysqli_fetch_array($consulta)) {
                if (($accion == "modificar") && ($dni == $registro['dni'])) {
                
                // Fila que queremos modificar
                ?>


                <tr class="fila-modificable">
                    <form action="#" method="post">
                        <td><input type="text" name="dni" value="<?= ($registro['dni']) ?>"></td>
                        <td><input type="text" name="nombre" value="<?= ($registro['nombre']) ?>"></td>
                        <td><input type="text" name="direccion" value="<?= ($registro['direccion']) ?>"></td>
                        <td><input type="text" name="telefono" value="<?= ($registro['telefono']) ?>"></td>

                        <td>

                            <input type="hidden" name="accion" value="actualizar">
                            <input type="hidden" name="dniAntiguo" value="<?= ($registro['dni']) ?>">
                            <button type="submit" class="btn btn-success">

                                <i class="bi bi-check-lg"></i>
                                Aceptar
                            </button>
                        </td>
                    </form>

                    <td>
                        <form action="#" method="post">
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-x-lg"></i>
                                Cancelar
                            </button>
                        </form>
                    </td>
                </tr>



                <?php
                } else {
                
               // fila normal //
                ?>





                <tr>
                    <td><?= ($registro['dni']) ?></td>
                    <td><?= ($registro['nombre']) ?></td>
                    <td><?= ($registro['direccion']) ?></td>
                    <td><?= ($registro['telefono']) ?></td>

                    <td>
                        <form action="#" method="post">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="dni" value="<?= ($registro['dni']) ?>">
                            <button type="submit" class="btn btn-danger"
                                <?= $accion == "modificar" ? "disabled" : "" ?>>
                                <i class="bi bi-trash"></i>
                                Borrar
                            </button>
                        </form>
                    </td>
                    <td>
                        <form action="#" method="post">
                            <input type="hidden" name="accion" value="modificar">
                            <input type="hidden" name="dni" value="<?= ($registro['dni']) ?>">
                            <button type="submit" class="btn btn-primary"
                                <?= $accion == "modificar" ? "disabled" : "" ?>>

                                <i class="bi bi-pencil"></i>
                                Modificar
                            </button>
                        </form>
                    </td>
                </tr>
                <?php
                } // fin else 
                } // fin while
                if ($accion !="modificar") {
                ?>
                

                <?php
                }
                ?>
            </table>
            <h3 class="mt-4">Añadir nuevo cliente</h3>
                <table class="table table-bordered">
                    <form action="" method="post">
                        <input type="hidden" name="accion" value="agregar">
                        <tr>
                            <th>DNI</th>
                            <td><input type="text" name="dni" required></td>
                        </tr>
                        <tr>
                            <th>Nombre</th>
                            <td><input type="text" name="nombre" required></td>
                        </tr>
                        <tr>
                            <th>Dirección</th>
                            <td><input type="text" name="direccion" required></td>
                        </tr>
                        <tr>
                            <th>Teléfono</th>
                            <td><input type="text" name="telefono" required></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-end">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-plus"></i> Añadir
                                </button>
                            </td>
                        </tr>
                    </form>
                </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>

</body>

</html>