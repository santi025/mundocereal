<?php
include '../dt_base/Conexion_db.php';

if (isset($_GET['nit'])) {
    $nit = $_GET['nit'];

    $query = $conn->query("SELECT * FROM Cliente WHERE nit = '$nit'");
    $cliente = $query->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nit = $_POST['nit'];
    $razon_social = $_POST['razon_social'];
    $correo = $_POST['correo'];
    $direccion = $_POST['direccion'];

    $conn->query("UPDATE Cliente SET razon_social='$razon_social', correo='$correo', direccion='$direccion' WHERE nit='$nit'");

    header("Location: ver_clientes.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* Aplicando la fuente Poppins */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f2e7; /* Fondo beige cálido */
            padding: 40px;
            font-size: 16px; /* Tamaño de letra legible */
        }

        h2 {
            text-align: center;
            color: #6a4f3b; /* Color café para el título */
            font-size: 24px;
        }

        .form-container {
            background-color: #fff9e6; /* Fondo blanco cálido */
            width: 500px;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: 600;
            color: #6a4f3b; /* Marrón oscuro para las etiquetas */
            font-size: 16px;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #bfa38f; /* Bordes en marrón claro */
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 16px;
            margin-top: 8px;
        }

        button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background-color: #8e7c5e; /* Botón café suave */
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #6f5b40; /* Tono más oscuro en hover */
        }

        .top-button {
            text-align: center;
            margin-bottom: 30px;
        }

        .btn-link {
            background-color: #6c757d; /* Gris cálido para el botón de volver */
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .btn-link:hover {
            background-color: #5a6268; /* Tono más oscuro en hover */
        }
    </style>
</head>
<body>

    <div class="top-button">
        <a class="btn-link" href="ver_clientes.php">Volver a Lista de Clientes</a>
    </div>

    <div class="form-container">
        <h2>Editar Cliente</h2>
        <form method="POST">
            <input type="hidden" name="nit" value="<?= htmlspecialchars($cliente['nit']) ?>">

            <label for="razon_social">Razón Social:</label>
            <input type="text" name="razon_social" id="razon_social" value="<?= htmlspecialchars($cliente['razon_social']) ?>" required>

            <label for="correo">Correo:</label>
            <input type="email" name="correo" id="correo" value="<?= htmlspecialchars($cliente['correo']) ?>" required>

            <label for="direccion">Dirección:</label>
            <input type="text" name="direccion" id="direccion" value="<?= htmlspecialchars($cliente['direccion']) ?>" required>

            <button type="submit">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>
