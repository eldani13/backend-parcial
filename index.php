<?php
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");  
header("Access-Control-Allow-Headers: Content-Type, Authorization"); 

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);  
    exit();
}

require_once './vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

require_once './config/database.php';
require_once './routes/web.php';
require_once './controllers/AuthController.php';

$input = json_decode(file_get_contents("php://input"), true);

if (isset($input['email'], $input['password'], $input['name'])) {
    $email = $input['email'];
    $password = $input['password'];
    $name = $input['name'];

    $authController = new AuthController($pdo);

    $response = $authController->register($email, $password, $name);
    echo json_encode($response); 
} else {
    echo json_encode(["status" => "error", "message" => "Faltan datos"]);
}
