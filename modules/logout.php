<?php
require '../base.php';

// Store the user role before destroying the session
$isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin';

// Clear all session data
$_SESSION = array();
session_destroy();

// Start a new session to store the logout message
session_start();
$_SESSION['message'] = 'You have been logged out successfully';

// Redirect based on previous role
if ($isAdmin) {
    header('Location: adminlogin.php');
} else {
    header('Location: customerlogin.php');
}
exit;
?>