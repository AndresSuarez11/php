<?php
include '../conexion.php';
session_start();

// Verificar si el usuario está autenticado y es un cliente
if (!isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'CLIENTE') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id_producto']) && isset($_POST['accion'])) {
        $id_producto = $_POST['id_producto'];
        if ($_POST['accion'] === 'eliminar') {
            unset($_SESSION['carrito'][$id_producto]);
        }
    }
}

$productosEnCarrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
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
    <h1>Carrito de Compras</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Precio Total</th>
            <th>Acciones</th>
        </tr>
        <?php if (!empty($productosEnCarrito)): ?>
            <?php
            $total = 0;
            foreach ($productosEnCarrito as $id_producto => $cantidad):
                $sql = "SELECT * FROM productos WHERE id_producto = $id_producto";
                $result = $conn->query($sql);
                $producto = $result->fetch_assoc();
                $precioTotal = $producto['precio'] * $cantidad;
                $total += $precioTotal;
            ?>
            <tr>
                <td><?php echo $producto['id_producto']; ?></td>
                <td><?php echo $producto['nombre']; ?></td>
                <td><?php echo $cantidad; ?></td>
                <td><?php echo $producto['precio']; ?></td>
                <td><?php echo $precioTotal; ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                        <input type="submit" name="accion" value="eliminar">
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="4">Total</td>
                <td><?php echo $total; ?></td>
                <td>
                    <form method="post" action="../compras/finalizarCompra.php">
                        <input type="submit" value="Finalizar Compra">
                    </form>
                </td>
            </tr>
        <?php else: ?>
            <tr>
                <td colspan="6">No hay productos en el carrito</td>
            </tr>
        <?php endif; ?>
    </table>
    <div>
        <a href="../cliente/cliente.php">Seguir Comprando</a>
        <a href="../compras/historialCompras.php">Historial de Compras</a>
        <a href="../logout.php">Cerrar Sesión</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>