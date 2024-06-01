<?php
include '../conexion.php';
session_start();

// Verificar si el usuario está autenticado y es un cliente
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'CLIENTE') {
    header("Location: ../login.php");
    exit();
}

// Obtener los productos en el carrito del usuario
$id_cliente = $_SESSION['user_id'];
$sql = "SELECT p.id_producto, p.nombre, p.precio, dp.cantidad 
        FROM productos p 
        JOIN detalles_pedido dp ON p.id_producto = dp.id_producto 
        WHERE dp.id_cliente = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="grid-item-1">
    <nav class="navbar navbar-light" style="background-color: #e3f2fd; width: 100%; height: 100%;">
        <div class="container-fluid">
            <a class="navbar-brand" href="../cliente/cliente.php">
                <h1>Panel Clientes</h1>
            </a>
            <ul class="nav justify-content-end">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="../img/person-svgrepo-com.svg" height="80px" width="auto" alt="">
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="../cliente/cliente.php">Cliente</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="../logout.php">Cerrar sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</div>
<div class="container">
    <h3>Carrito de Compras</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_general = 0;
            while ($row = $result->fetch_assoc()) {
                $total = $row['precio'] * $row['cantidad'];
                $total_general += $total;
                echo "<tr>
                        <td>{$row['nombre']}</td>
                        <td>{$row['precio']}</td>
                        <td>{$row['cantidad']}</td>
                        <td>{$total}</td>
                    </tr>";
            }
            ?>
            <tr>
                <td colspan="3" class="text-end"><strong>Total General</strong></td>
                <td><strong><?php echo $total_general; ?></strong></td>
            </tr>
        </tbody>
    </table>
    <a href="checkout.php" class="btn btn-success">Finalizar Compra</a>
</div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>