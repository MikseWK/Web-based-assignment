<?php
require_once '../base.php';

// Check if user is logged in
if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized User']);
    exit;
}

// Check if required parameters are provided
if (!isset($_POST['order_id']) || !isset($_POST['status']) || !isset($_POST['payment_intent'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required parameters']);
    exit;
}

$order_id = $_POST['order_id'];
$status = $_POST['status'];
$payment_intent = $_POST['payment_intent'];
$user_id = get_user_id();

// Debug info
$debug_info = [
    'order_id' => $order_id,
    'status' => $status,
    'payment_intent' => $payment_intent,
    'user_id' => $user_id
];

try {
    // Use the global PDO connection from base.php
    global $_db;
    
    // Start transaction
    $_db->beginTransaction();
    
    // 1. Update order status
    $query = "UPDATE orders SET status = 'Success' WHERE id = ?";
    $stmt = $_db->prepare($query);
    $result = $stmt->execute([$order_id]);
    
    $debug_info['order_update_rows'] = $stmt->rowCount();
    
    // 2. Get order details and user email
    $orderQuery = "SELECT o.total, c.email 
                  FROM orders o 
                  JOIN customer c ON o.user_id = c.id 
                  WHERE o.id = ?";
    $orderStmt = $_db->prepare($orderQuery);
    $orderStmt->execute([$order_id]);
    $orderData = $orderStmt->fetch(PDO::FETCH_ASSOC);
    
    $debug_info['order_data'] = $orderData;
    
    if (!$orderData) {
        throw new Exception("Order data not found");
    }
    
    // 3. Insert payment record
    $paymentQuery = "INSERT INTO payment (order_id, transaction_id, amount, email, payment_status, created_at) 
                    VALUES (?, ?, ?, ?, 'Success', NOW())";
    $paymentStmt = $_db->prepare($paymentQuery);
    $paymentResult = $paymentStmt->execute([
        $order_id,
        $payment_intent,
        $orderData['total'],
        $orderData['email']
    ]);
    
    $debug_info['payment_insert_id'] = $_db->lastInsertId();
    
    // Commit transaction
    $_db->commit();
    
    // Store the customer ID and completed status in session
    $_SESSION['order_completed'] = true;
    $_SESSION['order_customer_id'] = $user_id;
    $_SESSION['order_id'] = $order_id;
    
    echo json_encode(['success' => true, 'debug' => $debug_info]);
    
} catch (Exception $e) {
    // Rollback transaction on error
    if ($_db->inTransaction()) {
        $_db->rollBack();
    }
    
    $debug_info['error'] = $e->getMessage();
    http_response_code(500);
    echo json_encode([
        'error' => 'Failed to update order status: ' . $e->getMessage(),
        'debug' => $debug_info
    ]);
}
?>