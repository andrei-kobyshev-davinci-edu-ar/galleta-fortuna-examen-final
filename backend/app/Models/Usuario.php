<?php
require_once __DIR__ . '/../../config/database.php';

class Usuario {
    private $conn;
    private $tabla = 'usuarios';
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function crear($nombre, $email, $password) {
        $query = "INSERT INTO " . $this->tabla . " (nombre, email, password) VALUES (:nombre, :email, :password)";
        $stmt = $this->conn->prepare($query);
        
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password_hash);
        
        try {
            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }
    
    public function verificarCredenciales($email, $password) {
        $query = "SELECT id, nombre, email, password, rol FROM " . $this->tabla . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (password_verify($password, $row['password'])) {
                unset($row['password']);
                return $row;
            }
        }
        
        return false;
    }
    
    public function obtenerIdPorToken($token) {
        // Token simple: base64(id:timestamp)
        $decoded = base64_decode($token);
        $parts = explode(':', $decoded);
        
        if (count($parts) === 2 && is_numeric($parts[0])) {
            return intval($parts[0]);
        }
        
        return false;
    }
    
    public function esAdmin($usuarioId) {
        $query = "SELECT rol FROM " . $this->tabla . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $usuarioId);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row && $row['rol'] === 'admin';
    }
}