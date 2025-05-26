<?php
include '../dt_base/Conexion_db.php';
$codigo = $_GET['codigo'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $unidas_med = $_POST['unidad_med'];
    $cant = $_POST['cant_inventario'];
    $proveedor = $_POST['proveedor'];
    $valor = $_POST['valor_total'];
    $fecha = $_POST['fecha_ingreso'];
    $estado = $_POST['estado'];
    $lote = $_POST['lote'];
    $responsable = $_POST['responsable_inv'];

    $sql = "UPDATE inv_mat SET nombre='$nombre', unidad_med='$unidas_med', cant_inventario='$cant', 
            proveedor='$proveedor', valor_total='$valor', fecha_ingreso='$fecha', estado='$estado', lote='$lote', 
            responsable_inv='$responsable' WHERE codigo='$codigo'";

    if ($conn->query($sql)) {
        header("Location: inv_materia.php");
    } else {
        echo "Error: " . $conn->error;
    }
}

$result = $conn->query("SELECT * FROM inv_mat WHERE codigo='$codigo'");
$data = $result->fetch_assoc();
$proveedores_result = $conn->query("SELECT nombre FROM proveedor ORDER BY nombre ASC");
$proveedores = [];
if ($proveedores_result && $proveedores_result->num_rows > 0) {
    while ($row = $proveedores_result->fetch_assoc()) {
        $proveedores[] = $row['nombre'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Materia Prima</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Cambiado para evitar que se oculte la parte superior */
            min-height: 100vh; /* Asegura que la altura sea 100% del viewport */
        }

        .form-container {
            background-color: white;
            padding: 40px 40px;
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            box-sizing: border-box;
            margin-top: 30px; /* Asegura que haya espacio por encima */
        }

        h2 {
            text-align: center;
            margin-bottom: 35px;
            color: #2c3e50;
            font-size: 30px;
            font-weight: 600;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #34495e;
            font-size: 16px;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        select {
            width: 100%;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 16px;
            background-color: #f9f9f9;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        input[type="date"]:focus,
        select:focus {
            border-color: #3498db;
            outline: none;
        }

        select {
            cursor: pointer;
        }

        /* Mejora en el cuadro del campo Lote */
        input[name="lote"] {
            width: 100%;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #3498db;
            border-radius: 10px;
            font-size: 16px;
            background-color: #f4f7fb;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        input[name="lote"]:focus {
            border-color: #2980b9;
            background-color: #eaf2fa;
        }

        /* Mejora en el cuadro de Nombre, Unidades de Medida y Responsable */
        input[name="nombre"],
        input[name="unidad_med"],
        input[name="responsable_inv"] {
            width: 100%;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 16px;
            background-color: #f9f9f9;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        input[name="nombre"]:focus,
        input[name="unidad_med"]:focus,
        input[name="responsable_inv"]:focus {
            border-color: #3498db;
            background-color: #eaf2fa;
            outline: none;
        }

        button[type="submit"] {
            width: 100%;
            padding: 14px;
            border: none;
            background-color: #3498db;
            color: white;
            font-size: 16px;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-weight: bold;
        }

        button[type="submit"]:hover {
            background-color: #2980b9;
        }

        .back-link {
            text-align: center;
            margin-top: 25px;
        }

        .back-link a {
            color: #3498db;
            text-decoration: none;
            font-size: 16px;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
            }

            h2 {
                font-size: 24px;
            }

            input[type="text"],
            input[type="number"],
            input[type="date"],
            select,
            button {
                font-size: 14px;
                padding: 12px;
            }
        }

    </style>
</head>
<body>
    <div class="form-container">
        <h2>Editar Materia Prima</h2>
        <form method="post">

            <label>Nombre</label>
            <input name="nombre" value="<?php echo $data['nombre']; ?>" required>

            <label>Unidades de Medida</label>
            <input name="unidad_med" value="<?php echo $data['unidad_med']; ?>" required>

            <label>Cantidad en Inventario</label>
            <input name="cant_inventario" type="number" value="<?php echo $data['cant_inventario']; ?>" required>

            <label>Proveedor</label>
            <select name="proveedor" required>
                <option value="">Seleccione un proveedor</option>
                <?php foreach ($proveedores as $prov): ?>
                    <option value="<?php echo htmlspecialchars($prov); ?>" <?php echo ($prov == $data['proveedor']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($prov); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Valor Total</label>
            <input name="valor_total" type="number" value="<?php echo $data['valor_total']; ?>" required>

            <label>Fecha de Ingreso</label>
            <input name="fecha_ingreso" type="date" value="<?php echo $data['fecha_ingreso']; ?>" required>

            <label>Estado</label>
            <select name="estado" required>
                <option value="de buena calidad" <?php echo ($data['estado'] == 'de buena calidad') ? 'selected' : ''; ?>>De buena calidad</option>
                <option value="de mala calidad" <?php echo ($data['estado'] == 'de mala calidad') ? 'selected' : ''; ?>>De mala calidad</option>
                <option value="calidad media" <?php echo ($data['estado'] == 'calidad media') ? 'selected' : ''; ?>>Calidad media</option>
            </select>

            <label>Lote</label>
            <input name="lote" value="<?php echo $data['lote']; ?>" required>

            <label>Responsable</label>
            <input name="responsable_inv" value="<?php echo $data['responsable_inv']; ?>" required>

            <button type="submit">Actualizar</button>
        </form>

        <div class="back-link">
            <a href="./inv_materia.php">← Volver al listado</a>
        </div>
    </div>
</body>
</html>
