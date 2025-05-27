<?php
include '../dt_base/Conexion_db.php';

$buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';
$sql = "SELECT * FROM inv_mat WHERE nombre LIKE '%$buscar%' OR proveedor LIKE '%$buscar%'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario Materia Prima</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 0;
        }

        header {
            background: linear-gradient(135deg,rgb(210, 150, 44),rgb(41, 30, 9));
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        /* Estilo para los botones */
        .buttons-container {
            margin-bottom: 20px;
        }

        .add-button {
            display: inline-block;
            background:rgb(186, 219, 52);
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            margin-right: 10px;
        }

        .add-button:hover {
            background: #2c80b4;
        }

        .home-button {
            background:rgb(204, 183, 46);
        }

        .home-button:hover {
            background: #27ae60;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }

        th, td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }

        th {
            background-color: #f8f5f5;
            font-weight: bold;
        }

        tr:hover {
            background-color: #faf6f6;
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
            background: #43cea2;
        }

        .edit:hover {
            background: #36b191;
        }

        .delete {
            background: #e74c3c;
        }

        .delete:hover {
            background: #c0392b;
        }

        .search-form {
            margin-bottom: 20px;
            text-align: right;
        }

        .search-form input[type="text"] {
            padding: 8px;
            width: 200px;
            border-radius: 8px;
        }

        .search-form button {
            padding: 8px 16px;
            background-color: #3498db;
            color: white;
            border-radius: 8px;
            margin-left: 10px;
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
                color: #555;
            }
        }
    </style>
</head>
<body>

<header>Inventario de Materia Prima</header>
<div class="container">
    <!-- Botones superiores a la izquierda -->
    <div class="buttons-container">
        <a href="../../menus/menu_admin.php" class="add-button home-button">🏠 Volver al Inicio</a>
        <a href="agregar_mp.php" class="add-button">+ Agregar Producto</a>
    </div>

    <!-- Formulario de búsqueda -->
    <div class="search-form">
        <form method="get" action="">
            <input type="text" name="buscar" placeholder="Buscar materia..." value="<?php echo htmlspecialchars($buscar); ?>">
            <button type="submit">Buscar</button>
        </form>
    </div>

    <!-- Tabla de Inventario -->
    <table>
        <thead>
            <tr>
                <th>Código</th><th>Nombre</th><th>Unidad</th><th>Cantidad</th>
                <th>Proveedor</th><th>Valor Total</th><th>Fecha Ingreso</th>
                <th>Estado</th><th>Lote</th><th>Responsable</th><th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['codigo']}</td><td>{$row['nombre']}</td><td>{$row['unidad_med']}</td>";
                echo "<td>{$row['cant_inventario']}</td><td>{$row['proveedor']}</td><td>{$row['valor_total']}</td>";
                echo "<td>{$row['fecha_ingreso']}</td><td>{$row['estado']}</td><td>{$row['lote']}</td><td>{$row['responsable_inv']}</td>";
                echo "<td><a href='editarm.php?codigo={$row['codigo']}' class='button edit'>Editar</a> ";
                echo "<a href='eliminarm.php?codigo={$row['codigo']}' class='button delete' onclick=\"return confirm('¿Estás seguro?');\">Eliminar</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='11'>No hay materias primas que coincidan con la búsqueda.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

</body>
</html>
