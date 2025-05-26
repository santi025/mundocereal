<!-- agregar_mp.php -->
<?php
include '../dt_base/Conexion_db.php';

$proveedores = [];
$sql_prov = "SELECT nombre FROM proveedor ORDER BY nombre ASC";
$result_prov = $conn->query($sql_prov);
if ($result_prov && $result_prov->num_rows > 0) {
    while ($row = $result_prov->fetch_assoc()) {
        $proveedores[] = $row['nombre'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $unidad_med = $_POST['unidad_med'];
    $cant = $_POST['cant_inventario'];
    $proveedor = $_POST['proveedor'];
    $valor = $_POST['valor_total'];
    $fecha = $_POST['fecha_ingreso'];
    $estado = $_POST['estado'];
    $lote = $_POST['lote'];
    $responsable = $_POST['responsable_inv'];

    $sql = "INSERT INTO inv_mat(codigo, nombre, unidad_med, cant_inventario, proveedor, valor_total, fecha_ingreso, estado, lote, responsable_inv) 
            VALUES ('$codigo', '$nombre', '$unidad_med', '$cant', '$proveedor', '$valor', '$fecha', '$estado', '$lote', '$responsable')";

    if ($conn->query($sql)) {
        header("Location: inv_materia.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar a inventario de materia prima</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;  /* Cambio para que el formulario quede centrado pero con espacio arriba */
            min-height: 100vh;
            box-sizing: border-box;
        }

        .form-container {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 40px 30px;
            max-width: 600px;
            width: 100%;
            height: auto;
            box-sizing: border-box;
            overflow-y: auto;
            margin-top: 30px;  /* Aseguramos que haya un margen superior para el formulario */
        }

        h1 {
            text-align: center;
            color: #333;
            font-size: 30px;
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-top: 20px;
            color: #555;
            font-size: 16px;
        }

        input, select {
            width: 100%;
            padding: 18px;
            margin-top: 8px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 18px;
            box-sizing: border-box;
            background-color: #f8f9fa;
        }

        button[type="submit"] {
            width: 100%;
            padding: 18px;
            margin-top: 20px;
            background-color: #28a745;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 18px;
        }

        button[type="submit"]:hover {
            background-color: #218838;
        }

        .agregar-btn {
            display: block;
            margin-top: 20px;
            text-align: center;
            background-color: #17a2b8;
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
            font-size: 18px;
        }

        .agregar-btn:hover {
            background-color: #138496;
        }

        .volver-btn {
            display: inline-block;
            margin-top: 20px;
            background-color: #6c757d;
            color: white;
            padding: 15px;
            text-align: center;
            width: 100%;
            border-radius: 8px;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s ease;
            font-size: 18px;
        }

        .volver-btn:hover {
            background-color: #5a6268;
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 20px 15px;
            }

            input, select, button {
                font-size: 16px;
                padding: 14px;
            }

            h1 {
                font-size: 26px;
            }
        }

        @media (max-width: 480px) {
            .form-container {
                padding: 15px;
            }

            input, select, button {
                font-size: 14px;
                padding: 12px;
            }

            h1 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Agregar a Inventario de Materia Prima</h1>
        <form method="post">
            <label>Código</label>
            <input name="codigo" required placeholder="Código">

            <label>Nombre</label>
            <input name="nombre" required placeholder="Nombre">

            <label>Unidad de Medida</label>
            <input name="unidad_med" required placeholder="Unidad de Medida">

            <label>Cantidad en Inventario</label>
            <input name="cant_inventario" required type="number" placeholder="Cantidad">

            <label>Proveedor</label>
            <select name="proveedor" id="proveedorSelect" required>
                <option value="">Seleccione un proveedor</option>
                <?php foreach ($proveedores as $prov): ?>
                    <option value="<?php echo htmlspecialchars($prov); ?>">
                        <?php echo htmlspecialchars($prov); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <a href="../../proveedor/proveedor.php" class="agregar-btn">➕ Agregar nuevo proveedor</a>

            <label>Valor Total</label>
            <input name="valor_total" required type="number" placeholder="Valor Total">

            <label>Fecha de Ingreso</label>
            <input name="fecha_ingreso" required type="date">

            <label>Estado</label>
            <select name="estado" required>
                <option value="">Seleccione calidad</option>
                <option value="Buena calidad">Buena calidad</option>
                <option value="Calidad media">Calidad media</option>
                <option value="Mala calidad">Mala calidad</option>
            </select>

            <label>Lote</label>
            <input name="lote" required placeholder="Lote">

            <label>Responsable de Inventario</label>
            <input name="responsable_inv" required placeholder="Responsable">

            <button type="submit">Guardar</button>
        </form>
    </div>
</body>
</html>
