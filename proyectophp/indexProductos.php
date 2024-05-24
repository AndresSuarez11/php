<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'ADMIN') {
    header("Location: login.php");
    exit(); 
}
include 'conexion.php';

// Obtener la lista de productos
$sql = "SELECT * FROM productos";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $deleteSql = "DELETE FROM productos WHERE id_producto = ?";
        $stmt = $conn->prepare($deleteSql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo "Producto eliminado con éxito.";
        } else {
            echo "Error al eliminar el producto.";
        }
        // Refrescar la página para actualizar la lista
        header("Location: indexProductos.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Productos</title>
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
    <h1>Lista de Productos</h1>
    <a href="CrearProductos.php">Crear Producto</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Categoría</th>
            <th>Proveedor</th>
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
                <td><?php echo $producto['id_categoria']; ?></td>
                <td><?php echo $producto['id_proveedor']; ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $producto['id_producto']; ?>">
                        <input type="submit" name="delete" value="Eliminar">
                    </form>
                    <form method="get" action="ActualizarProductos.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $producto['id_producto']; ?>">
                        <input type="submit" value="Editar">
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">No se encontraron productos</td>
            </tr>
        <?php endif; ?>
    </table>
    <div>
        <a href="index.php">Inicio</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>