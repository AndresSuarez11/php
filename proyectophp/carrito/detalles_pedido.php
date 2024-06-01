<?php
session_start();
include 'conexion.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Verificar si se ha enviado una solicitud POST para agregar un producto al carrito
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_producto'])) {
    $id_producto = $_POST['id_producto'];
    $cantidad = $_POST['cantidad'];

    // Insertar los detalles del pedido en la tabla
    $stmt = $conn->prepare("INSERT INTO detalles_pedido (id_pedido, id_producto, cantidad) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $_SESSION['pedido_id'], $id_producto, $cantidad);
    $stmt->execute();
}

// Obtener los detalles del pedido actual
$sql = "SELECT productos.*, detalles_pedido.cantidad 
        FROM detalles_pedido 
        INNER JOIN productos ON detalles_pedido.id_producto = productos.id 
        WHERE detalles_pedido.id_pedido = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['pedido_id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <!-- Agrega tus estilos CSS y enlaces a Bootstrap u otros recursos aquí -->
</head>
<body>
    <h1>Carrito de Compras</h1>

    <table>
        <tr>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <!-- Agrega más encabezados según sea necesario -->
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['nombre']; ?></td>
            <td><?php echo $row['precio']; ?></td>
            <td><?php echo $row['cantidad']; ?></td>
            <!-- Agrega más celdas según sea necesario -->
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Agrega más HTML para mostrar otros detalles del pedido, como el total, botones de finalizar compra, etc. -->

    <!-- Agrega un formulario para agregar más productos al carrito -->
    <form method="post">
        <input type="hidden" name="id_producto" value="ID_DEL_PRODUCTO">
        Cantidad: <input type="number" name="cantidad" min="1" value="1">
        <input type="submit" value="Agregar al carrito">
    </form>

    <!-- Agrega enlaces para volver a la página de inicio, continuar comprando, etc. -->

</body>
</html>

<?php
$conn->close();
?>