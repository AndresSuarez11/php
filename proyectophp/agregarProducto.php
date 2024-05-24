<?php
include 'conexion.php';
session_start();

// Verificar si el usuario está autenticado y es un vendedor
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'VENDEDOR') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $id_vendedor = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, stock, id_vendedor) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdii", $nombre, $descripcion, $precio, $stock, $id_vendedor);

    if ($stmt->execute()) {
        echo "Producto agregado con éxito.";
    } else {
        echo "Error al agregar el producto.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
</head>
<body>
    <h1>Agregar Producto</h1>
    <form method="post">
        Nombre: <input type="text" name="nombre" required><br>
        Descripción: <textarea name="descripcion" required></textarea><br>
        Precio: <input type="number" name="precio" step="0.01" required><br>
        Stock: <input type="number" name="stock" required><br>
        <input type="submit" value="Agregar Producto">
    </form>
    <a href="vendedor.php">Volver</a>
</body>
</html>