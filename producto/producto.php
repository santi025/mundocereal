<?php
include '../dt_base/Conexion_db.php'; 

$sql = "SELECT * FROM producto";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Productos</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f2e6dc; /* Beige claro */
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background-color: #fffaf5; /* Fondo ligeramente beige */
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(120, 72, 36, 0.1); /* Sombra marrón clara */
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #5c3a21; /* Marrón oscuro */
        }

        .buttons-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .add-button,
        .back-button {
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.3s;
            font-weight: bold;
        }

        .add-button {
            background-color: #8d6748; /* Marrón medio */
            color: white;
        }

        .add-button:hover {
            background-color: #7a553a;
        }

        .back-button {
            background-color: #a1866f; /* Marrón claro */
            color: white;
        }

        .back-button:hover {
            background-color: #92735e;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #5c3a21; /* Marrón oscuro */
            color: white;
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #d5c2b3;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f5ebe0; /* Beige muy claro */
        }

        .action-btn {
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 14px;
            margin: 0 3px;
            font-weight: bold;
        }

        .edit-btn {
            background-color: #a97142; /* Marrón anaranjado */
            color: white;
        }

        .edit-btn:hover {
            background-color: #925e36;
        }

        .delete-btn {
            background-color: #b04a3f; /* Rojo quemado */
            color: white;
        }

        .delete-btn:hover {
            background-color: #93362f;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="buttons-container">
        <a href="../menus/Menu_Admin.php" class="back-button">🏠 Volver al Inicio</a>
        <a class="add-button" href="agregar.php">Agregar Producto</a>
    </div>

    <h2>Lista de Productos</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Gramaje</th>
                <th>Precio Unitario</th>
                <th>Lote</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id_Producto']) ?></td>
                <td><?= htmlspecialchars($row['codigo']) ?></td>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td><?= htmlspecialchars($row['gramaje']) ?></td>
                <td><?= htmlspecialchars($row['precio_unitario']) ?></td>
                <td><?= htmlspecialchars($row['lote']) ?></td>
                <td>
                    <a class="action-btn edit-btn" href="editar.php?id=<?= $row['id_Producto'] ?>">Editar</a>
                    <a class="action-btn delete-btn" href="eliminar.php?id=<?= $row['id_Producto'] ?>" onclick="return confirm('¿Seguro que deseas eliminar este producto?')">Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
