<?php

include 'conexion.php';

// Función para crear un cliente
function crearCliente($nombre, $apellido, $email, $telefono) {
    global $conn;
    $sql = "INSERT INTO clientes (nombre, apellido, email, telefono) VALUES ('$nombre', '$apellido', '$email', '$telefono')";
    if ($conn->query($sql) === TRUE) {
        echo "Cliente creado exitosamente";
    } else {
        echo "Error al crear el cliente: " . $conn->error;
    }
}

// Función para obtener todos los clientes
function obtenerClientes() {
    global $conn;
    $sql = "SELECT * FROM clientes";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "ID: " . $row["id_cliente"]. " - Nombre: " . $row["nombre"]. " " . $row["apellido"]. " - Email: " . $row["email"]. "<br>";
        }
    } else {
        echo "No se encontraron clientes";
    }
}

// Función para actualizar un cliente
function actualizarCliente($id, $nombre, $apellido, $email, $telefono) {
    global $conn;
    $sql = "UPDATE clientes SET nombre='$nombre', apellido='$apellido', email='$email', telefono='$telefono' WHERE id_cliente=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Cliente actualizado exitosamente";
    } else {
        echo "Error al actualizar el cliente: " . $conn->error;
    }
}

// Función para eliminar un cliente
function eliminarCliente($id) {
    global $conn;
    $sql = "DELETE FROM clientes WHERE id_cliente=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Cliente eliminado exitosamente";
    } else {
        echo "Error al eliminar el cliente: " . $conn->error;
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['crear'])) {
        crearCliente($_POST['nombre'], $_POST['apellido'], $_POST['email'], $_POST['telefono']);
    } elseif (isset($_POST['actualizar'])) {
        actualizarCliente($_POST['id_cliente'], $_POST['nombre'], $_POST['apellido'], $_POST['email'], $_POST['telefono']);
    } elseif (isset($_POST['eliminar'])) {
        eliminarCliente($_POST['id_cliente']);
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Clientes</title>
</head>
<body>
    <h1>Administrar Clientes</h1>
    <nav>
        <a href="index.php">Inicio</a>
    </nav>

    <h2>Crear Cliente</h2>
    <form method="post">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="text" name="apellido" placeholder="Apellido" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="telefono" placeholder="Teléfono">
        <button type="submit" name="crear">Crear</button>
    </form>

    <h2>Actualizar Cliente</h2>
    <form method="post">
        <input type="number" name="id_cliente" placeholder="ID del cliente" required>
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="text" name="apellido" placeholder="Apellido" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="telefono" placeholder="Teléfono">
        <button type="submit" name="actualizar">Actualizar</button>
    </form>

    <h2>Eliminar Cliente</h2>
    <form method="post">
        <input type="number" name="id_cliente" placeholder="ID del cliente" required>
        <button type="submit" name="eliminar">Eliminar</button>
    </form>

    <h2>Listar Clientes</h2>
    <?php obtenerClientes(); ?>
</body>
</html>