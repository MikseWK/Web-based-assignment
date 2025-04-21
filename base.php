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

// Obtain uploaded file --> cast to object
function get_file($key) {
    $f = $_FILES[$key] ?? null;
    
    if ($f && $f['error'] == 0) {
        return (object)$f;
    }

    return null;
}

// Crop, resize and save photo
function save_photo($f, $folder, $width = 200, $height = 200) {
    $photo = uniqid() . '.jpg';
    
    require_once 'lib/SimpleImage.php';
    $img = new SimpleImage();
    $img->fromFile($f->tmp_name)
        ->thumbnail($width, $height)
        ->toFile("$folder/$photo", 'image/jpeg');

    return $photo;
}

// Is money?
function is_money($value) {
    return preg_match('/^\-?\d+(\.\d{1,2})?$/', $value);
}

// Is email?
function is_email($value) {
    return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
}

function redirect($url = null) {
    $url ??= $_SERVER['REQUEST_URI'];
    header("Location: $url");    
    exit();
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

function isCustomer(): bool{
    return isset($_SESSION['role']) && $_SESSION['role'] == 'Customer';
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

// Get cart count (number of items)
function get_cart_count() {
    global $_db;
    
    if (!is_logged_in()) {
        return 0;
    }
    
    $user_id = get_user_id();
    $stmt = $_db->prepare("SELECT SUM(quantity) as count FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch(PDO::FETCH_OBJ);
    
    return $result->count ?? 0;
}

// Get cart items for logged-in user from database
function get_cart_items() {
    global $_db;
    
    if (!is_logged_in()) {
        return [];
    }
    
    $user_id = get_user_id();
    $stmt = $_db->prepare("
        SELECT c.*, p.name, p.price, p.photo 
        FROM cart c
        JOIN product p ON c.product_id = p.id
        WHERE c.user_id = ?
    ");
    $stmt->execute([$user_id]);
    
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function get_cart_total() {
    global $_db;
    
    if (!is_logged_in()) {
        return 0;
    }
    
    $user_id = get_user_id();
    $stmt = $_db->prepare("
        SELECT SUM(c.quantity * p.price) as total
        FROM cart c
        JOIN product p ON c.product_id = p.id
        WHERE c.user_id = ?
    ");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch(PDO::FETCH_OBJ);
    
    return $result->total ?? 0;
}

function add_to_cart($product_id, $quantity = 1) {
    global $_db;
    
    if (!is_logged_in()) {
        return false;
    }
    
    $user_id = get_user_id();
    
    // Check if product already in cart
    $stmt = $_db->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $existing = $stmt->fetch(PDO::FETCH_OBJ);
    
    if ($existing) {
        // Update quantity
        $stmt = $_db->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
        return $stmt->execute([$quantity, $user_id, $product_id]);
    } else {
        // Insert new item
        $stmt = $_db->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        return $stmt->execute([$user_id, $product_id, $quantity]);
    }
}

function remove_from_cart($product_id) {
    global $_db;
    
    if (!is_logged_in()) {
        return false;
    }
    
    $user_id = get_user_id();
    $stmt = $_db->prepare("DELETE FROM cart WHERE customer_id = ? AND product_id = ?");
    return $stmt->execute([$user_id, $product_id]);
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

// Generate <input type='hidden'>
function html_hidden($key, $attr = '') {
    $value ??= encode($GLOBALS[$key] ?? '');
    echo "<input type='hidden' id='$key' name='$key' value='$value' $attr>";
}

// Generate <input type='text'>
function html_text($key, $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='text' id='$key' name='$key' value='$value' $attr>";
}

// Generate <input type='password'>
function html_password($key, $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='password' id='$key' name='$key' value='$value' $attr>";
}

// Generate <input type='number'>
function html_number($key, $min = '', $max = '', $step = '', $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='number' id='$key' name='$key' value='$value'
                 min='$min' max='$max' step='$step' $attr>";
}

// Generate <input type='search'>
function html_search($key, $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='search' id='$key' name='$key' value='$value' $attr>";
}

// Generate <textarea>
function html_textarea($key, $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<textarea id='$key' name='$key' $attr>$value</textarea>";
}

// Generate SINGLE <input type='checkbox'>
function html_checkbox($key, $label = '', $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    $status = $value == 1 ? 'checked' : '';
    echo "<label><input type='checkbox' id='$key' name='$key' value='1' $status $attr>$label</label>";
}

// Generate <input type='radio'> list
function html_radios($key, $items, $br = false) {
    $value = encode($GLOBALS[$key] ?? '');
    echo '<div>';
    foreach ($items as $id => $text) {
        $state = $id == $value ? 'checked' : '';
        echo "<label><input type='radio' id='{$key}_$id' name='$key' value='$id' $state>$text</label>";
        if ($br) {
            echo '<br>';
        }
    }
    echo '</div>';
}

// Generate <select>
function html_select($key, $items, $default = '- Select One -', $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<select id='$key' name='$key' $attr>";
    if ($default !== null) {
        echo "<option value=''>$default</option>";
    }
    foreach ($items as $id => $text) {
        $state = $id == $value ? 'selected' : '';
        echo "<option value='$id' $state>$text</option>";
    }
    echo '</select>';
}

// Generate <input type='file'>
function html_file($key, $accept = '', $attr = '') {
    echo "<input type='file' id='$key' name='$key' accept='$accept' $attr>";
}

/**
 * Get database connection
 * @return mysqli Database connection
 */
function get_db_connection() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "assignment";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}