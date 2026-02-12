<?php
// Assumes Database class is accessible

class ProductModel {
    private $db;

    public function __construct() {
        // Initialize DB connection using your established pattern
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Fetches all products from the database.
     * @return array Array of products. Returns an empty array on failure.
     */
    public function getAllProducts() {
        try {
            // Select the fields needed for the catalog view
            $stmt = $this->db->query("SELECT productId, name, price, imageUrl FROM Products ORDER BY productId ASC");
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Database Error fetching products: " . $e->getMessage());
            return []; 
        }
    }
}