<?php
session_start();

// Check if cart exists and index is provided
if (isset($_SESSION['cart'], $_POST['index']) && is_array($_SESSION['cart'])) {
    $index = intval($_POST['index']);
    
    // Check if index exists in cart
    if (isset($_SESSION['cart'][$index])) {
        // Remove item from cart
        array_splice($_SESSION['cart'], $index, 1);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Item not found in cart']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}