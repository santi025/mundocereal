<?php
include '../dt_base/Conexion_db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['guardar'])) {
    // Obtener los datos del formulario
    $detalle_id = intval($_POST['detalle_id']);
    $venta_id = intval($_POST['venta_id']);
    $cantidad = intval($_POST['cantidad']);
    $cliente_nombre = $_POST['cliente_nombre'];
    $cliente_documento = $_POST['cliente_documento'];

    // Verificar que la cantidad sea válida
    if ($cantidad <= 0) {
        $error_message = "La cantidad debe ser mayor a 0.";
    } else {
        // Obtener el precio unitario del producto
        $sql = "SELECT precio_unitario FROM detalle_venta WHERE id = ? AND venta_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $detalle_id, $venta_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $producto = $result->fetch_assoc();
            $precio_unitario = $producto['precio_unitario'];

            // Calcular el nuevo subtotal
            $subtotal = $cantidad * $precio_unitario;

            // Actualizar en la base de datos el detalle de la venta
            $update_detalle_sql = "UPDATE detalle_venta 
                                   SET cantidad = ?, subtotal = ?
                                   WHERE id = ? AND venta_id = ?";
            $stmt = $conn->prepare($update_detalle_sql);
            $stmt->bind_param('diii', $cantidad, $subtotal, $detalle_id, $venta_id);
            $stmt->execute();

            // Actualizar los datos del cliente
            $update_cliente_sql = "UPDATE ventas 
                                   SET cliente_nombre = ?, cliente_documento = ?
                                   WHERE id = ?";
            $stmt = $conn->prepare($update_cliente_sql);
            $stmt->bind_param('ssi', $cliente_nombre, $cliente_documento, $venta_id);

            if ($stmt->execute()) {
                $success_message = "Los cambios se guardaron correctamente.";
            } else {
                $error_message = "Hubo un error al guardar los cambios en el cliente.";
            }
        } else {
            $error_message = "Producto no encontrado en la base de datos.";
        }
    }

    // Redirigir de vuelta a la página de detalles con el mensaje de éxito
    if (isset($success_message)) {
        header("Location: detalle_venta.php?venta_id=" . $venta_id . "&mensaje=" . urlencode($success_message));
        exit();
    }
}

// Obtener los detalles del producto a editar
$detalle_id = isset($_GET['detalle_id']) ? intval($_GET['detalle_id']) : 0;
$venta_id = isset($_GET['venta_id']) ? intval($_GET['venta_id']) : 0;

$sql = "SELECT id, producto, cantidad, precio_unitario, subtotal 
        FROM detalle_venta 
        WHERE id = ? AND venta_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $detalle_id, $venta_id);
$stmt->execute();
$result = $stmt->get_result();

// Comprobar si la consulta devolvió datos
if ($result && $result->num_rows > 0) {
    $producto = $result->fetch_assoc();
} else {
    $error_message = "El detalle de venta no fue encontrado.";
}

// Obtener los datos del cliente
$sql_cliente = "SELECT cliente_nombre, cliente_documento FROM ventas WHERE id = ?";
$stmt = $conn->prepare($sql_cliente);
$stmt->bind_param('i', $venta_id);
$stmt->execute();
$result_cliente = $stmt->get_result();

// Comprobar si la consulta devolvió datos del cliente
if ($result_cliente && $result_cliente->num_rows > 0) {
    $cliente = $result_cliente->fetch_assoc();
} else {
    $error_message = "Los datos del cliente no fueron encontrados.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Detalle de Venta</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        input[type="number"], input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        p {
            text-align: center;
        }

        a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: red;
            text-align: center;
        }

        .success-message {
            color: green;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Editar Cantidad y Cliente de la Venta</h2>

    <!-- Mostrar mensajes de error o éxito -->
    <?php if (isset($error_message)): ?>
        <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
    <?php endif; ?>

    <?php if (isset($success_message)): ?>
        <p class="success-message"><?= htmlspecialchars($success_message) ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="detalle_id" value="<?= isset($producto['id']) ? $producto['id'] : '' ?>">
        <input type="hidden" name="venta_id" value="<?= isset($venta_id) ? $venta_id : '' ?>">

        <label for="producto">Producto</label>
        <input type="text" id="producto" name="producto" value="<?= isset($producto['producto']) ? $producto['
::contentReference[oaicite:0]{index=0}
 
