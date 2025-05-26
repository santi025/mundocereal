<?php
include '../dt_base/Conexion_db.php';

$mensaje = "";
$tipoMensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nit = $_POST['nit'];
    $razon_social = $_POST['razon_social'];
    $correo = $_POST['correo'];
    $direccion = $_POST['direccion'];

    $verificar = $conn->query("SELECT nit FROM Cliente WHERE nit = '$nit'");

    if ($verificar->num_rows > 0) {
        $mensaje = "⚠️ El NIT ya está registrado. Intenta con otro.";
        $tipoMensaje = "error";
    } else {
        $sql = "INSERT INTO Cliente (nit, razon_social, correo, direccion)
                VALUES ('$nit', '$razon_social', '$correo', '$direccion')";

        if ($conn->query($sql) === TRUE) {
            $mensaje = "✅ Cliente registrado correctamente.";
            $tipoMensaje = "success";

            header("Location: pedido.php");
            exit();
        } else {
            $mensaje = "❌ Error al registrar cliente: " . $conn->error;
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
    <title>Registrar Cliente</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fuente Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4e9d8;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            color: #4b2e2e;
        }

        .form-container {
            background: #fffaf0;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            width: 100%;
            max-width: 420px;
        }

        h2 {
            text-align: center;
            color: #6e4b3c;
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
            color: #5c4033;
            font-size: 1rem;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #c2b39b;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 1rem;
            background-color: #fdfaf7;
            color: #4b2e2e;
        }

        input[type="submit"] {
            margin-top: 20px;
            background-color: #8e7c5e;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 8px;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #7c664e;
        }

        .mensaje {
            margin-top: 20px;
            text-align: center;
            padding: 12px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 0.95rem;
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
            background-color: #6c5c45;
            color: white;
            padding: 10px;
            width: 100%;
            border-radius: 8px;
            font-weight: bold;
            font-size: 1rem;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .volver-btn:hover {
            background-color: #5a4b38;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Registrar Cliente</h2>
        <form method="POST" action="">
            <label for="nit">NIT:</label>
            <input type="text" id="nit" name="nit" required>

            <label for="razon_social">Razón Social:</label>
            <input type="text" id="razon_social" name="razon_social" required>

            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" required>

            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" required>

            <input type="submit" value="Registrar">
            <a class="volver-btn" href="lista_pedidos.php">⟵ Volver</a>
        </form>

        <?php if (!empty($mensaje)): ?>
            <div class="mensaje <?= $tipoMensaje ?>">
                <?= $mensaje ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
