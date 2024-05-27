<?php
include '../conexion.php';
session_start();

// Verificar si el usuario está autenticado y es un cliente
if (!isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'CLIENTE') {
    header("Location: ../login.php");
    exit();
}

// Obtener el historial de compras del cliente
$id_cliente = $_SESSION['user_id'];
$sql = "SELECT p.id_pedido, p.fecha, dp.id_producto, pr.nombre, dp.cantidad, dp.precio_unitario 
        FROM pedidos p 
        JOIN detalles_pedido dp ON p.id_pedido = dp.id_pedido 
        JOIN productos pr ON dp.id_producto = pr.id_producto 
        WHERE p.id_cliente = ? 
        ORDER BY p.fecha DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Compras</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Historial de Compras</h1>
    <table>
        <tr>
            <th>ID Pedido</th>
            <th>Fecha</th>
            <th>ID Producto</th>
            <th>Nombre Producto</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Precio Total</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while($pedido = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $pedido['id_pedido']; ?></td>
                <td><?php echo $pedido['fecha']; ?></td>
                <td><?php echo $pedido['id_producto']; ?></td>
                <td><?php echo $pedido['nombre']; ?></td>
                <td><?php echo $pedido['cantidad']; ?></td>
                <td><?php echo $pedido['precio_unitario']; ?></td>
                <td><?php echo $pedido['precio_unitario'] * $pedido['cantidad']; ?></td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">No se encontraron compras</td>
            </tr>
        <?php endif; ?>
    </table>
    <div>
        <a href="../cliente/cliente.php">Seguir Comprando</a>
        <a href="../logout.php">Cerrar Sesión</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>