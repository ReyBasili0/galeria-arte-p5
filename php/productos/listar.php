<?php
session_start();
require_once '../database.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Obtener todos los productos
    $query = "SELECT id, titulo, descripcion, categoria, tecnica, precio, imagen, usuario_id, DATE_FORMAT(fecha_creacion, '%Y-%m-%d') as fecha FROM productos ORDER BY fecha_creacion DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'data' => $productos,
        'count' => count($productos)
    ]);
    
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error al obtener productos: ' . $e->getMessage()
    ]);
}
?>
