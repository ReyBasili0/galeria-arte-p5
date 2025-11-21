<?php
session_start();
require_once '../database.php';

if($_POST){
    $database = new Database();
    $db = $database->getConnection();
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $query = "SELECT id, username, password FROM usuarios WHERE username = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$username]);
    
    if($stmt->rowCount() == 1){
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verificar contraseña cifrada
        if(password_verify($password, $user['password'])){
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            echo "<script>alert('¡Bienvenido $username!'); window.location.href='../../index.php';</script>";
            exit;
        }
    }
    
    echo "<script>alert('Usuario o contraseña incorrectos'); window.location.href='../../forms/login.html';</script>";
}
?>