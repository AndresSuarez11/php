<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];

    $stmt = $conn->prepare("UPDATE proveedores SET nombre = ?, direccion = ? WHERE id_proveedor = ?");
    $stmt->bind_param("ssi", $nombre, $direccion, $id);

    if ($stmt->execute()) {
        header("Location: indexProveedores.php");
        exit();
    } else {
        echo "Error al actualizar el proveedor.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Proveedor</title>
</head>
<body>
    <h1>Actualizar Proveedor</h1>
    <?php
    // Obtener los datos del proveedor a actualizar
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM proveedores WHERE id_proveedor = $id";
        $result = $conn->query($sql);
        if ($result->num_rows == 1) {
            $proveedor = $result->fetch_assoc();
        } else {
            echo "Proveedor no encontrado.";
            exit();
        }
    } else {
        echo "ID de proveedor no proporcionado.";
        exit();
    }
    ?>
    <form method="post">
        <input type="hidden" name="id" value="<?php echo $proveedor['id_proveedor']; ?>">
        Nombre: <input type="text" name="nombre" value="<?php echo $proveedor['nombre']; ?>" required><br>
        Dirección: <input type="text" name="direccion" value="<?php echo $proveedor['direccion']; ?>" required><br>
        <input type="submit" value="Actualizar Proveedor">
    </form>
</body>
</html>