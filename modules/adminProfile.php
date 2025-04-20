<?php
include '../base.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'Admin') {
    $_SESSION['message'] = 'Please log in to access your profile';
    header("Location: adminlogin.php");
    exit();
}

// Create database connection directly instead of including database.php
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

// Get admin data from database - use id from session if available, otherwise use a default value
// Change this line to use the correct session variable
$adminId = $_SESSION['admin_id'] ?? $_SESSION['id'] ?? 1; // Try both possible session variables
// Remove debugging echo statement

$query = "SELECT * FROM admin WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $adminId);
$stmt->execute();
$result = $stmt->get_result();
$adminArray = $result->fetch_assoc();

// If no admin found with that ID, try to find by name
if (!$adminArray && isset($_SESSION['name'])) {
    $query = "SELECT * FROM admin WHERE name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $_SESSION['name']);
    $stmt->execute();
    $result = $stmt->get_result();
    $adminArray = $result->fetch_assoc();
    
    if ($adminArray) {
        $adminId = $adminArray['id']; // Update adminId if found by name
    }
}
$query = "SELECT * FROM admin WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $adminId);
$stmt->execute();
$result = $stmt->get_result();
$adminArray = $result->fetch_assoc();

// Fetch all admins for the admin list
$query = "SELECT id, name, email FROM admin";
$result = $conn->query($query);
$allAdmins = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $allAdmins[] = $row;
    }
}

include '../header.php';
?>

