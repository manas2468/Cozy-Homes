<?php
// Assumes database.php is included

class OrderModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Creates a new pending order and populates order items.
     * @param int $userId
     * @param string $orderRefId
     * @param float $totalAmount
     * @param array $cartItems (Snapshot of items to store)
     * @return int The new orderId
     */
    public function createPendingOrder(int $userId, string $orderRefId, float $totalAmount, array $cartItems): int {
        
        try {
            $this->db->beginTransaction();

            // 1. Insert into Orders table
            $stmt = $this->db->prepare("INSERT INTO Orders (userId, orderRefId, totalAmount, paymentMethod, orderStatus) 
                                       VALUES (?, ?, ?, 'Fampay QR', 'PENDINGPAYMENT')");
            $stmt->execute([$userId, $orderRefId, $totalAmount]);
            $orderId = $this->db->lastInsertId();

            // 2. Insert items into a separate OrderItems table (Necessary for permanent record)
            // NOTE: A new OrderItems table/model would be needed here. 
            
            // 3. Clear the user's cart after successfully moving items to Order
            // $cartModel->clearCart($userId); 

            $this->db->commit();
            return (int)$orderId;

        } catch (Exception $e) {
            $this->db->rollBack();
            // Log error
            throw new Exception("Failed to create order: " . $e->getMessage());
        }
    }
    
    /**
     * Updates the status of an order (Called by the Polling API when payment confirmed).
     */
    public function updateOrderStatus(string $orderRefId, string $newStatus): bool {
        $stmt = $this->db->prepare("UPDATE Orders SET orderStatus = ? WHERE orderRefId = ?");
        return $stmt->execute([$newStatus, $orderRefId]);
    }
}