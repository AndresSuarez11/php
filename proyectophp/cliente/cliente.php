<?php
include '../conexion.php';
session_start();

// Verificar si el usuario está autenticado y es un cliente
if (!isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'CLIENTE') {
    header("Location: ../login.php");
    exit();
}

// Obtener la lista de productos
$sql = "SELECT * FROM productos WHERE stock > 0";
$result = $conn->query($sql);

// Procesar la compra
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_producto = $_POST['id_producto'];
    $cantidad = $_POST['cantidad'];

    // Obtener el stock actual del producto
    $stmt = $conn->prepare("SELECT * FROM productos WHERE id_producto = ?");
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $producto = $stmt->get_result()->fetch_assoc();

    if ($producto['stock'] >= $cantidad) {
        $nuevo_stock = $producto['stock'] - $cantidad;

        // Actualizar el stock del producto
        $updateStmt = $conn->prepare("UPDATE productos SET stock = ? WHERE id_producto = ?");
        $updateStmt->bind_param("ii", $nuevo_stock, $id_producto);

        if ($updateStmt->execute()) {
            // Registrar la compra en el historial
            $insertStmt = $conn->prepare("INSERT INTO historial_compras (id_cliente, id_producto, cantidad) VALUES (?, ?, ?)");
            $insertStmt->bind_param("iii", $_SESSION['user_id'], $id_producto, $cantidad);
            $insertStmt->execute();

            echo "Compra realizada con éxito.";
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
    <title>Panel del Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="script" href="footer.js">
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
<div class="grid-item-1">
        <nav class="navbar navbar-light"  style="background-color: #e3f2fd; width: 100%; height: 100%;">
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
                    <li><a class="dropdown-item" href="../logout.php">Cerrar sesion</a></li>
                  </ul>
                </li>
        
                
          
            </ul>
        
            
        
        
          </div>
        </nav>

      </div>
        
    <h3>Comprar Productos</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Cantidad</th>
            <th>Acciones</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while($producto = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $producto['id_producto']; ?></td>
                <td><?php echo $producto['nombre']; ?></td>
                <td><?php echo $producto['descripcion']; ?></td>
                <td><?php echo $producto['precio']; ?></td>
                <td><?php echo $producto['stock']; ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                        <input type="number" name="cantidad" min="1" max="<?php echo $producto['stock']; ?>" required>
                        <input type="submit" value="Comprar">
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">No se encontraron productos disponibles</td>
            </tr>
        <?php endif; ?>
    </table>
    
</body>
</html>

<?php
$conn->close();
?>