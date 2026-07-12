<?php
session_start();
require_once __DIR__ . '/../../../backend/src/models/conexion.php';
$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "INSERT INTO productos (NOMBRE, DESCRIPCION, PRECIO) VALUES (?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->execute([$_POST['nombre'], $_POST['descripcion'], $_POST['precio']]);
    header("Location: ../../index.php?url=productos");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Producto</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
    <?php require_once '../components/navbar.php'; ?>
    <div class="container">
        <h1>Agregar Nuevo Producto</h1>
        <form method="POST">
            <input type="text" name="nombre" placeholder="Nombre del producto" required>
            <input type="text" name="descripcion" placeholder="Descripción">
            <input type="number" step="0.01" name="precio" placeholder="Precio" required>
            <button type="submit" class="btn btn-primary">Guardar Producto</button>
        </form>
    </div>
</body>
</html>