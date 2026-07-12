<?php
session_start();
require_once __DIR__ . '/../../../backend/src/conexion.php';
$db = (new Database())->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Insertar en Facturas
    $stmt1 = $db->prepare("INSERT INTO facturas (FECHA, ID_Cliente) VALUES (?, ?)");
    $stmt1->execute([$_POST['fecha'], $_POST['id_cliente']]);
    $id_factura = $db->lastInsertId();

    // 2. Insertar en Ventas
    $stmt2 = $db->prepare("INSERT INTO ventas (ID_FACTURA, ID_PRODUCTO, ID_VENDEDOR) VALUES (?, ?, ?)");
    $stmt2->execute([$id_factura, $_POST['id_producto'], $_SESSION['usuario_id']]);

    header("Location: ../../index.php?url=dashboard");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Venta - Axioma</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
    <?php require_once '../components/navbar.php'; ?>
    <div class="container">
        <h1>Registrar Nueva Venta</h1>
        <form method="POST">
            <label>Fecha:</label>
            <input type="date" name="fecha" required value="<?php echo date('Y-m-d'); ?>">

            <label>Cliente:</label>
            <select name="id_cliente" required>
                <?php
                $clientes = $db->query("SELECT ID_CLIENTE, NOMBRE FROM clientes"); // Asegúrate que tu tabla sea 'clientes'
                while ($c = $clientes->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='".$c['ID_CLIENTE']."'>".$c['NOMBRE']."</option>";
                }
                ?>
            </select>

            <label>Producto:</label>
            <select name="id_producto" required>
                <?php
                $prods = $db->query("SELECT ID_PRODUCTO, NOMBRE FROM productos");
                while ($p = $prods->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='".$p['ID_PRODUCTO']."'>".$p['NOMBRE']."</option>";
                }
                ?>
            </select>

            <button type="submit" class="btn-primary">Registrar Venta</button>
        </form>
    </div>
</body>
</html>