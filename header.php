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
            <a href="/index.php">
                <img src="/images/logo.png" alt="Frost Delights Logo">
            </a>
        </div>
        
        <nav>
            <ul class="main-menu">
                <li><a href="/index.php">Home</a></li>
                <li><a href="/about.php">About Us</a></li>
                <li><a href="/menu.php">Menu</a></li>
                <li><a href="/contact.php">Contact</a></li>
            </ul>
        </nav>
        
        <div class="user-profile">
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                <!-- User is logged in -->
                <div class="dropdown">
                    <div class="profile-pic-container">
                        <?php if (isset($_SESSION['profile_picture']) && $_SESSION['profile_picture']): ?>
                            <img src="/<?= $_SESSION['profile_picture'] ?>" alt="Profile" class="profile-pic">
                        <?php else: ?>
                            <img src="/assets/images/default-user.png" alt="Profile" class="profile-pic">
                        <?php endif; ?>
                    </div>
                    <div class="dropdown-content">
                        <span class="user-email"><?= $_SESSION['user_email'] ?></span>
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                            <a href="/modules/adminprofile.php">Admin Dashboard</a>
                        <?php else: ?>
                            <a href="/modules/customerprofile.php">My Profile</a>
                        <?php endif; ?>
                        <a href="/modules/orders.php">My Orders</a>
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