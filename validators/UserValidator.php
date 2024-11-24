<?php
class UserValidator
{

    public static function validateRegistration($email, $password, $name)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Correo electrónico inválido.";
            return false;
        }

        if (strlen($password) < 6) {
            echo "La contraseña debe tener al menos 6 caracteres.";
            return false;
        }

        return true;
    }

    public static function validateLogin($email, $password)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Correo electrónico inválido.";
            return false;
        }

        return true;
    }
}
