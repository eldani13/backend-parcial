<?php


class Quiz
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getRandomQuestions($limit)
    {
        $stmt = $this->pdo->query("SELECT id, question, option_a, option_b, option_c, option_d FROM questions ORDER BY RAND() LIMIT $limit");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}