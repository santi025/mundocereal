<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "prueba.2");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Captura de datos del formulario
$usuario = $_POST['usuario'];
$contraseña = $_POST['contraseña'];

// Consulta para verificar el usuario
$sql = "SELECT * FROM usuario WHERE nombre = '$usuario' AND contraseña = '$contraseña'";
$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    // Usuario encontrado
    $fila = $resultado->fetch_assoc();
    
    switch ($fila['nombre']) {
        case "Admin":
            header("Location: menus/Menu_Admin.php");
            break;
        case "AdminInv":
            header("Location: menus/Menu_Admin_Inv.php");
            break;
        case "AdminMat":
            header("Location: menus/Menu_Admin_M.php");
            break;
        case "Vendedor":
            header("Location: menus/Menu_Vendedor.php");
            break;
        default:
            echo "Usuario no autorizado.";
            break;
    }
    exit();
} else {
    // Usuario no encontrado
    echo "<script>alert('Usuario o contraseña incorrectos'); window.location.href='index.html';</script>";
}

$conexion->close();
?>
