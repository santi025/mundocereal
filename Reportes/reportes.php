<?php
require_once('../dt_base/Conexion_db.php');

$tipo = $_POST['tipo'] ?? '';
$datos = [];
$titulos = [];
$columnas = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $tipo !== '') {
    $consultas = [
        "producto" => [
            "sql" => "SELECT id_Producto, codigo, nombre, gramaje, precio_unitario, lote FROM producto",
            "titulos" => ["ID", "Código", "Nombre", "Gramaje", "Precio", "Lote"]
        ],
        "inventario" => [
            // 🔄 CORREGIDO: JOIN con tabla producto
            "sql" => "SELECT i.id_Inventario, p.nombre AS producto, p.gramaje, i.cantidad, i.precio_unitario, i.precio_total
                      FROM inventario i
                      JOIN producto p ON i.id_producto = p.id_Producto",
            "titulos" => ["ID", "Producto", "Gramaje", "Cantidad", "P. Unitario", "P. Total"]
        ],
        "cliente" => [
            "sql" => "SELECT nit, razon_social, correo, direccion FROM cliente",
            "titulos" => ["NIT", "Razón Social", "Correo", "Dirección"]
        ],
        "inv_mat" => [
            "sql" => "SELECT codigo, nombre, unidad_med, cant_inventario, proveedor, valor_total, fecha_ingreso, estado, lote, responsable_inv FROM inv_mat",
            "titulos" => ["Código", "Nombre", "Unidad", "Cantidad", "Proveedor", "Valor Total", "Fecha", "Estado", "Lote", "Responsable"]
        ],
        "proveedor" => [
            "sql" => "SELECT id_proveedor, nombre, entidad, nit, telefono, direccion FROM proveedor",
            "titulos" => ["ID", "Nombre", "Entidad", "NIT", "Teléfono", "Dirección"]
        ],
    ];

    if (isset($consultas[$tipo])) {
        $consulta = $consultas[$tipo];
        $resultado = $conn->query($consulta["sql"]);
        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $datos[] = $fila;
            }
            $titulos = $consulta["titulos"];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de <?= htmlspecialchars($tipo) ?></title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f3f3; padding: 20px; }
        h2 { text-align: center; }
        form { margin-bottom: 20px; text-align: center; }
        table { width: 100%; border-collapse: collapse; background: white; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background: #8d6748; color: white; }
        button { padding: 10px 20px; background: #8d6748; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #755133; }
        .export-btn { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>

<h2>📄 Generador de Reportes</h2>

<form method="POST">
    <label for="tipo">Selecciona una categoría:</label>
    <select name="tipo" required>
        <option value="">-- Seleccionar --</option>
        <option value="producto" <?= $tipo == "producto" ? "selected" : "" ?>>Productos</option>
        <option value="inventario" <?= $tipo == "inventario" ? "selected" : "" ?>>Inventario</option>
        <option value="cliente" <?= $tipo == "cliente" ? "selected" : "" ?>>Clientes</option>
        <option value="inv_mat" <?= $tipo == "inv_mat" ? "selected" : "" ?>>Materia Prima</option>
        <option value="proveedor" <?= $tipo == "proveedor" ? "selected" : "" ?>>Proveedores</option>
    </select>
    <button type="submit">📋 Ver Reporte</button>
</form>

<?php if (!empty($datos)): ?>
    <div id="reporte">
        <h2>Reporte de <?= ucfirst($tipo) ?></h2>
        <table>
            <thead>
                <tr>
                    <?php foreach ($titulos as $col): ?>
                        <th><?= htmlspecialchars($col) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($datos as $fila): ?>
                    <tr>
                        <?php foreach ($fila as $valor): ?>
                            <td><?= htmlspecialchars($valor) ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="export-btn">
        <button onclick="exportarPDF()">📥 Descargar PDF</button>
    </div>

    <script>
        function exportarPDF() {
            const element = document.getElementById('reporte');
            const opt = {
                margin:       0.5,
                filename:     'reporte_<?= $tipo ?>.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>
<?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <p style="text-align: center; color: red;">❌ No se encontraron datos para esta categoría.</p>
<?php endif; ?>

</body>
</html>
