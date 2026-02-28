<?php
require_once 'Database.php';

class Comment {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function add($userId, $imageId, $commentText) {
        $sql = "INSERT INTO comments (user_id, image_id, content) VALUES (:uid, :pid, :comment)";
        $stmt = $this->pdo->prepare($sql);
        $success = $stmt->execute([
            'uid' => $userId,
            'pid' => $imageId,
            'comment' => htmlspecialchars($commentText)
        ]);

        if ($success) {
            $this->notifyOwner($userId, $imageId, $commentText);
            return true;
        }
        return false;
    }

    public function getByImageId($imageId) {
        $sql = "SELECT c.*, u.username 
                FROM comments c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.image_id = :iid 
                ORDER BY c.created_at ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['iid' => $imageId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function notifyOwner($commenterId, $imageId, $commentText) {
        $sql = "SELECT u.email, u.username AS owner_name, 
                       (SELECT username FROM users WHERE id = :cid) AS commenter_name
                FROM images p 
                JOIN users u ON p.user_id = u.id 
                WHERE p.id = :pid";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['cid' => $commenterId, 'pid' => $imageId]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data && $data['email']) {
            $to = $data['email'];
            $subject = "Camagru - New comment on your picture!";
            $message = "Hello " . $data['owner_name'] . ",\n\n";
            $message .= "The user " . $data['commenter_name'] . " Commented on your picture:\n";
            $message .= "\"" . $commentText . "\"\n\n";
            $headers = "From: noreply@camagru.com\r\n";

            @mail($to, $subject, $message, $headers);
        }
    }
}
