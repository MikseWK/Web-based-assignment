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

// Authorization
function auth(...$roles){
    global $_user;
    if ($_user) {
        if ($roles) {
            if (in_array($_user->role, $roles)){
                return;
            }
        }
        else {
            return;
        }
    }

    redirect('/switchRole.php');
}

//database for customer signup and login
$_db = new PDO('mysql:dbname=assignment', 'root', '', [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
]);