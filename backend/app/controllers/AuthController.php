<?php
require_once __DIR__ . '/../Models/Usuario.php';

class AuthController {
    private $usuario;
    
    public function __construct() {
        $this->usuario = new Usuario();
    }
    
    public function registro() {
        $data = json_decode(file_get_contents("php://input"));
        
        if (!isset($data->nombre) || !isset($data->email) || !isset($data->password)) {
            http_response_code(400);
            echo json_encode(['error' => 'Faltan datos requeridos']);
            return;
        }
        
        $resultado = $this->usuario->crear($data->nombre, $data->email, $data->password);
        
        if ($resultado) {
            http_response_code(201);
            echo json_encode(['mensaje' => 'Usuario registrado exitosamente']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'El email ya está registrado']);
        }
    }
    
    public function login() {
        $data = json_decode(file_get_contents("php://input"));
        
        if (!isset($data->email) || !isset($data->password)) {
            http_response_code(400);
            echo json_encode(['error' => 'Email y contraseña son requeridos']);
            return;
        }
        
        $usuario = $this->usuario->verificarCredenciales($data->email, $data->password);
        
        if ($usuario) {
            $token = base64_encode($usuario['id'] . ':' . time());
            echo json_encode([
                'token' => $token,
                'usuario' => [
                    'id' => $usuario['id'],
                    'nombre' => $usuario['nombre'],
                    'email' => $usuario['email'],
                    'rol' => $usuario['rol']
                ]
            ]);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Credenciales inválidas']);
        }
    }
}