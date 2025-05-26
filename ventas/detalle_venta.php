<?php 
include '../dt_base/Conexion_db.php';

$venta_id = isset($_GET['venta_id']) ? intval($_GET['venta_id']) : 0;

// Obtener información general de la venta
$sql_venta = "SELECT v.fecha, v.comprobante_tipo, v.comprobante_numero, v.cliente_nombre, v.cliente_documento, v.total
              FROM ventas v
              WHERE v.id = $venta_id";
$result_venta = $conn->query($sql_venta);

if ($result_venta->num_rows === 0) {
    die("Venta no encontrada.");
}
$venta = $result_venta->fetch_assoc();

// Obtener detalles de la venta
$sql_detalle = "SELECT d.id, p.id_producto AS producto_id, p.nombre AS producto, d.cantidad, d.precio_unitario, d.subtotal
                FROM detalle_venta d
                JOIN producto p ON d.producto = p.id_producto
                WHERE d.venta_id = $venta_id";
$result_detalle = $conn->query($sql_detalle);
$total_venta = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Venta</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
            color: #6d4c41;
        }

        .info {
            text-align: center;
            margin: 20px 0;
        }

        .info p {
            font-size: 1.1rem;
            color: #6d4c41;
        }

        table {
            border-collapse: collapse;
            width: 90%;
            margin: auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 1rem;
            color: #6d4c41;
        }

        th {
            background-color: #8d6e63;
            color: white;
        }

        .total-row {
            font-weight: bold;
            background-color: #f0f0f0;
        }

        .botones {
            text-align: center;
            margin: 30px 0;
        }

        .botones button, .botones a {
            padding: 12px 25px;
            margin: 5px;
            font-size: 16px;
            text-decoration: none;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .botones button {
            background-color: #6d4c41;
            color: white;
        }

        .botones button:hover {
            background-color: #5f4033;
        }

        .botones a {
            background-color: #8d6e63;
            color: white;
            display: inline-block;
        }

        .botones a:hover {
            background-color: #6f4b3a;
        }
    </style>
</head>
<body>

<div id="reporte-pdf">
    <h2>Detalle de la Venta #<?= $venta_id ?></h2>

    <div class="info">
        <p><strong>Fecha:</strong> <?= $venta['fecha'] ?></p>
        <p><strong>Tipo de Comprobante:</strong> <?= $venta['comprobante_tipo'] ?></p>
        <p><strong>Número:</strong> <?= $venta['comprobante_numero'] ?></p>
        <p><strong>Cliente:</strong> <?= $venta['cliente_nombre'] ?></p>
        <p><strong>Documento:</strong> <?= $venta['cliente_documento'] ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID Producto</th>
                <th>Nombre Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result_detalle->num_rows > 0): ?>
                <?php while($row = $result_detalle->fetch_assoc()):
                    $total_venta += $row['subtotal'];
                ?>
                <tr>
                    <td><?= $row['producto_id'] ?></td>
                    <td><?= htmlspecialchars($row['producto']) ?></td>
                    <td><?= $row['cantidad'] ?></td>
                    <td><?= number_format($row['precio_unitario'], 2) ?></td>
                    <td><?= number_format($row['subtotal'], 2) ?></td>
                </tr>
                <?php endwhile; ?>
                <tr class="total-row">
                    <td colspan="4">Total de la venta:</td>
                    <td><?= number_format($total_venta, 2) ?></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="5">No hay productos en esta venta.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="botones">
    <button onclick="exportToPDF()">Descargar PDF</button>
    <a href="ventas.php">Volver al listado</a>
</div>

<!-- html2pdf.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function exportToPDF() {
    const element = document.getElementById('reporte-pdf');
    const opt = {
        margin: 0.5,
        filename: 'reporte_venta_<?= $venta_id ?>.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
    };
    html2pdf().set(opt).from(element).save();
}
</script>

</body>
</html>

<?php $conn->close(); ?>
