<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM productos WHERE id_producto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $producto = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $id_categoria = $_POST['id_categoria'];
    $id_proveedor = $_POST['id_proveedor'];

    $stmt = $conn->prepare("UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, stock = ?, id_categoria = ?, id_proveedor = ? WHERE id_producto = ?");
    if ($stmt->execute([$nombre, $descripcion, $precio, $stock, $id_categoria, $id_proveedor, $id])) {
        echo "Producto actualizado con éxito.";
        header("Location: indexProductos.php");
        exit();
    } else {
        echo "Error al actualizar el producto.";
    }
}

// Obtener categorías para el menú desplegable
$sqlCategorias = "SELECT * FROM categorias";
$resultCategorias = $conn->query($sqlCategorias);
?>

<?php if (isset($producto)): ?>
<form method="post">
    <input type="hidden" name="id" value="<?php echo $producto['id_producto']; ?>">
    Nombre: <input type="text" name="nombre" value="<?php echo $producto['nombre']; ?>" required><br>
    Descripción: <input type="text" name="descripcion" value="<?php echo $producto['descripcion']; ?>" required><br>
    Precio: <input type