<?php

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $host = getenv('DB_HOST') ?: 'db';
        $db   = getenv('MYSQL_DATABASE') ?: 'camagru';
        $user = getenv('MYSQL_USER') ?: 'user';
        $pass = getenv('MYSQL_PASSWORD') ?: 'secret_password';
        $dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";

        try {
            $this->connection = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            die("Error encountered. Please try again later");
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->getConnection();
    }

    public function getConnection() {
        return $this->connection;
    }
}
