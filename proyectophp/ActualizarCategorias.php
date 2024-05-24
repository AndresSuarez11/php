<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM categorias WHERE id_categoria = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $categoria = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];

    $stmt = $conn->prepare("UPDATE categorias SET nombre = ? WHERE id_categoria = ?");
    if ($stmt->execute([$nombre, $id])) {
        echo "Categoría actualizada con éxito.";
        header("Location: indexCategorias.php");
        exit();
    } else {
        echo "Error al actualizar la categoría.";
    }
}
?>

<?php if (isset($categoria)): ?>
<form method="post">
    <input type="hidden" name="id" value="<?php echo $categoria['id_categoria']; ?>">
    Nombre: <input type="text" name="nombre" value="<?php echo $categoria['nombre']; ?>" required>
    <input type="submit" value="Actualizar Categoría">
</form>
<?php else: ?>
<p>Categoría no encontrada.</p>
<?php endif; ?>