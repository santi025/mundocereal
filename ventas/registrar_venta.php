<?php 
include '../dt_base/Conexion_db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fecha = $_POST['fecha'];
    $comprobante_tipo = $_POST['comprobante_tipo'];
    $comprobante_numero = $_POST['comprobante_numero'];
    $cliente_nombre = $_POST['cliente_nombre'];
    $cliente_documento = $_POST['cliente_documento'];
    $productos = $_POST['producto'];
    $cantidades = $_POST['cantidad'];
    $precios = $_POST['precio_unitario'];
    $subtotales = $_POST['subtotal'];

    $total = array_sum($subtotales);
    $alertasStock = []; // Array para almacenar alertas de bajo stock

    // Iniciar transacción
    $conn->begin_transaction();

    try {
        // Insertar venta principal
        $sqlVenta = "INSERT INTO ventas (fecha, comprobante_tipo, comprobante_numero, cliente_nombre, cliente_documento, total)
                     VALUES ('$fecha', '$comprobante_tipo', '$comprobante_numero', '$cliente_nombre', '$cliente_documento', $total)";
        if ($conn->query($sqlVenta) === TRUE) {
            $venta_id = $conn->insert_id;

            foreach ($productos as $index => $producto) {
                $cantidad = $cantidades[$index];
                $precio_unitario = $precios[$index];
                $subtotal = $subtotales[$index];

                // Insertar detalle de la venta
                $sqlDetalle = "INSERT INTO detalle_venta (venta_id, producto, cantidad, precio_unitario, subtotal)
                               VALUES ($venta_id, '$producto', $cantidad, $precio_unitario, $subtotal)";
                $conn->query($sqlDetalle);

                // Verificar y actualizar inventario
                $sqlInventario = "SELECT cantidad FROM inventario WHERE id_producto = '$producto'";
                $resultInventario = $conn->query($sqlInventario);

                if ($resultInventario->num_rows > 0) {
                    $row = $resultInventario->fetch_assoc();
                    $cantidadInventario = $row['cantidad'];

                    if ($cantidadInventario >= $cantidad) {
                        // Verificar si el stock es bajo
                        if ($cantidadInventario <= 1) {
                            $alertasStock[] = "El producto ID: $producto está con baja de stock (solo $cantidadInventario unidad(es) disponible(s)).";
                        }

                        // Restar cantidad del inventario
                        $newCantidad = $cantidadInventario - $cantidad;
                        $sqlActualizarInventario = "UPDATE inventario SET cantidad = $newCantidad WHERE id_producto = '$producto'";
                        $conn->query($sqlActualizarInventario);
                    } else {
                        throw new Exception("No hay suficiente inventario para el producto ID: $producto");
                    }
                } else {
                    throw new Exception("Producto ID: $producto no encontrado en inventario");
                }
            }

            // Confirmar transacción
            $conn->commit();

            // Si hay alertas de stock bajo, redirigir con el mensaje
            if (!empty($alertasStock)) {
                echo "<script>alert('" . implode("\\n", $alertasStock) . "');</script>";
            }

            header("Location: ventas.php");
            exit;
        } else {
            throw new Exception("Error al registrar la venta: " . $conn->error);
        }
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $conn->rollback();
        echo "<p style='color:red;'>❌ Error: " . $e->getMessage() . "</p>";
    }
}

