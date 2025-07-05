<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/Fortuna.php';

class FortunaController extends BaseController {
    private $fortuna;
    
    public function __construct() {
        parent::__construct();
        $this->fortuna = new Fortuna();
    }
    
    public function obtenerFortuna() {
        $usuarioId = $this->verificarAutenticacion();
        if (!$usuarioId) return;
        
        $mensaje = $this->fortuna->obtenerMensajeAleatorio();
        
        if ($mensaje) {
            echo json_encode(['fortuna' => $mensaje['mensaje']]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al obtener la fortuna']);
        }
    }
}