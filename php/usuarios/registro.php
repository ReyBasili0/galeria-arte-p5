<?php
session_start();
require_once '../database.php';

if($_POST){
    $database = new Database();
    $db = $database->getConnection();
    
    // Obtener datos del formulario
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Validaciones básicas
    if(empty($username) || empty($email) || empty($password)) {
        echo "<script>alert('Todos los campos son obligatorios'); window.location.href = '../../forms/registro.html';</script>";
        exit;
    }
    
    if(strlen($password) < 6) {
        echo "<script>alert('La contraseña debe tener al menos 6 caracteres'); window.location.href = '../../forms/registro.html';</script>";
        exit;
    }
    
    // Verificar si usuario ya existe
    $query = "SELECT id FROM usuarios WHERE username = ? OR email = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$username, $email]);
    
    if($stmt->rowCount() > 0){
        echo "<script>alert('El usuario o email ya están registrados'); window.location.href = '../../forms/registro.html';</script>";
        exit;
    }
    
    // Cifrar contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insertar nuevo usuario
    $query = "INSERT INTO usuarios (username, email, password) VALUES (?, ?, ?)";
    $stmt = $db->prepare($query);
    
    if($stmt->execute([$username, $email, $hashed_password])){
        // Iniciar sesión automáticamente
        $usuario_id = $db->lastInsertId();
        $_SESSION['usuario_id'] = $usuario_id;
        $_SESSION['username'] = $username;
        
        echo "<script>alert('¡Registro exitoso! Bienvenido $username'); window.location.href = '../../index.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error en el registro. Intenta nuevamente'); window.location.href = '../../forms/registro.html';</script>";
        exit;
    }
} else {
    // Si no es POST, redirigir al formulario
    header("Location: ../../forms/registro.html");
    exit;
}
?>