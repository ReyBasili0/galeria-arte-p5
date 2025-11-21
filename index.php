<?php
session_start();
require_once 'php/database.php';

// Conexión a BD
$database = new Database();
$db = $database->getConnection();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería de Arte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .gallery-card { 
            transition: transform 0.3s ease, box-shadow 0.3s ease; 
            cursor: pointer;
        }
        .gallery-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .card-img-top { 
            height: 250px; 
            object-fit: cover; 
            transition: transform 0.3s ease;
        }
        .gallery-card:hover .card-img-top {
            transform: scale(1.05);
        }
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">🎨 Galería de Arte</a>
            <div class="navbar-nav ms-auto">
                <?php if(isset($_SESSION['usuario_id'])): ?>
                    <span class="nav-link">👤 Bienvenido, <?= $_SESSION['username'] ?></span>
                    <a class="nav-link" href="forms/alta-obra.php">➕ Nueva Obra</a>
                    <a class="nav-link" href="php/usuarios/logout.php">🚪 Cerrar Sesión</a>
                <?php else: ?>
                    <a class="nav-link" href="forms/login.html">🔐 Login</a>
                    <a class="nav-link" href="forms/registro.html">📝 Registrarse</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero text-white text-center py-5">
        <div class="container">
            <h1 class="display-4">Bienvenido a la Galería de Arte</h1>
            <p class="lead">
                <?php if(isset($_SESSION['usuario_id'])): ?>
                    ¡Hola <?= $_SESSION['username'] ?>! Comparte tu creatividad con el mundo.
                <?php else: ?>
                    Descubre y comparte obras de arte increíbles. Regístrate para comenzar.
                <?php endif; ?>
            </p>
        </div>
    </section>

    <!-- Contenido Principal -->
    <div class="container my-5">
        <!-- Botón para agregar obra (solo para usuarios logueados) -->
        <?php if(isset($_SESSION['usuario_id'])): ?>
            <div class="text-center mb-4">
                <a href="forms/alta-obra.php" class="btn btn-success btn-lg">➕ Agregar Nueva Obra</a>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                <h5>¿Quieres compartir tus obras?</h5>
                <p>Inicia sesión o regístrate para agregar tus creaciones a la galería</p>
                <a href="forms/login.html" class="btn btn-primary me-2">🔐 Iniciar Sesión</a>
                <a href="forms/registro.html" class="btn btn-success">📝 Registrarse</a>
            </div>
        <?php endif; ?>

        <!-- Galería de Obras -->
        <h2 class="text-center mb-4">🎨 Obras de la Comunidad</h2>
        
        <div class="row">
            <?php
            $query = "SELECT * FROM productos ORDER BY fecha_creacion DESC";
            $stmt = $db->prepare($query);
            $stmt->execute();
            
            if($stmt->rowCount() > 0):
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
            ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 gallery-card">
                    <img src="<?= $row['imagen'] ?: 'https://via.placeholder.com/400x300/667eea/white?text=Sin+Imagen' ?>" 
                         class="card-img-top" alt="<?= $row['titulo'] ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($row['titulo']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($row['descripcion']) ?></p>
                        <div class="mb-2">
                            <span class="badge bg-primary"><?= $row['categoria'] ?></span>
                            <?php if($row['tecnica']): ?>
                                <span class="badge bg-secondary"><?= $row['tecnica'] ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if($row['precio'] && $row['precio'] > 0): ?>
                            <p class="text-success fw-bold">$<?= number_format($row['precio'], 2) ?> MXN</p>
                        <?php endif; ?>
                    </div>
                    <?php if(isset($_SESSION['usuario_id'])): ?>
                    <div class="card-footer bg-transparent">
                        <a href="forms/editar-obra.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">✏️ Editar</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php 
                endwhile;
            else: 
            ?>
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    <h5>¡Aún no hay obras en la galería!</h5>
                    <p>
                        <?php if(isset($_SESSION['usuario_id'])): ?>
                            Sé el primero en compartir tu arte. 
                            <a href="forms/alta-obra.php" class="alert-link">¡Agrega una obra ahora!</a>
                        <?php else: ?>
                            <a href="forms/registro.html" class="alert-link">Regístrate</a> 
                            para ser el primero en exhibir tu talento.
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p>&copy; 2025 Galería de Arte. Todos los derechos reservados.</p>
            <div class="mt-2">
                <a href="https://validator.w3.org/" target="_blank" class="text-white me-3">
                    ✅ Validar HTML
                </a>
                <a href="https://jigsaw.w3.org/css-validator/" target="_blank" class="text-white">
                    🎨 Validar CSS
                </a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>