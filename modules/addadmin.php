<?php
include '../base.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in as admin
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'Admin') {
    $_SESSION['message'] = 'You do not have permission to add administrators';
    header("Location: adminlogin.php");
    exit();
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include database connection
    include_once '../config/database.php';
    
    // Get form data
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $role = $_POST['role'] ?? 'Administrator';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Validate form data
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match";
    }
    
    // Check if email already exists
    $query = "SELECT id FROM admins WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $errors[] = "Email already exists";
    }
    
    // If there are no errors, insert the new admin
    if (empty($errors)) {
        // Hash the password
        $hashedPassword = sha1($password); // Using SHA1 to match your existing password hashing
        
        // Insert the new admin
        $query = "INSERT INTO admins (name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Administrator added successfully";
            header("Location: adminProfile.php");
            exit();
        } else {
            $_SESSION['message'] = "Error adding administrator: " . $conn->error;
            header("Location: adminProfile.php");
            exit();
        }
    } else {
        // If there are errors, store them in session and redirect back
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header("Location: adminProfile.php");
        exit();
    }
} else {
    // If not a POST request, redirect to admin profile
    header("Location: adminProfile.php");
    exit();
}
?>