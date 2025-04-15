<?php
require '../base.php';

// Clear all session data
session_unset();
session_destroy();

// Start a new session to set a message
session_start();
$_SESSION['message'] = 'You have been logged out successfully';

// Redirect to home page
redirect('/index.php');
?>