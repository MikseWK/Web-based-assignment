<?php 
require '../base.php';
include '../header.php';

if (!isset($_SESSION['user_id'])) {
    redirect('../modules/customerlogin.php');
}

$user_id = $_SESSION['user_id'];
$error = '';

// Change 'users' to 'member' and update column names to match your database structure
$stmt = $_db->prepare('SELECT id, emailAddress, password, profile_picture FROM member WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (is_post()){
    if (isset($_POST['update_profile'])){
        $email = post('email');
        
        if(!is_email($email)){
            $error = 'Invalid email';
        } else {
            // Update query to match your table structure
            $stmt = $_db->prepare('UPDATE member SET emailAddress = ? WHERE id = ?');
            $stmt->execute([$email, $user_id]);
            redirect();
        }
    }

    if (isset($_POST['update_password'])){
        $current_password = post('current_password');
        $new_password = post('new_password');
        $confirm_password = post('confirm_password');

        $stmt = $_db -> prepare('SELECT password FROM users WHERE id = ?');
        $stmt-> execute([$user_id]);
        $user_password = $stmt->fetchColumn();

        if (!password_verify($current_password, $user_password)){
            $error = 'Invalid password';
        } else if ($new_password != $confirm_password){
            $error = 'Password does not match';
        } else {
           $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
           $_db -> prepare('UPDATE users SET password = ? WHERE id = ?')->execute([$hashed_password, $user_id]);
           redirect();
        }
    }

    if(isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0){
        $file = $_FILES['profile_picture'];
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];

        if (!in_array($file['type'], $allowed_types)){
            $error = 'Invalid file type, only jpeg, jpg and png allowed';
        } else {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $new_filename = "uploads/profile_{$user_id}.$ext";
            move_uploaded_file($file['tmp_name'], __DIR__ . "/../$new_filename");

            $_db -> prepare('UPDATE users SET profile_picture = ? WHERE id = ?')->execute([$new_filename, $user_id]);
            redirect();
        }
    }
}
?>


<link rel="stylesheet" href="../css/style.css">

<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">
           <div class="text-center">
             <div class="rounded-circle bg-light d-inline-flex justify-content-center
                         align-items-center " style="width: 150px; height: 150px;">
                          <?php if ($user->profile_picture):?>
                            <img src="/<?= encode ($user->profile_picture)?>" class="rounded-circle" alt="Profile Picture" 
                            style="width: 150px; height: 150px; object-fit: cover;">
                          <?php else: ?>
                            <i class="fas fa-user fa-5x text-secondary"></i>
                          <?php endif; ?>
           </div>
           <h5 class="mt-2"><?= encode($user->name)?></h5>
        </div>

        <div class="list-group">
            <a href="#" class="list-group-item list-group-item-action active">
                <i class="fas fa-user mr-2"></i>My Account
            </a>
            <a href="#" class="list-group-item list-group-item-action">
                    <i class="fas fa-address-card mr-2"></i> Profile
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    <i class="fas fa-credit-card mr-2"></i> Banks & Cards
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    <i class="fas fa-map-marker-alt mr-2"></i> Addresses
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    <i class="fas fa-key mr-2"></i> Change Password
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    <i class="fas fa-bell mr-2"></i> Notification Settings
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    <i class="fas fa-shield-alt mr-2"></i> Privacy Settings
                </a>
        </div>
</div>

<main class="profile-container">
    <div class="profile-card">
        <h1 class="profile-header">User Profile</h1>
        <?php if ($error): ?>
            <p class="error-message"><?= encode($error) ?></p>
        <?php endif; ?>

        <div class="profile-image-container">
            <img src="/<?= $user->profile_picture ?: 'images/default-profile.png' ?>" class="profile-image">
        </div>

        <form method="POST" class="profile-form">
            <h2>Update Profile</h2>
            <label>Name:</label>
            <input type="text" name="name" value="<?= encode($user->name) ?>" required>
            
            <label>Email:</label>
            <input type="email" name="email" value="<?= encode($user->email) ?>" required>
            
            <label>Phone:</label>
            <input type="text" name="phone" value="<?= encode($user->phone) ?>" required>

            <button type="submit" name="update_profile" class="profile-button">Update Profile</button>
        </form>

        <form method="POST" class="profile-form">
            <h2>Update Password</h2>
            <label>Current Password:</label>
            <input type="password" name="current_password" required>

            <label>New Password:</label>
            <input type="password" name="new_password" required>

            <label>Confirm New Password:</label>
            <input type="password" name="confirm_password" required>

            <button type="submit" name="update_password" class="profile-button">Update Password</button>
        </form>

        <form method="POST" enctype="multipart/form-data" class="profile-form">
            <h2>Update Profile Picture</h2>
            <input type="file" name="profile_picture" accept="image/*">
            <button type="submit" name="update_profile_picture" class="profile-button">Update Profile Picture</button>
        </form>
    </div>
</main>

<?php
include 'footer.php';
