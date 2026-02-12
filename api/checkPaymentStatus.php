<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/database.php';

header('Content-Type: application/json');

// This API is stateless, it only checks the status based on the reference ID
$input = json_decode(file_get_contents('php://input'), true);
$orderRefId = $input['orderRefId'] ?? '';

if (empty($orderRefId)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'status' => 'error', 'message' => 'Missing Order Reference ID.']);
    exit;
}

$db = Database::getInstance()->getConnection();

try {
    // Check the order status in the database
    $stmt = $db->prepare("SELECT orderStatus FROM Orders WHERE orderRefId = ?");
    $stmt->execute([$orderRefId]);
    $order = $stmt->fetch();

    if (!$order) {
        http_response_code(404);
        echo json_encode(['success' => false, 'status' => 'error', 'message' => 'Order not found.']);
        exit;
    }

    // This is where you would ideally have a background job or external system
    // update the database status to 'PAID' once Fampay confirms payment.

    $currentStatus = $order['orderStatus'];

    if ($currentStatus === 'PENDINGPAYMENT') {
        // Payment not yet confirmed
        echo json_encode(['success' => true, 'status' => 'pending']);
    } elseif ($currentStatus === 'PAID') {
        // Payment confirmed!
        echo json_encode(['success' => true, 'status' => 'paid']);
    } else {
        // Handle other statuses (e.g., failed, cancelled)
        echo json_encode(['success' => true, 'status' => 'other', 'orderStatus' => $currentStatus]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'status' => 'error', 'message' => 'Server error.']);
}