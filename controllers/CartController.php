<?php
require_once ROOTPATH . '/models/CartModel.php';

class CartController {
    private $cartModel;

    public function __construct() {
        $this->cartModel = new CartModel();
    }

    // --- Existing viewAction (Remains unchanged) ---
    public function viewAction() {
        if (!isset($_SESSION['userId'])) {
            header('Location: /auth/login');
            exit;
        }

        $userId = $_SESSION['userId'];
        $cartItems = $this->cartModel->getCartItems($userId);

        $subtotal = 0;
        foreach ($cartItems as &$item) {
            $item['lineTotal'] = $item['quantity'] * $item['priceSnapshot'];
            $subtotal += $item['lineTotal'];
        }
        
        // Fetch full totals to define discount and shippingCost for the view
        $totals = $this->cartModel->calculateTotals($userId);
        $discount = $totals['discount'];
        $shippingCost = $totals['shippingCost'];
        $grandTotal = $totals['grandTotal'];

        // Render the cart view
        $pageTitle = "Your Shopping Cart";
        include ROOTPATH . '/views/layout/header.php';
        include ROOTPATH . '/views/cart/cartView.php';
        include ROOTPATH . '/views/layout/footer.php';
    }
    
    // --- New/Updated removeItemAction (Hardened for JSON output) ---
    public function removeItemAction() {
        // Set content type for AJAX response immediately
        header('Content-Type: application/json');

        // Check if user is logged in and request method is correct
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['userId'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid request or user not logged in.']);
            exit;
        }
        
        $userId = $_SESSION['userId'];

        // 1. Read and Sanitize Input
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        // Sanitize itemId (it must be an integer)
        $itemId = filter_var($data['itemId'] ?? null, FILTER_SANITIZE_NUMBER_INT);
        
        if (!$itemId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing or invalid Item ID.']);
            exit;
        }

        // 2. Process Removal via Model
        // We cast to int just to be absolutely sure the model gets an integer
        $isRemoved = $this->cartModel->removeItem((int)$itemId, $userId);

        if ($isRemoved) {
            // 3. Recalculate Totals
            $newTotals = $this->cartModel->calculateTotals($userId);

            // 4. Success Response
            echo json_encode([
                'success' => true,
                'message' => 'Item removed successfully.',
                'summary' => $newTotals
            ]);
        } else {
            // 5. Failure Response (e.g., item not found)
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Item could not be deleted or does not exist for this user.']);
        }
        
        // CRITICAL: Stop ALL further execution to prevent stray HTML/output
        exit;
    }
    
}
// NO CLOSING PHP TAG HERE