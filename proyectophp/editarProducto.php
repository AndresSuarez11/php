<?php
include 'conexion.php';
session_start();

// Verificar si el usuario está autenticado y es un vendedor
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'VENDEDOR') {
    header("Location: login.php");
    exit();
}

$id_producto = $_GET['id'];
$id_vendedor = $_SESSION['user_id'];

// Obtener la información del producto
$sql = "SELECT * FROM productos WHERE id_producto = ? AND id_vendedor = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_producto, $id_vendedor);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $producto = $result->fetch_assoc();
} else {
    echo "Producto no encontrado.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    $stmt = $conn->prepare("UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, stock = ? WHERE id_producto = ? AND id_vendedor = ?");
    $stmt->bind_param("ssdiii", $nombre, $descripcion, $precio, $stock, $id_producto, $id_vendedor);

    if ($stmt->execute()) {
        echo "Producto actualizado con éxito.";
    } else {
        echo "Error al actualizar el producto.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
</head>
<body>
    <h1>Editar Producto</h1>
    <form method="post">
        Nombre: <input type="text" name="nombre" value="<?php echo $producto['nombre']; ?>" required><br>
        Descripción: <textarea name="descripcion" required><?php echo $producto['descripcion']; ?></textarea><br>
        Precio: <input type="number" name="precio" step="0.01" value="<?php echo $producto['precio']; ?>" required><br>
        Stock: <input type="number" name="stock" value="<?php echo $producto['stock']; ?>" required><br>
        <input type="submit" value="Actualizar Producto">
    </form>
    <a href="vendedor.php">Volver</a>
</body>
</html>

<?php
$conn->close();
?>