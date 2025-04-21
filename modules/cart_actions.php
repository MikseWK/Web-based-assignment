<?php
require '../base.php';

// Check if user is logged in
if (!is_logged_in()) {
    echo json_encode([
        'success' => false,
        'message' => 'You must be logged in to manage your cart'
    ]);
    exit;
}

$user_id = get_user_id();

// Get the action parameter
$action = $_REQUEST['action'] ?? '';

switch ($action) {
    case 'add':
        // Add item to cart
        $product_id = $_POST['product_id'] ?? 0;
        $quantity = (int)($_POST['quantity'] ?? 1);
        
        if ($product_id && $quantity > 0) {
            // Check if product exists
            $stmt = $_db->prepare("SELECT id FROM product WHERE id = ?");
            $stmt->execute([$product_id]);
            if (!$stmt->fetch()) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Product not found'
                ]);
                exit;
            }
            
            // Check if product already in cart
            $stmt = $_db->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$user_id, $product_id]);
            $current_quantity = $stmt->fetchColumn();
            
            if ($current_quantity !== false) {
                // Update existing cart item
                $new_quantity = $current_quantity + $quantity;
                $stmt = $_db->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
                $result = $stmt->execute([$new_quantity, $user_id, $product_id]);
            } else {
                // Add new cart item
                $stmt = $_db->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
                $result = $stmt->execute([$user_id, $product_id, $quantity]);
            }
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Item added to cart'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to add item to cart'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid product or quantity'
            ]);
        }
        break;
        
    case 'remove':
        // Remove item from cart
        $product_id = $_POST['product_id'] ?? 0;
        
        if ($product_id) {
            $stmt = $_db->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
            $result = $stmt->execute([$user_id, $product_id]);
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Item removed from cart'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to remove item from cart'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid product ID'
            ]);
        }
        break;
        
    case 'get_items':
        // Get cart items
        $stmt = $_db->prepare("SELECT c.product_id, c.quantity, p.name, p.price, p.photo 
                              FROM cart c 
                              JOIN product p ON c.product_id = p.id 
                              WHERE c.user_id = ?");
        $stmt->execute([$user_id]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calculate total
        $stmt = $_db->prepare("SELECT SUM(c.quantity * p.price) 
                              FROM cart c 
                              JOIN product p ON c.product_id = p.id 
                              WHERE c.user_id = ?");
        $stmt->execute([$user_id]);
        $total = $stmt->fetchColumn() ?: 0;
        
        // Get cart count
        $stmt = $_db->prepare("SELECT SUM(quantity) FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $count = (int)$stmt->fetchColumn() ?: 0;
        
        echo json_encode([
            'success' => true,
            'items' => $items,
            'total' => $total,
            'count' => $count
        ]);
        break;
        
    case 'get_description':
        // Get product ID from request
        $product_id = $_GET['product_id'] ?? 0;
        
        if ($product_id) {
            // Fetch product description from database
            $stmt = $_db->prepare("SELECT description FROM product WHERE id = ?");
            $stmt->execute([$product_id]);
            $description = $stmt->fetchColumn();
            
            if ($description !== false) {
                echo json_encode([
                    'success' => true,
                    'description' => $description
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Product description not found'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid product ID'
            ]);
        }
        break;
        
    default:
        echo json_encode([
            'success' => false,
            'message' => 'Invalid action'
        ]);}
