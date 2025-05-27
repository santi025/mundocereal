<?php
include '../dt_base/Conexion_db.php';

if (isset($_GET['nit'])) {
    $nit = $_GET['nit'];

    $consulta = "SELECT estado FROM proveedor WHERE nit = '$nit'";
    $resultado = $conn->query($consulta);

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $nuevoEstado = ($fila['estado'] == 1) ? 0 : 1;

        $update = "UPDATE proveedor SET estado = '$nuevoEstado' WHERE nit = '$nit'";
        $conn->query($update);
    }
}

header("Location: lista_proveedores.php"); // Asegúrate que coincida con tu archivo principal
exit;
?>
