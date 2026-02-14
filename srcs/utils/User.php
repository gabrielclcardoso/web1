<?php
require_once 'Database.php';

class User {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function create($username, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $token = bin2hex(random_bytes(50));

        $sql = "INSERT INTO users (username, email, password, activation_token) VALUES (:u, :e, :p, :t)";
        $stmt = $this->pdo->prepare($sql);
        
		try {
			if ($stmt->execute([
        	    'u' => $username,
        	    'e' => $email,
        	    'p' => $hashedPassword,
        	    't' => $token
			])) {
        	    return $token;
			}
    		    return false;
    	} catch (PDOException $e) {
    		    return false;
    	}
    }
}
