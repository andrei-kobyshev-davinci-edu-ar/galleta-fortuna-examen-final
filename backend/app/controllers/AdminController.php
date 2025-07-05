<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/Fortuna.php';
require_once __DIR__ . '/../Exceptions/CustomExceptions.php';

class AdminController extends BaseController {
    private $fortuna;
    
    public function __construct() {
        parent::__construct();
        $this->fortuna = new Fortuna();
    }
    
    public function agregarFrase() {
        $usuarioId = $this->verificarAdmin();
        if (!$usuarioId) return;
        
        $data = $this->obtenerDatosJSON();
        
        if (!isset($data->mensaje) || trim($data->mensaje) === '') {
            http_response_code(400);
            echo json_encode(['error' => 'La frase no puede estar vacía']);
            return;
        }
        
        try {
            $fraseId = $this->fortuna->agregarFrase($data->mensaje, $usuarioId);
            
            http_response_code(201);
            echo json_encode([
                'mensaje' => 'Frase agregada exitosamente',
                'id' => $fraseId
            ]);
        } catch (FraseDuplicadaException $e) {
            http_response_code(409);
            echo json_encode(['error' => 'Esta frase ya existe en la base de datos']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al agregar la frase: ' . $e->getMessage()]);
        }
    }
    
    public function listarFrases() {
        $usuarioId = $this->verificarAdmin();
        if (!$usuarioId) return;
        
        try {
            $frases = $this->fortuna->listarTodas();
            echo json_encode(['frases' => $frases]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al obtener las frases']);
        }
    }
    
    public function eliminarFrase($id) {
        $usuarioId = $this->verificarAdmin();
        if (!$usuarioId) return;
        
        try {
            $this->fortuna->eliminar($id);
            echo json_encode(['mensaje' => 'Frase eliminada exitosamente']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al eliminar la frase']);
        }
    }
}