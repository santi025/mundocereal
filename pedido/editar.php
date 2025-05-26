<?php
include '../dt_base/Conexion_db.php';

if (isset($_GET['id_pedido'])) {
    $id_pedido = $_GET['id_pedido'];

    $query_pedido = $conn->query("SELECT * FROM Pedido WHERE id_pedido = '$id_pedido'");
    $pedido = $query_pedido->fetch_assoc();

    $query_detalles = $conn->query("SELECT pd.id_producto, pd.cantidad, p.nombre AS producto_nombre 
                                    FROM pedido_detalle pd 
                                    JOIN producto p ON pd.id_producto = p.id_producto
                                    WHERE pd.id_pedido = '$id_pedido'");
    $productos_detalles = [];
    while ($detalle = $query_detalles->fetch_assoc()) {
        $productos_detalles[] = $detalle;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pedido = $_POST['id_pedido'];
    $fecha_pedido = $_POST['fecha_pedido'];

    $conn->query("UPDATE Pedido SET fecha_pedido='$fecha_pedido' WHERE id_pedido='$id_pedido'");

    foreach ($_POST['cantidad'] as $id_producto => $cantidad) {
        $conn->query("UPDATE pedido_detalle SET cantidad='$cantidad' WHERE id_pedido='$id_pedido' AND id_producto='$id_producto'");
    }

    if (!empty($_POST['id_producto_nuevo']) && !empty($_POST['cantidad_nueva'])) {
        foreach ($_POST['id_producto_nuevo'] as $index => $id_producto_nuevo) {
            $cantidad_nueva = $_POST['cantidad_nueva'][$index];
            if ($id_producto_nuevo && $cantidad_nueva > 0) {
                $conn->query("INSERT INTO pedido_detalle (id_pedido, id_producto, cantidad) 
                              VALUES ('$id_pedido', '$id_producto_nuevo', '$cantidad_nueva')");
            }
        }
    }

    header("Location: lista_pedidos.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Pedido</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f2ea;
            padding: 40px;
            color: #4b2e2e;
        }

        h2, h3 {
            text-align: center;
            color: #6e4b3c;
            margin-bottom: 20px;
        }

        .form-container {
            background-color: #fffaf3;
            width: 600px;
            margin: auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: 600;
            color: #5a3e36;
            font-size: 1rem;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #cdb89e;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 1rem;
            background-color: #fdfaf7;
            color: #4b2e2e;
        }

        button,
        .btn-link {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }

        button {
            background-color: #a0815c;
            color: white;
        }

        button:hover {
            background-color: #8c6d48;
        }

        .btn-link {
            background-color: #7a5d44;
            color: white;
        }

        .btn-link:hover {
            background-color: #6c4e39;
        }

        .btn-add {
            background-color: #957b5d;
        }

        .btn-add:hover {
            background-color: #7b6349;
        }

        .product-row {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Editar Pedido</h2>
    <form method="POST">
        <input type="hidden" name="id_pedido" value="<?= htmlspecialchars($pedido['id_pedido']) ?>">

        <label for="fecha_pedido">Fecha del Pedido:</label>
        <input type="date" name="fecha_pedido" id="fecha_pedido" value="<?= htmlspecialchars($pedido['fecha_pedido']) ?>" required>

        <h3>Productos en el Pedido</h3>
        <?php foreach ($productos_detalles as $detalle): ?>
            <div class="product-row">
                <label for="cantidad_<?= $detalle['id_producto'] ?>">Producto: <?= htmlspecialchars($detalle['producto_nombre']) ?></label>
                <input type="number" name="cantidad[<?= $detalle['id_producto'] ?>]" id="cantidad_<?= $detalle['id_producto'] ?>"
                       value="<?= htmlspecialchars($detalle['cantidad']) ?>" required min="1">
            </div>
        <?php endforeach; ?>

        <h3>Agregar Nuevo Producto</h3>
        <div id="productos-extra"></div>

        <button type="button" class="btn-add" onclick="agregarProducto()">➕ Agregar Producto</button>

        <button type="submit">Guardar Cambios</button>

        <!-- Botón volver ubicado después de Guardar Cambios -->
        <a class="btn-link" href="lista_pedidos.php">← Volver a Lista de Pedidos</a>
    </form>
</div>

<script>
function agregarProducto() {
    const productosExtra = document.getElementById('productos-extra');
    const nuevaFila = document.createElement('div');
    nuevaFila.classList.add('product-row');

    nuevaFila.innerHTML = `
        <label>Producto nuevo:</label>
        <select name="id_producto_nuevo[]">
            <option value="">-- Selecciona --</option>
            <?php
            $productos_nuevos = $conn->query("SELECT * FROM Producto");
            while ($producto = $productos_nuevos->fetch_assoc()) {
                echo "<option value='" . $producto['id_Producto'] . "'>" . htmlspecialchars($producto['nombre']) . " - $" . $producto['precio_unitario'] . "</option>";
            }
            ?>
        </select>
        <input type="number" name="cantidad_nueva[]" min="1" placeholder="Cantidad" required>
    `;

    productosExtra.appendChild(nuevaFila);
}
</script>

</body>
</html>
