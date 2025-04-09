<?php

// ============================================================================
// PHP Setups
// ============================================================================

date_default_timezone_set('Asia/Kuala_Lumpur');
session_start();

function is_get() {
    return $_SERVER['REQUEST_METHOD'] == 'GET';
}


function is_post() {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function encode($value) {
    return htmlentities($value);
}

// Obtain GET parameter
function get($key, $value = null) {
    $value = $_GET[$key] ?? $value;
    return is_array($value) ? array_map('trim', $value) : trim($value);
}

// Obtain POST parameter
function post($key, $value = null) {
    $value = $_POST[$key] ?? $value;
    return is_array($value) ? array_map('trim', $value) : trim($value);
}

// Obtain REQUEST (GET and POST) parameter
function req($key, $value = null) {
    $value = $_REQUEST[$key] ?? $value;
    return is_array($value) ? array_map('trim', $value) : trim($value);
}


function redirect($url = null) {
    $url ??= $_SERVER['REQUEST_URI'];
    header("Location: $url");    
    exit();
}

function is_email($value) {
    return filter_var($value, FILTER_VALIDATE_EMAIL) !== false; 
}

// Generate table headers <th>
function table_headers($fields, $sort, $dir, $href = '') {
    foreach ($fields as $k => $v) {
        $d = 'asc'; // Default direction
        $c = '';    // Default class
        
        if ($k == $sort) {
            $d = $dir == 'asc' ? 'desc' : 'asc';
            $c = $dir;
        }

        echo "<th><a href='?sort=$k&dir=$d&$href' class='$c'>$v</a></th>";
    }
}

//Error
//Global error array
$_err = [];

//Security
//Global user object
$_user = $_SESSION['user'] ?? null;

//Login user
function login($user, $url){
    $_SESSION['user'] = $user;
    redirect($url ?? '/index.php');
}

//Logout user
function logout($url){
    unset($_SESSION['user']);
    redirect($url ?? '/index.php');
}

//Verify is admin
function isAdmin(): bool{
    return isset($_SESSION['role']) && $_SESSION['role'] == 'Admin';
}

// Authorization
function auth(...$roles){
    global $_user;
    if ($_user) {
        if (in_array($_SESSION['role'], $roles)){
            return;
        }
    }

    redirect('/index.php');
}

//database for customer signup and login
$_db = new PDO('mysql:dbname=assignment', 'root', '', [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
]);

// ============================================================================
// Shopping Cart
// ============================================================================

// Check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user']);
}

// Get user ID if logged in
function get_user_id() {
    global $_user;
    return $_user->id ?? null;
}

// Get cart items for logged-in user from database
function get_cart_items() {
    global $_db;
    $items = [];
    
    if (is_logged_in()) {
        $user_id = get_user_id();
        $stmt = $_db->prepare("SELECT c.product_id, c.quantity, p.name, p.price, p.photo 
                              FROM cart c 
                              JOIN product p ON c.product_id = p.id 
                              WHERE c.user_id = ?");
        $stmt->execute([$user_id]);
        $items = $stmt->fetchAll();
    }
    
    return $items;
}

// Get cart count (number of items)
function get_cart_count() {
    global $_db;
    
    if (is_logged_in()) {
        $user_id = get_user_id();
        $stmt = $_db->prepare("SELECT SUM(quantity) FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return (int)$stmt->fetchColumn() ?: 0;
    }
    
    return 0;
}

// Get cart total price
function get_cart_total() {
    global $_db;
    
    if (is_logged_in()) {
        $user_id = get_user_id();
        $stmt = $_db->prepare("SELECT SUM(c.quantity * p.price) 
                              FROM cart c 
                              JOIN product p ON c.product_id = p.id 
                              WHERE c.user_id = ?");
        $stmt->execute([$user_id]);
        return (float)$stmt->fetchColumn() ?: 0;
    }
    
    return 0;
}

// Add item to cart
function add_to_cart($product_id, $quantity = 1) {
    global $_db;
    
    if (!is_logged_in()) {
        return false;
    }
    
    if (!is_exists($product_id, 'product', 'id')) {
        return false;
    }
    
    $user_id = get_user_id();
    
    // Check if product already in cart
    $stmt = $_db->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $current_quantity = $stmt->fetchColumn();
    
    try {
        if ($current_quantity !== false) {
            // Update existing cart item
            $new_quantity = $current_quantity + $quantity;
            $stmt = $_db->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
            $result = $stmt->execute([$new_quantity, $user_id, $product_id]);
            return $result;
        } else {
            // Add new cart item
            $stmt = $_db->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $result = $stmt->execute([$user_id, $product_id, $quantity]);
            return $result;
        }
    } catch (PDOException $e) {
        // Log error for debugging
        error_log("Cart error: " . $e->getMessage());
        return false;
    }
}

// Update cart item quantity
function update_cart_quantity($product_id, $quantity) {
    global $_db;
    
    if (!is_logged_in()) {
        return false;
    }
    
    $user_id = get_user_id();
    
    if ($quantity <= 0) {
        // Remove item from cart if quantity is 0 or negative
        return remove_from_cart($product_id);
    } else {
        // Update quantity
        $stmt = $_db->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        return $stmt->execute([$quantity, $user_id, $product_id]);
    }
}

// Remove item from cart
function remove_from_cart($product_id) {
    global $_db;
    
    if (!is_logged_in()) {
        return false;
    }
    
    $user_id = get_user_id();
    $stmt = $_db->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    return $stmt->execute([$user_id, $product_id]);
}

// Clear entire cart
function clear_cart() {
    global $_db;
    
    if (!is_logged_in()) {
        return false;
    }
    
    $user_id = get_user_id();
    $stmt = $_db->prepare("DELETE FROM cart WHERE user_id = ?");
    return $stmt->execute([$user_id]);
}

// Is exists?
function is_exists($value, $table, $field) {
    global $_db;
    $stm = $_db->prepare("SELECT COUNT(*) FROM $table WHERE $field = ?");
    $stm->execute([$value]);
    return $stm->fetchColumn() > 0;
}


// Debug function to check cart operations
function debug_cart_operation($operation, $product_id, $result) {
    error_log("Cart $operation: Product ID = $product_id, Result = " . ($result ? 'Success' : 'Failed'));
    return $result;
}

//temp
function is_money($value) {
    return preg_match('/^\-?\d+(\.\d{1,2})?$/', $value);
}
// Generate <input type='text'>
function html_text($key, $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='text' id='$key' name='$key' value='$value' $attr>";
}

// Generate <input type='number'>
function html_number($key, $min = '', $max = '', $step = '', $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='number' id='$key' name='$key' value='$value'
                 min='$min' max='$max' step='$step' $attr>";
}
// Generate <input type='file'>
function html_file($key, $accept = '', $attr = '') {
    echo "<input type='file' id='$key' name='$key' accept='$accept' $attr>";
}