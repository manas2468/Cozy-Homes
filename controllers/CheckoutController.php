<?php
require_once ROOTPATH . '/models/CartModel.php';
require_once ROOTPATH . '/models/OrderModel.php';

class CheckoutController {
    private $cartModel;
    private $orderModel;

    public function __construct() {
        $this->cartModel = new CartModel();
        $this->orderModel = new OrderModel();
    }
    
    /**
     * Handles the display of Step 1: Shipping details (GET request).
     */
    public function step1Shipping() {
        if (!isset($_SESSION['userId'])) { 
            header('Location: /auth/login'); 
            exit; 
        }
        $userId = $_SESSION['userId'];

        $cartItems = $this->cartModel->getCartItems($userId);
        if (empty($cartItems)) {
            header('Location: /cart/view');
            exit;
        }
        
        // Load the view
        $pageTitle = "Checkout | Shipping Details";
        
        include ROOTPATH . '/views/layout/header.php';
        include ROOTPATH . '/views/checkout/step1Shipping.php'; 
        include ROOTPATH . '/views/layout/footer.php';
    }


    /**
     * Handles the submission from Step 1 (POST) and renders Step 2 (Summary).
     */
    public function summaryAction() {
        if (!isset($_SESSION['userId'])) { header('Location: /auth/login'); exit; }
        $userId = $_SESSION['userId'];
        
        // 1. Handle POST data from Step 1 (Shipping Form)
        // You should implement input validation here!
        $shippingDetails = $_POST; 
        
        // 2. Store shipping details in session for later use 
        $_SESSION['shippingDetails'] = $shippingDetails;

        // 3. Calculate Totals
        $cartItems = $this->cartModel->getCartItems($userId);
        
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['quantity'] * $item['priceSnapshot'];
        }
        
        // --- TEMPORARY Placeholder Calculation (Update this with real logic) ---
        $shippingCost = 10.00; 
        $totalAmount = $subtotal + $shippingCost; 
        // --- END TEMPORARY ---

        // 4. Load the view, passing all calculated data
        $pageTitle = "Checkout | Order Summary";
        
        // Variables available to step2Summary.php via PHP's scope injection
        $cartItems = $cartItems;
        $shippingDetails = $shippingDetails;
        $subtotal = $subtotal;
        $shippingCost = $shippingCost;
        $totalAmount = $totalAmount;
        
        include ROOTPATH . '/views/layout/header.php';
        include ROOTPATH . '/views/checkout/step2Summary.php'; 
        include ROOTPATH . '/views/layout/footer.php';
    }

    /**
     * Handles the final confirmation from Step 2 and proceeds to order creation/payment.
     */
    public function placeOrderAction() {
        if (!isset($_SESSION['userId'])) {
            header('Location: /auth/login');
            exit;
        }
        $userId = $_SESSION['userId'];
        
        // Ensure shipping details and cart data are ready
        $cartItems = $this->cartModel->getCartItems($userId);
        if (empty($cartItems) || !isset($_SESSION['shippingDetails'])) {
           header('Location: /checkout/step1shipping');
           exit;
        }
        
        // NOTE: Total calculation should be final and secure here!
        $totalAmount = 100.00; // Placeholder for final calculated amount

        // 1. Create the PENDING order in the database
        $orderRefId = 'CH' . time() . rand(100, 999);
        $orderId = $this->orderModel->createPendingOrder($userId, $orderRefId, $totalAmount, $cartItems);

        // 2. Prepare Payment URL
        $upiString = $this->generateUpiString($orderRefId, $totalAmount);

        // 3. Redirect to the Payment QR View
        $pageTitle = "Complete Payment";
        
        // Variables passed to the view (using global scope for simplicity)
        $GLOBALS['orderRefId'] = $orderRefId;
        $GLOBALS['totalAmount'] = $totalAmount;
        $GLOBALS['upiString'] = $upiString;

        include ROOTPATH . '/views/layout/header.php';
        include ROOTPATH . '/views/checkout/step3PaymentQR.php';
        include ROOTPATH . '/views/layout/footer.php';
    }
    
    private function generateUpiString(string $refId, float $amount): string {
        // ... (Your implementation) ...
        // Placeholder return to ensure function exists
        return 'upi://pay?ref=' . $refId;
    }
}