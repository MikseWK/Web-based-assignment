<?php
// Add this at the top of your header.php file
$base_path = '';
if (strpos($_SERVER['PHP_SELF'], '/modules/') !== false) {
    $base_path = '../';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Change the CSS path to use relative paths -->
    <link rel="stylesheet" href="css/style.css">
    <!-- <link rel="stylesheet" href="css/checkout.css"> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- ADD THIS BELOW IF WANT  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Add this script to properly handle the message fadeout -->
    <script>
        $(document).ready(function() {
            // If message exists, fade it out after 3 seconds and remove from DOM
            if ($('.message-container').length) {
                setTimeout(function() {
                    $('.message-container').fadeOut(500, function() {
                        $(this).remove(); // Completely remove from DOM after fade
                    });
                }, 3000);
            }
        });
    </script>
</head>
<body>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="message-container">
            <div class="message"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
        </div>
    <?php endif; ?>

    <header>
        <div class="logo">
            <a href="<?php echo isset($_SESSION['role']) && $_SESSION['role'] == 'Admin' ? '/modules/adminPage.php' : '/index.php'; ?>">
                <img src="/images/logo.png" alt="Frost Delights Logo">
            </a>
        </div>
        
        <nav>
            <ul class="main-menu">
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'Admin'): ?>
                    <li><a href="<?= $base_path ?>modules/adminPage.php">Home</a></li>
                    <li><a href="<?= $base_path ?>modules/memberMain.php">Member</a></li>
                    <li><a href="<?= $base_path ?>modules/productMain.php">Product</a></li>
                    <li><a href="<?= $base_path ?>modules/orderMain.php">Order</a></li>
                <?php else: ?>
                    <li><a href="<?= $base_path ?>index.php">Home</a></li>
                    <li><a href="<?= $base_path ?>modules/aboutus.php">About Us</a></li>
                    <li><a href="<?= $base_path ?>modules/menu.php">Menu</a></li>
                    <li><a href="<?= $base_path ?>modules/contact.php">Contact</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        
        <div class="user-profile">
            <?php if ($_user): ?>
                <!-- User is logged in -->
                <div class="dropdown">
                    <div class="profile-pic-container">
                        <!-- Need change -->
                        <?php if (isset($_SESSION['profile_picture']) && $_SESSION['profile_picture']): ?>
                            <img src="/<?= $_SESSION['profile_picture'] ?>" alt="Profile" class="profile-pic">
                        <?php else: ?>
                            <img src="/assets/images/default-user.png" alt="Profile" class="profile-pic">
                        <?php endif; ?>
                    </div>
                    <div class="dropdown-content">
                        <?php if (isAdmin()): ?>
                            <span class="user-name">Admin <?= $_user->name ?? 'User' ?></span>
                        <?php else: ?>
                            <span class="user-name"><?= $_user->name ?? 'User' ?></span>
                        <?php endif; ?>
                        <!-- In the dropdown menu for logged-in users -->
                        <div class="dropdown-menu">
                            <?php if (isAdmin()): ?>
                                <a href="/modules/adminProfile.php"><?= $_user->name ?? 'Admin' ?>'s Profile</a>
                                <a href="/modules/adminPage.php">Admin Dashboard</a>
                            <?php else: ?>
                                <a href="/modules/customerprofile.php">My Profile</a>
                                <a href="/modules/orders.php">My Orders</a>
                            <?php endif; ?>
                            <a href="/modules/logout.php">Logout</a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- User is not logged in - show role selection -->
                <div class="dropdown">
                    <div class="profile-pic-container">
                        <img src="/images/loginIcon.png" alt="Login" class="profile-pic">
                    </div>
                    <div class="dropdown-content">
                        <a href="/modules/customerlogin.php">Customer Login</a>
                        <a href="/modules/adminlogin.php">Admin Login</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </header>

    