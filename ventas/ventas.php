<?php
include '../dt_base/Conexion_db.php';

$sql = "SELECT id, fecha, comprobante_tipo, comprobante_numero, cliente_nombre, cliente_documento, total 
        FROM ventas 
        ORDER BY fecha DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Ventas</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3eee9;
            margin: 0;
            padding: 0;
            color: #4e342e;
        }

        h2 {
            text-align: center;
            margin-top: 30px;
            font-size: 32px;
            color: #5d4037;
        }

        table {
            width: 90%;
            margin: 30px auto;
            border-collapse: collapse;
            background-color: #fffaf5;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        th, td {
            border: 1px solid #d7ccc8;
            padding: 12px 15px;
            text-align: center;
        }

        th {
            background-color: #8d6e63;
            color: white;
        }

        td {
            background-color: #f9f4ef;
        }

        tr:nth-child(even) td {
            background-color: #f1eae4;
        }

        td a {
            color: #6d4c41;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }

        td a:hover {
            color: #4e342e;
        }

        .no-ventas {
            text-align: center;
            font-size: 18px;
            color: #a1887f;
        }

        .container {
            width: 90%;
            margin: 20px auto;
        }

        .btn-volver {
            display: inline-block;
            padding: 12px 25px;
            background-color: #6d4c41;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s;
            margin: 30px auto 20px;
        }

        .btn-volver:hover {
            background-color: #4e342e;
        }

        .btn-action {
            display: inline-block;
            padding: 8px 15px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn-edit {
            background-color: #a1887f;
        }

        .btn-edit:hover {
            background-color: #8d6e63;
        }

        .btn-delete {
            background-color: #d84315;
        }

        .btn-delete:hover {
            background-color: #bf360c;
        }

        .btn-nueva {
            background-color: #5d4037;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn-nueva:hover {
            background-color: #3e2723;
        }

        .acciones {
            display: flex;
            justify-content: center;
            gap: 8px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Ventas Registradas</h2>

    <!-- Botón para registrar nueva venta -->
    <div style="text-align: center; margin-top: 20px;">
        <a href="botones.php" class="btn-nueva"> Registrar Nueva Venta</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>N° Comprobante</th>
                <th>Cliente</th>
                <th>Documento</th>
                <th>Total</th>
                <th>Detalles</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($row['fecha'])) ?></td>
                        <td><?= $row['comprobante_tipo'] ?></td>
                        <td><?= $row['comprobante_numero'] ?></td>
                        <td><?= $row['cliente_nombre'] ?></td>
                        <td><?= $row['cliente_documento'] ?></td>
                        <td><?= number_format($row['total'], 2) ?></td>
                        <td><a href="detalle_venta.php?venta_id=<?= $row['id'] ?>">Ver detalles</a></td>
                        <td class="acciones">
                            <!-- <a href="editar_venta.php?id=<?= $row['id'] ?>" class="btn-action btn-edit">Editar</a> -->
                            <a href="eliminar_venta.php?id=<?= $row['id'] ?>" class="btn-action btn-delete" onclick="return confirm('¿Estás seguro de eliminar esta venta?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="no-ventas">No hay ventas registradas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Botón Volver al Inicio -->
    <div style="text-align: center;">
        <a href="../menus/Menu_Admin.php" class="btn-volver"> Volver al inicio</a>
    </div>
</div>

</body>
</html>

<?php $conn->close(); ?>
