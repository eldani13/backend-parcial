<?php
require_once '../models/Score.php';
require_once '../models/Score.php'; // Modelo de puntuaciones
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ScoreController
{
    private $scoreModel;
    private $secretKey;

    public function __construct($pdo)
    {
        $this->scoreModel = new Score($pdo); // Inicializa el modelo
        $this->secretKey = 'ca27aa6223b44754b63516223cfb2760'; // Clave secreta para el JWT
    }

    public function saveScore($score)
    {
        // Obtener encabezados de la solicitud
        $headers = getallheaders();
        
        if (isset($headers['Authorization'])) {
            // Extraer el token JWT del encabezado
            $jwt = str_replace('Bearer ', '', $headers['Authorization']);
            
            try {
                // Decodificar el JWT
                $decoded = JWT::decode($jwt, new Key($this->secretKey, 'HS256'));
                $userId = $decoded->user_id;

                // Validar puntaje
                if (!is_numeric($score) || $score < 0) {
                    return json_encode(["status" => "error", "message" => "Puntaje inválido."]);
                }

                // Guardar el puntaje en la base de datos
                if ($this->scoreModel->saveScore($userId, $score)) {
                    return json_encode(["status" => "success", "message" => "Puntaje guardado correctamente."]);
                } else {
                    return json_encode(["status" => "error", "message" => "Error al guardar el puntaje."]);
                }
            } catch (Exception $e) {
                // Manejar errores de JWT
                return json_encode(["status" => "error", "message" => "Token inválido o expirado."]);
            }
        }

        // Si no se envió el token
        return json_encode(["status" => "error", "message" => "No autorizado."]);
    }
}

class QuizController
{
    private $scoreModel;

    public function __construct($pdo)
    {
        $this->scoreModel = new Score($pdo);
    }

    // Ruta para guardar el puntaje
    public function saveScore($userId, $score)
    {
        if ($this->scoreModel->saveScore($userId, $score)) {
            return json_encode(["status" => "success", "message" => "Puntaje guardado correctamente."]);
        }
        return json_encode(["status" => "error", "message" => "Error al guardar el puntaje."]);
    }

    // Ruta para obtener el ranking diario
    public function getRanking()
    {
        $ranking = $this->scoreModel->getDailyRanking();
        return json_encode($ranking);
    }
}


