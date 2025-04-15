<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

// Include database connection
require_once '../includes/db_connection.php';

// Get form data
$fullName = $_POST['fullName'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$address = $_POST['address'] ?? '';
$user_id = $_SESSION['user_id'];

// Validate data
if (empty($fullName) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Name and email are required']);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit();
}

// Check if email already exists for another user
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
$stmt->bind_param("si", $email, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Email already in use by another account']);
    exit();
}
$stmt->close();

// Update user data
$stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
$stmt->bind_param("ssssi", $fullName, $email, $phone, $address, $user_id);

if ($stmt->execute()) {
    // Update session data
    $_SESSION['user_name'] = $fullName;
    $_SESSION['user_email'] = $email;
    
    echo json_encode([
        'success' => true, 
        'message' => 'Profile updated successfully',
        'name' => $fullName,
        'email' => $email
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update profile: ' . $conn->error]);
}

$stmt->close();
$conn->close();
?>