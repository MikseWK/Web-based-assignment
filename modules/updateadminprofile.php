<?php
include '../base.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'Admin') {
    $_SESSION['error_message'] = 'Please log in to update your profile';
    header("Location: adminlogin.php");
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get admin ID from session
    $adminId = $_SESSION['admin_id'] ?? $_SESSION['id'] ?? 1;
    
    // Get form data
    $fullName = $_POST['fullName'] ?? '';
    $role = $_POST['role'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "assignment";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        $_SESSION['error_message'] = "Connection failed: " . $conn->connect_error;
        header("Location: adminProfile.php");
        exit();
    }
    
    // Handle profile picture upload if provided
    $profilePicturePath = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../assets/images/';
        
        // Create directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = 'admin_' . $adminId . '_' . time() . '_' . basename($_FILES['profile_picture']['name']);
        $uploadFile = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadFile)) {
            $profilePicturePath = $uploadFile;
        }
    }
    
    // Update admin profile in database
    $query = "UPDATE admin SET name = ?, role = ?, phoneNumber = ?, email = ?"; // Use 'phoneNumber' as per your database schema
    $params = [$fullName, $role, $phone, $email];
    $types = "ssss";
    
    if ($profilePicturePath) {
        $query .= ", profilePicture = ?"; // Use 'profilePicture' as per your database schema
        $params[] = $profilePicturePath;
        $types .= "s";
    }
    
    $query .= " WHERE id = ?";
    $params[] = $adminId;
    $types .= "i";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Profile updated successfully!";
    } else {
        $_SESSION['error_message'] = "Error updating profile: " . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
    
    // Redirect back to profile page
    header("Location: adminProfile.php");
    exit();
} else {
    // If not a POST request, redirect to profile page
    header("Location: adminProfile.php");
    exit();
}
?>