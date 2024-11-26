<?php

// Configuración de encabezados CORS y manejo de preflight
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}

// Importación de controladores, modelos y configuración
require_once '../controllers/QuizController.php';
require_once '../controllers/AuthController.php';
require_once '../controllers/PreguntaController.php'; // Nuevo controlador para preguntas
require_once '../models/User.php';
require_once '../models/Pregunta.php'; // Modelo de preguntas
require_once '../config/database.php';
require_once __DIR__ . '/../vendor/autoload.php';


// Instancia de controladores
use Controllers\PreguntaController;
$authController = new AuthController($pdo);
$quizController = new QuizController($pdo);
$preguntaController = new PreguntaController($pdo); // Instancia para manejar preguntas


// Leer datos del cuerpo (si es POST o PUT)
$requestData = json_decode(file_get_contents("php://input"), true);
$action = $_GET['action'] ?? $requestData['action'] ?? null;

// Enrutamiento centralizado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'register') {
        // Registro de usuarios
        $email = $requestData['email'] ?? null;
        $password = $requestData['password'] ?? null;
        $name = $requestData['name'] ?? null;

        if ($email && $password && $name) {
            echo $authController->register($email, $password, $name);
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Datos incompletos."]);
        }
    } elseif ($action === 'login') {
        // Inicio de sesión
        $email = $requestData['email'] ?? null;
        $password = $requestData['password'] ?? null;

        if ($email && $password) {
            echo $authController->login($email, $password);
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Datos incompletos."]);
        }
    } elseif ($action === 'save_score') {
        // Guardar puntaje
        $nameId = $requestData['name_id'] ?? null;
        $score = $requestData['score'] ?? null;

        if ($nameId && $score !== null) {
            echo $quizController->saveScore($nameId, $score);
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Datos incompletos."]);
        }
    } elseif ($action === 'crearPregunta') {
        // Crear pregunta
        if (!empty($requestData)) {
            echo $preguntaController->crearPregunta($requestData);
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Datos incompletos para crear la pregunta."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Acción no especificada."]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'get_ranking') {
        // Obtener ranking diario
        echo $quizController->getRanking();
    } else {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Acción no permitida o inválida."]);
    }
} else {
    // Método no permitido
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}
