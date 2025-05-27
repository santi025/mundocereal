<?php 
include '../dt_base/Conexion_db.php';

$sql = "SELECT * FROM proveedor";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Proveedores Registrados</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7e8d3;
            padding: 40px;
            margin: 0;
            position: relative;
            color: #4b2e2e;
        }

        h2 {
            text-align: center;
            font-size: 1.8em;
            color: #6e4c3c;
            margin-bottom: 30px;
        }

        .top-button {
            text-align: center;
            margin-bottom: 20px;
        }

        .top-right-button {
            position: absolute;
            top: 20px;
            right: 40px;
        }

        .btn-link {
            background-color: #8d6748;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            font-size: 1.1em;
        }

        .btn-link:hover {
            background-color: #755133;
        }

        table {
            margin: auto;
            border-collapse: collapse;
            width: 90%;
            background-color: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            font-size: 1em;
        }

        th {
            background-color: #8d6748;
            color: white;
            text-align: left;
        }

        td {
            text-align: left;
            color: #5c3b2e;
        }

        .button {
            padding: 6px 12px;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.9em;
            margin-right: 4px;
        }

        .edit-btn { background-color: #28a745; }
        .delete-btn { background-color: #dc3545; }
        .toggle-btn { background-color: #007bff; }
        .button:hover { opacity: 0.85; }

        .estado-activo {
            background-color: #d4edda;
            color: #155724;
            font-weight: bold;
            padding: 6px 10px;
            border-radius: 5px;
            display: inline-block;
        }

        .estado-inactivo {
            background-color: #f8d7da;
            color: #721c24;
            font-weight: bold;
            padding: 6px 10px;
            border-radius: 5px;
            display: inline-block;
        }
    </style>
</head>
<body>

    <div class="top-right-button">
        <a class="btn-link" href="../menus/Menu_Admin.php">🏠 Volver al Inicio</a>
    </div>

    <h2>Proveedores Registrados</h2>

    <div class="top-button">
        <a class="btn-link" href="proveedor.php">+ Registrar Nuevo Proveedor</a>
    </div>

    <table>
        <tr>
            <th>NIT</th>
            <th>Nombre</th>
            <th>Entidad</th>
            <th>Teléfono</th>
            <th>Dirección</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        <?php while ($fila = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?= $fila['nit'] ?></td>
            <td><?= $fila['nombre'] ?></td>
            <td><?= $fila['entidad'] ?></td>
            <td><?= $fila['telefono'] ?></td>
            <td><?= $fila['direccion'] ?></td>
            <td>
                <span class="<?= $fila['estado'] == 1 ? 'estado-activo' : 'estado-inactivo' ?>">
                    <?= $fila['estado'] == 1 ? 'Activo' : 'Inactivo' ?>
                </span>
            </td>
            <td>
                <a class="button edit-btn" href="editar_proveedor.php?nit=<?= $fila['nit'] ?>">Editar</a>
                <a class="button delete-btn" href="eliminar_proveedor.php?nit=<?= $fila['nit'] ?>" onclick="return confirm('¿Estás seguro de eliminar este proveedor?');">Eliminar</a>
                <a class="button toggle-btn" href="cambiar_estado_proveedor.php?nit=<?= $fila['nit'] ?>" onclick="return confirm('¿Deseas cambiar el estado del proveedor?');">
                    <?= $fila['estado'] == 1 ? 'Inactivar' : 'Activar' ?>
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

</body>
</html>

<?php $conn->close(); ?>
