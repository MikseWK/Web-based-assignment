<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_title ?? 'Frost Delights' ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="message-container">
            <div class="message"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
        </div>
    <?php endif; ?>

    <header>
        <div class="logo">
            <a href="<?php isAdmin() ? '/modules/adminPage.php' : '/index.php'; ?>">
                <img src="/images/logo.png" alt="Frost Delights Logo">
            </a>
        </div>
        
        <nav>
            <ul class="main-menu">
                <?php if (isAdmin()): ?>
                    <li><a href="/modules/adminPage.php">Home</a></li>
                    <li><a href="/modules/memberMain.php">Member</a></li>
                    <li><a href="/modules/productMain.php">Product</a></li>
                    <li><a href="/modules/orderMain.php">Order</a></li>
                <?php else: ?>
                    <li><a href="/index.php">Home</a></li>
                    <li><a href="/about.php">About Us</a></li>
                    <li><a href="/menu.php">Menu</a></li>
                    <li><a href="/contact.php">Contact</a></li>
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
                            <span class="user-name">Admin <?= $_user->name ?></span>
                            <a href="/modules/adminProfile.php">Admin Dashboard</a>
                        <?php else: ?>
                            <span class="user-name"><?= $_user->name ?></span>
                            <a href="/modules/customerprofile.php">My Profile</a>
                            <a href="/modules/orders.php">My Orders</a>
                        <?php endif; ?>
                        <!-- In the dropdown menu for logged-in users -->
                        <a href="/modules/logout.php">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <!-- User is not logged in - show role selection -->
                <div class="dropdown">
                    <div class="profile-pic-container">
                        <img src="/assets/images/user-icon.png" alt="Login" class="profile-pic">
                    </div>
                    <div class="dropdown-content">
                        <a href="/modules/customerlogin.php">Customer Login</a>
                        <a href="/modules/adminlogin.php">Admin Login</a>
                        <a href="/modules/register.php">Register</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <main>
        <h1><?= $_title ?? 'Frosty Delights' ?></h1>