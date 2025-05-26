<?php
include '../dt_base/Conexion_db.php';

if (isset($_GET['id'])) {
    $venta_id = intval($_GET['id']); // Asegura que sea un número entero

    // Primero elimina los detalles asociados a la venta
    $conn->query("DELETE FROM detalle_venta WHERE venta_id = $venta_id");

    // Luego elimina la venta
    $conn->query("DELETE FROM ventas WHERE id = $venta_id");

    // Redirige al listado de ventas
    header("Location: ventas.php");
    exit();
}
?>
