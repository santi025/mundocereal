<?php
include '../dt_base/Conexion_db.php';

if (isset($_GET['nit'])) {
    $nit = $_GET['nit'];
    $query = $conn->query("SELECT * FROM proveedor WHERE nit = '$nit'");
    $proveedor = $query->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nit = $_POST['nit'];
    $nombre = $_POST['nombre'];
    $entidad = $_POST['entidad'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    $conn->query("UPDATE proveedor SET nombre='$nombre', entidad='$entidad', telefono='$telefono', direccion='$direccion' WHERE nit='$nit'");

    header("Location: ver_proveedor.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Proveedor</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fb;
            padding: 40px;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            width: 100%;
            max-width: 500px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            background-color: #fffaf0;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #6e4c3c;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
            color: #5c3b2e;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border: 1px solid #cdb6a2;
            border-radius: 8px;
            background-color: #f9f4f0;
            font-size: 16px;
            color: #4b2e2e;
            box-sizing: border-box;
        }

        .botones {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
        }

        .botones button,
        .botones a {
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .guardar {
            background-color: #a97c50; /* Tonos de marrón */
        }

        .guardar:hover {
            background-color: #8a6642;
        }

        .cancelar {
            background-color: #6c757d; /* Gris cálido */
        }

        .cancelar:hover {
            background-color: #5a636a;
        }

        .cancelar {
            text-align: center;
            display: inline-block;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Editar Proveedor</h2>
        <form method="POST">
            <input type="hidden" name="nit" value="<?= htmlspecialchars($proveedor['nit']) ?>">

            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($proveedor['nombre']) ?>" required>

            <label for="entidad">Entidad:</label>
            <input type="text" name="entidad" id="entidad" value="<?= htmlspecialchars($proveedor['entidad']) ?>">

            <label for="telefono">Teléfono:</label>
            <input type="text" name="telefono" id="telefono" value="<?= htmlspecialchars($proveedor['telefono']) ?>">

            <label for="direccion">Dirección:</label>
            <input type="text" name="direccion" id="direccion" value="<?= htmlspecialchars($proveedor['direccion']) ?>" required>

            <div class="botones">
                <button class="guardar" type="submit">Guardar Cambios</button>
                <a class="cancelar" href="ver_proveedor.php">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
