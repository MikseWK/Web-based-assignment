<?php
include '../base.php';
include '../header.php';
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'Customer') {
    $_SESSION['message'] = 'Please log in to access your profile';
    header("Location: customerlogin.php");
    exit();
}

// Get user data from session
$user = $_SESSION['user'];

// Convert user object to array to maintain compatibility with the rest of the code
$userArray = (array)$user;
?>

<!-- Include customer profile CSS -->
<link rel="stylesheet" href="../css/style.css">
<!-- Include Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="profile-container">
    <aside class="profile-sidebar">
        <div class="profile-user-info">
            <img src="<?= isset($userArray['profile_picture']) && !empty($userArray['profile_picture']) ? $userArray['profile_picture'] : '../assets/images/default-user.png' ?>" alt="Profile">
            <h3 class="profile-user-name">Hello<br><?= $userArray['name'] ?? 'User' ?></h3>
        </div>
        
        <ul class="profile-menu">
            <li class="profile-menu-item active">
                <i class="fas fa-user"></i>
                <a href="#account">My Accounts</a>
            </li>
            <li class="profile-menu-item">
                <i class="fas fa-shopping-bag"></i>
                <a href="#orders">My Orders</a>
            </li>
            <li class="profile-menu-item">
                <i class="fas fa-undo"></i>
                <a href="#returns">Returns & Cancel</a>
            </li>
            <li class="profile-menu-item">
                <i class="fas fa-star"></i>
                <a href="#ratings">My Rating & Reviews</a>
            </li>
            <li class="profile-menu-item">
                <i class="fas fa-heart"></i>
                <a href="#wishlist">My Wishlist</a>
            </li>
            <li class="profile-menu-item">
                <i class="fas fa-credit-card"></i>
                <a href="#payment">Payment</a>
            </li>
            <li class="profile-menu-item">
                <i class="fas fa-lock"></i>
                <a href="#password">Change Password</a>
            </li>
            <li class="profile-menu-item">
                <i class="fas fa-sign-out-alt"></i>
                <a href="../modules/logout.php">Logout</a>
            </li>
        </ul>
    </aside>

    <main class="profile-content">
        <h1 class="profile-section-title">Profile</h1>
        
        <div class="profile-card">
            <div class="profile-card-header">
                <h2 class="profile-card-title">Personal Information</h2>
                <button class="profile-edit-btn" id="editProfileBtn" >
                    <i class="fas fa-edit"></i> Change Profile Information
                </button>
            </div>
            
            <div id="profileViewMode">
                <div class="profile-user-photo">
                    <img src="<?= isset($userArray['profile_picture']) && !empty($userArray['profile_picture']) ? $userArray['profile_picture'] : '../assets/images/default-user.png' ?>" alt="Profile">
                    <div class="profile-photo-edit" id="viewModePhotoEdit">
                        <i class="fas fa-camera"></i>
                    </div>
                </div>
                
                <div class="profile-info-row">
                    <div class="profile-info-label">Name</div>
                    <div class="profile-info-value"><?= $userArray['name']?></div>
                </div>
                
                <div class="profile-info-row">
                    <div class="profile-info-label">Date Of Birth</div>
                    <div class="profile-info-value"><?= $userArray['birthday']?></div>
                </div>
                
                <div class="profile-info-row">
                    <div class="profile-info-label">Gender</div>
                    <div class="profile-info-value">
                        <?php if ($userArray['gender'] = 'M'):?>
                            <?= 'Male'?>
                        <?php else :?>
                            <?= 'Female'?>
                        <?php endif;?>
                    </div>
                </div>
                
                <div class="profile-info-row">
                    <div class="profile-info-label">Phone Number</div>
                    <div class="profile-info-value"><?= $userArray['phoneNumber'] ?></div>
                </div>
                
                <div class="profile-info-row">
                    <div class="profile-info-label">Email</div>
                    <div class="profile-info-value"><?= $userArray['email'] ?></div>
                </div>
            </div>
            
            <form id="profileForm" method="post" action="updateprofile.php" class="profile-form" style="display: none;" enctype="multipart/form-data">
                <div class="profile-user-photo">
                    <img id="profilePreview" src="<?= isset($userArray['profile_picture']) && !empty($userArray['profile_picture']) ? $userArray['profile_picture'] : '../assets/images/default-user.png' ?>" alt="Profile">
                    <div class="profile-photo-edit" id="editModePhotoEdit">
                        <i class="fas fa-camera"></i>
                    </div>
                    <input type="file" id="profilePictureInput" name="profile_picture" accept="image/*" style="display: none;">
                </div>
                
                <div class="profile-form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" class="profile-form-control" value="<?= $userArray['name'] ?? '' ?>" required>
                </div>
                
                <div class="profile-form-group">
                    <label for="birthday">Date Of Birth</label>
                    <input type="date" id="birthday" name="birthday" class="profile-form-control" value="<?= $userArray['birthday'] ?? '' ?>">
                </div>
                
                <div class="profile-form-group">
                    <label>Gender</label>
                    <div class="profile-gender-options">
                        <div class="profile-gender-option">
                            <input type="radio" id="male" name="gender" value="Male" <?= ($userArray['gender'] ?? '') == 'Male' ? 'checked' : '' ?>>
                            <label for="male">Male</label>
                        </div>
                        <div class="profile-gender-option">
                            <input type="radio" id="female" name="gender" value="Female" <?= ($userArray['gender'] ?? '') == 'Female' ? 'checked' : '' ?>>
                            <label for="female">Female</label>
                        </div>
                    </div>
                </div>
                
                <div class="profile-form-group">
                    <label for="phoneNumber">Phone Number</label>
                    <div class="profile-phone-input">
                        <div class="profile-phone-flag">
                            <img src="../assets/images/turkey-flag.png" alt="Turkey">
                            <span>+90</span>
                        </div>
                        <input type="tel" id="phoneNumber" name="phoneNumber" class="profile-form-control profile-phone-number" value="<?= $userArray['phoneNumber'] ?? '' ?>">
                    </div>
                </div>
                
                <div class="profile-form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="profile-form-control" value="<?= $userArray['email'] ?? '' ?>" required>
                </div>
                
                <div class="profile-form-actions" style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                    <button type="submit" class="profile-btn profile-btn-primary">Save Changes</button>
                    <button type="button" id="cancelEdit" class="profile-btn profile-btn-secondary">Cancel</button>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editProfileBtn = document.getElementById('editProfileBtn');
        const profileViewMode = document.getElementById('profileViewMode');
        const profileForm = document.getElementById('profileForm');
        const editModePhotoEdit = document.getElementById('editModePhotoEdit');
        const profilePictureInput = document.getElementById('profilePictureInput');
        const profilePreview = document.getElementById('profilePreview');

        if(editProfileBtn) {
            editProfileBtn.addEventListener('click', function() {
                profileViewMode.style.display = 'none';
                profileForm.style.display = 'block';
            });
        }

        // Add this block to handle the camera icon click in edit mode
        if(editModePhotoEdit && profilePictureInput) {
            editModePhotoEdit.addEventListener('click', function() {
                profilePictureInput.click();
            });
        }

        // Optional: Show image preview when a file is selected
        if(profilePictureInput && profilePreview) {
            profilePictureInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        profilePreview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>

</body>
</html>

<!-- Include jQuery first -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Then include your app.js -->
<script src="../js/app.js"></script>

<?php include '../footer.php'; ?>
