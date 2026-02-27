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

	public function getTotalCount() {
        $sql = "SELECT COUNT(*) FROM images";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchColumn();
    }

    public function getAll($limit = 20, $offset = 0) {
        $sql = "SELECT p.*, u.username 
                FROM images p 
                JOIN users u ON p.user_id = u.id 
                ORDER BY p.created_at DESC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
		$stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserImages($userId) {
		$sql = "SELECT *
				FROM images
				WHERE user_id = :uid
				ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($imageId, $userId) {
		$stmt = $this->pdo->prepare("SELECT path FROM images WHERE id = :pid AND user_id = :uid");
    	$stmt->execute(['pid' => $imageId, 'uid' => $userId]);
    	$image = $stmt->fetch();

    	if ($image) {
    	    $del = $this->pdo->prepare("DELETE FROM images WHERE id = :pid AND user_id = :uid");
    	    if ($del->execute(['pid' => $imageId, 'uid' => $userId])) {
    	        $absolutePath = __DIR__ . '/../../public/' . $image['path'];
    	        if (file_exists($absolutePath)) {
    	            unlink($absolutePath);
    	        }
    	        return true;
    	    }
    	}
    	return false;
    }
}
