<!DOCTYPE html> 
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Opciones de Venta</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3eee9; /* Fondo beige claro */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .button-container {
            text-align: center;
            background-color: #fffaf5;
            padding: 50px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .btn {
            display: block;
            width: 300px;
            background-color: #6d4c41; /* Café principal */
            border: none;
            color: white;
            padding: 18px 0;
            margin: 20px auto;
            font-size: 18px;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.1s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn:hover {
            background-color: #4e342e;
            transform: scale(1.03);
        }

        .btn-back {
            background-color: #8d6e63; /* Café grisáceo */
        }

        .btn-back:hover {
            background-color: #5d4037;
        }
    </style>
</head>
<body>
    <div class="button-container">
        <button class="btn" onclick="location.href='lista_pedidos.php'">Registrar venta de pedido</button>
        <button class="btn" onclick="location.href='registrar_venta.php'">Registrar venta</button>
        <button class="btn btn-back" onclick="location.href='ventas.php'">Volver</button>
    </div>
</body>
</html>
