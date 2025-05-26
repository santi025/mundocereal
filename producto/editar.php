<?php
include '../dt_base/Conexion_db.php';

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $gramaje = $_POST['gramaje'];
    $precio = $_POST['precio_unitario'];
    $lote = $_POST['lote'];

    $sql = "UPDATE producto SET codigo=?, nombre=?, gramaje=?, precio_unitario=?, lote=? WHERE id_Producto=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdssi", $codigo, $nombre, $gramaje, $precio, $lote, $id);
    $stmt->execute();

    header("Location: producto.php");
    exit();
}

$sql = "SELECT * FROM producto WHERE id_Producto=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$row = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4e9dc; /* Fondo beige claro */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: #fffaf5; /* Fondo crema */
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 12px 25px rgba(120, 72, 36, 0.15); /* Sombra marrón suave */
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
            margin-bottom: 5px;
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
            background-color: #f9f4f0;
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

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #a1866f; /* Marrón claro */
            text-decoration: none;
            font-weight: bold;
        }

        .back-link a:hover {
            text-decoration: underline;
            color: #92735e;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Editar Producto</h2>
    <form method="POST">
        <label for="codigo">Código:</label>
        <input type="text" name="codigo" id="codigo" value="<?= htmlspecialchars($row['codigo']) ?>" required>

        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($row['nombre']) ?>" required>

        <label for="gramaje">Gramaje:</label>
        <input type="text" name="gramaje" id="gramaje" value="<?= htmlspecialchars($row['gramaje']) ?>" required>

        <label for="precio_unitario">Precio Unitario:</label>
        <input type="number" step="0.01" name="precio_unitario" id="precio_unitario" value="<?= htmlspecialchars($row['precio_unitario']) ?>" required>

        <label for="lote">Lote:</label>
        <input type="text" name="lote" id="lote" value="<?= htmlspecialchars($row['lote']) ?>" required>

        <input type="submit" value="Actualizar">
    </form>

    <div class="back-link">
        <a href="producto.php">Volver </a>
    </div>
</div>

</body>
</html>
