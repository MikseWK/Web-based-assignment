<?php
include '../base.php';

// Set content type to JSON - THIS IS THE IMPORTANT ADDITION
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'Admin') {
    echo json_encode(['success' => false, 'message' => 'Not authorized']);
    exit();
}

// Create database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "assignment";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

// Get admin ID from POST request
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $adminId = (int)$_POST['id'];
    
    // Don't allow deleting your own account
    if ($adminId == $_SESSION['id'] || $adminId == ($_SESSION['id'] ?? 0)) {
        echo json_encode(['success' => false, 'message' => 'Cannot delete your own account']);
        exit();
    }
    
    // Delete the admin
    $query = "DELETE FROM admin WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $adminId);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Administrator deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete administrator: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid admin ID']);
}

$conn->close();
?>