<?php
require_once '../base.php';

// Check if user is logged in
if (!is_logged_in()) {
    echo json_encode([
        'success' => false,
        'message' => 'You must be logged in to create an order'
    ]);
    exit;
}

// Create order from cart
$order_id = create_order_from_cart();

if ($order_id) {
    echo json_encode([
        'success' => true,
        'order_id' => $order_id,
        'message' => 'Order created successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to create order. Your cart might be empty.'
    ]);
}