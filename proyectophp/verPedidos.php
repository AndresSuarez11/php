<?php
include 'conexion.php';
session_start();

// Verificar si el usuario está autenticado y es un vendedor
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'VENDEDOR') {
    header("Location: login.php");
    exit();
}

// Obtener los pedidos de los productos del vendedor
$id_vendedor = $_SESSION['user_id'];
$sql = "SELECT p.id_pedido, p.fecha, dp.id_producto, pr.nombre, dp.cantidad, dp.precio_unitario 
        FROM pedidos p 
        JOIN detalles_pedido dp ON p.id_pedido = dp.id_pedido 
        JOIN productos pr ON dp.id_producto = pr.id_producto 
        WHERE pr.id_vendedor = ? 
        ORDER BY p.fecha DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_vendedor);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedidos de Mis Productos</title>
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
    <h1>Pedidos de Mis Productos</h1>
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
                <td colspan="7">No se encontraron pedidos</td>
            </tr>
        <?php endif; ?>
    </table>
    <div>
        <a href="vendedor.php">Volver</a>
        <a href="logout.php">Cerrar Sesión</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>