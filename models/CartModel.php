<?php
// /models/CartModel.php
// Assumes database.php is included

class CartModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getOrCreateCartId(int $userId): int {
        $stmt = $this->db->prepare("SELECT cartId FROM Carts WHERE userId = ?");
        $stmt->execute([$userId]);
        $cart = $stmt->fetch();

        if ($cart) {
            return $cart['cartId'];
        }

        // Create new cart
        $stmt = $this->db->prepare("INSERT INTO Carts (userId) VALUES (?)");
        $stmt->execute([$userId]);
        return (int)$this->db->lastInsertId();
    }

    public function getCartItems(int $userId): array {
        $sql = "SELECT ci.itemId, ci.quantity, ci.priceSnapshot, p.name, p.imageUrl 
                FROM CartItems ci
                JOIN Carts c ON ci.cartId = c.cartId
                JOIN Products p ON ci.productId = p.productId
                WHERE c.userId = ?";
            
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function removeItem(int $itemId, int $userId): bool {
        // Securely delete the item based on item ID and user ID
        $sql = "DELETE FROM CartItems 
                WHERE itemId = ? 
                AND cartId = (
                    SELECT cartId FROM Carts WHERE userId = ?
                )";
            
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$itemId, $userId]);
        return $stmt->rowCount() === 1;
    }
    
    public function calculateTotals(int $userId): array {
        $cartItems = $this->getCartItems($userId);
        $subtotal = 0.00;
        $discount = 0.00; 
        $shippingCost = 0.00; 

        foreach ($cartItems as $item) {
            $subtotal += ($item['quantity'] * $item['priceSnapshot']);
        }
        
        $grandTotal = $subtotal - $discount + $shippingCost;

        // Return numbers formatted as strings for consistent JSON output
        return [
            'subtotal' => number_format($subtotal, 2, '.', ''),
            'discount' => number_format($discount, 2, '.', ''),
            'shippingCost' => number_format($shippingCost, 2, '.', ''),
            'grandTotal' => number_format($grandTotal, 2, '.', ''),
        ];
    }
}
// NO CLOSING PHP TAG HERE