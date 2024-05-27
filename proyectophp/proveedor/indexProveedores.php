<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'ADMIN') {
    header("Location: ../login.php");
    exit(); 
}
include '../conexion.php';

// Obtener la lista de proveedores
$sql = "SELECT * FROM proveedores";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $deleteSql = "DELETE FROM proveedores WHERE id_proveedor = ?";
        $stmt = $conn->prepare($deleteSql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo "Proveedor eliminado con éxito.";
        } else {
            echo "Error al eliminar el proveedor.";
        }
        // Refrescar la página para actualizar la lista
        header("Location: indexProveedores.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Proveedores</title>
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
    <h1>Lista de Proveedores</h1>
    <a href="CrearProveedores.php">Crear Proveedor</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Telefono</th>
            <th>Email</th>
            <th>Acciones</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while($proveedor = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $proveedor['id_proveedor']; ?></td>
                <td><?php echo $proveedor['nombre_compania']; ?></td>
                <td><?php echo $proveedor['direccion']; ?></td>
                <td><?php echo $proveedor['telefono']; ?></td>
                <td><?php echo $proveedor['email']; ?></td>
            
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $proveedor['id_proveedor']; ?>">
                        <input type="submit" name="delete" value="Eliminar">
                    </form>
                    <form method="get" action="ActualizarProveedor.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $proveedor['id_proveedor']; ?>">
                        <input type="submit" value="Editar">
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No se encontraron proveedores</td>
            </tr>
        <?php endif; ?>
    </table>
    <div>
        <a href="../Admin.php">Inicio</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>