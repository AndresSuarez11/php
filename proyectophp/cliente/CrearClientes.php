<?php
include '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    // Usar la variable de conexión correcta
    $stmt = $conn->prepare("INSERT INTO clientes (nombre, apellido, email, telefono) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$nombre, $apellido, $email, $telefono])) {
        echo "Cliente creado con éxito.";
    } else {
        echo "Error al crear el cliente.";
    }
}
?>

<form method="post">
    Nombre: <input type="text" name="nombre" required>
    Apellido: <input type="text" name="apellido" required>
    Email: <input type="email" name="email" required>
    Teléfono: <input type="text" name="telefono" required>
    <input type="submit" value="Crear Cliente">
    <a href="indexClientes.php">volver</a>
</form>