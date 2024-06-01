<?php
include '../conexion.php';
session_start();

$result = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar si las credenciales están presentes en el formulario
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Preparar la consulta para obtener el cliente por su nombre de usuario
        $stmt = $conn->prepare("SELECT id_cliente, nombre, apellido, email, telefono FROM clientes WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if (!$cliente) {
            echo "Cliente no encontrado.";
            exit();
        }

        // Obtener la lista de productos
        $sql = "SELECT id_producto, nombre, imagen FROM productos WHERE stock > 0";
        $result = $conn->query($sql);
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
</head>
<body>
<div class="grid-item-1">
    <nav class="navbar navbar-light" style="background-color: #e3f2fd; width: 100%; height: 100%;">
        <div class="container-fluid">
            <a class="navbar-brand" href="cliente.php">
                <h1>Panel Clientes</h1>
            </a>
            <ul class="nav justify-content-end">  
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="../img/person-svgrepo-com.svg" height="80px" width="auto" alt="">
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="cliente.php">Cliente</a></li>
                        <li><a class="dropdown-item" href="../carrito/ver_carrito.php">Ver Carrito</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="../logout.php">Cerrar sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</div>
    
<h3>Comprar Productos</h3>
<table>
    <tr>
        <th>Nombre</th>
        <th>Imagen</th>
        <th>Acciones</th>
    </tr>
    <?php if ($result !== null && $result->num_rows > 0): ?>
    <?php while($producto = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $producto['nombre']; ?></td>
            <td><img src="<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>"></td>
            <td>
                <a href="../producto/detalles_producto.php?id=<?php echo $producto['id_producto'];?>" class="btn btn-primary">Comprar</a>
            </td>
        </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr>
        <td colspan="3">No se encontraron productos disponibles</td>
    </tr>
<?php endif; ?>
</table>

</body>
</html>

<?php
$conn->close();
?>