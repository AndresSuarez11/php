<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'conexion.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verificar las credenciales del usuario
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Inicio de sesión exitoso
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role']; 
            $_SESSION['user_nombre'] = $user['username'];

            // Redirigir al usuario según su tipo
            switch ($user['role']) { 
                case 'ADMIN':
                    header("Location: Admin.php");
                    break;
                case 'CLIENTE':
                    header("Location: cliente/cliente.php");
                    break;
                case 'VENDEDOR':
                    header("Location: vendedor/vendedor.php");
                    break;
                case 'PROVEEDOR':
                    header("Location: proveedor/proveedor.php");
                    break;
                default:
                    echo "Tipo de usuario no reconocido.";
                    break;
            }
            exit();
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Correo electrónico no encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
</head>
<body>
    <h1>Iniciar Sesión</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="post">
        Email: <input type="email" name="email" required><br>
        Contraseña: <input type="password" name="password" required><br>
        <input type="submit" value="Iniciar Sesión">
    </form>
    <p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
</body>
</html>