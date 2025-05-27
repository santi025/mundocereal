<?php
include '../dt_base/Conexion_db.php';

if (!isset($_GET['id_pedido'])) {
    die("ID de pedido no especificado.");
}

$id_pedido = (int) $_GET['id_pedido'];

// Obtener información del pedido
$sqlPedido = "SELECT p.id_pedido, p.fecha_pedido, c.razon_social AS cliente_nombre, c.nit AS cliente_documento
              FROM Pedido p
              JOIN Cliente c ON p.id_cliente = c.nit
              WHERE p.id_pedido = $id_pedido";

$resPedido = $conn->query($sqlPedido);
if (!$resPedido || $resPedido->num_rows == 0) {
    die("❌ Pedido no encontrado con ID $id_pedido.");
}

$pedido = $resPedido->fetch_assoc();
$cliente_nombre = $conn->real_escape_string($pedido['cliente_nombre']);
$cliente_documento = $pedido['cliente_documento'];

// Obtener productos del pedido
$sqlDetalle = "SELECT pd.id_producto, pd.cantidad, pr.precio_unitario
               FROM Pedido_Detalle pd
               JOIN Producto pr ON pd.id_producto = pr.id_Producto
               WHERE pd.id_pedido = $id_pedido";

$resDetalle = $conn->query($sqlDetalle);
if (!$resDetalle || $resDetalle->num_rows == 0) {
    die("❌ No hay productos en el pedido.");
}

date_default_timezone_set('America/Lima');
$fecha = date("Y-m-d H:i:s");

// Generar comprobante secuencialmente
$sqlUltimo = "SELECT MAX(comprobante_numero) as ultimo FROM ventas";
$resUltimo = $conn->query($sqlUltimo);
$rowUltimo = $resUltimo->fetch_assoc();
$comprobante_numero = $rowUltimo && $rowUltimo['ultimo'] ? $rowUltimo['ultimo'] + 1 : 10000;

$comprobante_tipo = 'BOLETA';
$total = 0;
$alertasStock = [];

$conn->begin_transaction();

try {
    // Insertar venta
    $sqlVenta = "INSERT INTO ventas (fecha, comprobante_tipo, comprobante_numero, cliente_nombre, cliente_documento, total)
                 VALUES ('$fecha', '$comprobante_tipo', '$comprobante_numero', '$cliente_nombre', '$cliente_documento', 0)";
    if (!$conn->query($sqlVenta)) {
        throw new Exception("Error al insertar venta: " . $conn->error);
    }

    $venta_id = $conn->insert_id;

    while ($fila = $resDetalle->fetch_assoc()) {
        $id_producto = $fila['id_producto'];
        $cantidad = $fila['cantidad'];
        $precio_unitario = $fila['precio_unitario'];

        $subtotal = $cantidad * $precio_unitario;
        $total += $subtotal;

        // Insertar detalle_venta
        $sqlDetalleVenta = "INSERT INTO detalle_venta (venta_id, producto, cantidad, precio_unitario, subtotal)
                            VALUES ($venta_id, '$id_producto', $cantidad, $precio_unitario, $subtotal)";
        if (!$conn->query($sqlDetalleVenta)) {
            throw new Exception("Error en detalle de venta: " . $conn->error);
        }

        // Actualizar inventario
        $invCheck = $conn->query("SELECT cantidad FROM inventario WHERE id_producto = '$id_producto'");
        if ($invCheck && $invCheck->num_rows > 0) {
            $invRow = $invCheck->fetch_assoc();
            $stock = $invRow['cantidad'];
            if ($stock >= $cantidad) {
                if ($stock <= 1) {
                    $alertasStock[] = "⚠ Producto ID: $id_producto con bajo stock ($stock unidad(es)).";
                }
                $nuevoStock = $stock - $cantidad;
                $conn->query("UPDATE inventario SET cantidad = $nuevoStock WHERE id_producto = '$id_producto'");
            } else {
                throw new Exception("❌ Stock insuficiente para producto ID: $id_producto");
            }
        } else {
            throw new Exception("❌ Producto ID: $id_producto no existe en inventario.");
        }
    }

    // Actualizar total en venta
    $conn->query("UPDATE ventas SET total = $total WHERE id = $venta_id");

    // Marcar pedido como vendido (si tienes ese campo o quieres enlazarlo)
    $conn->query("UPDATE Pedido SET vendido = 1 WHERE id_pedido = $id_pedido");

    // Confirmar
    $conn->commit();

    // Alertas
    if (!empty($alertasStock)) {
        echo "<script>alert('" . implode("\\n", $alertasStock) . "');</script>";
    }

    // Redirigir
    header("Location: ../ventas/ventas.php");
    exit;

} catch (Exception $e) {
    $conn->rollback();
    echo "<p style='color:red;'>❌ Error al registrar venta: " . $e->getMessage() . "</p>";
}
?>
