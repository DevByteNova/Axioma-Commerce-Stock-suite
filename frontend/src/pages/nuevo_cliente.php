<?php
session_start();
require_once 'C:/xampp/htdocs/project/Axioma/backend/src/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->getConnection();

    $sql = "INSERT INTO clientes (NOMBRE, APELLIDOS, email, DIRECCION, TELEFONO) VALUES (?, ?, ?, ?, ?)";

    $stmt = $db->prepare($sql);

    if ($stmt->execute([
        $_POST['nombre'],
        $_POST['apellido'],
        $_POST['email'],
        $_POST['direccion'],
        $_POST['telefono']
    ])) {
        header("Location: clientes.php");
        exit;
    } else {
        $error = "Hubo un error al guardar al cliente.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Cliente - Axioma</title>
    <link rel="stylesheet" href="/project/Axioma/frontend/src/public/css/styles.css">
</head>
<body class="page-body">
    <div class="form-shell">
        <div class="form-card">
            <h2>Registrar Nuevo Cliente</h2>

            <?php if(isset($error)) echo "<p class='error-message'>$error</p>"; ?>

            <form method="post">
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre" required>
                </div>
                <div class="form-group">
                    <label>Apellido</label>
                    <input type="text" name="apellido" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Dirección</label>
                    <input type="text" name="direccion">
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono">
                </div>
                <div class="button-row">
                    <a href="clientes.php" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>