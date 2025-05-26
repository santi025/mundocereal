<?php 
include '../dt_base/Conexion_db.php'; // Tu conexión

// Obtener los datos de los clientes y productos para mostrarlos en el formulario
$clientes = $conn->query("SELECT * FROM Cliente");
$productos = $conn->query("SELECT * FROM Producto");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $id_cliente = $_POST['id_cliente'];
    $id_producto = $_POST['id_producto'];
    $cantidad_producto = $_POST['cantidad_producto'];
    
    // Insertar el pedido en la tabla 'Pedido' (suponiendo que ya tienes esta tabla)
    $fecha_pedido = date('Y-m-d H:i:s');
    $sql_pedido = "INSERT INTO Pedido (id_cliente, fecha_pedido) VALUES ('$id_cliente', '$fecha_pedido')";
    if ($conn->query($sql_pedido) === TRUE) {
        $id_pedido = $conn->insert_id;  // Obtener el ID del pedido recién insertado
        
        // Insertar los detalles del pedido en la tabla 'pedido_detalle'
        foreach ($id_producto as $index => $producto) {
            $cantidad = $cantidad_producto[$index];
            $sql_detalle = "INSERT INTO pedido_detalle (id_pedido, id_producto, cantidad) 
                            VALUES ('$id_pedido', '$producto', '$cantidad')";
            if (!$conn->query($sql_detalle)) {
                echo "Error al insertar detalle: " . $conn->error;
            }
        }
        
        // Redirigir a la lista de pedidos (actualizada)
        header("Location: lista_pedidos.php");
        exit();
    } else {
        echo "Error al registrar el pedido: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f7f2ea;
            padding: 20px;
            color: #4b2e2e;
        }

        h2 {
            text-align: center;
            color: #6e4b3c;
            margin-bottom: 20px;
            font-size: 2rem;
        }

        form {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            max-width: 900px;
            margin: auto;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: 600;
            display: block;
            margin-top: 10px;
            color: #6e4b3c;
        }

        select, input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1rem;
            color: #4b2e2e;
        }

        table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #f5f5f5;
        }

        .btn {
            background-color: #a0815c;
            color: white;
            padding: 12px;
            margin-top: 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1rem;
            width: 100%;
        }

        .btn:hover {
            background-color: #8c6d48;
        }

        .btn-add {
            background-color:rgba(212, 154, 29, 0.9);
            margin-top: 15px;
            width: auto;
        }

        .btn-delete {
            background-color:rgb(221, 189, 7);
            border: none;
            padding: 5px 10px;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-delete:hover {
            background-color:rgb(224, 173, 2);
        }

        .action-buttons {
            text-align: center;
            margin-top: 20px;
        }

        .action-buttons a {
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            margin: 0 10px;
        }

        .action-buttons .btn-back {
            background-color:rgb(161, 135, 20);
            color: white;
        }

        .action-buttons .btn-add-client {
            background-color:rgb(192, 148, 29);
            color: white;
        }

        #infoCliente {
            margin-top: 15px;
            background: #f2e8d5;
            padding: 10px;
            border-radius: 5px;
            display: none;
        }
    </style>
</head>
<body>

<h2>Registrar Pedido</h2>

<form action="pedido.php" method="POST">
    <label for="cliente">Selecciona un cliente:</label>
    <select name="id_cliente" id="cliente" required>
        <option value="">-- Selecciona --</option>
        <?php
        // Reiniciar puntero de resultado
        $clientes->data_seek(0);
        while ($cliente = $clientes->fetch_assoc()): ?>
            <option value="<?= $cliente['nit'] ?>"><?= htmlspecialchars($cliente['razon_social']) ?></option>
        <?php endwhile; ?>
    </select>

    <!-- Botón para agregar cliente debajo de la selección -->
    <a href="cliente.php" class="btn btn-add-client" style="width: auto; margin-top: 10px; display: block; text-align: center;">Agregar Cliente</a>

    <div id="infoCliente">
        <h4>Información del Cliente:</h4>
        <p><strong>NIT:</strong> <span id="nitCliente"></span></p>
        <p><strong>Razón Social:</strong> <span id="razonCliente"></span></p>
        <p><strong>Dirección:</strong> <span id="direccionCliente"></span></p>
        <p><strong>Teléfono:</strong> <span id="telefonoCliente"></span></p>
    </div>

    <table id="productos">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <select name="id_producto[]" onchange="validarProductos()" required>
                        <option value="">-- Selecciona --</option>
                        <?php 
                        $productos_inicial = $conn->query("SELECT * FROM Producto");
                        while ($producto = $productos_inicial->fetch_assoc()): ?>
                            <option value="<?= $producto['id_Producto'] ?>"><?= htmlspecialchars($producto['nombre']) ?> - $<?= $producto['precio_unitario'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </td>
                <td><input type="number" name="cantidad_producto[]" min="1" required></td>
                <td><button type="button" class="btn-delete" onclick="eliminarProducto(this)">Eliminar</button></td>
            </tr>
        </tbody>
    </table>

    <button type="button" class="btn btn-add" onclick="agregarProducto()">➕ Agregar Producto</button>

    <input type="submit" value="Registrar Pedido" class="btn">
</form>

<!-- Botón para volver al inicio -->
<div class="action-buttons">
    <a href="../pedido/lista_pedidos.php" class="btn-back">Volver al inicio</a>
</div>

<script>
function agregarProducto() {
    var tbody = document.querySelector("#productos tbody");
    var newRow = document.createElement("tr");
    newRow.innerHTML = ` 
        <td>
            <select name="id_producto[]" onchange="validarProductos()" required>
                <option value="">-- Selecciona --</option>
                <?php
                $productos_js = $conn->query("SELECT * FROM Producto");
                while ($producto = $productos_js->fetch_assoc()) {
                    echo "<option value='" . $producto['id_Producto'] . "'>" . htmlspecialchars($producto['nombre']) . " - $" . $producto['precio_unitario'] . "</option>";
                }
                ?>
            </select>
        </td>
        <td><input type="number" name="cantidad_producto[]" min="1" required></td>
        <td><button type="button" class="btn-delete" onclick="eliminarProducto(this)">Eliminar</button></td>
    `;
    tbody.appendChild(newRow);
    validarProductos();
}

function eliminarProducto(button) {
    button.closest('tr').remove();
    validarProductos();
}

function validarProductos() {
    var selects = document.querySelectorAll('select[name="id_producto[]"]');
    var valores = [];

    selects.forEach(function(select) {
        if (select.value) {
            if (valores.includes(select.value)) {
                alert('⚠️ Este producto ya fue seleccionado. Por favor elige otro.');
                select.value = '';
            } else {
                valores.push(select.value);
            }
        }
    });
}

document.getElementById('cliente').addEventListener('change', function () {
    const id = this.value;
    const infoDiv = document.getElementById('infoCliente');

    if (id && clientesData[id]) {
        const cliente = clientesData[id];
        document.getElementById('nitCliente').textContent = cliente.nit;
        document.getElementById('razonCliente').textContent = cliente.razon_social;
        document.getElementById('direccionCliente').textContent = cliente.direccion;
        document.getElementById('telefonoCliente').textContent = cliente.telefono;
        infoDiv.style.display = 'block';
    } else {
        infoDiv.style.display = 'none';
    }
});
</script>

</body>
</html>
