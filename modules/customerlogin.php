<?php
require '../base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    $email    = req('email');
    $password = req('password');

    // Validate: email
    if ($email == '') {
        $_err['email'] = 'Required';
    }
    else if (!is_email($email)) {
        $_err['email'] = 'Invalid email';
    }

    // Validate: password
    if ($password == '') {
        $_err['password'] = 'Required';
    }

    // Login user with hard-coded admin credentials
    if (!$_err) {
        // Hard-coded admin credentials
        $admin_email = 'abc@gmail.com';
        $admin_password = 'Abc12345!'; // In production, use a strong password
        
        if ($email === $admin_email && $password === $admin_password) {
            $_SESSION['role'] = 'Admin';
            $_SESSION['logged_in'] = true;
            $_SESSION['message'] = 'You have logged in successfully';
            // Create a user object with basic admin info
            $u = [
                'id' => 1,
                'email' => $admin_email,
                'name' => 'Administrator'
            ];
            login($u, '/modules/adminPage.php');
        } else {
            $_err['password'] = 'Email or password not matched';
        }
    }
}

// ----------------------------------------------------------------------------

$_title = 'Admin Login';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_title ?></title>
    <link rel="stylesheet" href="../css/style.css">
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
        
        <div class="dropdown">
                    <div class="profile-pic-container">
                        <img src="/assets/images/user-icon.png" alt="Login" class="profile-pic">
                    </div>
                    <div class="dropdown-content">
                        <a href="/modules/customerlogin.php">Customer Login</a>
                        <a href="/modules/adminlogin.php">Admin Login</a>
                        <!-- Registration link removed -->
                    </div>
                </div>
    </header>
    <div class="login-container">
        <div class="login-form-container">
            <div class="login-tabs">
                <div class="login-tab-active">Admin Log In</div>
            </div>

            <form method="POST">
                <input type="email" name="email" class="login-input" placeholder="Email" value="<?= htmlspecialchars(isset($_POST['email']) ? $_POST['email'] : '') ?>">
                <?php if(isset($_err['email'])): ?>
                    <div class="error-message"><?= $_err['email']?></div>
                <?php endif; ?>
                
                <input type="password" name="password" class="login-input" placeholder="Password">
                <?php if (isset($_err['password'])): ?>
                    <div class="error-message"><?= $_err['password'] ?></div>
                <?php endif; ?>

                <button type="submit" class="login-button">Log In</button>

                <div class="login-options">
                    <a href="#">Forgot Password</a>
                </div>
                
                <!-- Removed OR divider, social login buttons, and signup link -->
            </form>
        </div>
    </div>
</body>

<?php
include 'footer.php';