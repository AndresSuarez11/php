<?php
include 'conexion.php';
session_start();

// Verificar si el usuario está autenticado y es un cliente
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'CLIENTE') {
    header("Location: login.php");
    exit();
}

$productosEnCarrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];

if (!empty($productosEnCarrito)) {
    $conn->begin_transaction();
    try {
        // Crear el pedido
        $id_cliente = $_SESSION['user_id'];
        $fecha = date('Y-m-d');
        $stmt = $conn->prepare("INSERT INTO pedidos (fecha, id_cliente) VALUES (?, ?)");
        $stmt->bind_param("si", $fecha, $id_cliente);
        $stmt->execute();
        $id_pedido = $stmt->insert_id;

        // Insertar detalles del pedido
        $stmt = $conn->prepare("INSERT INTO detalles_pedido (id_pedido, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
        foreach ($productosEnCarrito as $id_producto => $cantidad) {
            $sql = "SELECT * FROM productos WHERE id_producto = $id_producto";
            $result = $conn->query($sql);
            $producto = $result->fetch_assoc();
            $precio_unitario = $producto['precio'];
            $stmt->bind_param("iiid", $id_pedido, $id_producto, $cantidad, $precio_unitario);
            $stmt->execute();

            // Actualizar stock
            $nuevo_stock = $producto['stock'] - $cantidad;
            $sql = "UPDATE productos SET stock = $nuevo_stock WHERE id_producto = $id_producto";
            $conn->query($sql);
        }

        // Limpiar el carrito
        unset($_SESSION['carrito']);

        $conn->commit();
        echo "Compra realizada con éxito.";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error al finalizar la compra: " . $e->getMessage();
    }
} else {
    echo "No hay productos en el carrito.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Compra Finalizada</title>
</head>
<body>
    <a href="cliente.php">Seguir Comprando</a>
    <a href="historialCompras.php">Historial de Compras</a>
</body>
</html>

<?php
$conn->close();
?>