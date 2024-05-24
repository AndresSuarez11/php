<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'ADMIN') {
    header("Location: login.php");
    exit(); 
}
include 'conexion.php';

// Obtener la lista de categorías
$sql = "SELECT * FROM categorias";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $deleteSql = "DELETE FROM categorias WHERE id_categoria = ?";
        $stmt = $conn->prepare($deleteSql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo "Categoría eliminada con éxito.";
        } else {
            echo "Error al eliminar la categoría.";
        }
        // Refrescar la página para actualizar la lista
        header("Location: indexCategorias.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Categorías</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Lista de Categorías</h1>
    <a href="CrearCategorias.php">Crear Categoría</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while($categoria = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $categoria['id_categoria']; ?></td>
                <td><?php echo $categoria['nombre']; ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $categoria['id_categoria']; ?>">
                        <input type="submit" name="delete" value="Eliminar">
                    </form>
                    <form method="get" action="ActualizarCategorias.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $categoria['id_categoria']; ?>">
                        <input type="submit" value="Editar">
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">No se encontraron categorías</td>
            </tr>
        <?php endif; ?>
    </table>
    <div>
        <a href="index.php">Inicio</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>