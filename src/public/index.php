<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\UserController;
use App\Config\Database;
use App\Models\User;

// Instância das classes
$database = new Database();
$userModel = new User($database);
$userController = new UserController($userModel);

// Obtêm a URL requisitada
$request = $_SERVER['REQUEST_URI'];

// Remove parâmetros de query string (exemplo: ?id=1)
$request = strtok($request, '?');

// Define as rotas
if ($request === '/' || $request === '/users') {
    // Listar usuários
    $userController->index();
} elseif ($request === '/user/add') {
      $userController->add();
} elseif ($request === '/user/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
      $userController->create();
} elseif (preg_match('/\/user\/edit\/(\d+)/', $request, $matches)) {
    // Página de edição de usuário
    $userId = $matches[1];
    $userController->edit($userId);

} elseif ($request === '/user/update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Atualizar usuário via POST
    $userController->update();

} elseif (preg_match('/\/user\/delete\/(\d+)/', $request, $matches)) {
    // Excluir usuário
    $userId = $matches[1];
    $userController->delete($userId);

} else {
    // Página não encontrada
    http_response_code(404);
    echo "Página não encontrada!";
}

?>