// Obtener productos y precios
$productos_query = "SELECT * FROM producto";
$productos_result = $conn->query($productos_query);
$precios = [];
while ($row = $productos_result->fetch_assoc()) {
    $precios[$row['id_Producto']] = $row['precio_unitario'];
}
$productos_result->data_seek(0);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Venta</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fa;
            padding: 30px;
            font-family: 'Poppins', sans-serif;
        }

        h2 {
            color: #6d4c41;
            text-align: center;
            margin-bottom: 20px;
            font-size: 2rem;
        }

        form {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 800px;
            margin: 0 auto;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #6d4c41;
        }

        input[type="text"],
        input[type="number"],
        input[type="datetime-local"],
        select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            background-color: #f9f9f9;
        }

        #productos-container {
            margin-top: 20px;
        }

        #productos-container div {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            background-color: #f3e5f5;
            align-items: center;
        }

        #productos-container select,
        #productos-container input {
            flex-grow: 1;
        }

        button[type="button"] {
            background-color: #ff7043;
            color: white;
            padding: 8px 15px;
            font-size: 0.9rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="button"]:hover {
            background-color: #e64a19;
        }

        .btn-agregar {
            background-color: #007bff;
            margin-top: 10px;
        }

        .btn-agregar:hover {
            background-color: #0056b3;
        }

        #total {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            padding-top: 10px;
            display: block;
            text-align: center;
        }

        span {
            font-size: 1rem;
            color: #888;
        }

        button[type="submit"] {
            background: linear-gradient(to right, #6d4c41, #3e2723);
            color: #fff;
            padding: 15px 25px;
            border: none;
            border-radius: 8px;
            font-size: 1.2rem;
            cursor: pointer;
            transition: transform 0.2s, background-color 0.3s;
            width: 100%;
            margin-top: 20px;
        }

        button[type="submit"]:hover {
            background: linear-gradient(to right, #3e2723, #4e342e);
            transform: scale(1.02);
        }

        /* Estilo del botón Volver */
        .btn-volver {
            background-color: #607D8B;
            color: white;
            padding: 12px 20px;
            font-size: 1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
        }

        .btn-volver:hover {
            background-color: #546E7A;
        }
    </style>
</head>
<body>

<h2>Registrar Venta</h2>

<form method="POST" onsubmit="return calcularTotales();">
    <label>Fecha:</label>
    <input type="datetime-local" name="fecha" required>

    <label>Tipo de Comprobante:</label>
    <input type="text" name="comprobante_tipo" required>

    <label>Número de Comprobante:</label>
    <input type="text" name="comprobante_numero" required>

    <label>Cliente:</label>
    <input type="text" name="cliente_nombre" required>

    <label>Documento del Cliente:</label>
    <input type="text" name="cliente_documento" required>

    <div id="productos-container"></div>
    <button type="button" class="btn-agregar" onclick="agregarProducto()">+ Agregar Producto</button>

    <div>
        <span>Total: S/ <span id="total">0.00</span></span>
    </div>

    <button type="submit">✅ Registrar Venta</button>
</form>

<!-- Botón de Volver -->
<button class="btn-volver" onclick="window.location.href='ventas.php'">← Volver</button>

<script>
const precios = <?= json_encode($precios) ?>;

function agregarProducto() {
    const container = document.getElementById('productos-container');
    const div = document.createElement('div');
    div.innerHTML = `
        <select name="producto[]" onchange="setPrecio(this)" required>
            <option value="">-- Selecciona --</option>
            <?php
            $productos_result->data_seek(0);
            while ($row = $productos_result->fetch_assoc()):
                $id = $row['id_Producto'];
                $nombre = htmlspecialchars($row['nombre']);
                $gramaje = $row['gramaje'];
                $descripcion = "$nombre - $gramaje g";
            ?>
            <option value="<?= $id ?>"><?= $descripcion ?></option>
            <?php endwhile; ?>
        </select>

        <input type="number" name="cantidad[]" oninput="calcularTotales()" placeholder="Cantidad" required>
        <input type="number" name="precio_unitario[]" step="0.01" oninput="calcularTotales()" placeholder="Precio" required readonly>
        <input type="hidden" name="subtotal[]" value="0">
        <button type="button" onclick="eliminarProducto(this)">Eliminar</button>
    `;
    container.appendChild(div);
}

function setPrecio(selectElem) {
    const productoId = selectElem.value;
    const precio = precios[productoId] || 0;
    const grupo = selectElem.parentNode;
    grupo.querySelector('input[name="precio_unitario[]"]').value = precio;
    calcularTotales();
}

function eliminarProducto(button) {
    button.parentElement.remove();
    calcularTotales();
}

function calcularTotales() {
    const contenedor = document.getElementById('productos-container');
    const grupos = contenedor.querySelectorAll('div');
    let total = 0;
    grupos.forEach(grupo => {
        const cantidad = parseFloat(grupo.querySelector('input[name="cantidad[]"]').value) || 0;
        const precio = parseFloat(grupo.querySelector('input[name="precio_unitario[]"]').value) || 0;
        const subtotal = cantidad * precio;
        grupo.querySelector('input[name="subtotal[]"]').value = subtotal.toFixed(2);
        total += subtotal;
    });
    document.getElementById('total').textContent = total.toFixed(2);
    return true;
}
</script>

</body>
</html>

<?php $conn->close(); ?>
