<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}


require_once '../controllers/AuthController.php';
require_once '../models/User.php';
require_once '../config/database.php';
require_once __DIR__ . '/../vendor/autoload.php';

$authController = new AuthController($pdo);

$requestData = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($requestData['register'])) {
        $email = $requestData['email'];
        $password = $requestData['password'];
        $name = $requestData['name'];
        echo $authController->register($email, $password, $name);
    } elseif (isset($requestData['login'])) {
        $email = $requestData['email'];
        $password = $requestData['password'];
        echo $authController->login($email, $password);
    } else {
        echo json_encode(["status" => "error", "message" => "Acción no especificada"]);
    }
}
