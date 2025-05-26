<?php  
include '../dt_base/Conexion_db.php';

// Obtener las fechas desde los parámetros GET
$fechaInicio = $_GET['fecha_inicio'] ?? '';
$fechaFin = $_GET['fecha_fin'] ?? '';

$ventas = [];
$totalGeneral = 0;
$resumenProductos = [];
$totalPrecioGeneral = 0; // Variable para el total del precio

if ($fechaInicio && $fechaFin) {
    // Validar que las fechas estén en el formato correcto (YYYY-MM-DD)
    if (DateTime::createFromFormat('Y-m-d', $fechaInicio) && DateTime::createFromFormat('Y-m-d', $fechaFin)) {
        // Mostrar las fechas para depuración
        echo "Fecha inicio: " . htmlspecialchars($fechaInicio) . "<br>";
        echo "Fecha fin: " . htmlspecialchars($fechaFin) . "<br>";

        // Consulta SQL para obtener las ventas entre las fechas
        $sql = "SELECT v.fecha, p.nombre AS producto, dv.precio_unitario, SUM(dv.cantidad) AS cantidad
                FROM ventas v
                JOIN detalle_venta dv ON v.id = dv.venta_id
                JOIN producto p ON dv.producto = p.id_producto
                WHERE v.fecha BETWEEN ? AND ?
                GROUP BY v.fecha, p.nombre, dv.precio_unitario
                ORDER BY v.fecha DESC";

        // Preparar la consulta
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $fechaInicio, $fechaFin);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si hay resultados
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Calcular el precio total
                $row['precio_total'] = $row['cantidad'] * $row['precio_unitario'];

                // Guardamos cada venta en el array $ventas
                $ventas[] = $row;

                // Sumar la cantidad total vendida
                $totalGeneral += $row['cantidad'];

                // Sumar el precio total general
                $totalPrecioGeneral += $row['precio_total'];

                // Resumen por producto
                $producto = $row['producto'];
                $resumenProductos[$producto]['cantidad'] = ($resumenProductos[$producto]['cantidad'] ?? 0) + $row['cantidad'];
                $resumenProductos[$producto]['precio_total'] = ($resumenProductos[$producto]['precio_total'] ?? 0) + $row['precio_total'];
            }
        } else {
            echo "<p>No se encontraron ventas para las fechas seleccionadas.</p>";
        }
    } else {
        echo "<p>Las fechas seleccionadas no son válidas. Por favor, ingrese un formato correcto (YYYY-MM-DD).</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Poppins', sans-serif;
            background-color: #f7e8d3;
            color: #4b2e2e;
            padding: 30px;
            margin: 0;
        }

        h2 {
            text-align: center;
            font-size: 2em;
            color: #6e4c3c;
        }

        form {
            text-align: center;
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        input[type="date"] {
            padding: 8px;
            margin: 0 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            padding: 8px 16px;
            background-color: #8d6748;
            border: none;
            color: white;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #755133;
        }

        .export-buttons {
            text-align: center;
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            margin: 20px 0;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        thead {
            background-color: #8d6748;
            color: white;
        }

        p {
            text-align: right;
            font-weight: bold;
        }

        canvas {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .top-right-button {
            position: absolute;
            top: 20px;
            right: 30px;
        }

        .btn-link {
            font-size: 18px;
            color: #8d6748;
            text-decoration: none;
        }

        .btn-link:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>

<div class="top-right-button">
    <a class="btn-link" href="../menus/Menu_Admin.php">🏠 Volver al Inicio</a>
</div>

<h2>📊 Reporte de Ventas</h2>

<form method="GET">
    Desde: <input type="date" name="fecha_inicio" value="<?= htmlspecialchars($fechaInicio) ?>" required>
    Hasta: <input type="date" name="fecha_fin" value="<?= htmlspecialchars($fechaFin) ?>" required>
    <button type="submit">Filtrar</button>
</form>

<?php if ($fechaInicio && $fechaFin): ?>
    <?php if (!empty($ventas)): ?>
        <div class="export-buttons">
            <button onclick="exportTableToExcel('tabla-reporte', 'reporte_ventas')">📥 Exportar a Excel</button>
            <button onclick="exportToPDF()">📄 Exportar a PDF</button>
        </div>

        <div id="reporte-pdf">
            <h1 style="text-align:center;">Mundo Cereal</h1>
            <p style="text-align:center;">Reporte de Ventas desde <?= htmlspecialchars($fechaInicio) ?> hasta <?= htmlspecialchars($fechaFin) ?></p>
            
            <table id="tabla-reporte">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Precio Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ventas as $venta): ?>
                        <tr>
                            <td><?= $venta['fecha'] ?></td>
                            <td><?= $venta['producto'] ?></td>
                            <td><?= number_format($venta['cantidad'], 0) ?></td>
                            <td><?= "$" . number_format($venta['precio_unitario'], 2, ',', '.') ?> COP</td>
                            <td><?= "$" . number_format($venta['precio_total'], 2, ',', '.') ?> COP</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <p>Total General: <?= number_format($totalGeneral, 0) ?> Unidades</p>
            <p>Total Precio General: <?= "$" . number_format($totalPrecioGeneral, 2, ',', '.') ?> COP</p>
        </div>

        <h3 style="margin-top: 40px;">Resumen por Producto</h3>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resumenProductos as $producto => $data): ?>
                    <tr>
                        <td><?= $producto ?></td>
                        <td><?= number_format($data['cantidad'], 0) ?></td>
                        <td><?= "$" . number_format($data['precio_total'], 2, ',', '.') ?> COP</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3 style="text-align:center;">📈 Gráfico de Ventas por Producto</h3>
        <canvas id="ventasChart" height="100"></canvas>

        <script>
            const ctx = document.getElementById('ventasChart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode(array_keys($resumenProductos)) ?>,
                    datasets: [{
                        label: 'Precio Total',
                        data: <?= json_encode(array_column($resumenProductos, 'precio_total')) ?>,
                        backgroundColor: '#28a745',
                        borderColor: '#1e7e34',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        title: {
                            display: true,
                            text: 'Ventas por Producto (Precio Total)',
                            font: { size: 18 }
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, title: { display: true, text: 'Precio Total (COP)' }},
                        x: { title: { display: true, text: 'Producto' }}
                    }
                }
            });
        </script>
    <?php else: ?>
        <p>No se encontraron resultados para las fechas seleccionadas.</p>
    <?php endif; ?>
