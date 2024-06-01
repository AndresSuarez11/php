<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];
    $role = $_POST['role'];
    $nombre = $_POST['nombre'];
    $apellido = isset($_POST['apellido']) ? $_POST['apellido'] : null;
    $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : null;
    $nombre_compania = isset($_POST['nombre_compania']) ? $_POST['nombre_compania'] : null;
    $direccion = isset($_POST['direccion']) ? $_POST['direccion'] : null;
    $contacto = isset($_POST['contacto']) ? $_POST['contacto'] : null;

    $conn->begin_transaction();

    try {
        // Inserta en la tabla usuarios
        $stmt = $conn->prepare("INSERT INTO usuarios (username, password, email, role, nombre) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $password, $email, $role, $nombre);
        $stmt->execute();
        $stmt->close();

        // Inserta en la tabla correspondiente según el rol
        if ($role == 'CLIENTE') {
            $stmt = $conn->prepare("INSERT INTO clientes (nombre, apellido, email, telefono, id_usuario) VALUES (?, ?, ?, ?, LAST_INSERT_ID())");
            $stmt->bind_param("ssss", $nombre, $apellido, $email, $telefono);
            $stmt->execute();
            $stmt->close();
        } elseif ($role == 'PROVEEDOR') {
            $stmt = $conn->prepare("INSERT INTO proveedores (nombre_compania, contacto, email, telefono, direccion, id_usuario) VALUES (?, ?, ?, ?, ?, LAST_INSERT_ID())");
            $stmt->bind_param("sssss", $nombre_compania, $contacto, $email, $telefono, $direccion);
            $stmt->execute();
            $stmt->close();
        } elseif ($role == 'VENDEDOR') {
            $stmt = $conn->prepare("INSERT INTO vendedores (nombre, apellido, email, telefono, id_usuario) VALUES (?, ?, ?, ?, LAST_INSERT_ID())");
            $stmt->bind_param("ssss", $nombre, $apellido, $email, $telefono);
            $stmt->execute();
            $stmt->close();
        }

        // Confirma la transacción
        $conn->commit();
        echo "Usuario registrado con éxito.";

    } catch (Exception $e) {
        // Si ocurre un error, revierte la transacción
        $conn->rollback();
        echo "Error al registrar el usuario: " . $e->getMessage();
    }

    $conn->close();
} else {

}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <script>
        function updateForm() {
            var role = document.getElementById('role').value;
            var clienteFields = document.getElementById('clienteFields');
            var proveedorFields = document.getElementById('proveedorFields');
            var vendedorFields = document.getElementById('vendedorFields');

            clienteFields.style.display = 'none';
            proveedorFields.style.display = 'none';
            vendedorFields.style.display = 'none';

            if (role === 'CLIENTE') {
                clienteFields.style.display = 'block';
            } else if (role === 'PROVEEDOR') {
                proveedorFields.style.display = 'block';
            } else if (role === 'VENDEDOR') {
                vendedorFields.style.display = 'block';
            }
        }
    </script>
</head>
<body>
    <h1>Registro de Usuario</h1>
    <form method="post">
        Usuario: <input type="text" name="username" required><br>
        Contraseña: <input type="password" name="password" required><br>
        Email: <input type="email" name="email" required><br>
        Nombre: <input type="text" name="nombre" required><br>
        
        Rol: 
        <select name="role" id="role" onchange="updateForm()" required>
            <option value="ADMIN">ADMIN</option>
            <option value="CLIENTE">CLIENTE</option>
            <option value="VENDEDOR">VENDEDOR</option>
            <option value="PROVEEDOR">PROVEEDOR</option>
        </select><br>

        <div id="clienteFields" style="display:none;">
            Apellido: <input type="text" name="apellido"><br>
            Teléfono: <input type="text" name="telefono"><br>
        </div>

        <div id="proveedorFields" style="display:none;">
            Nombre de Compañía: <input type="text" name="nombre_compania"><br>
            Contacto: <input type="text" name="contacto"><br>
            Teléfono: <input type="text" name="telefono"><br>
            Dirección: <input type="text" name="direccion"><br>
        </div>

        <div id="vendedorFields" style="display:none;">
            Apellido: <input type="text" name="apellido"><br>
            Teléfono: <input type="text" name="telefono"><br>
        </div>

        <input type="submit" value="Registrar">
    </form>
    <a href="login.php">Iniciar Sesión</a>
</body>
</html>