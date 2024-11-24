<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../vendor/autoload.php';

class AuthMiddleware
{
    public static function checkAuth()
    {
        // Obtener todos los encabezados de la solicitud
        $headers = getallheaders();

        // Verificar que el encabezado Authorization esté presente
        if (!isset($headers['Authorization'])) {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(["status" => "error", "message" => "Token no proporcionado."]);
            exit();
        }

        // Extraer el token de tipo Bearer
        $token = str_replace('Bearer ', '', $headers['Authorization']);

        // Verificar que la clave secreta esté definida
        $secretKey = getenv('SECRET_KEY');  // Usar getenv() para cargar la variable de entorno

        // Verificación si la variable de entorno está cargada correctamente
        if (empty($secretKey)) {
            error_log('ERROR: SECRET_KEY no está definida en el entorno.');
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(["status" => "error", "message" => "Error en la configuración del servidor."]);
            exit();
        }

        try {
            // Intentar decodificar el token usando la clave secreta
            $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));

            // Log de la fecha de expiración del token (en formato Unix timestamp)
            error_log("Expiración del token: " . date('Y-m-d H:i:s', $decoded->exp));

            // Verificación de los datos en el token (por ejemplo, user_id y username)
            if (!isset($decoded->user_id) || !isset($decoded->username)) {
                throw new Exception('Datos del token inválidos.');
            }

            // Retornar el contenido decodificado del token (payload)
            return $decoded;
        } catch (Exception $e) {
            // Log de errores con detalle
            error_log('ERROR al verificar el token: ' . $e->getMessage());
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(["status" => "error", "message" => "Token inválido o expirado."]);
            exit();
        }
    }
}
?>
