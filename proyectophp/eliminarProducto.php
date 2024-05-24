<?php
include 'conexion.php';
session_start();

// Verificar si el usuario está autenticado y es un vendedor
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'VENDEDOR') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_producto = $_POST['id_producto'];
    $id_vendedor = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM productos WHERE id_producto = ? AND id_vendedor = ?");
    $stmt->bind_param("ii", $id_producto, $id_vendedor);

    if ($stmt->execute()) {
        echo "Producto eliminado con éxito.";
    } else {
        echo "Error al eliminar el producto.";
    }

    header("Location: vendedor.php");
    exit();
}
?>