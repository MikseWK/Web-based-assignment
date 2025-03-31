<?php 
require 'base.php'; 
require 'header.php'; 

if (!isset($_SESSION['user_id'])) {
    redirect('/module/login.php');
}

$user_id = $_SESSION['user_id'];
$error = '';

$stmt = $_db -> prepare('SELECT name, email, phone, profile_picture FROM users WHERE id = ?');
$stmt-> execute([$user_id]);
$user = $stmt->fetch();

if (is_post()){
    if (isset($_POST['update_profile'])){
        $name = post('name');
        $email = post('email');
        $phone = post('phone');

        if(!is_email($email)){
            $error = 'Invalid email';
        } else {
            $stmt = $_db -> prepare('UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?');
            $stmt-> execute([$name, $email, $phone, $user_id]);
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

<main class="profile-container">
    <div class="profile-card">
        <h1 class="profile-header">User Profile</h1>
        <?php if ($error): ?>
            <p class="error-message"><?= encode($error) ?></p>
        <?php endif; ?>

        <div class="profile-image-container">
            <img src="/<?= $user->profile_picture ?: 'Images/default-profile.png' ?>" class="profile-image">
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

<?php require '../module/footer.php'; ?>
