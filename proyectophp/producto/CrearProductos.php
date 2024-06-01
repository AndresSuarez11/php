<?php
include '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $id_categoria = $_POST['id_categoria'];
    $id_proveedor = $_POST['id_proveedor'];

    // Verificar si el proveedor existe
    $stmtProveedor = $conn->prepare("SELECT * FROM proveedores WHERE id_proveedor = ?");
    $stmtProveedor->bind_param("i", $id_proveedor);
    $stmtProveedor->execute();
    $resultProveedor = $stmtProveedor->get_result();

    if ($resultProveedor->num_rows > 0) {
        // Manejar la subida de la imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
            $imagen = $_FILES['imagen'];
            $imagenNombre = $imagen['name'];
            $imagenTmpNombre = $imagen['tmp_name'];
            $imagenSize = $imagen['size'];
            $imagenError = $imagen['error'];
            $imagenTipo = $imagen['type'];

            // Extensiones permitidas
            $extensionesPermitidas = array('jpg', 'jpeg', 'png', 'gif');
            $imagenExt = explode('.', $imagenNombre);
            $imagenExt = strtolower(end($imagenExt));

            if (in_array($imagenExt, $extensionesPermitidas)) {
                if ($imagenError === 0) {
                    if ($imagenSize < 5000000) { // 5MB máximo
                        $imagenNombreNuevo = uniqid('', true) . "." . $imagenExt;
                        $imagenDestino = '../uploads/' . $imagenNombreNuevo;

                        // Verificar si la ruta ../uploads/ es correcta y el directorio existe
                        $uploadDir = '../uploads/';
                        if (!file_exists($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }

                        if (move_uploaded_file($imagenTmpNombre, $imagenDestino)) {
                            // Insertar datos del producto en la base de datos, incluyendo la ruta de la imagen
                            $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, stock, id_categoria, id_proveedor, imagen) VALUES (?, ?, ?, ?, ?, ?, ?)");
                            if ($stmt->execute([$nombre, $descripcion, $precio, $stock, $id_categoria, $id_proveedor, $imagenDestino])) {
                                echo "Producto creado con éxito.";
                            } else {
                                echo "Error al crear el producto.";
                            }
                        } else {
                            echo "Error al mover la imagen.";
                        }
                    } else {
                        echo "El archivo es demasiado grande.";
                    }
                } else {
                    echo "Error al subir la imagen.";
                }
            } else {
                echo "Tipo de archivo no permitido.";
            }
        } else {
            echo "No se subió ninguna imagen.";
        }
    } else {
        echo "El proveedor no existe.";
    }
}

// Obtener categorías para el menú desplegable
$sqlCategorias = "SELECT * FROM categorias";
$resultCategorias = $conn->query($sqlCategorias);

// Obtener proveedores para el menú desplegable
$sqlProveedores = "SELECT * FROM proveedores";
$resultProveedores = $conn->query($sqlProveedores);
?>

<form method="post" enctype="multipart/form-data">
    Nombre: <input type="text" name="nombre" required><br>
    Descripción: <input type="text" name="descripcion" required><br>
    Precio: <input type="text" name="precio" required><br>
    Stock: <input type="number" name="stock" required><br>
    Categoría:
    <select name="id_categoria" required>
        <?php while($categoria = $resultCategorias->fetch_assoc()): ?>
            <option value="<?php echo $categoria['id_categoria']; ?>"><?php echo $categoria['nombre']; ?></option>
        <?php endwhile; ?>
    </select><br>
    Proveedor:
    <select name="id_proveedor" required>
        <?php while($proveedor = $resultProveedores->fetch_assoc()): ?>
            <option value="<?php echo $proveedor['id_proveedor']; ?>"><?php echo $proveedor['nombre_compania']; ?></option>
        <?php endwhile; ?>
    </select><br>
    Imagen: <input type="file" name="imagen" required><br>
    <input type="submit" value="Crear Producto">
    <div>
        <a href="../Admin.php">Inicio</a>
    </div>
</form>
