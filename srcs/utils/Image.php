<?php
require_once 'Database.php';

class Image {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function create($userId, $imagePath) {
        $sql = "INSERT INTO images (user_id, path, created_at) VALUES (:uid, :path, NOW())";
        $stmt = $this->pdo->prepare($sql);
        
        try {
            return $stmt->execute([
                'uid' => $userId,
                'path' => $imagePath
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getAll($limit = 20) {
        $sql = "SELECT p.*, u.username 
                FROM images p 
                JOIN users u ON p.user_id = u.id 
                ORDER BY p.created_at DESC 
                LIMIT :limit";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($imageId, $userId) {
        $sql = "DELETE FROM images WHERE id = :pid AND user_id = :uid";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['pid' => $imageId, 'uid' => $userId]);
        return $stmt->rowCount() > 0;
    }
}
