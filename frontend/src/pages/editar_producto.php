<?php
session_start();
require_once __DIR__ . '/../../../backend/src/conexion.php';
$db = (new Database())->getConnection();

$id = $_GET['id'] ?? null;

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "UPDATE productos SET NOMBRE = ?, DESCRIPCION = ?, PRECIO = ? WHERE ID_PRODUCTO = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$_POST['nombre'], $_POST['descripcion'], $_POST['precio'], $id]);
    header("Location: ../../index.php?url=productos");
    exit;
}

// Cargar datos actuales
$stmt = $db->prepare("SELECT * FROM productos WHERE ID_PRODUCTO = ?");
$stmt->execute([$id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
    <?php require_once '../components/navbar.php'; ?>
    <div class="container">
        <h1>Editar Producto</h1>
        <form method="POST">
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($producto['NOMBRE']); ?>" required>
            <input type="text" name="descripcion" value="<?php echo htmlspecialchars($producto['DESCRIPCION']); ?>">
            <input type="number" step="0.01" name="precio" value="<?php echo $producto['PRECIO']; ?>" required>
            <button type="submit" class="btn-save">Guardar Cambios</button>
            <a href="../../index.php?url=productos">Cancelar</a>
        </form>
    </div>
</body>
</html>