<?php
include '../dt_base/Conexion_db.php';

// Verificar si el parámetro 'id_pedido' está presente en la URL
if (isset($_GET['id_pedido'])) {
    $id_pedido = $_GET['id_pedido'];

    // Primero eliminar los detalles del pedido en la tabla 'pedido_detalle'
    $conn->query("DELETE FROM pedido_detalle WHERE id_pedido = '$id_pedido'");

    // Ahora eliminar el pedido de la tabla 'Pedido'
    $conn->query("DELETE FROM Pedido WHERE id_pedido = '$id_pedido'");

    // Redirigir a la página de lista de pedidos después de la eliminación
    header("Location: lista_pedidos.php");
    exit();
}
?>
