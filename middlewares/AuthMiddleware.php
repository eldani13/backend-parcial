<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../vendor/autoload.php';

class AuthMiddleware
{
    public static function checkAuth()
    {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(["status" => "error", "message" => "Token no proporcionado."]);
            exit();
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);
        $secretKey = $_ENV['SECRET_KEY']; 
        try {
            $decoded = JWT::decode($token, new Key($secretKey, 'HS256')); 
            return $decoded;
        } catch (Exception $e) {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(["status" => "error", "message" => "Token inválido o expirado."]);
            exit();
        }
    }
}