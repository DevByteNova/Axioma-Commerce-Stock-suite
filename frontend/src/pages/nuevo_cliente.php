<?php
session_start();
require_once 'C:/xampp/htdocs/project/Axioma/backend/src/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    
    // 1. Consulta SQL corregida según tus columnas: NOMBRE, APELLIDOS, email, DIRECCION, TELEFONO
    $sql = "INSERT INTO clientes (NOMBRE, APELLIDOS, email, DIRECCION, TELEFONO) VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $db->prepare($sql);
    
    // 2. Ejecutamos una sola vez pasando los 5 valores correspondientes a los 5 signos (?)
    if ($stmt->execute([
        $_POST['nombre'], 
        $_POST['apellido'], 
        $_POST['email'], 
        $_POST['direccion'], 
        $_POST['telefono']
    ])) {
        // Éxito: Redirigir a la lista
        header("Location: clientes.php");    
        exit;
    } else {
        // Error: Capturar el error si ocurre
        $error = "Hubo un error al guardar al cliente.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-sm border border-gray-200">
        <h2 class="text-xl font-bold mb-6">Registrar Nuevo Cliente</h2>

        <?php if(isset($error)) echo "<p class='text-red-500 mb-4'>$error</p>"; ?>

        <form method="post">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" name="nombre" required class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Apellido</label>
                <input type="text" name="apellido" required class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" required class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="mb-4">
                 <label class="block mb-2 font-bold">Dirección</label>
                <input type="text" name="direccion" class="w-full px-4 py-2 border rounded-lg">
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Teléfono</label>
                <input type="text" name="telefono" class="w-full px-4 py-2 border rounded-lg">
            </div>
            <div class="flex gap-4">
                <a href="clientes.php" class="w-full text-center py-2 border rounded-lg hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg font-bold hover:bg-blue-700">Guardar</button>
        </form>
    </div>
</body>
</html>