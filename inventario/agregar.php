<?php 
include '../dt_base/Conexion_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $gramaje = $_POST['gramaje'];
    $precio = $_POST['precio_unitario'];
    $lote = $_POST['lote'];

    if(empty($codigo) || empty($nombre) || empty($gramaje) || empty($precio) || empty($lote)) {
        echo "Todos los campos son obligatorios.";
    } else {
        $sql = "INSERT INTO producto (codigo, nombre, gramaje, precio_unitario, lote) VALUES (?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssdis", $codigo, $nombre, $gramaje, $precio, $lote);

            if ($stmt->execute()) {
                header("Location: inventario.php");
                exit();
            } else {
                echo "Error al insertar el producto: " . $stmt->error;
            }
        } else {
            echo "Error al preparar la consulta: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4e9dc; /* Fondo beige claro */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: #fffaf5; /* Fondo crema claro */
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(120, 72, 36, 0.1); /* Sombra marrón claro */
            width: 100%;
            max-width: 500px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #5c3a21; /* Marrón oscuro */
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #6b4e3d; /* Marrón medio */
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 18px;
            border: 1px solid #cbb8a9;
            border-radius: 8px;
            font-size: 16px;
            background-color: #f9f4f0; /* Beige muy claro */
            color: #4e3b31;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            border: none;
            background-color: #8d6748; /* Marrón medio */
            color: white;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #7a553a;
        }

        .buttons-container {
            text-align: center;
            margin-top: 20px;
        }

        .add-button {
            display: inline-block;
            padding: 10px 16px;
            background-color: #a1866f; /* Marrón claro */
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .add-button:hover {
            background-color: #92735e;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Agregar Producto</h2>
    <form method="POST">
        <label for="codigo">Código:</label>
        <input type="text" name="codigo" id="codigo" required>

        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>

        <label for="gramaje">Gramaje:</label>
        <input type="text" name="gramaje" id="gramaje" required>

        <label for="precio_unitario">Precio Unitario:</label>
        <input type="number" step="0.01" name="precio_unitario" id="precio_unitario" required>

        <label for="lote">Lote:</label>
        <input type="text" name="lote" id="lote" required>

        <input type="submit" value="Agregar">
    </form>
    <div class="buttons-container">
        <a href="inventario.php" class="add-button">🏠 Volver</a>
    </div>
</div>

</body>
</html>
