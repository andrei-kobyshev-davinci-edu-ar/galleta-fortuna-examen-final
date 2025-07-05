<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/FortunaController.php';
require_once __DIR__ . '/../app/controllers/AdminController.php';

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_method = $_SERVER['REQUEST_METHOD'];

// Remover /api del inicio si existe
$request_uri = preg_replace('/^\/api/', '', $request_uri);

$authController = new AuthController();
$fortunaController = new FortunaController();
$adminController = new AdminController();

// Rutas públicas
if ($request_uri === '/register' && $request_method === 'POST') {
    $authController->registro();
} elseif ($request_uri === '/login' && $request_method === 'POST') {
    $authController->login();
// Rutas autenticadas
} elseif ($request_uri === '/fortuna' && $request_method === 'GET') {
    $fortunaController->obtenerFortuna();
// Rutas de admin
} elseif ($request_uri === '/admin/frases' && $request_method === 'POST') {
    $adminController->agregarFrase();
} elseif ($request_uri === '/admin/frases' && $request_method === 'GET') {
    $adminController->listarFrases();
} elseif (preg_match('/^\/admin\/frases\/(\d+)$/', $request_uri, $matches) && $request_method === 'DELETE') {
    $adminController->eliminarFrase($matches[1]);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Ruta no encontrada']);
}