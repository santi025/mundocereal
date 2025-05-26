<?php
// Puedes incluir tu conexión a la base de datos si es necesario
// include '../conexion.php';
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
            flex-direction: row-reverse;
        }

        .nav {
            width: 400px;
            background: #d7b8b1;
            transition: width 0.3s ease;
            overflow: hidden;
            border-left: 1px solid #b89c91;
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
            font-weight: 400; /* Cambiado de 600 a 400 */
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
            transition: opacity 0.3s ease;
            font-weight: 400; /* Cambiado de 600 a 400 */
            line-height: 1;
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
            left: 10px;
            z-index: 1000;
            width: 60px;
            height: 40px;
            background-color: transparent;
            border: none;
            font-size: 30px;
            cursor: pointer;
            color: #4b2e2e;
        }

        .main-content {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #e8d6c3;
        }

        .main-content img {
            width: 80vw;
            height: 100vh;
            object-fit: cover;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Menú lateral derecho -->
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
            <img src="Imagen.jpg" alt="Imagen de bienvenida">
        </div>
    </div>

    <script>
        const listElements = document.querySelectorAll('.list__item--click');
        const toggleButton = document.getElementById('toggle-menu');
        const nav = document.querySelector('.nav');

        toggleButton.addEventListener('click', () => {
            nav.classList.toggle('collapsed');
        });

        listElements.forEach(listElement => {
            listElement.addEventListener('click', () => {
                listElement.classList.toggle('arrow');
                let height = 0;
                let menu = listElement.nextElementSibling;
                if (menu && menu.clientHeight === 0) {
                    height = menu.scrollHeight;
                }
                if (menu) {
                    menu.style.height = `${height}px`;
                }
            });
        });
    </script>

</body>
</html>
