<?php
require '../base.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in as admin
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'Admin') {
    $_SESSION['message'] = 'Please log in to access admin features';
    header("Location: adminlogin.php");
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'Administrator';
    $phone = $_POST['phone'] ?? '';
    
    // Create database connection directly
    $servername = "localhost";
    $username = "root";
    $dbpassword = "";
    $dbname = "assignment";

    // Create connection
    $conn = new mysqli($servername, $username, $dbpassword, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Insert new admin
    $query = "INSERT INTO admin (name, email, password, phoneNumber, role) VALUES (?, ?, SHA1(?), ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $name, $email, $password, $phone, $role);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = 'Admin added successfully';
    } else {
        $_SESSION['message'] = 'Error adding admin: ' . $conn->error;
    }
    
    $conn->close();
    
    // Redirect back to admin profile
    header("Location: adminProfile.php");
    exit();
} else {
    // If not a POST request, redirect to admin profile
    header("Location: adminProfile.php");
    exit();
}
?>