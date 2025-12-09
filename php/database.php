<?php
class Database {
    private $db_path;
    public $conn;

    public function __construct(){
        // En Railway, usa /data para archivos persistentes
        $this->db_path = getenv('DB_PATH') ?: __DIR__ . '/../database/data.sqlite';
    }

    public function getConnection() {
        $this->conn = null;
        try {
            // DSN para SQLite
            $dsn = 'sqlite:' . $this->db_path;
            
            $this->conn = new PDO($dsn, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_PERSISTENT => false,
                PDO::ATTR_TIMEOUT => 30,
            ]);
            
            // Activar claves foráneas en SQLite
            $this->conn->exec('PRAGMA foreign_keys = ON;');
            
        } catch(PDOException $exception) {
            error_log("DB connection error: " . $exception->getMessage());
            echo "Error temporal en la base de datos. Por favor intenta más tarde.";
        }
        return $this->conn;
    }
}
?>
