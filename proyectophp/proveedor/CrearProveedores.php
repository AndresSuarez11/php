<?php
include '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre_compania'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $contacto = $_POST['contacto'];

    $stmt = $conn->prepare("INSERT INTO proveedores (nombre_compania, direccion, telefono, email, contacto) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nombre, $direccion, $telefono, $email, $contacto);

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
        Nombre: <input type="text" name="nombre_compania" required><br>
        Dirección: <input type="text" name="direccion" required><br>
        Telefono: <input type="text" name="telefono" required><br>
        email: <input type="email" name="email" required><br>
        contacto: <input type="text" name="contacto" required><br>
    
        <input type="submit" value="Agregar Proveedor">
    </form>
</body>
</html>