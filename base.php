<?php

// ============================================================================
// PHP Setups
// ============================================================================

date_default_timezone_set('Asia/Kuala_Lumpur');
session_start();

// Initialize global user object
$_user = $_SESSION['user'] ?? null;

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

// Improved user session handling
// Check if user is logged in
function is_logged_in() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Get current user role
function get_user_role() {
    return $_SESSION['user_role'] ?? null;
}

// Check if user is admin
function is_admin() {
    return is_logged_in() && get_user_role() === 'admin';
}

// Check if user is customer
function is_customer() {
    return is_logged_in() && get_user_role() === 'customer';
}

// Login user
function login($user_id, $name, $role, $url = null) {
    $_SESSION['logged_in'] = true;
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_name'] = $name;
    $_SESSION['user_role'] = $role;
    redirect($url ?? '/index.php');
}

// Logout user
function logout($url = null) {
    // Clear all session variables
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
    
    // Start a new session
    session_start();
    
    redirect($url ?? '/index.php');
}

// Authorization
function auth($required_role = null) {
    if (!is_logged_in()) {
        $_SESSION['message'] = "You must be logged in to access this page.";
        redirect('/login.php');
    }
    
    if ($required_role && get_user_role() !== $required_role) {
        $_SESSION['message'] = "You don't have permission to access this page.";
        redirect('/index.php');
    }
}

//database for customer signup and login
// $_db = new PDO('mysql:dbname=assignment', 'root', '', [
//     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
// ]);
// commend the database to test with dummy data

// Database connection
// try {
//     $pdo = new PDO('mysql:host=localhost;dbname=assignment', 'root', '', [
//         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
//     ]);
// } catch (PDOException $e) {
//     die("Database connection failed: " . $e->getMessage());
// }