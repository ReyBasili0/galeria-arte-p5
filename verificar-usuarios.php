<?php
// Archivo temporal para verificar usuarios registrados
header('Content-Type: text/html; charset=utf-8');
echo "<h2>🔍 Verificación de Usuarios Registrados</h2>";

try {
    // Conexión a SQLite
    $db = new PDO('sqlite:database/data.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Consultar usuarios
    $stmt = $db->query("SELECT * FROM usuarios ORDER BY id DESC");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($usuarios) > 0) {
        echo "<p>✅ <strong>Base de datos CONECTADA</strong></p>";
        echo "<p>📊 <strong>Total de usuarios:</strong> " . count($usuarios) . "</p>";
        
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>ID</th><th>Nombre</th><th>Email</th><th>Registrado el</th></tr>";
        
        foreach ($usuarios as $usuario) {
            // Mostrar contraseña en hash (no texto plano)
            $pass_display = substr($usuario['password'] ?? '', 0, 20) . "...";
            
            echo "<tr>";
            echo "<td>" . ($usuario['id'] ?? 'N/A') . "</td>";
            echo "<td>" . ($usuario['nombre'] ?? $usuario['username'] ?? 'Sin nombre') . "</td>";
            echo "<td>" . ($usuario['email'] ?? 'Sin email') . "</td>";
            echo "<td>" . ($usuario['created_at'] ?? 'Fecha desconocida') . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>⚠️ Base de datos conectada pero NO hay usuarios registrados todavía.</p>";
        echo "<p>Prueba registrar uno y actualiza esta página.</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ ERROR de conexión: " . $e->getMessage() . "</p>";
    echo "<p>Ruta intentada: database/data.sqlite</p>";
    
    // Debug: ver archivos en directorio
    echo "<h3>Archivos en /database:</h3>";
    if (file_exists('database')) {
        $files = scandir('database');
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                echo "- $file (" . filesize("database/$file") . " bytes)<br>";
            }
        }
    }
}

echo "<hr>";
echo "<h3>📱 Prueba rápida:</h3>";
echo "<p>1. Abre otra pestaña y regístrate</p>";
echo "<p>2. Regresa aquí y actualiza (F5)</p>";
echo "<p>3. ¡Deberías ver tu nuevo usuario!</p>";
?>
