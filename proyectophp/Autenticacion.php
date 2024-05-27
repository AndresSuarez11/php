<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        switch($user['role']) {
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
        }
    } else {
        echo "Usuario o contraseña incorrectos.";
    }
}
?>