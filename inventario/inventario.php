<?php 
include '../dt_base/conexion_db.php';

$buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';

$sql = "SELECT 
            p.id_producto,
            p.nombre AS producto_nombre,
            p.gramaje AS producto_gramaje,
            p.precio_unitario,
            i.id_Inventario,
            i.cantidad
        FROM producto p
        LEFT JOIN inventario i ON p.id_producto = i.id_producto
        WHERE p.nombre LIKE '%$buscar%' OR p.gramaje LIKE '%$buscar%'";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario de Productos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f3e9df; /* Fondo general */
            margin: 0;
            padding: 0;
        }

        header {
            background: linear-gradient(135deg, #a1866f, #5c3a21); /* Marrón degradado */
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            background: #fffaf5;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(120, 72, 36, 0.15);
            overflow-x: auto;
        }

        .add-button {
            display: inline-block;
            background: #8d6748;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .add-button:hover {
            background: #7a553a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }

        th, td {
            padding: 15px;
            border-bottom: 1px solid #e0d5ca;
            text-align: center;
            color: #4e3b31;
        }

        th {
            background-color: #f7f1ec;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f3ece7;
        }

        .button {
            padding: 8px 12px;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            margin: 2px;
            display: inline-block;
        }

        .edit {
            background: #a37c5b;
        }

        .edit:hover {
            background: #916d4d;
        }

        .delete {
            background: #b04f3a;
        }

        .delete:hover {
            background: #943c2b;
        }

        .search-form {
            margin-bottom: 20px;
            text-align: right;
        }

        .search-form input[type="text"] {
            padding: 8px;
            width: 200px;
            border-radius: 8px;
            border: 1px solid #cbb8a9;
            background-color: #f9f4f0;
            color: #4e3b31;
        }

        .search-form button {
            padding: 8px 16px;
            background-color: #8d6748;
            color: white;
            border-radius: 8px;
            border: none;
            cursor: pointer;
        }

        .search-form button:hover {
            background-color: #7a553a;
        }

        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead tr {
                display: none;
            }

            td {
                position: relative;
                padding-left: 50%;
                margin-bottom: 12px;
                text-align: left;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                font-weight: bold;
                color: #6b4e3d;
            }
        }
    </style>
</head>
<body>

<header>
    Inventario de Productos
</header>

<div class="container">
    <div class="search-form">
        <a href="agregar.php" class="add-button">Agregar Nuevo Producto</a>
        <form method="get" action="">
            <input type="text" name="buscar" placeholder="Buscar producto..." value="<?php echo htmlspecialchars($buscar); ?>">
            <button type="submit">Buscar</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID Inventario</th>
                <th>Producto</th>
                <th>Gramaje</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Precio Total</th>
                <th>ID Producto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $contador = 1;
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $idInventario = !empty($row["id_Inventario"]) ? $row["id_Inventario"] : "Inventario-" . $contador;
                    $cantidad = is_numeric($row["cantidad"]) ? (int)$row["cantidad"] : 0;
                    $precioUnitario = $row["precio_unitario"];
                    $precioTotal = $cantidad * $precioUnitario;

                    echo "<tr>
                        <td>" . $idInventario . "</td>
                        <td>" . htmlspecialchars($row["producto_nombre"]) . "</td>
                        <td>" . htmlspecialchars($row["producto_gramaje"]) . "</td>
                        <td>" . $cantidad . "</td>
                        <td>$" . number_format($precioUnitario, 2) . "</td>
                        <td>$" . number_format($precioTotal, 2) . "</td>
                        <td>" . $row["id_producto"] . "</td>
                        <td>
                            <a href='editar.php?id=" . $row["id_Inventario"] . "' class='button edit'>Editar</a>
                        </td>
                    </tr>";

                    $contador++;
                }
            } else {
                echo "<tr><td colspan='8'>No hay productos que coincidan con la búsqueda.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: center;">
        <a href="../menus/Menu_Admin.php" class="add-button" style="background: #6e4f3a;">🏠 Volver al Inicio</a>
    </div>
</div>

</body>
</html>
