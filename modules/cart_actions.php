<?php
require_once '../base.php';
session_start();

// Ensure customer is logged in
if (!isset($_SESSION['customer_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Please log in to continue']);
    exit;
}

$customer_id = $_SESSION['customer_id'];

// Handle cart submission for checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_items'])) {
    $cart_items = json_decode($_POST['cart_items'], true);
    
    if (empty($cart_items)) {
        $_SESSION['error'] = "Your cart is empty.";
        header('Location: product.php');
        exit;
    }
    
    try {
        // Begin transaction
        $_db->beginTransaction();
        
        // Create a new order
        $order_stmt = $_db->prepare("INSERT INTO orders (customer_id, order_date, status) VALUES (?, NOW(), 'pending')");
        $order_stmt->execute([$customer_id]);
        $order_id = $_db->lastInsertId();
        
        // Add order items
        $item_stmt = $_db->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price, subtotal) VALUES (?, ?, ?, ?, ?)");
        
        foreach ($cart_items as $item) {
            $product_id = $item['id'];
            $quantity = $item['quantity'];
            $price = $item['price'];
            $subtotal = $price * $quantity;
            
            $item_stmt->execute([$order_id, $product_id, $quantity, $price, $subtotal]);
        }
        
        // Commit transaction
        $_db->commit();
        
        // Store order ID in session for checkout
        $_SESSION['pending_order_id'] = $order_id;
        
        // Redirect to checkout
        header('Location: checkout.php');
        exit;
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $_db->rollBack();
        $_SESSION['error'] = "Error creating order: " . $e->getMessage();
        header('Location: product.php');
        exit;
    }
}

// Return JSON response for AJAX requests
header('Content-Type: application/json');
echo json_encode(['success' => false, 'message' => 'Invalid request']);