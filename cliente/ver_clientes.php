<?php 
include '../dt_base/Conexion_db.php';

$sql = "SELECT * FROM Cliente";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clientes Registrados</title>
    <!-- Enlace a Google Fonts para la tipografía Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* Aplicando la tipografía Poppins */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f2e1;
            padding: 40px;
            color: #4b2e2e;
            font-size: 16px; /* Tamaño de letra legible */
        }

        h2 {
            text-align: center;
            font-size: 2.5em;
            color: #6e4b3c;
        }

        .top-button {
            text-align: center;
            margin-bottom: 20px;
        }

        .btn-link {
            background-color: #8e7c5e;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-size: 16px;
        }

        .btn-link:hover {
            background-color: #7c664e;
        }

        table {
            margin: auto;
            border-collapse: collapse;
            width: 90%;
            background: #fff5e1;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #e1d0b7;
            font-size: 16px; /* Tamaño de letra consistente */
        }

        th {
            background-color: #6e4b3c;
            color: white;
        }

        td {
            background-color: #faf1e6;
        }

        a.button {
            padding: 8px 15px;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-right: 5px;
            font-weight: bold;
            font-size: 14px;
        }

        .edit-btn { background-color: rgb(154, 146, 108); }
        .delete-btn { background-color: #b85c5c; }

        .button:hover { opacity: 0.85; }

        .top-right-button {
            position: absolute;
            top: 20px;
            right: 20px;
        }
    </style>
</head>

<body>
    <div class="top-right-button">
        <a class="btn-link" href="../menus/Menu_Admin.php">🏠 Volver al Inicio</a>
    </div>

    <h2>Clientes Registrados</h2>

    <div class="top-button">
        <a class="btn-link" href="cliente.php">Registrar Nuevo Cliente</a>
    </div>

    <table>
        <tr>
            <th>NIT</th>
            <th>Razón Social</th>
            <th>Correo</th>
            <th>Dirección</th>
            <th>Acciones</th>
        </tr>
        <?php while ($fila = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?= $fila['nit'] ?></td>
            <td><?= $fila['razon_social'] ?></td>
            <td><?= $fila['correo'] ?></td>
            <td><?= $fila['direccion'] ?></td>
            <td>
                <a class="button edit-btn" href="editar_cliente.php?nit=<?= $fila['nit'] ?>">Editar</a>
                <a class="button delete-btn" href="eliminar_cliente.php?nit=<?= $fila['nit'] ?>" onclick="return confirm('¿Estás seguro de eliminar este cliente?');">Eliminar</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php $conn->close(); ?>
