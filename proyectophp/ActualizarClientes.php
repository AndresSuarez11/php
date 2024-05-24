<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    $stmt = $conn->prepare("UPDATE clientes SET nombre = ?, apellido = ?, email = ?, telefono = ? WHERE id_cliente = ?");
    $stmt->bind_param("ssssi", $nombre, $apellido, $email, $telefono, $id);
    if ($stmt->execute()) {
        echo "Cliente actualizado con éxito.";
    } else {
        echo "Error al actualizar el cliente.";
    }
    header("Location: indexClientes.php");
    exit();
} else {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Cliente</title>
</head>
<body>
    <h1>Actualizar Cliente</h1>
    <form method="post">
        <input type="hidden" name="id" value="<?php echo $cliente['id_cliente']; ?>">
        Nombre: <input type="text" name="nombre" value="<?php echo $cliente['nombre']; ?>"><br>
        Apellido: <input type="text" name="apellido" value="<?php echo $cliente['apellido']; ?>"><br>
        Email: <input type="text" name="email" value="<?php echo $cliente['email']; ?>"><br>
        Teléfono: <input type="text" name="telefono" value="<?php echo $cliente['telefono']; ?>"><br>
        <input type="submit" value="Actualizar Cliente">
    </form>
</body>
</html>

<?php
$conn->close();
?>