<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once '../models/User.php';
require_once __DIR__ . '/../vendor/autoload.php';

class AuthController
{

    private $userModel;
    private $secretKey;

    public function __construct($pdo)
    {
        $this->userModel = new User($pdo);
        if (!isset($_ENV['SECRET_KEY'])) {
            die('La clave secreta no se ha definido en el archivo .env');
        }
        $this->secretKey = $_ENV['SECRET_KEY']; 
        var_dump($_ENV['SECRET_KEY']);
    }
    

    public function register($email, $password, $name)
    {
        if ($this->userModel->findUserByEmail($email)) {
            return json_encode(["status" => "error", "message" => "El correo electrónico ya está registrado."]);
        }

        if ($this->userModel->register($email, $password, $name)) {
            return json_encode(["status" => "success", "message" => "Usuario registrado con éxito."]);
        } else {
            return json_encode(["status" => "error", "message" => "Error al registrar el usuario."]);
        }
    }

    public function login($email, $password)
    {
        $user = $this->userModel->findUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $payload = [
                'iss' => 'http://localhost:8000',   
                'aud' => 'http://localhost:8000',   
                'iat' => time(),                    
                'exp' => time() + 3600,            
                'user_id' => $user['id'],           
                'user_name' => $user['name']        
            ];
            

            $jwt = JWT::encode($payload, $this->secretKey, 'HS256');

            return json_encode([
                "status" => "success",
                "message" => "Login exitoso.",
                "token" => $jwt,
                "name" => $user['name']
            ]);
            exit;
        } else {
            return json_encode(["status" => "error", "message" => "Credenciales incorrectas."]);
        }
    }
}
