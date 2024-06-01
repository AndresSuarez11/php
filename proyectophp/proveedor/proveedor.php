<?php
include '../conexion.php';
session_start();

// Verificar si el usuario está autenticado y es un proveedor
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'PROVEEDOR') {
    header("Location: ../login.php");
    exit();
}

// Obtener el ID del proveedor desde la sesión
$id_proveedor = $_SESSION['user_id'];

// Obtener todos los productos del proveedor
$sql = "SELECT * FROM productos WHERE id_proveedor = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_proveedor);
$stmt->execute();
$result = $stmt->get_result();
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
    <h1>Productos del Proveedor</h1>
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
                <td colspan="6">No se encontraron productos</td>
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