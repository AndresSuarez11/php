<?php
include '../conexion.php';
session_start();

// Verificar si el usuario está autenticado y es un cliente
if (!isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'CLIENTE') {
    header("Location: ../login.php");
    exit();
}

// Obtener la lista de productos
$sql = "SELECT * FROM productos WHERE stock > 0";
$result = $conn->query($sql);

// Procesar la compra
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_producto = $_POST['id_producto'];
    $cantidad = $_POST['cantidad'];

    // Obtener el stock actual del producto
    $stmt = $conn->prepare("SELECT * FROM productos WHERE id_producto = ?");
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $producto = $stmt->get_result()->fetch_assoc();

    if ($producto['stock'] >= $cantidad) {
        $nuevo_stock = $producto['stock'] - $cantidad;

        // Actualizar el stock del producto
        $updateStmt = $conn->prepare("UPDATE productos SET stock = ? WHERE id_producto = ?");
        $updateStmt->bind_param("ii", $nuevo_stock, $id_producto);

        if ($updateStmt->execute()) {
            // Registrar la compra en el historial
            $insertStmt = $conn->prepare("INSERT INTO historial_compras (id_cliente, id_producto, cantidad) VALUES (?, ?, ?)");
            $insertStmt->bind_param("iii", $_SESSION['user_id'], $id_producto, $cantidad);
            $insertStmt->execute();

            echo "Compra realizada con éxito.";
        } else {
            echo "Error al actualizar el stock.";
        }
    } else {
        echo "Stock insuficiente para la cantidad solicitada.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Cliente</title>
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
    <h1>Comprar Productos</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Cantidad</th>
            <th>Acciones</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while($producto = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $producto['id_producto']; ?></td>
                <td><?php echo $producto['nombre']; ?></td>
                <td><?php echo $producto['descripcion']; ?></td>
                <td><?php echo $producto['precio']; ?></td>
                <td><?php echo $producto['stock']; ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                        <input type="number" name="cantidad" min="1" max="<?php echo $producto['stock']; ?>" required>
                        <input type="submit" value="Comprar">
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">No se encontraron productos disponibles</td>
            </tr>
        <?php endif; ?>
    </table>
    <div>
        <a href="../logout.php">Cerrar Sesión</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>