<?php
include 'conexion.php';

// Función para crear un producto
function crearProducto($nombre, $descripcion, $precio, $stock, $id_categoria) {
    global $conn;
    $sql = "INSERT INTO productos (nombre, descripcion, precio, stock, id_categoria) VALUES ('$nombre', '$descripcion', $precio, $stock, $id_categoria)";
    if ($conn->query($sql) === TRUE) {
        echo "Producto creado exitosamente";
    } else {
        echo "Error al crear el producto: " . $conn->error;
    }
}

// Función para obtener todos los productos
function obtenerProductos() {
    global $conn;
    $sql = "SELECT * FROM productos";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "ID: " . $row["id_producto"]. " - Nombre: " . $row["nombre"]. " - Descripción: " . $row["descripcion"]. " - Precio: $" . $row["precio"]. " - Stock: " . $row["stock"]. "<br>";
        }
    } else {
        echo "No se encontraron productos";
    }
}

// Función para actualizar un producto
function actualizarProducto($id, $nombre, $descripcion, $precio, $stock, $id_categoria) {
    global $conn;
    $sql = "UPDATE productos SET nombre='$nombre', descripcion='$descripcion', precio=$precio, stock=$stock, id_categoria=$id_categoria WHERE id_producto=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Producto actualizado exitosamente";
    } else {
        echo "Error al actualizar el producto: " . $conn->error;
    }
}

// Función para eliminar un producto
function eliminarProducto($id) {
    global $conn;
    $sql = "DELETE FROM productos WHERE id_producto=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Producto eliminado exitosamente";
    } else {
        echo "Error al eliminar el producto: " . $conn->error;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['crear'])) {
        crearProducto($_POST['nombre'], $_POST['descripcion'], $_POST['precio'], $_POST['stock'], $_POST['id_categoria']);
    } elseif (isset($_POST['actualizar'])) {
        actualizarProducto($_POST['id_producto'], $_POST['nombre'], $_POST['descripcion'], $_POST['precio'], $_POST['stock'], $_POST['id_categoria']);
    } elseif (isset($_POST['eliminar'])) {
        eliminarProducto($_POST['id_producto']);
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Productos</title>
</head>
<body>
    <h1>Administrar Productos</h1>
    <nav>
        <a href="index.php">Inicio</a>
    </nav>

    <h2>Crear Producto</h2>
    <form method="post">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="text" name="descripcion" placeholder="Descripción" required>
        <input type="number" step="0.01" name="precio" placeholder="Precio" required>
        <input type="number" name="stock" placeholder="Stock" required>
        <input type="number" name="id_categoria" placeholder="ID de Categoría" required>
        <button type="submit" name="crear">Crear</button>
    </form>

    <h2>Actualizar Producto</h2>
    <form method="post">
        <input type="number" name="id_producto" placeholder="ID del producto" required>
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="text" name="descripcion" placeholder="Descripción" required>
        <input type="number" step="0.01" name="precio" placeholder="Precio" required>
        <input type="number" name="stock" placeholder="Stock" required>
        <input type="number" name="id_categoria" placeholder="ID de Categoría" required>
        <button type="submit" name="actualizar">Actualizar</button>
    </form>

    <h2>Eliminar Producto</h2>
    <form method="post">
        <input type="number" name="id_producto" placeholder="ID del producto" required>
        <button type="submit" name="eliminar">Eliminar</button>
    </form>

    <h2>Listar Productos</h2>
    <?php obtenerProductos(); ?>
</body>
</html>