<?php
session_start();
if (!isset($_SESSION['user_id']) && $_SESSION['role'] !== 'ADMIN') {
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="script" href="footer.js">
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
                    <img src="img/hat-and-glasses-summer-svgrepo-com.svg" height="80px" width="auto" alt="">
                  </a>
                  <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="Admin.php">Admin</a></li>
                    <li><a class="dropdown-item" href="#">Another action</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="logout.php">Cerrar sesion</a></li>
                  </ul>
                </li>
        
                
          
            </ul>
        
            
        
        
          </div>
        </nav>

      </div>
      <div class="grid-container-menu">
        <div class="categoria" onclick="window.location.href='categoria/indexCategorias.php'">
            <img src="img/category-svgrepo-com.svg" alt="Categorias">
            <p>Categorias</p>
        </div>
        <div class="categoria" onclick="window.location.href='cliente/indexClientes.php'">
            <img src="img/person-svgrepo-com.svg" alt="Clientes">
            <p>Clientes</p>
        </div>
        <div class="categoria" onclick="window.location.href='producto/indexProductos.php'">
            <img src="img/products-svgrepo-com.svg" alt="Productos">
            <p>Productos</p>
        </div>
        <div class="categoria" onclick="window.location.href='proveedor/indexProveedores.php'">
            <img src="img/shopping-cart-free-15-svgrepo-com.svg" alt="Proveedores">
            <p>Proveedores</p>
        </div>
        
    </div>
    
    <!--Contenido-->
  <div clas="grid-item-contenido">


      
  
  
  
  
  </div>





</div>














    
</body>


<footer>
        <div class="footer-container">
            <div class="footer-section about">
                <h2>Sobre Nosotros</h2>
                <p>Somos una empresa dedicada a proporcionar soluciones innovadoras para el mercado moderno. Nuestro objetivo es mejorar la vida de nuestros clientes a través de productos de calidad y un excelente servicio al cliente.</p>
            </div>
            <div class="footer-section links">
                <h2>Enlaces Rápidos</h2>
                <ul>
                    <li><a href="#">Inicio</a></li>
                    <li><a href="#">Servicios</a></li>
                    <li><a href="#">Sobre Nosotros</a></li>
                    <li><a href="#">Contacto</a></li>
                </ul>
            </div>
            <div class="footer-section contact">
                <h2>Contacto</h2>
                <ul>
                    <li>Email: info@ejemplo.com</li>
                    <li>Teléfono: +34 123 456 789</li>
                    <li>Dirección: Calle Falsa 123, Ciudad, País</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024. Todos los derechos reservados.</p>
        </div>
    </footer>
</html>