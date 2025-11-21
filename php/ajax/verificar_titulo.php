<?php
require_once '../database.php';

if($_POST && isset($_POST['titulo'])){
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT id FROM productos WHERE titulo = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$_POST['titulo']]);
    
    echo json_encode(['existe' => $stmt->rowCount() > 0]);
}
?>