<?php 
include '../dt_base/Conexion_db.php'; // Conexión a la base de datos

// Recuperar el ID del pedido desde la URL
$id_pedido = isset($_GET['id_pedido']) ? (int)$_GET['id_pedido'] : 0;

if ($id_pedido == 0) {
    echo "❌ ID de pedido no válido.";
    exit;
}

// Recuperar los detalles del pedido, usando el precio actual del producto
$sql_detalle = "SELECT pd.id_producto, p.nombre, pd.cantidad, p.precio_unitario
                FROM Pedido_Detalle pd
                JOIN Producto p ON pd.id_producto = p.id_Producto
                WHERE pd.id_pedido = $id_pedido";
$detalle_result = $conn->query($sql_detalle);

// Recuperar información del pedido
$sql_pedido = "SELECT p.fecha_pedido, c.razon_social 
               FROM Pedido p 
               JOIN Cliente c ON p.id_cliente = c.nit 
               WHERE p.id_pedido = $id_pedido";
$pedido_result = $conn->query($sql_pedido);
$pedido = $pedido_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Pedido</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f7f7;
            padding: 20px;
            color: #4e4b46; /* Color café oscuro */
        }
        .container {
            max-width: 1000px;
            margin: auto;
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1, h2 {
            color: #6f4f23; /* Tono café */
        }
        .pedido-info {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #8b5e3c; /* Tono café claro */
            color: white;
        }
        td {
            background-color: #faf3e0; /* Tono crema claro */
        }
        .total-final {
            text-align: right;
            font-weight: bold;
            font-size: 18px;
            margin-top: 10px;
            color: #6f4f23; /* Tono café */
        }
        .btn {
            background-color: #a66e4d; /* Tono café rojizo */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
        }
        .btn:hover {
            background-color: #8b5e3c; /* Tono café claro */
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Detalles del Pedido #<?= $id_pedido ?></h1>

    <div class="pedido-info">
        <p><strong>Fecha del Pedido:</strong> <?= $pedido['fecha_pedido'] ?></p>
        <p><strong>Cliente:</strong> <?= $pedido['razon_social'] ?></p>
    </div>

    <h2>Productos en el Pedido</h2>

    <?php 
    $total_general = 0;
    if ($detalle_result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID Producto</th>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario ($)</th>
                    <th>Total Producto ($)</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($detalle = $detalle_result->fetch_assoc()): 
                    $total_producto = $detalle['cantidad'] * $detalle['precio_unitario'];
                    $total_general += $total_producto;
                ?>
                    <tr>
                        <td><?= $detalle['id_producto'] ?></td>
                        <td><?= htmlspecialchars($detalle['nombre']) ?></td>
                        <td><?= $detalle['cantidad'] ?></td>
                        <td>$<?= number_format($detalle['precio_unitario'], 2, ',', '.') ?></td>
                        <td>$<?= number_format($total_producto, 2, ',', '.') ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="total-final">
            Total del Pedido: $<?= number_format($total_general, 2, ',', '.') ?>
        </div>
    <?php else: ?>
        <p>No se encontraron detalles para este pedido.</p>
    <?php endif; ?>

    <a href="lista_pedidos.php" class="btn">Volver a Lista de Pedidos</a>
</div>

</body>
</html>

<?php
$conn->close();
?>
