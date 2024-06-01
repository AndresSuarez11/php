<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar si las claves están presentes en el array POST
    $username = isset($_POST['username']) ? $_POST['username'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    // Asegúrate de que los campos del formulario están definidos
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT id, username, password, role FROM usuarios WHERE username = ?");
        $stmt->bind_param("s", $username); // "s" indica que el parámetro es de tipo cadena (string)
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            // Establece las variables de sesión
            $_SESSION['user_id'] = $user['id_usuario'];
            $_SESSION['user_role'] = $user['role'];

            // Redirige al usuario según su rol
            if ($user['role'] == 'CLIENTE') {
                header("Location: cliente/cliente.php");
            } elseif ($user['role'] == 'PROVEEDOR') {
                header("Location: proveedor/proveedor.php");
            } elseif ($user['role'] == 'VENDEDOR') {
                header("Location: vendedor/vendedor.php");
            } elseif ($user['role'] == 'ADMIN') {
                header("Location: ../proyectophp/admin.php");
            }
            exit();
        } else {
            echo "Credenciales incorrectas.";
        }
        $stmt->close();
    } else {
        echo "Por favor, complete todos los campos.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
</head>
<body>
    <h1>Iniciar Sesión</h1>
    <form method="post" action="login.php">
    <input type="text" name="username" placeholder="Nombre de usuario">
    <input type="password" name="password" placeholder="Contraseña">
    <input type="submit" value="Iniciar sesión">
</form>
    <a href="registro.php">Registrarse</a>
</body>
</html>