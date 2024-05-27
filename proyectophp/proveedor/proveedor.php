<?php
include '../conexion.php';
session_start();

// Verificar si el usuario está autenticado y es un proveedor
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'PROVEEDOR') {
    header("Location: ../login.php");
    exit();
}

// Actualizar stock del producto
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_producto = $_POST['id_producto'];
    $cantidad = $_POST['cantidad'];

    // Obtener el stock actual del producto
    $stmt = $conn->prepare("SELECT stock FROM productos WHERE id_producto = ?");
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $producto = $result->fetch_assoc();
        $nuevo_stock = $producto['stock'] + $cantidad;

        // Actualizar el stock del producto
        $updateStmt = $conn->prepare("UPDATE productos SET stock = ? WHERE id_producto = ?");
        $updateStmt->bind_param("ii", $nuevo_stock, $id_producto);

        if ($updateStmt->execute()) {
            echo "Stock actualizado con éxito.";
        } else {
            echo "Error al actualizar el stock.";
        }
    } else {
        echo "Producto no encontrado.";
    }
}

// Obtener productos con stock bajo
$sql = "SELECT * FROM productos WHERE stock <= 10";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Proveedor</title>
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
    <h1>Productos con Stock Bajo</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Stock</th>
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
                        <input type="number" name="cantidad" placeholder="Cantidad" required>
                        <input type="submit" value="Agregar Stock">
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No se encontraron productos con stock bajo</td>
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