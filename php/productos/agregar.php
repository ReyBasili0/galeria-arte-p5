<?php
session_start();
require_once '../database.php';

if($_POST && isset($_SESSION['usuario_id'])){
    $database = new Database();
    $db = $database->getConnection();
    
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $categoria = $_POST['categoria'];
    $tecnica = $_POST['tecnica'];
    $precio = $_POST['precio'];
    $imagen = $_POST['imagen'];
    $usuario_id = $_SESSION['usuario_id'];
    
    $query = "INSERT INTO productos (titulo, descripcion, categoria, tecnica, precio, imagen, usuario_id) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $db->prepare($query);
    if($stmt->execute([$titulo, $descripcion, $categoria, $tecnica, $precio, $imagen, $usuario_id])){
        header("Location: ../../index.php?success=1");
    } else {
        header("Location: ../../forms/alta-obra.php?error=1");
    }
} else {
    header("Location: ../../forms/login.html");
}
?>