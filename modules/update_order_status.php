<?php
require_once '../base.php';

// Check if user is logged in
if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
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

// Connect to database
$conn = get_db_connection();

// Debug information
error_log("Attempting to update order ID: " . $order_id . " with status: " . $status);

// Update order status using the correct column names
$stmt = $conn->prepare("UPDATE orders SET status = ?, payment_method = ?, updated_at = NOW() WHERE id = ?");
$stmt->bind_param("ssi", $status, $payment_intent, $order_id);

if ($stmt->execute()) {
    error_log("Order updated successfully");
    
    // Store the customer ID and completed status in session
    // but don't clear the cart yet - we'll do that after confirmation
    if ($status === 'completed') {
        $_SESSION['order_completed'] = true;
        $_SESSION['order_customer_id'] = get_user_id();
        // Keep order_reference for the confirmation page
    }
    
    echo json_encode(['success' => true]);
} else {
    error_log("Failed to update order: " . $conn->error);
    http_response_code(500);
    echo json_encode(['error' => 'Failed to update order status: ' . $conn->error]);
}

$conn->close();
?>