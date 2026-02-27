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

    public function getAll($limit = 20, $offset = 0, $currentUserId = 0) {
		$sql = "SELECT
					i.*,
					u.username,
					COUNT(l.id) AS like_count,
                    (SELECT COUNT(*) FROM likes WHERE image_id = i.id AND user_id = :current_user_id) AS user_liked
                FROM images i 
                JOIN users u ON i.user_id = u.id 
				LEFT JOIN likes l ON i.id = l.image_id
				GROUP BY i.id
                ORDER BY i.created_at DESC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
		$stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
		$stmt->bindValue(':current_user_id', (int)$currentUserId, PDO::PARAM_INT);
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