<?php endif; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function exportTableToExcel(tableID, filename = '') {
    const dataType = 'application/vnd.ms-excel';
    const tableSelect = document.getElementById(tableID);
    const tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    filename = filename ? filename + '.xls' : 'excel_data.xls';

    // Agregar título "Mundo Cereal" y las fechas en la primera fila
    const header = `<table><tr><td colspan="5" style="text-align:center; font-size:18px; font-weight:bold;">Mundo Cereal</td></tr><tr><td colspan="5" style="text-align:center;">Reporte de Ventas desde <?= htmlspecialchars($fechaInicio) ?> hasta <?= htmlspecialchars($fechaFin) ?></td></tr></table>`;
    const fullTableHTML = header + tableHTML;

    const downloadLink = document.createElement("a");
    document.body.appendChild(downloadLink);
    if (navigator.msSaveOrOpenBlob) {
        const blob = new Blob(['\ufeff', fullTableHTML], { type: dataType });
        navigator.msSaveOrOpenBlob(blob, filename);
    } else {
        downloadLink.href = 'data:' + dataType + ', ' + fullTableHTML;
        downloadLink.download = filename;
        downloadLink.click();
    }
}

function exportToPDF() {
    const element = document.getElementById('reporte-pdf');
    const opt = {
        margin: 0.5,
        filename: 'reporte_ventas.pdf',
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
