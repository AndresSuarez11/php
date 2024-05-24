<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];

    $stmt = $conn->prepare("INSERT INTO categorias (nombre) VALUES (?)");
    if ($stmt->execute([$nombre])) {
        echo "Categoría creada con éxito.";
    } else {
        echo "Error al crear la categoría.";
    }
}
?>

<form method="post">
    Nombre: <input type="text" name="nombre" required>
    <input type="submit" value="Crear Categoría">
</form>