<?php
/**
 * Inicializador de BD — crea tablas e inserta datos si no existen
 * Ejecuta automáticamente si las tablas no existen
 */

require_once 'php/database.php';

$database = new Database();
$db = $database->getConnection();

if (!$db) {
    die('Error: No hay conexión a la BD');
}

try {
    // Verificar si la tabla usuarios ya existe
    if (getenv('DB_TYPE') === 'postgresql') {
        $checkTable = $db->query("SELECT EXISTS (
            SELECT FROM information_schema.tables 
            WHERE table_name = 'usuarios'
        )");
        $exists = $checkTable->fetch(PDO::FETCH_ASSOC);
        $tableExists = $exists['exists'] ?? false;
    } else {
        $checkTable = $db->query("SHOW TABLES LIKE 'usuarios'");
        $tableExists = $checkTable->rowCount() > 0;
    }

    if (!$tableExists) {
        echo "🔧 Creando tablas...<br>";

        // Crear tabla usuarios
        $createUsuarios = "
        CREATE TABLE usuarios (
            id SERIAL PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            rol VARCHAR(20) DEFAULT 'usuario',
            fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $db->exec($createUsuarios);
        echo "✅ Tabla 'usuarios' creada<br>";

        // Crear tabla productos
        $createProductos = "
        CREATE TABLE productos (
            id SERIAL PRIMARY KEY,
            titulo VARCHAR(100) NOT NULL,
            descripcion TEXT,
            categoria VARCHAR(50),
            tecnica VARCHAR(100),
            precio DECIMAL(10, 2),
            imagen VARCHAR(255),
            usuario_id INT REFERENCES usuarios(id) ON DELETE CASCADE,
            fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $db->exec($createProductos);
        echo "✅ Tabla 'productos' creada<br>";

        // Insertar usuarios de ejemplo
        echo "🔧 Insertando datos de ejemplo...<br>";
        
        $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $userPassword = password_hash('user123', PASSWORD_DEFAULT);

        $insertUsers = "
        INSERT INTO usuarios (username, email, password, rol) VALUES
        ('admin', 'admin@galeria.com', :admin_pass, 'admin'),
        ('nataly', 'nataly@mail.com', :user_pass, 'usuario'),
        ('edd', 'edd@mail.com', :user_pass, 'usuario')
        ";
        
        $stmt = $db->prepare($insertUsers);
        $stmt->execute([
            ':admin_pass' => $adminPassword,
            ':user_pass' => $userPassword
        ]);
        echo "✅ Usuarios creados (admin/admin123, nataly/user123, edd/user123)<br>";

        // Insertar productos de ejemplo
        $insertProducts = "
        INSERT INTO productos (titulo, descripcion, categoria, tecnica, precio, imagen, usuario_id) VALUES
        (:titulo1, :desc1, 'digital', 'digital art', 2000.00, 'https://picsum.photos/400/300?random=1', 1),
        (:titulo2, :desc2, 'tradicional', 'acuarela', 3500.00, 'https://picsum.photos/400/300?random=2', 2),
        (:titulo3, :desc3, 'sketch', 'lápiz', 1500.00, 'https://picsum.photos/400/300?random=3', 3)
        ";
        
        $stmt = $db->prepare($insertProducts);
        $stmt->execute([
            ':titulo1' => 'El libro de Bill',
            ':desc1' => 'Un dibujo digital increíble',
            ':titulo2' => 'Gregorio Samsa',
            ':desc2' => 'Retrato tradicional con acuarela',
            ':titulo3' => 'Sketch Turbia',
            ':desc3' => 'Un simple sketch en lápiz'
        ]);
        echo "✅ Productos insertados<br>";

        echo "<hr><h3>✨ ¡Base de datos inicializada correctamente!</h3>";
        echo "<p><a href='index.php'>Ir a la galería →</a></p>";
        echo "<p><strong>Usuarios de prueba:</strong><br>";
        echo "👤 admin / admin123<br>";
        echo "👤 nataly / user123<br>";
        echo "👤 edd / user123</p>";

    } else {
        echo "✅ La base de datos ya está inicializada.<br>";
        echo "<p><a href='index.php'>Ir a la galería →</a></p>";
    }

} catch (PDOException $e) {
    echo "❌ Error al inicializar BD: " . $e->getMessage();
}
?>
