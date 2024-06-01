<?php
include '../conexion.php';
session_start();

require_once('../TCPDF-main/tcpdf.php');

// Crear una nueva instancia de TCPDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Establecer información del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Tu Tienda Online');
$pdf->SetTitle('Recibo de Compra');
$pdf->SetSubject('Recibo de Compra');
$pdf->SetKeywords('Compra, Recibo, Tienda Online');

// Establecer márgenes
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

// Agregar nueva página
$pdf->AddPage();

// Definir estilo de fuente y tamaño
$pdf->SetFont('helvetica', 'B', 16);

// Agregar título
$pdf->Cell(0, 10, 'Recibo de Compra', 0, 1, 'C');

// Espacio
$pdf->Ln(10);

// Obtener información de la compra
$id_cliente = $_SESSION['user_id'];
$sql = "SELECT p.id_producto, p.nombre, p.precio, dp.cantidad, p.stock 
        FROM productos p 
        JOIN detalles_pedido dp ON p.id_producto = dp.id_producto 
        WHERE dp.id_cliente = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;

// Mostrar productos comprados
while ($row = $result->fetch_assoc()) {
    $nombre_producto = $row['nombre'];
    $precio_producto = $row['precio'];
    $cantidad = $row['cantidad'];
    $subtotal = $precio_producto * $cantidad;
    $total += $subtotal;

    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, "Producto: $nombre_producto | Cantidad: $cantidad | Precio Unitario: $precio_producto | Subtotal: $subtotal", 0, 1);
}

// Espacio
$pdf->Ln(10);

// Mostrar total
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, "Total: $total", 0, 1);

// Salida del PDF
$pdf->Output('recibo_compra.pdf', 'D');


// Verificar si el usuario está autenticado y es un cliente
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'CLIENTE') {
    header("Location: ../login.php");
    exit();
}

// Obtener los productos en el carrito del usuario
$id_cliente = $_SESSION['user_id'];
$sql = "SELECT p.id_producto, p.nombre, p.precio, dp.cantidad, p.stock 
        FROM productos p 
        JOIN detalles_pedido dp ON p.id_producto = dp.id_producto 
        WHERE dp.id_cliente = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$result = $stmt->get_result();

// Iniciar una transacción
$conn->begin_transaction();

try {
    // Procesar cada producto en el carrito
    while ($row = $result->fetch_assoc()) {
        $id_producto = $row['id_producto'];
        $cantidad = $row['cantidad'];
        $nuevo_stock = $row['stock'] - $cantidad;

        if ($nuevo_stock < 0) {
            throw new Exception("Stock insuficiente para el producto: " . $row['nombre']);
        }

        // Actualizar el stock del producto
        $updateStmt = $conn->prepare("UPDATE productos SET stock = ? WHERE id_producto = ?");
        $updateStmt->bind_param("ii", $nuevo_stock, $id_producto);
        $updateStmt->execute();

        // Registrar la compra en la tabla de pedidos
        $insertStmt = $conn->prepare("INSERT INTO pedidos (id_cliente, id_producto, cantidad, fecha) VALUES (?, ?, ?, NOW())");
        $insertStmt->bind_param("iii", $id_cliente, $id_producto, $cantidad);
        $insertStmt->execute();
    }

    // Vaciar el carrito
    $deleteStmt = $conn->prepare("DELETE FROM detalles_pedido WHERE id_cliente = ?");
    $deleteStmt->bind_param("i", $id_cliente);
    $deleteStmt->execute();

    // Confirmar la transacción
    $conn->commit();

    $mensaje = "Compra realizada con éxito.";
} catch (Exception $e) {
    // Revertir la transacción en caso de error
    $conn->rollback();
    $mensaje = "Error al realizar la compra: " . $e->getMessage();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
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
    <h3>Checkout</h3>
    <p><?php echo $mensaje; ?></p>
    <?php if ($mensaje === "Compra realizada con éxito."): ?>
        <form method="post" action="generar_pdf.php">
            <button type="submit" class="btn btn-primary">Descargar Recibo</button>
        </form>
    <?php endif; ?>
    <a href="../cliente/cliente.php" class="btn btn-primary">Volver a la tienda</a>
</div>
</body>
</html>