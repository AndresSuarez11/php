<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tienda";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Opcional: establecer el conjunto de caracteres
$conn->set_charset("utf8");

// Opcional: mostrar un mensaje de éxito
// echo "Conexión exitosa";
?>