<?php
include '../dt_base/Conexion_db.php';


// Consultamos productos agotados
$query = "SELECT p.nombre, p.gramaje
          FROM inventario i
          JOIN producto p ON i.id_producto = p.id_producto
          WHERE i.cantidad = 0";
$result = mysqli_query($conn, $query);
$productosAgotados = mysqli_fetch_all($result, MYSQLI_ASSOC);
$hay_alerta = count($productosAgotados) > 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Administrador</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: rgb(206, 187, 168);
            font-family: 'Poppins', sans-serif;
            font-weight: 400;
        }

        .container {
            display: flex;
            height: 100vh;
            flex-direction: row;
        }

        .nav {
            width: 250px;
            background: #d7b8b1;
            transition: width 0.3s ease;
            overflow: hidden;
            border-right: 1px solid #b89c91;
            position: relative;
        }

        .nav.collapsed {
            width: 90px;
        }

        .nav__link {
            color: rgb(0, 0, 0);
            display: block;
            padding: 15px 0;
            text-decoration: none;
            transition: opacity 0.3s ease;
            font-size: 1.1em;
        }

        .nav.collapsed .nav__link {
            opacity: 0;
            pointer-events: none;
        }

        .list {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            background: hsla(11, 32.90%, 67.30%, 0.71);
            padding-top: 50px;
        }

        .list__item {
            list-style: none;
            width: 100%;
            text-align: center;
            height: 53px;
            padding-top: 10px;
            line-height: 1;
            font-size: 1.2em;
        }

        .list__link {
            color: #4b2e2e;
            display: block;
            padding: 15px 0;
            text-decoration: none;
        }

        .list__item--click {
            cursor: pointer;
        }

        .list__button {
            display: flex;
            align-items: center;
            gap: 1em;
            width: 80%;
            margin: 0 auto;
            height: 100%;
            color: #4b2e2e;
        }

        .list__img {
            width: 30px;
            height: auto;
        }

        .toggle-button {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1000;
            width: 40px;
            height: 40px;
            background-color: transparent;
            border: none;
            font-size: 30px;
            cursor: pointer;
            color: #4b2e2e;
        }

        .main-content {
            flex-grow: 1;
            position: relative;
            background: #e8d6c3;
        }

        .main-content img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .header {
            width: 100%;
            height: 60px;
            background-color: #d7b8b1;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding-right: 20px;
            position: absolute;
            top: 0;
            z-index: 10;
        }

        .notification-bell {
            position: relative;
        }

        .notification-bell img {
            width: 30px;
        }

        .notification-dot {
            position: absolute;
            top: 0;
            right: 0;
            width: 10px;
            height: 10px;
            background-color: red;
            border-radius: 50%;
        }

        .notification-dropdown {
            position: absolute;
            top: 40px;
            right: 0;
            background-color: white;
            color: black;
            border: 1px solid #ccc;
            padding: 10px;
            width: 220px;
            border-radius: 5px;
            z-index: 20;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            font-size: 0.9em;
        }

        .notification-dropdown ul {
            list-style: disc;
            padding-left: 20px;
            margin-top: 5px;
        }

        .notification-dropdown li {
            margin-bottom: 5px;
        }

        .main-body {
            height: 100%;
            padding-top: 60px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Menú lateral izquierdo -->
        <nav class="nav">
            <button id="toggle-menu" class="toggle-button">☰</button>
            <ul class="list">
                <li class="list__item">
                    <div class="list__button">
                        <img src="assets/dashboard.svg" class="list__img">
                        <a href="../menus/menu_admin.php" class="nav__link">Inicio</a>
                    </div>
                </li>
                <li class="list__item list__item--click">
                    <div class="list__button">
                        <img src="assets/prod.svg" class="list__img">
                        <a href="../producto/producto.php" class="nav__link">Producto</a>
                    </div>
                </li>
                <li class="list__item list__item--click">
                    <div class="list__button">
                        <img src="assets/inv.svg" class="list__img">
                        <a href="../inventario/inventario.php" class="nav__link">Inventario</a>
                    </div>
                </li>
                <li class="list__item list__item--click">
                    <div class="list__button">
                        <img src="assets/pedido.svg" class="list__img">
                        <a href="../pedido/lista_pedidos.php" class="nav__link">Pedidos</a>
                    </div>
                </li>
                <li class="list__item list__item--click">
                    <div class="list__button">
                        <img src="assets/venta.svg" class="list__img">
                        <a href="../ventas/ventas.php" class="nav__link">Ventas</a>
                    </div>
                </li>
                <li class="list__item list__item--click">
                    <div class="list__button">
                        <img src="assets/user.svg" class="list__img">
                        <a href="../cliente/ver_clientes.php" class="nav__link">Cliente</a>
                    </div>
                </li>
                <li class="list__item">
                    <div class="list__button">
                        <img src="assets/docs.svg" class="list__img">
                        <a href="../factura.php" class="nav__link">Factura</a>
                    </div>
                </li>
                <li class="list__item">
                    <div class="list__button">
                        <img src="assets/invm.svg" class="list__img">
                        <a href="../imateria/inv_materia/inv_materia.php" class="nav__link">Inventario MP</a>
                    </div>
                </li>
                <li class="list__item">
                    <div class="list__button">
                        <img src="assets/prov.svg" class="list__img">
                        <a href="../proveedor/ver_proveedor.php" class="nav__link">Proveedor</a>
                    </div>
                </li>
                <li class="list__item">
                    <div class="list__button">
                        <img src="assets/docs.svg" class="list__img">
                        <a href="../ventas/reporte_ventas.php" class="nav__link">Informes</a>
                    </div>
                </li>
                <li class="list__item">
                    <div class="list__button">
                        <img src="assets/backk.svg" class="list__img">
                        <a href="../index.html" class="nav__link">Cerrar sesión</a>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Contenido principal -->
        <div class="main-content">
            <div class="header">
                <div class="notification-bell" id="noti-bell" title="Productos agotados">
                    <img src="assets/bell.svg" alt="Notificaciones" style="cursor: pointer;">
                    <?php if ($hay_alerta): ?>
                        <span class="notification-dot"></span>
                    <?php endif; ?>
                    <div id="noti-dropdown" class="notification-dropdown" style="display: none;">
                        <strong>Productos agotados:</strong>
                        <ul>
                            <?php foreach ($productosAgotados as $producto): ?>
                                <li><?= htmlspecialchars($producto['nombre']) ?> - <?= htmlspecialchars($producto['gramaje']) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="main-body">
                <img src="Imagen.jpg" alt="Imagen de bienvenida">
            </div>
        </div>
    </div>

    <script>
        const toggleButton = document.getElementById('toggle-menu');
        const nav = document.querySelector('.nav');
        toggleButton.addEventListener('click', () => {
            nav.classList.toggle('collapsed');
        });

        const bell = document.getElementById('noti-bell');
        const dropdown = document.getElementById('noti-dropdown');
        bell.addEventListener('click', function (e) {
            e.stopPropagation();
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });
        window.addEventListener('click', function () {
            dropdown.style.display = 'none';
        });
    </script>
</body>
</html>
