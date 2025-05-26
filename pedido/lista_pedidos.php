<?php
// Conexión a la base de datos
include '../dt_base/Conexion_db.php';

// Recuperar todos los pedidos
$sql_pedidos = "SELECT p.id_pedido, p.fecha_pedido, c.razon_social 
                FROM Pedido p
                JOIN Cliente c ON p.id_cliente = c.nit";
$pedidos_result = $conn->query($sql_pedidos);

// Verificar si hay un mensaje de éxito o error en la URL
$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Pedidos</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Estilos generales */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f2ea;
            margin: 0;
            padding: 20px;
            color: #4b2e2e;
        }

        h2 {
            text-align: center;
            color: #6e4b3c;
            margin-bottom: 20px;
            font-size: 2rem;
        }

        /* Estilo para los botones */
        .btn-link {
            font-size: 1rem;
            background-color: #7a5d44;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 6px;
            margin: 10px;
            display: inline-block;
            transition: background-color 0.3s;
        }

        .btn-link:hover {
            background-color: #6c4e39;
        }

        .add-button {
            display: inline-block;
            font-size: 1.2rem;
            background-color: #a0815c;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 6px;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        .add-button:hover {
            background-color: #8c6d48;
        }

        /* Estilos de las alertas */
        .alert {
            padding: 10px;
            margin: 20px 0;
            border-radius: 6px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #6e4b3c;
            color: white;
        }

        td {
            background-color: #fffaf3;
            color: #4b2e2e;
        }

        tr:hover {
            background-color: #f1e2d2;
        }

        /* Botones dentro de la tabla */
        .button {
            text-decoration: none;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            display: inline-block;
            font-size: 1rem;
            margin: 5px;
        }

        .view-btn {
            background-color: #8c6d48;
        }

        .view-btn:hover {
            background-color: #7b5e3c;
        }

        .edit-btn {
            background-color: #957b5d;
        }

        .edit-btn:hover {
            background-color: #7b6349;
        }

        .delete-btn {
            background-color: #a81d0e;
        }

        .delete-btn:hover {
            background-color: #8c1b0b;
        }

        /* Posicionamiento del botón fijo */
        .top-right-button {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10;
        }
    </style>
</head>
<body>

    <!-- Botón fijo en esquina superior derecha -->
    <div class="top-right-button">
        <a class="btn-link" href="../menus/Menu_Admin.php">🏠 Volver al Inicio</a>
    </div>

    <h2>Lista de Pedidos</h2>

    <!-- Mostrar mensaje de éxito o error si existe -->
    <?php if ($mensaje): ?>
        <div class="alert <?= strpos($mensaje, 'Error') === false ? 'alert-success' : 'alert-error' ?>">
            <?= htmlspecialchars($mensaje) ?>
        </div>
    <?php endif; ?>

    <!-- Botón para agregar un nuevo pedido -->
    <a href="pedido.php" class="add-button"> Agregar Nuevo Pedido</a>

    <?php
    if ($pedidos_result->num_rows > 0) {
        echo "<table>
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>";

        while ($pedido = $pedidos_result->fetch_assoc()) {
            $id_pedido = $pedido['id_pedido'];
            $fecha_pedido = $pedido['fecha_pedido'];
            $razon_social = $pedido['razon_social'];
            
            // Mostrar los botones de acción
            echo "<tr>
                    <td>" . $id_pedido . "</td>
                    <td>" . $fecha_pedido . "</td>
                    <td>" . $razon_social . "</td>
                    <td>
                        <a href='ver_detalle_pedido.php?id_pedido=$id_pedido' class='button view-btn'>Ver Detalles</a>
                        <a href='editar.php?id_pedido=$id_pedido' class='button edit-btn'>Editar</a>
                        <a href='eliminar.php?id_pedido=$id_pedido' class='button delete-btn' onclick='return confirm(\"¿Estás seguro de eliminar este pedido?\");'>Eliminar</a>
                        <!-- Enlace para vender el pedido -->
                        <a href='../ventas/ventas.php?id_pedido=$id_pedido' class='button edit-btn'>Vender</a>
                    </td>
                  </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<p>No se encontraron pedidos.</p>";
    }

    $conn->close();
    ?>

</body>
</html>
