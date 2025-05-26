<?php 
include '../dt_base/Conexion_db.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_inventario = intval($_GET['id']);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nueva_cantidad = intval($_POST['nueva_cantidad']);

        if ($nueva_cantidad > 0) {
            $sql_update_cantidad = "UPDATE inventario 
                                    SET cantidad = cantidad + ?
                                    WHERE id_Inventario = ?";
            $stmt_cantidad = $conn->prepare($sql_update_cantidad);
            $stmt_cantidad->bind_param("ii", $nueva_cantidad, $id_inventario);
            $stmt_cantidad->execute();
        }

        header("Location: inventario.php?mensaje=actualizado");
        exit();
    }

    // Obtener cantidad actual y nombre del producto
    $sql = "SELECT i.cantidad, p.nombre AS nombre_producto 
            FROM inventario i
            INNER JOIN producto p ON i.id_producto = p.id_producto
            WHERE i.id_Inventario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_inventario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
    } else {
        echo "Inventario no encontrado.";
        exit;
    }
} else {
    echo "ID no especificado o inválido.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sumar Cantidad</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f6eee4;
            color: #4b2e2e;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 50px;
        }

        header {
            font-size: 2em;
            margin-bottom: 20px;
            color: #6e4c3c;
        }

        .container {
            background-color: #fff7f0;
            border: 1px solid #d3b8a3;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #5c3b2e;
            font-weight: 500;
        }

        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #cdb6a2;
            border-radius: 6px;
            background-color: #fffdfb;
            font-size: 16px;
            color: #3b2a22;
        }

        .button {
            display: inline-block;
            padding: 10px 15px;
            margin-top: 10px;
            background-color: #a97c50;
            color: white;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #8a6642;
        }

        .button + .button {
            margin-left: 10px;
        }

        .product-name {
            margin-bottom: 20px;
            font-size: 1.1em;
            color: #6b4b3e;
            background-color: #f2e1d4;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
        }
    </style>
</head>
<body>

<header>Sumar Cantidad</header>

<div class="container">
    <div class="product-name">
        Producto: <strong><?= htmlspecialchars($row['nombre_producto']) ?></strong>
    </div>

    <form method="POST">
        <label>Cantidad Actual:</label>
        <input type="number" value="<?= $row['cantidad'] ?>" disabled>

        <label>Sumar Cantidad:</label>
        <input type="number" name="nueva_cantidad" value="0" min="1" required>

        <input type="submit" value="Actualizar Cantidad" class="button">
        <a href="inventario.php" class="button">Cancelar</a>
    </form>
</div>

</body>
</html>
