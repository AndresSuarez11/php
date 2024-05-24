<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'ADMIN') {
    header("Location: login.php");
    exit(); 
}

include 'conexion.php';

// Obtener la lista de clientes
$sql = "SELECT * FROM clientes";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $deleteSql = "DELETE FROM clientes WHERE id_cliente = ?";
        $stmt = $conn->prepare($deleteSql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo "Cliente eliminado con éxito.";
        } else {
            echo "Error al eliminar el cliente.";
        }
        // Refrescar la página para actualizar la lista
        header("Location: indexClientes.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Clientes</title>
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
    <h1>Lista de Clientes</h1>
    <a href="CrearClientes.php">Crear Cliente</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Acciones</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while($cliente = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $cliente['id_cliente']; ?></td>
                <td><?php echo $cliente['nombre']; ?></td>
                <td><?php echo $cliente['apellido']; ?></td>
                <td><?php echo $cliente['email']; ?></td>
                <td><?php echo $cliente['telefono']; ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $cliente['id_cliente']; ?>">
                        <input type="submit" name="delete" value="Eliminar">
                    </form>
                    <form method="get" action="ActualizarClientes.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $cliente['id_cliente']; ?>">
                        <input type="submit" value="Editar">
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No se encontraron clientes</td>
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