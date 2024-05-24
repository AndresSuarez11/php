<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $id_categoria = $_POST['id_categoria'];
    $id_proveedor = $_POST['id_proveedor'];

    $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, stock, id_categoria, id_proveedor) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$nombre, $descripcion, $precio, $stock, $id_categoria, $id_proveedor])) {
        echo "Producto creado con éxito.";
    } else {
        echo "Error al crear el producto.";
    }
}

// Obtener categorías para el menú desplegable
$sqlCategorias = "SELECT * FROM categorias";
$resultCategorias = $conn->query($sqlCategorias);
?>

<form method="post">
    Nombre: <input type="text" name="nombre" required><br>
    Descripción: <input type="text" name="descripcion" required><br>
    Precio: <input type="text" name="precio" required><br>
    Stock: <input type="number" name="stock" required><br>
    Categoría:
    <select name="id_categoria" required>
        <?php while($categoria = $resultCategorias->fetch_assoc()): ?>
            <option value="<?php echo $categoria['id_categoria']; ?>"><?php echo $categoria['nombre']; ?></option>
        <?php endwhile; ?>
    </select><br>
    Proveedor: <input type="number" name="id_proveedor" required><br>
    <input type="submit" value="Crear Producto">
</form>