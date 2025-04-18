<?php
session_start();

// Initialize cart if not exists
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Calculate cart total
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Return cart data
echo json_encode([
    'count' => count($_SESSION['cart']),
    'items' => $_SESSION['cart'],
    'total' => $total
]);