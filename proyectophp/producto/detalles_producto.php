<?php
include '../conexion.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Obtener el ID del cliente desde la sesión
$id_cliente = $_SESSION['user_id'];

// Línea de depuración
error_log("ID del cliente en sesión: " . $id_cliente);

// Verificar que el cliente exista en la base de datos
$clienteStmt = $conn->prepare("SELECT id_cliente FROM clientes WHERE id_cliente = ?");
$clienteStmt->bind_param("i", $id_cliente);
$clienteStmt->execute();
$cliente = $clienteStmt->get_result()->fetch_assoc();

if (!$cliente) {
    echo "Cliente no encontrado.";
    exit();
}

// Obtener el ID del producto desde la URL
$id_producto = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Obtener los detalles del producto
$stmt = $conn->prepare("SELECT * FROM productos WHERE id_producto = ?");
$stmt->bind_param("i", $id_producto);
$stmt->execute();
$producto = $stmt->get_result()->fetch_assoc();

if (!$producto) {
    echo "Producto no encontrado.";
    exit();
}

// Procesar la compra
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cantidad = $_POST['cantidad'];

    if ($producto['stock'] >= $cantidad) {
        $nuevo_stock = $producto['stock'] - $cantidad;

        // Actualizar el stock del producto
        $updateStmt = $conn->prepare("UPDATE productos SET stock = ? WHERE id_producto = ?");
        $updateStmt->bind_param("ii", $nuevo_stock, $id_producto);

        if ($updateStmt->execute()) {
            // Registrar el pedido
            $pedidoStmt = $conn->prepare("INSERT INTO pedidos (id_cliente, fecha) VALUES (?, NOW())");
            $pedidoStmt->bind_param("i", $id_cliente);

            if ($pedidoStmt->execute()) {
                $id_pedido = $conn->insert_id;

                // Insertar el detalle del pedido
                $detalleStmt = $conn->prepare("INSERT INTO detalles_pedido (id_pedido, id_producto, cantidad) VALUES (?, ?, ?)");
                $detalleStmt->bind_param("iii", $id_pedido, $id_producto, $cantidad);
                $detalleStmt->execute();

                echo "Compra realizada con éxito.";
            } else {
                echo "Error al registrar el pedido.";
            }
        } else {
            echo "Error al actualizar el stock.";
        }
    } else {
        echo "Stock insuficiente para la cantidad solicitada.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="grid-item-1">
    <nav class="navbar navbar-light" style="background-color: #e3f2fd; width: 100%; height: 100%;">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <h1>Panel Clientes</h1>
            </a>
            <ul class="nav justify-content-end">  
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="../img/person-svgrepo-com.svg" height="80px" width="auto" alt="">
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="cliente.php">Cliente</a></li>
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
    <h3>Detalles del Producto</h3>
    <div class="row">
        <div class="col-md-6">
            <img src="<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>" class="img-fluid">
        </div>
        <div class="col-md-6">
            <h4><?php echo $producto['nombre']; ?></h4>
            <p><?php echo $producto['descripcion']; ?></p>
            <p><strong>Precio:</strong> <?php echo $producto['precio']; ?></p>
            <p><strong>Stock:</strong> <?php echo $producto['stock']; ?></p>
            <form method="POST" action="detalles_producto.php?id=<?php echo $id_producto; ?>">
                <div class="mb-3">
                    <label for="cantidad" class="form-label">Cantidad</label>
                    <input type="number" id="cantidad" name="cantidad" class="form-control" min="1" max="<?php echo $producto['stock']; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Comprar</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
<?php
$conn->close();
?>