<?php

include 'conexion.php';

// Función para crear una categoría
function crearCategoria($nombre) {
    global $conn;
    $sql = "INSERT INTO categorias (nombre) VALUES ('$nombre')";
    if ($conn->query($sql) === TRUE) {
        echo "Categoría creada exitosamente";
    } else {
        echo "Error al crear la categoría: " . $conn->error;
    }
}

// Función para obtener todas las categorías
function obtenerCategorias() {
    global $conn;
    $sql = "SELECT * FROM categorias";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "ID: " . $row["id_categoria"]. " - Nombre: " . $row["nombre"]. "<br>";
        }
    } else {
        echo "No se encontraron categorías";
    }
}

// Función para actualizar una categoría
function actualizarCategoria($id, $nombre) {
    global $conn;
    $sql = "UPDATE categorias SET nombre='$nombre' WHERE id_categoria=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Categoría actualizada exitosamente";
    } else {
        echo "Error al actualizar la categoría: " . $conn->error;
    }
}

// Función para eliminar una categoría
function eliminarCategoria($id) {
    global $conn;
    $sql = "DELETE FROM categorias WHERE id_categoria=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Categoría eliminada exitosamente";
    } else {
        echo "Error al eliminar la categoría: " . $conn->error;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['crear'])) {
        crearCategoria($_POST['nombre']);
    } elseif (isset($_POST['actualizar'])) {
        actualizarCategoria($_POST['id_categoria'], $_POST['nombre']);
    } elseif (isset($_POST['eliminar'])) {
        eliminarCategoria($_POST['id_categoria']);
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Categorías</title>
</head>
<body>
    <h1>Administrar Categorías</h1>
    <nav>
        <a href="index.php">Inicio</a>
    </nav>

    <h2>Crear Categoría</h2>
    <form method="post">
        <input type="text" name="nombre" placeholder="Nombre de la categoría" required>
        <button type="submit" name="crear">Crear</button>
    </form>

    <h2>Actualizar Categoría</h2>
    <form method="post">
        <input type="number" name="id_categoria" placeholder="ID de la categoría" required>
        <input type="text" name="nombre" placeholder="Nuevo nombre de la categoría" required>
        <button type="submit" name="actualizar">Actualizar</button>
    </form>

    <h2>Eliminar Categoría</h2>
    <form method="post">
        <input type="number" name="id_categoria" placeholder="ID de la categoría" required>
        <button type="submit" name="eliminar">Eliminar</button>
    </form>

    <h2>Listar Categorías</h2>
    <?php obtenerCategorias(); ?>
</body>
</html>