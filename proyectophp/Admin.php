<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        nav {
            margin-bottom: 20px;
        }
        nav a {
            margin-right: 10px;
            text-decoration: none;
            color: blue;
        }
    </style>
</head>
<body>
    <h1>Panel de Administración</h1>
    <nav>
        <a href="categorias.php">Categorías</a>
        <a href="indexClientes.php">Clientes</a>
        <a href="indexProductos.php">Productos</a>
        <a href="indexProveedores.php">Proveedores</a>
    </nav>
    <div id="contenido">
        <h2>Bienvenido al Panel de Administración</h2>
        <p>Seleccione una de las opciones del menú para administrar los datos.</p>
    </div>
</body>
</html>