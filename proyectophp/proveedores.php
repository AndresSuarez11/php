<?php

include 'conexion.php';
// Función para crear un proveedor
function crearProveedor($nombre_compania, $contacto, $email, $telefono, $direccion) {
    global $conn;
    $sql = "INSERT INTO proveedores (nombre_compania, contacto, email, telefono, direccion) VALUES ('$nombre_compania', '$contacto', '$email', '$telefono', '$direccion')";
    if ($conn->query($sql) === TRUE) {
        echo "Proveedor creado exitosamente";
    } else {
        echo "Error al crear el proveedor: " . $conn->error;
    }
}

// Función para obtener todos los proveedores
function obtenerProveedores() {
    global $conn;
    $sql = "SELECT * FROM proveedores";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "ID: " . $row["id_proveedor"]. " - Nombre de la compañía: " . $row["nombre_compania"]. " - Contacto: " . $row["contacto"]. " - Email: " . $row["email"]. " - Teléfono: " . $row["telefono"]. " - Dirección: " . $row["direccion"]. "<br>";
        }
    } else {
        echo "No se encontraron proveedores";
    }
}

// Función para actualizar un proveedor
function actualizarProveedor($id, $nombre_compania, $contacto, $email, $telefono, $direccion) {
    global $conn;
    $sql = "UPDATE proveedores SET nombre_compania='$nombre_compania', contacto='$contacto', email='$email', telefono='$telefono', direccion='$direccion' WHERE id_proveedor=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Proveedor actualizado exitosamente";
    } else {
        echo "Error al actualizar el proveedor: " . $conn->error;
    }
}

// Función para eliminar un proveedor
function eliminarProveedor($id) {
    global $conn;
    $sql = "DELETE FROM proveedores WHERE id_proveedor=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Proveedor eliminado exitosamente";
    } else {
        echo "Error al eliminar el proveedor: " . $conn->error;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['crear'])) {
        crearProveedor($_POST['nombre_compania'], $_POST['contacto'], $_POST['email'], $_POST['telefono'], $_POST['direccion']);
    } elseif (isset($_POST['actualizar'])) {
        actualizarProveedor($_POST['id_proveedor'], $_POST['nombre_compania'], $_POST['contacto'], $_POST['email'], $_POST['telefono'], $_POST['direccion']);
    } elseif (isset($_POST['eliminar'])) {
        eliminarProveedor($_POST['id_proveedor']);
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Proveedores</title>
</head>
<body>
    <h1>Administrar Proveedores</h1>
    <nav>
        <a href="index.php">Inicio</a>
    </nav>

    <h2>Crear Proveedor</h2>
    <form method="post">
        <input type="text" name="nombre_compania" placeholder="Nombre de la compañía" required>
        <input type="text" name="contacto" placeholder="Contacto" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="telefono" placeholder="Teléfono">
        <input type="text" name="direccion" placeholder="Dirección">
        <button type="submit" name="crear">Crear</button>
    </form>

    <h2>Actualizar Proveedor</h2>
    <form method="post">
        <input type="number" name="id_proveedor" placeholder="ID del proveedor" required>
        <input type="text" name="nombre_compania" placeholder="Nombre de la compañía" required>
        <input type="text" name="contacto" placeholder="Contacto" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="telefono" placeholder="Teléfono">
        <input type="text" name="direccion" placeholder="Dirección">
        <button type="submit" name="actualizar">Actualizar</button>
    </form>

    <h2>Eliminar Proveedor</h2>
    <form method="post">
        <input type="number" name="id_proveedor" placeholder="ID del proveedor" required>
        <button type="submit" name="eliminar">Eliminar</button>
    </form>

    <h2>Listar Proveedores</h2>
    <?php obtenerProveedores(); ?>
</body>
</html>