
<?php
include '../base.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'Customer') {
    $_SESSION['error_message'] = 'Please log in to update your profile';
    header("Location: customerlogin.php");
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get customer ID from session
    $customerId = isset($_SESSION['user']->id) ? $_SESSION['user']->id : (isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : 1);

    // Get form data
    $fullName = $_POST['name'] ?? '';
    $birthday = $_POST['birthday'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $phone = $_POST['phoneNumber'] ?? '';
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
        header("Location: customerprofile.php");
        exit();
    }

    // Handle profile picture upload if provided
    $profilePicturePath = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/profile_pictures/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileName = 'customer_' . $customerId . '_' . time() . '.jpg';
        $uploadFile = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadFile)) {
            $profilePicturePath = $uploadFile;
        }
    }

    // Update customer profile in database
    $query = "UPDATE customer SET name = ?, phoneNumber = ?, email = ?, birthday = ?, gender = ?";
    $params = [$fullName, $phone, $email, $birthday, $gender];
    $types = "sssss";

    if ($profilePicturePath) {
        $query .= ", profile_picture = ?";
        $params[] = $profilePicturePath;
        $types .= "s";
    }

    $query .= " WHERE id = ?";
    $params[] = $customerId;
    $types .= "i";

    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        // Fetch updated user data and update session
        $result = $conn->query("SELECT * FROM customer WHERE id = $customerId");
        if ($result && $result->num_rows > 0) {
            $_SESSION['user'] = (object)$result->fetch_assoc(); // Store as object for consistency
        }
        $_SESSION['success_message'] = "Profile updated successfully!";
    } else {
        $_SESSION['error_message'] = "Error updating profile: " . $conn->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: customerprofile.php");
    exit();
} else {
    header("Location: customerprofile.php");
    exit();
}

?>