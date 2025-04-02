<?php
include '../base.php';

if (is_post()) {
    $name = req('name');
    $password = req('password');
    $phoneNumber = req('phoneNumber');
    $email = req('email');

    //Validate: name
    if ($name == '') {
        $_err['name'] = 'Name Required';
    }

    //Validate: phone number
    if ($phoneNumber == '') {
        $_err['phoneNumber'] = 'Phone Number Required';
    }

    // Validate: email
    if ($email == '') {
        $_err['email'] = 'Email Required';
    }
    else if (!is_email($email)) {
        $_err['email'] = 'Invalid email';
    } else {
        $stm = $_db->prepare('SELECT COUNT(*) FROM customer WHERE email = ?');
        $stm->execute([$email]);

        if ($stm->fetchColumn() > 0) {
            $_err['email'] = 'Email already exists';
        }
    }

    // Validate: password
    if ($password == '') {
        $_err['password'] = 'Required';
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        $_err['password'] = 'Password must have at least 8 characters with at least 1 uppercase, 1 lowercase, 1 number, and 1 special character.';
    }

    // If no errors, insert into database
    if (!$_err) {
        $stm = $_db->prepare('INSERT INTO customer (name, password, phoneNumber, email) VALUES (?, SHA1(?), ?, ?)');

        if ($stm->execute([$name, $password, $phoneNumber, $email])) {
            $_SESSION['message'] = 'You have registered successfully';
            redirect('/modules/customerlogin.php');
        } else {
            $_err['db'] = 'Database error. Please try again.';
        }
    }
}

$_title = 'Sign Up';
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
                        <a href="/modules/register.php">Register</a>
                    </div>
                </div>
    </header>

    <div class="login-container">
        <div class="login-form-container">
            <div class="login-tabs">
                <div class="login-tab-active">Sign Up</div>
            </div>

            <form method="POST">
                <input type="name" name="name" class="login-input" placeholder="Name"
                value="<?= isset($name) ? htmlspecialchars($name) : ''; ?>">
                <?php if (isset($_err['name'])): ?>
                    <div class="error-message"><?= $_err['name'] ?></div>
                <?php endif; ?>
                <input type="phoneNumber" name="phoneNumber" class="login-input" placeholder="PhoneNumber"
                value="<?= isset($phoneNumber) ? htmlspecialchars($phoneNumber) : ''; ?>">
                <?php if (isset($_err['phoneNumber'])): ?>
                    <div class="error-message"><?= $_err['phoneNumber'] ?></div>
                <?php endif; ?>
                <input type="email" name="email" class="login-input" placeholder="Email" 
                value="<?= isset($email) ? htmlspecialchars($email) : ''; ?>">
                <?php if(isset($_err['email'])): ?>
                    <div class="error-message"><?= $_err['email']?></div>
                <?php endif; ?>
                
                <input type="password" name="password" class="login-input" placeholder="Password">
                <?php if (isset($_err['password'])): ?>
                    <div class="error-message"><?= $_err['password'] ?></div>
                <?php endif; ?>

                <?php if (isset($_err['db'])): ?>
                    <div class="error-message"><?= $_err['db']; ?></div>
                <?php endif; ?>

                <button type="submit" class="login-button">Sign Up</button>
                
                <div class="login-divider">OR</div>
                
                <div class="social-login">
                    <button type="button" class="social-button">
                        <img src="https://cdn-icons-png.flaticon.com/512/124/124010.png" alt="Facebook">
                        Facebook
                    </button>
                    <button type="button" class="social-button">
                        <img src="https://cdn-icons-png.flaticon.com/512/2991/2991148.png" alt="Google">
                        Google
                    </button>
                </div>
                
                <div class="signup-link">
                    Already have an account? <a href="customerlogin.php">Log In</a>
                </div>
            </form>
        </div>
    </div>
    <?php include '../footer.php'; ?>
</body>
</html>
