<?php
session_start();

if (!isset($_SESSION['user_id']) && $_SESSION['user_role'] !== 'ADMIN') {
    header("Location: ../login.php");
    exit(); 
}

include '../conexion.php';

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../style.css">
    <link rel="script" href="../footer.js">
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

<div class="grid-container">
      <div class="grid-item-1">
        <nav class="navbar navbar-light"  style="background-color: #e3f2fd; width: 100%; height: 100%;">
          <div class="container-fluid">
            <a class="navbar-brand" href="#">
              
              <h1>Panel de Administracion</h1>
            </a>
            <ul class="nav justify-content-end">  
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="../img/hat-and-glasses-summer-svgrepo-com.svg" height="80px" width="auto" alt="">
                  </a>
                  <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="../Admin.php">Admin</a></li>
                    <li><a class="dropdown-item" href="#">Another action</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../logout.php">Cerrar sesion</a></li>
                  </ul>
                </li>
        
                
          
            </ul>
        
            
        
        
          </div>
        </nav>

      </div>
      <div class="grid-container-menu">
        <div class="categoria" onclick="window.location.href='../categoria/indexCategorias.php'">
            <img src="../img/category-svgrepo-com.svg" alt="Categorias">
            <p>Categorias</p>
        </div>
        <div class="categoria" onclick="window.location.href='../cliente/indexClientes.php'">
            <img src="../img/person-svgrepo-com.svg" alt="Clientes">
            <p>Clientes</p>
        </div>
        <div class="categoria" onclick="window.location.href='../producto/indexProductos.php'">
            <img src="../img/products-svgrepo-com.svg" alt="Productos">
            <p>Productos</p>
        </div>
        <div class="categoria" onclick="window.location.href='../proveedor/indexProveedores.php'">
            <img src="../img/shopping-cart-free-15-svgrepo-com.svg" alt="Proveedores">
            <p>Proveedores</p>
        </div>
        
    </div>
    
    <!--Contenido-->
  <div clas="grid-item-contenido">

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
                <a href="../Admin.php">Inicio</a>
            </div>
      
  
  
  
  
  </div>





</div>





    
</body>
</html>

<?php
$conn->close();
?>