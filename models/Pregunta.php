<?php

namespace Models;

class Pregunta
{
    private $db;

    public function __construct()
    {
        // Asume que tienes una conexión $pdo configurada
        global $pdo;
        $this->db = $pdo;
    }

    // Método para guardar preguntas en la base de datos
    public function saveQuestion($pregunta, $opciones, $respuestaCorrecta)
    {
        $query = "INSERT INTO preguntas (pregunta, opcion_a, opcion_b, opcion_c, opcion_d, respuesta_correcta) 
                  VALUES (:pregunta, :opcion_a, :opcion_b, :opcion_c, :opcion_d, :respuesta_correcta)";

        $statement = $this->db->prepare($query);

        // Asignar valores
        $statement->bindValue(':pregunta', $pregunta);
        $statement->bindValue(':opcion_a', $opciones['a']);
        $statement->bindValue(':opcion_b', $opciones['b']);
        $statement->bindValue(':opcion_c', $opciones['c']);
        $statement->bindValue(':opcion_d', $opciones['d']);
        $statement->bindValue(':respuesta_correcta', $respuestaCorrecta);

        // Ejecutar la consulta
        if ($statement->execute()) {
            return $this->db->lastInsertId(); // Devuelve el ID generado
        }

        return false; // Si no se guarda, devuelve false
    }
}
