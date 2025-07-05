<?php
class Database {
    private $conn;
    private $host;
    private $port = "3306";
    private $db_name;
    private $username;
    private $password;
    
    public function __construct() {
        // Detectar si estamos en DDEV
        if (getenv('IS_DDEV_PROJECT') === 'true') {
            $this->host = 'db';
            $this->db_name = 'db';
            $this->username = 'db';
            $this->password = 'db';
        } else {
            // Configuración para XAMPP/otros
            $this->host = 'localhost';
            $this->db_name = 'galleta_fortuna';
            $this->username = 'root';
            $this->password = '';
        }
    }

    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            // Configurar MySQL para modo UTF-8
            $this->conn->exec("SET NAMES utf8mb4");
            $this->conn->exec("SET CHARACTER SET utf8mb4");
            
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }
}