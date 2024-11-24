<?php
class Score
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function saveScore($nameId, $score)
    {
        $query = "INSERT INTO scores (name_id, score, created_at) VALUES (:name_id, :score, NOW())";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            ':name_id' => $nameId,
            ':score' => $score
        ]);
    }

    public function getDailyRanking()
    {
        $stmt = $this->pdo->query("
            SELECT u.name, s.score 
            FROM scores s 
            JOIN users u ON s.user_id = u.id 
            WHERE DATE(s.created_at) = CURDATE()
            ORDER BY s.score DESC LIMIT 10
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
