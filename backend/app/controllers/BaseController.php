<?php
require_once __DIR__ . '/../Models/Usuario.php';

abstract class BaseController {
    protected $usuario;
    
    public function __construct() {
        $this->usuario = new Usuario();
    }
    
    protected function verificarAutenticacion() {
        $headers = getallheaders();
        
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Token de autorización requerido']);
            return false;
        }
        
        $token = str_replace('Bearer ', '', $headers['Authorization']);
        $usuarioId = $this->usuario->obtenerIdPorToken($token);
        
        if (!$usuarioId) {
            http_response_code(401);
            echo json_encode(['error' => 'Token inválido']);
            return false;
        }
        
        return $usuarioId;
    }
    
    protected function verificarAdmin() {
        $usuarioId = $this->verificarAutenticacion();
        
        if (!$usuarioId) {
            return false;
        }
        
        if (!$this->usuario->esAdmin($usuarioId)) {
            http_response_code(403);
            echo json_encode(['error' => 'Acceso denegado. Se requieren permisos de administrador']);
            return false;
        }
        
        return $usuarioId;
    }
    
    protected function obtenerDatosJSON() {
        return json_decode(file_get_contents("php://input"));
    }
}