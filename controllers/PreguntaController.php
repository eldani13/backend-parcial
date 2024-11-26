<?php

namespace Controllers; // Define el namespace

use Models\Pregunta; // Importa el modelo Pregunta
use Firebase\JWT\JWT; // Para JWT si lo usas
use Firebase\JWT\Key; // Para la validación del token

class PreguntaController
{
    private $preguntaModel;

    // Constructor para inicializar el modelo
    public function __construct()
    {
        $this->preguntaModel = new Pregunta();
    }

    // Método para manejar la creación de preguntas
    public function crearPregunta($data)
    {
        // Validación básica de datos
        if (!isset($data['pregunta'], $data['opciones'], $data['respuesta_correcta'])) {
            return json_encode([
                "status" => "error",
                "message" => "Todos los campos son obligatorios."
            ]);
        }

        $pregunta = $data['pregunta'];
        $opciones = $data['opciones'];
        $respuestaCorrecta = $data['respuesta_correcta'];

        // Guardar la pregunta a través del modelo
        $result = $this->preguntaModel->saveQuestion($pregunta, $opciones, $respuestaCorrecta);

        if ($result) {
            return json_encode([
                "status" => "success",
                "message" => "Pregunta creada correctamente.",
                "data" => [
                    "id" => $result,
                    "pregunta" => $pregunta,
                    "opciones" => $opciones,
                    "respuesta_correcta" => $respuestaCorrecta
                ]
            ]);
        }

        return json_encode([
            "status" => "error",
            "message" => "Error al guardar la pregunta."
        ]);
    }
}
