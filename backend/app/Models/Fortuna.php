<?php
require_once __DIR__ . '/../../config/database.php';

class Fortuna {
    private $conn;
    private $tabla = 'fortunas';
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function obtenerMensajeAleatorio() {
        $query = "SELECT mensaje FROM " . $this->tabla . " ORDER BY RAND() LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function agregarFrase($mensaje, $usuarioId) {
        // Verificar si ya existe (case insensitive)
        $query = "SELECT id FROM " . $this->tabla . " WHERE LOWER(mensaje) = LOWER(:mensaje)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':mensaje', $mensaje);
        $stmt->execute();
        
        if ($stmt->fetch()) {
            require_once __DIR__ . '/../Exceptions/CustomExceptions.php';
            throw new FraseDuplicadaException();
        }
        
        // Insertar nueva frase
        $this->conn->beginTransaction();
        
        try {
            $query = "INSERT INTO " . $this->tabla . " (mensaje, agregado_por) VALUES (:mensaje, :usuario_id)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':mensaje', $mensaje);
            $stmt->bindParam(':usuario_id', $usuarioId);
            $stmt->execute();
            
            $fraseId = $this->conn->lastInsertId();
            
            $this->conn->commit();
            
            return $fraseId;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }
    
    public function listarTodas() {
        $query = "SELECT f.*, 
                         u.nombre as agregado_por
                  FROM " . $this->tabla . " f
                  LEFT JOIN usuarios u ON f.agregado_por = u.id
                  ORDER BY f.id DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function eliminar($id) {
        // Hard delete since we don't have an 'activo' column
        $query = "DELETE FROM " . $this->tabla . " WHERE id = :id AND agregado_por IS NOT NULL";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
}