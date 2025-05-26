<?php
include '../dt_base/Conexion_db.php';

$mensaje = "";
$tipoMensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nit = $_POST['nit'];
    $razon_social = $_POST['razon_social'];
    $correo = $_POST['correo'];
    $direccion = $_POST['direccion'];

    // Validar si el NIT ya existe
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

            header("Location: ver_clientes.php");
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
    
    <!-- Enlace a Google Fonts para la fuente Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        /* Aplicando la fuente Poppins a todo el documento */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4e9d8; /* Color de fondo beige cálido */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-size: 16px; /* Tamaño de fuente legible */
        }

        .form-container {
            background: #fff5e1; /* Fondo suave beige */
            padding: 30px 25px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            width: 100%;
            max-width: 420px;
        }

        h2 {
            text-align: center;
            color: #6e4b3c; /* Título en tono marrón oscuro */
            font-size: 24px; /* Tamaño de fuente para el título */
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
            color: #5c4033; /* Marrón cálido */
            font-size: 16px; /* Tamaño de fuente para las etiquetas */
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border: 1px solid #bfa38f; /* Bordes en marrón claro */
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px; /* Tamaño de fuente consistente para los campos de entrada */
        }

        input[type="submit"] {
            margin-top: 20px;
            background-color: #8e7c5e; /* Botón de color café suave */
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px; /* Tamaño de fuente para el botón */
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #7c664e; /* Cambio a un tono más oscuro en hover */
        }

        .mensaje {
            margin-top: 20px;
            text-align: center;
            padding: 12px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px; /* Tamaño de fuente para los mensajes */
        }

        .success {
            background-color: #d4edda; /* Verde suave para mensajes de éxito */
            color: #155724;
        }

        .error {
            background-color: #f8d7da; /* Rojo suave para mensajes de error */
            color: #721c24;
        }

        .volver-btn {
            display: inline-block;
            text-align: center;
            margin-top: 10px;
            background-color: #6c757d; /* Gris cálido para el botón de volver */
            color: white;
            padding: 10px;
            width: 100%;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px; /* Tamaño de fuente para el botón de volver */
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .volver-btn:hover {
            background-color: #5a6268; /* Cambio a tono más oscuro en hover */
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
            
            <a class="volver-btn" href="ver_clientes.php"> Volver</a>

        </form>

        <?php if (!empty($mensaje)): ?>
            <div class="mensaje <?= $tipoMensaje ?>">
                <?= $mensaje ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
