<?php
// Ensure config.php variables are defined

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            // CRITICAL FIX: Explicitly use 'mysql' instead of the undefined constant DB_TYPE
            $dsn = 'mysql:host=' . DBHOST . ';dbname=' . DBNAME . ';charset=utf8mb4';
            
            $this->pdo = new PDO($dsn, DBUSER, DBPASS, $options);
            
        } catch (\PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage() . " - Check config.php");
            throw new Exception("Database connection failed. Check config.php credentials.");
        }
    }
    
    // ... rest of the class ...
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}