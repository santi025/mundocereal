<?php 
include '../dt_base/Conexion_db.php';

$mensaje = "";
$tipoMensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nit = $_POST['nit'];
    $nombre = $_POST['nombre'];
    $entidad = $_POST['entidad'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    $verificar = $conn->query("SELECT nit FROM proveedor WHERE nit = '$nit'");

    if ($verificar->num_rows > 0) {
        $mensaje = "⚠️ El NIT ya está registrado.";
        $tipoMensaje = "error";
    } else {
        $sql = "INSERT INTO proveedor (nombre, entidad, nit, telefono, direccion)
                VALUES ('$nombre', '$entidad', '$nit', '$telefono', '$direccion')";

        if ($conn->query($sql) === TRUE) {
            $mensaje = "✅ Proveedor registrado correctamente.";
            $tipoMensaje = "success";

            header("Location: ver_proveedor.php");
            exit(); 

        } else {
            $mensaje = "❌ Error al registrar proveedor: " . $conn->error;
            $tipoMensaje = "error";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Proveedor</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4e9dc;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .form-container {
            background: #fffaf0;
            padding: 30px 25px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            width: 100%;
            max-width: 420px;
        }

        h2 {
            text-align: center;
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

        input[type="submit"] {
            margin-top: 20px;
            background-color: #8d6748;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }

        .mensaje {
            margin-top: 20px;
            text-align: center;
            padding: 12px;
            border-radius: 8px;
            font-weight: bold;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .volver-btn {
            display: inline-block;
            text-align: center;
            margin-top: 10px;
            background-color: #6c757d;
            color: white;
            padding: 10px;
            width: 100%;
            border-radius: 8px;
            font-weight: bold;
            text-decoration: none;
        }

        .volver-btn:hover {
            background-color: #5a636a;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Registrar Proveedor</h2>
        <form method="POST">
            <label for="nit">NIT:</label>
            <input type="text" id="nit" name="nit" required>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="entidad">Entidad:</label>
            <input type="text" id="entidad" name="entidad">

            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono">

            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" required>

            <input type="submit" value="Registrar">
            <a class="volver-btn" href="ver_proveedor.php">⟵ Volver</a>
        </form>

        <?php if (!empty($mensaje)): ?>
            <div class="mensaje <?= $tipoMensaje ?>">
                <?= $mensaje ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
