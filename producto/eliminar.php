<?php
include '../dt_base/Conexion_db.php'; 

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_producto = $_GET['id'];

    $conn->begin_transaction();

    try {
        // Eliminar dependencias
        $stmt1 = $conn->prepare("DELETE FROM pedido_detalle WHERE id_producto = ?");
        $stmt1->bind_param("i", $id_producto);
        $stmt1->execute();

        $stmt2 = $conn->prepare("DELETE FROM inventario WHERE id_Producto = ?");
        $stmt2->bind_param("i", $id_producto);
        $stmt2->execute();

        // Eliminar producto
        $stmt3 = $conn->prepare("DELETE FROM producto WHERE id_Producto = ?");
        $stmt3->bind_param("i", $id_producto);
        $stmt3->execute();

        $conn->commit();

        // Redirige a producto.php con mensaje
        header("Location: producto.php?mensaje=producto_eliminado");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        header("Location: producto.php?mensaje=error_eliminacion");
        exit();
    }
} else {
    header("Location: producto.php?mensaje=id_invalido");
    exit();
}
?>

