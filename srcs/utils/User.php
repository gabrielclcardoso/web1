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

	public function generateResetToken($email) {
	    $token = bin2hex(random_bytes(50));
	    $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));
	
	    $sql = "UPDATE users SET reset_token = :t, reset_token_expiry = :e WHERE email = :email";
	    $stmt = $this->pdo->prepare($sql);
	    
	    if ($stmt->execute(['t' => $token, 'e' => $expiry, 'email' => $email])) {
	        return ($stmt->rowCount() > 0) ? $token : false;
	    }
	    return false;
	}
	
	public function resetPassword($token, $newPassword) {
	    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
	
	    $sql = "UPDATE users SET password = :p, reset_token = NULL, reset_token_expiry = NULL 
	            WHERE reset_token = :t AND reset_token_expiry > NOW()";
	    
	    $stmt = $this->pdo->prepare($sql);
	    return $stmt->execute(['p' => $hashedPassword, 't' => $token]) && $stmt->rowCount() > 0;
	}

	public function updateInfo($id, $newUsername, $newEmail) {
	    $check = $this->pdo->prepare("SELECT id FROM users WHERE (username = :u OR email = :e) AND id != :id");
	    $check->execute(['u' => $newUsername, 'e' => $newEmail, 'id' => $id]);
	    
	    if ($check->fetch()) {
	        return ['status' => false, 'message' => 'Username or Email already in use'];
	    }
	
	    $sql = "UPDATE users SET username = :u, email = :e WHERE id = :id";
	    $stmt = $this->pdo->prepare($sql);
	    
	    if ($stmt->execute(['u' => $newUsername, 'e' => $newEmail, 'id' => $id])) {
	        return ['status' => true];
	    }
	    return ['status' => false, 'message' => 'Error updating profile.'];
	}
	
	public function updatePassword($id, $newPassword) {
	    $hashed = password_hash($newPassword, PASSWORD_BCRYPT);
	    $sql = "UPDATE users SET password = :p WHERE id = :id";
	    $stmt = $this->pdo->prepare($sql);
	    return $stmt->execute(['p' => $hashed, 'id' => $id]);
	}
	
	public function getById($id) {
	    $stmt = $this->pdo->prepare("SELECT username, email FROM users WHERE id = :id");
	    $stmt->execute(['id' => $id]);
	    return $stmt->fetch();
	}
}
