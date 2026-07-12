<?php
session_start();
require_once 'C:/xampp/htdocs/project/Axioma/backend/src/conexion.php';
$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "UPDATE clientes SET NOMBRE = ?, APELLIDOS = ?, email = ?, DIRECCION = ?, TELEFONO = ? WHERE ID_CLIENTE = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        $_POST['nombre'],
        $_POST['apellido'],
        $_POST['email'],
        $_POST['direccion'],
        $_POST['telefono'],
        $_POST['id']
    ]);
    header("Location: clientes.php");
    exit;
}

$stmt = $db->prepare("SELECT * FROM clientes WHERE ID_CLIENTE = ?");
$stmt->execute([$_GET['id']]);
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente - Axioma</title>
    <link rel="stylesheet" href="/project/Axioma/frontend/src/public/css/styles.css">
</head>
<body class="page-body">
    <div class="form-shell">
        <div class="form-card">
            <h2>Editar Cliente</h2>

            <form method="POST">
                <input type="hidden" name="id" value="<?php echo $cliente['ID_CLIENTE']; ?>">

                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre" value="<?php echo htmlspecialchars($cliente['NOMBRE'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label>Apellido</label>
                    <input type="text" name="apellido" value="<?php echo htmlspecialchars($cliente['APELLIDO'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($cliente['email'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label>Dirección</label>
                    <input type="text" name="direccion" value="<?php echo htmlspecialchars($cliente['DIRECCION'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" value="<?php echo htmlspecialchars($cliente['TELEFONO'] ?? ''); ?>">
                </div>

                <div class="button-row">
                    <a href="clientes.php" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>