<?php
session_start();

// Initialize cart if not exists
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if product data is received
if (isset($_POST['id'], $_POST['name'], $_POST['price'], $_POST['quantity'])) {
    $productId = $_POST['id'];
    $productName = $_POST['name'];
    $productPrice = floatval($_POST['price']);
    $quantity = intval($_POST['quantity']);
    
    // Check if product already exists in cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            // Increment quantity
            $item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }
    
    // If product not found, add it to cart
    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $productId,
            'name' => $productName,
            'price' => $productPrice,
            'quantity' => $quantity
        ];
    }
    
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid product data']);
}