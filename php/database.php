<?php
class Database {
    // Valores por defecto (puedes sobrescribir mediante variables de entorno)
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $port;
    public $conn;

    public function __construct(){
        $this->host = getenv('DB_HOST') ?: "galeria-mysql-nat.mysql.database.azure.com";
        $this->db_name = getenv('DB_NAME') ?: "galeria_arte";
        $this->username = getenv('DB_USER') ?: "admin_galeria@galeria-mysql-nat";
        $this->password = getenv('DB_PASS') ?: "TuContraseñaSegura123";
        $this->port = getenv('DB_PORT') ?: 3306;
    }

    public function getConnection() {
        $this->conn = null;
        try {
            $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $this->host, $this->port, $this->db_name);
            $this->conn = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch(PDOException $exception) {
            error_log("DB connection error: " . $exception->getMessage());
            echo "Error temporal en la base de datos. Por favor intenta más tarde.";
        }
        return $this->conn;
    }
}
?>