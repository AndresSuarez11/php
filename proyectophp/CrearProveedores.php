<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];

    $stmt = $conn->prepare("INSERT INTO proveedores (nombre, direccion) VALUES (?, ?)");
    $stmt->bind_param("ss", $nombre, $direccion);

    if ($stmt->execute()) {
        header("Location: indexProveedores.php");
        exit();
    } else {
        echo "Error al agregar el proveedor.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Proveedor</title>
</head>
<body>
    <h1>Crear Proveedor</h1>
    <form method="post">
        Nombre: <input type="text" name="nombre" required><br>
        Dirección: <input type="text" name="direccion" required><br>
        <input type="submit" value="Agregar Proveedor">
    </form>
</body>
</html>