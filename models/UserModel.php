<?php
// Assumes Database class is already available
class UserModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function registerUser($firstName, $email, $password) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO Users (firstName, email, passwordHash) VALUES (?, ?, ?)";
        
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$firstName, $email, $passwordHash]);
        } catch (PDOException $e) {
            // Handle unique constraint failure (email already exists)
            if ($e->getCode() == '23000') {
                return false; // Indicate registration failure (email duplicate)
            }
            throw $e; // Re-throw other errors
        }
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM Users WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
}