<?php
include '../dt_base/Conexion_db.php';

if (isset($_GET['codigo'])) {
    $id = intval($_GET['codigo']); 

    if ($id > 0) { 
        $sql = "DELETE FROM inv_mat WHERE codigo = $id";

        if ($conn->query($sql) === TRUE) {
            header("Location: inv_materia.php");
            exit(); 
        } else {
            echo "Error eliminando: " . $conn->error;
        }
    } else {
        echo "ID inválido.";
    }
} else {
    echo "ID no especificado.";
}
?>