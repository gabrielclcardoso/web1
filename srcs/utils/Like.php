<?php
require_once 'Database.php';

class Like {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function toggle($userId, $imageId) {
        $check = $this->pdo->prepare("SELECT id FROM likes WHERE user_id = :uid AND image_id = :pid");
        $check->execute(['uid' => $userId, 'pid' => $imageId]);
        
        if ($check->fetch()) {
            $sql = "DELETE FROM likes WHERE user_id = :uid AND image_id = :pid";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['uid' => $userId, 'pid' => $imageId]);
            return ['action' => 'unliked'];
        } else {
            $sql = "INSERT INTO likes (user_id, image_id) VALUES (:uid, :pid)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['uid' => $userId, 'pid' => $imageId]);
            return ['action' => 'liked'];
        }
    }

    public function getCount($imageId) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM likes WHERE image_id = :pid");
        $stmt->execute(['pid' => $imageId]);
        return $stmt->fetchColumn();
    }
}
