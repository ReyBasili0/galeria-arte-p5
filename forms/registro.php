<?php
session_start();
require_once '../php/database.php';

if($_POST){
    try {
        $database = new Database();
        $db = $database->getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        
        // Verificar si usuario existe
        $query = "SELECT id FROM usuarios WHERE username = ? OR email = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$username, $email]);
        
        if($stmt->rowCount() > 0){
            $error = "El usuario o email ya existen";
        } else {
            // Cifrar y guardar
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO usuarios (username, email, password) VALUES (?, ?, ?)";
            $stmt = $db->prepare($query);
            
            if($stmt->execute([$username, $email, $hashed_password])){
                $_SESSION['usuario_id'] = $db->lastInsertId();
                $_SESSION['username'] = $username;
                header("Location: ../index.php");
                exit;
            }
        }
    } catch(PDOException $e) {
        $error = "Error de base de datos: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-success text-white text-center">
                        <h4>📝 Registro</h4>
                    </div>
                    <div class="card-body">
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Usuario *</label>
                                <input type="text" class="form-control" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Contraseña *</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success">Registrarse</button>
                                <a href="login.html" class="btn btn-secondary">Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>