<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO usuarios (username, password, email, role) VALUES (?, ?, ?, ?)");
    if ($stmt->bind_param("ssss", $username, $password, $email, $role) && $stmt->execute()) {
        echo "Usuario registrado con éxito.";
    } else {
        echo "Error al registrar el usuario.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
</head>
<body>
    <h1>Registro de Usuario</h1>
    <form method="post">
        Usuario: <input type="text" name="username" required><br>
        Contraseña: <input type="password" name="password" required><br>
        Email: <input type="email" name="email" required><br>
        Rol: 
        <select name="role" required>
            <option value="ADMIN">ADMIN</option>
            <option value="CLIENTE">CLIENTE</option>
            <option value="VENDEDOR">VENDEDOR</option>
            <option value="PROVEEDOR">PROVEEDOR</option>
        </select><br>
        <input type="submit" value="Registrar">
    </form>
    <a href="login.php">Iniciar Sesión</a>
</body>
</html>