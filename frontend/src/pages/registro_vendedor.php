<?php
// Solo permitir si el usuario es administrador (opcional pero recomendado)
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'Administrador') {
    // Si no es admin, redirigir al login o dashboard
    header('Location: index.php?url=login');
    exit;
}

require_once __DIR__ . '/../../../backend/src/conexion.php';
$db = (new Database())->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Seguridad ante todo
    $rol = $_POST['rol'];

    $sql = "INSERT INTO usuarios (nombre, usuario, password, rol) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    
    if ($stmt->execute([$nombre, $usuario, $password, $rol])) {
        echo "<script>alert('Usuario registrado exitosamente');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Usuario - Axioma</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
    <?php require_once '../components/navbar.php'; ?>
    <div class="container">
        <h1>Registrar Nuevo Usuario</h1>
        <form method="POST">
            <label>Nombre Completo:</label>
            <input type="text" name="nombre" required>
            
            <label>Nombre de Usuario:</label>
            <input type="text" name="usuario" required>
            
            <label>Contraseña:</label>
            <input type="password" name="password" required>
            
            <label>Rol:</label>
            <select name="rol">
                <option value="Vendedor">Vendedor</option>
                <option value="Administrador">Administrador</option>
            </select>
            
            <button type="submit" class="btn btn-primary">Registrar Usuario</button>
        </form>
    </div>
</body>
</html>