<?php
// Ensure this is the absolute first thing executed
session_start();

// Use the corrected path based on flat structure (Root/api/ -> Root/includes/)
require_once '../includes/config.php';
require_once '../includes/database.php';

header('Content-Type: application/json');

// --- 1. Session Check (401 Error) ---
if (!isset($_SESSION['userId'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Authentication required. Please log in to add items to your cart.']);
    exit;
}

$userId = $_SESSION['userId'];
$input = json_decode(file_get_contents('php://input'), true);

$productId = (int)($input['productId'] ?? 0);
$quantity = (int)($input['quantity'] ?? 1);

// ... Input Validation ...

$db = Database::getInstance()->getConnection();
$stmt = null; // Initialize $stmt for use in the catch block

try {
    $db->beginTransaction();

    // --- 2. Get or Create Cart Header (Verify Table Name: Carts) ---
    $stmt = $db->prepare("SELECT cartId FROM Carts WHERE userId = ?");
    $stmt->execute([$userId]);
    $cart = $stmt->fetch();

    if (!$cart) {
        $stmt = $db->prepare("INSERT INTO Carts (userId) VALUES (?)");
        $stmt->execute([$userId]);
        $cartId = $db->lastInsertId();
        if (!$cartId) { throw new Exception("Failed to create new cart header."); }
    } else {
        $cartId = $cart['cartId'];
    }

    // --- 3. Get Product Snapshot (Verify Table Name: Products) ---
    $stmt = $db->prepare("SELECT price FROM Products WHERE productId = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();

    if (!$product) { throw new Exception("Product ID {$productId} not found."); }
    $priceSnapshot = $product['price'];

    // --- 4. Update or Insert Cart Item (Verify Table Name: CartItems) ---
    $stmt = $db->prepare("SELECT itemId, quantity FROM CartItems WHERE cartId = ? AND productId = ?");
    $stmt->execute([$cartId, $productId]);
    $item = $stmt->fetch();

    if ($item) {
        $newQuantity = $item['quantity'] + $quantity;
        $stmt = $db->prepare("UPDATE CartItems SET quantity = ? WHERE itemId = ?");
        $stmt->execute([$newQuantity, $item['itemId']]);
    } else {
        $stmt = $db->prepare("INSERT INTO CartItems (cartId, productId, quantity, priceSnapshot) VALUES (?, ?, ?, ?)");
        $stmt->execute([$cartId, $productId, $quantity, $priceSnapshot]);
    }

    $db->commit();
    
    // --- 5. Calculate New Total for UI Badge ---
    $stmt = $db->prepare("SELECT SUM(quantity) as totalItems FROM CartItems WHERE cartId = ?");
    $stmt->execute([$cartId]);
    $totalItems = $stmt->fetch()['totalItems'] ?? 0;

    echo json_encode([
        'success' => true, 
        'message' => 'Item added to cart successfully!', 
        'totalItems' => (int)$totalItems 
    ]);

} catch (PDOException $e) {
    // === CATCH DATABASE ERRORS AND DISPLAY DETAIL ===
    if ($db->inTransaction()) { $db->rollBack(); }
    
    // Use $e->getMessage() for the precise SQL error description
    $errorMessage = "PDO Exception: " . $e->getMessage(); 
    $queryExecuted = isset($stmt) ? $stmt->queryString : "N/A";
    
    // Output the error detail to the browser
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => $errorMessage, 
        'query' => $queryExecuted
    ]);
    
} catch (Exception $e) {
    // Catch general logic errors (e.g., Product Not Found)
    if ($db->inTransaction()) { $db->rollBack(); }
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Logic Error: ' . $e->getMessage()
    ]);
}