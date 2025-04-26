<?php
include '../base.php';
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}



// Check if file was uploaded
if (!isset($_FILES['profile_picture']) || $_FILES['profile_picture']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error']);
    exit();
}

$file = $_FILES['profile_picture'];
$user_id = $_SESSION['user_id'];

// Validate file type
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
if (!in_array($file['type'], $allowedTypes)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, and GIF are allowed.']);
    exit();
}

// Validate file size (max 2MB)
$maxSize = 2 * 1024 * 1024; // 2MB in bytes
if ($file['size'] > $maxSize) {
    echo json_encode(['success' => false, 'message' => 'File is too large. Maximum size is 2MB.']);
    exit();
}

// Create uploads directory if it doesn't exist
$uploadDir = '../uploads/profile_pictures/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Generate unique filename
$filename = $user_id . '_' . time() . '_' . basename($file['name']);
$uploadPath = $uploadDir . $filename;

// Move uploaded file
if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
    // Get relative path for database storage
    $relativePath = 'uploads/profile_pictures/' . $filename;
    
    // Update user profile picture in database
    $stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
    $stmt->bind_param("si", $relativePath, $user_id);
    
    if ($stmt->execute()) {
        // Update session
        $_SESSION['profile_picture'] = $relativePath;
        
        echo json_encode([
            'success' => true, 
            'message' => 'Profile picture updated successfully',
            'image_url' => $relativePath
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update profile picture in database: ' . $conn->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to upload file']);
}

$conn->close();
?>