<div class="admin-profile-container">
    <link rel="stylesheet" href="../css/style.css">
    
    <aside class="admin-profile-sidebar">
        <div class="admin-profile-user-info">
            <img src="<?= isset($adminArray['profile_picture']) && !empty($adminArray['profile_picture']) ? $adminArray['profile_picture'] : '../assets/images/default-admin.png' ?>" alt="Admin Profile">
            <h3 class="admin-profile-user-name">Hello<br><?= $adminArray['name'] ?? 'Admin' ?></h3>
        </div>
        
        <ul class="admin-profile-menu">
            <li class="admin-profile-menu-item active">
                <i class="fas fa-user-shield"></i>
                <a href="#account">My Account</a>
            </li>
        
            <li class="admin-profile-menu-item">
                <i class="fas fa-shopping-basket"></i>
                <a href="#manage-products">Manage Products</a>
            </li>
            <li class="admin-profile-menu-item">
                <i class="fas fa-chart-line"></i>
                <a href="#analytics">Analytics</a>
            </li>
        
            <li class="admin-profile-menu-item">
                <i class="fas fa-lock"></i>
                <a href="#password">Change Password</a>
            </li>
            <li class="admin-profile-menu-item">
                <i class="fas fa-sign-out-alt"></i>
                <a href="../modules/logout.php">Logout</a>
            </li>
        </ul>
    </aside>

    <main class="admin-profile-content">
        <h1 class="admin-profile-section-title">Admin Profile</h1>
        
        <div class="admin-profile-card">
            <div class="admin-profile-card-header">
                <h2 class="admin-profile-card-title">Personal Information</h2>
                <button class="admin-profile-edit-btn" id="adminEditProfileBtn">
                    <i class="fas fa-edit"></i> Change Profile Information
                </button>
            </div>
            
            <!-- Add success message display -->
            <?php if(isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success_message']; ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
            
            <!-- Add error message display -->
            <?php if(isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error_message']; ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
            
            <div id="adminProfileViewMode">
                <div class="admin-profile-user-photo">
                    <img src="<?= isset($adminArray['profile_picture']) && !empty($adminArray['profile_picture']) ? $adminArray['profile_picture'] : '../assets/images/default-admin.png' ?>" alt="Profile">
                    <div class="admin-profile-photo-edit" id="adminViewModePhotoEdit">
                        <i class="fas fa-camera"></i>
                    </div>
                </div>
                
                <div class="admin-profile-info-row">
                    <div class="admin-profile-info-label">Name</div>
                    <div class="admin-profile-info-value"><?= $adminArray['name'] ?? 'Admin User' ?></div>
                </div>
                
                <div class="admin-profile-info-row">
                    <div class="admin-profile-info-label">Role</div>
                    <div class="admin-profile-info-value"><?= $adminArray['role'] ?? 'Administrator' ?></div>
                </div>
                
                <div class="admin-profile-info-row">
                    <div class="admin-profile-info-label">Phone Number</div>
                    <div class="admin-profile-info-value"><?= $adminArray['phone'] ?? '+90-12345678' ?></div>
                </div>
                
                <div class="admin-profile-info-row">
                    <div class="admin-profile-info-label">Email</div>
                    <div class="admin-profile-info-value"><?= $adminArray['email'] ?? 'admin@example.com' ?></div>
                </div>
                
                <div class="admin-profile-info-row">
                    <div class="admin-profile-info-label">Last Login</div>
                    <div class="admin-profile-info-value"><?= $adminArray['last_login'] ?? date('Y-m-d H:i:s') ?></div>
                </div>
            </div>
            
            <form id="adminProfileForm" method="post" action="../modules/updateadminprofile.php" class="admin-profile-form" style="display: none;" enctype="multipart/form-data">
                <div class="admin-profile-user-photo">
                    <img id="adminProfilePreview" src="<?= isset($adminArray['profile_picture']) && !empty($adminArray['profile_picture']) ? $adminArray['profile_picture'] : '../assets/images/default-admin.png' ?>" alt="Profile">
                    <div class="admin-profile-photo-edit" id="adminEditModePhotoEdit">
                        <i class="fas fa-camera"></i>
                    </div>
                    <input type="file" id="adminProfilePictureInput" name="profile_picture" accept="image/*" style="display: none;">
                </div>
                
                <div class="admin-profile-form-group">
                    <label for="adminFullName">Full Name</label>
                    <input type="text" id="adminFullName" name="fullName" class="admin-profile-form-control" value="<?= $adminArray['name'] ?? '' ?>" required>
                </div>
                
                <div class="admin-profile-form-group">
                    <label for="adminRole">Role</label>
                    <select id="adminRole" name="role" class="admin-profile-form-control">
                        <option value="Administrator" <?= ($adminArray['role'] ?? '') == 'Administrator' ? 'selected' : '' ?>>Administrator</option>
                        <option value="Manager" <?= ($adminArray['role'] ?? '') == 'Manager' ? 'selected' : '' ?>>Manager</option>
                        <option value="Editor" <?= ($adminArray['role'] ?? '') == 'Editor' ? 'selected' : '' ?>>Editor</option>
                    </select>
                </div>
                
                <div class="admin-profile-form-group">
                    <label for="adminPhone">Phone Number</label>
                    <div class="admin-profile-phone-input">
                        <div class="admin-profile-phone-flag">
                            <img src="../assets/images/turkey-flag.png" alt="Turkey">
                            <span>+90</span>
                        </div>
                        <input type="text" id="adminPhone" name="phone" class="admin-profile-form-control" value="<?= $adminArray['phone'] ?? '' ?>">
                    </div>
                </div>
                
                <div class="admin-profile-form-group">
                    <label for="adminEmail">Email</label>
                    <input type="email" id="adminEmail" name="email" class="admin-profile-form-control" value="<?= $adminArray['email'] ?? '' ?>" required>
                </div>
                
                <div class="admin-profile-form-actions">
                    <button type="submit" class="admin-profile-btn admin-profile-btn-primary">Save Changes</button>
                    <button type="button" id="adminCancelEdit" class="admin-profile-btn admin-profile-btn-secondary">Cancel</button>
                </div>
            </form>
        </div>
        
        <!-- Admin Management Section -->
        <div class="admin-profile-card admin-management-card">
            <div class="admin-profile-card-header">
                <h2 class="admin-profile-card-title">Manage Administrators</h2>
                <button class="admin-profile-edit-btn" id="adminAddBtn">
                    <i class="fas fa-user-plus"></i> Add New Admin
                </button>
            </div>
            
            <div class="admin-list-container">
                <div class="admin-list-header">
                    <div class="admin-list-col">Name</div>
                    <div class="admin-list-col">Email</div>
                    <div class="admin-list-col">Role</div>
                    <div class="admin-list-col">Actions</div>
                </div>
                
                <?php foreach ($allAdmins as $admin): ?>
                <div class="admin-list-item">
                    <div class="admin-list-col"><?= htmlspecialchars($admin['name']) ?></div>
                    <div class="admin-list-col"><?= htmlspecialchars($admin['email']) ?></div>
                    <div class="admin-list-col"><?= htmlspecialchars($admin['role'] ?? 'Administrator') ?></div>
                    <div class="admin-list-col admin-list-actions">
                        <?php if ($admin['id'] == $adminId): ?>
                        <button class="admin-action-btn admin-edit-btn" disabled title="Cannot edit yourself">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="admin-action-btn admin-delete-btn" disabled title="Cannot delete yourself">
                            <i class="fas fa-trash"></i>
                        </button>
                        <?php else: ?>
                        <button class="admin-action-btn admin-edit-btn" data-id="<?= $admin['id'] ?>">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="admin-action-btn admin-delete-btn" data-id="<?= $admin['id'] ?>">
                            <i class="fas fa-trash"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Add Admin Form (Hidden by default) -->
            <form id="adminAddForm" class="admin-profile-form admin-add-form" style="display: none;" method="post" action="../modules/addadmin.php">
                <h3 class="admin-form-subtitle">Add New Administrator</h3>
                
                <div class="admin-profile-form-group">
                    <label for="newAdminName">Full Name</label>
                    <input type="text" id="newAdminName" name="name" class="admin-profile-form-control" required>
                </div>
                
                <div class="admin-profile-form-group">
                    <label for="newAdminEmail">Email</label>
                    <input type="email" id="newAdminEmail" name="email" class="admin-profile-form-control" required>
                </div>
                
                <div class="admin-profile-form-group">
                    <label for="newAdminRole">Role</label>
                    <select id="newAdminRole" name="role" class="admin-profile-form-control">
                        <option value="Administrator">Administrator</option>
                        <option value="Manager">Manager</option>
                        <option value="Editor">Editor</option>
                    </select>
                </div>
                
                <div class="admin-profile-form-group">
                    <label for="newAdminPassword">Password</label>
                    <input type="password" id="newAdminPassword" name="password" class="admin-profile-form-control" required>
                </div>
                
                <div class="admin-profile-form-group">
                    <label for="newAdminConfirmPassword">Confirm Password</label>
                    <input type="password" id="newAdminConfirmPassword" name="confirm_password" class="admin-profile-form-control" required>
                </div>
                
                <div class="admin-profile-form-actions">
                    <button type="submit" class="admin-profile-btn admin-profile-btn-primary">Add Admin</button>
                    <button type="button" id="adminCancelAdd" class="admin-profile-btn admin-profile-btn-secondary">Cancel</button>
                </div>
            </form>
        </div>
    </main>
</div>

<!-- Include jQuery first -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Then include your app.js -->
<script src="../js/app.js"></script>

<script>
$(document).ready(function() {
    // Show the Add Admin form
    $('#adminAddBtn').on('click', function() {
        $('#adminAddForm').slideDown();
        $(this).hide(); // Hide instead of disable for better UX
    });
    // Hide the Add Admin form
    $('#adminCancelAdd').on('click', function() {
        $('#adminAddForm').slideUp();
        $('#adminAddBtn').show();
    });

    // If there was a validation error and the form is visible, keep the button hidden
    if ($('#adminAddForm').is(':visible')) {
        $('#adminAddBtn').hide();
    }
});
</script>
<?php include '../footer.php'; ?>