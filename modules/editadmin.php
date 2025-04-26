<?php
include '../base.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'Admin') {
    $_SESSION['message'] = 'Please log in to access this page';
    header("Location: adminlogin.php");
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
    die("Connection failed: " . $conn->connect_error);
}

// Get admin ID from URL
$adminId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch admin data
$query = "SELECT * FROM admin WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $adminId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error_message'] = 'Administrator not found';
    header("Location: profile.php");
    exit();
}

$admin = $result->fetch_assoc();

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $role = $_POST['role'] ?? 'Administrator';
    $password = $_POST['password'] ?? '';
    
    if (empty($name) || empty($email)) {
        $_SESSION['error_message'] = 'Name and email are required';
    } else {
    // Update your password handling code in editadmin.php
    if (!empty($password)) {
        // Update with new password - Use SHA1 to match your login functionality
        $query = "UPDATE admin SET name = ?, email = ?, role = ?, password = SHA1(?) WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $name, $email, $role, $password, $adminId);
    } else {
        // Update without changing password
        $query = "UPDATE admin SET name = ?, email = ?, role = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $name, $email, $role, $adminId);
    }
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'Administrator updated successfully';
            header("Location: adminProfile.php");
            exit();
        } else {
            $_SESSION['error_message'] = 'Failed to update administrator';
        }
    }
}

include '../header.php';
?>

<div class="admin-profile-container">
    <link rel="stylesheet" href="../css/style.css">
    
    <main class="admin-profile-content">
        <h1 class="admin-profile-section-title">Edit Administrator</h1>
        
        <?php if(isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
        </div>
        <?php endif; ?>
        
        <div class="admin-profile-card">
            <form method="post" class="admin-profile-form">
                <div class="admin-profile-form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" class="admin-profile-form-control" 
                           value="<?= htmlspecialchars($admin['name']) ?>" required>
                </div>
                
                <div class="admin-profile-form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="admin-profile-form-control" 
                           value="<?= htmlspecialchars($admin['email']) ?>" required>
                </div>
                
                <div class="admin-profile-form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" class="admin-profile-form-control">
                        <option value="Administrator" <?= $admin['role'] == 'Administrator' ? 'selected' : '' ?>>Administrator</option>
                        <option value="Manager" <?= $admin['role'] == 'Manager' ? 'selected' : '' ?>>Manager</option>
                        <option value="Editor" <?= $admin['role'] == 'Editor' ? 'selected' : '' ?>>Editor</option>
                    </select>
                </div>
                
                <div class="admin-profile-form-group">
                    <label for="password">New Password (leave blank to keep current)</label>
                    <input type="password" id="password" name="password" class="admin-profile-form-control">
                </div>
                
                <div class="admin-profile-form-actions">
                    <button type="submit" class="admin-profile-btn admin-profile-btn-primary">Save Changes</button>
                    <a href="profile.php" class="admin-profile-btn admin-profile-btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </main>
</div>

<?php include '../footer.php'; ?>