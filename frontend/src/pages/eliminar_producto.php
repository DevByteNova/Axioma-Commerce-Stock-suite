<?php
session_start();
require_once __DIR__ . '/../../../backend/src/conexion.php'; 
$db = (new Database())->getConnection();

if (isset($_GET['id'])) {
    $stmt = $db->prepare("DELETE FROM productos WHERE ID_PRODUCTO = ?");
    $stmt->execute([$_GET['id']]);
}

header("Location: ../../index.php?url=productos");
exit;