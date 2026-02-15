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

	public function login($username, $password) {
	    $sql = "SELECT id, username, password, is_active FROM users WHERE username = :u";
	    $stmt = $this->pdo->prepare($sql);
	    $stmt->execute(['u' => $username]);
	    $user = $stmt->fetch();
	
	    if (!$user) {
	        return ['status' => false, 'message' => 'Wrong username or password'];
	    }
	
	    if (!$user['is_active']) {
	        return ['status' => false, 'message' => 'Verify your email before signing in'];
	    }
	
	    if (password_verify($password, $user['password'])) {
	        return ['status' => true, 'user_id' => $user['id'], 'username' => $user['username']];
	    }
	
	    return ['status' => false, 'message' => 'Wrong username or password'];
	}
}
