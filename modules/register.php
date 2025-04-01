<?php
include '../base.php';

if (is_post()) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $_err = [];

    // Validate email
    if ($email == '') {
        $_err['email'] = 'Required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_err['email'] = 'Invalid email format';
    } else {
        $stm = $_db->prepare('SELECT COUNT(*) FROM member WHERE emailAddress = ?');
        $stm->execute([$email]);

        if ($stm->fetchColumn() > 0) {
            $_err['email'] = 'Email already exists';
        }
    }

    // Validate password
    if ($password == '') {
        $_err['password'] = 'Required';
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        $_err['password'] = 'Password must have at least 8 characters with at least 1 uppercase, 1 lowercase, 1 number, and 1 special character.';
    }

    // If no errors, insert into database
    if (empty($_err)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash password
        $stm = $_db->prepare('INSERT INTO member (emailAddress, password) VALUES (?, ?)');

        if ($stm->execute([$email, $hashed_password])) {
            header('Location: customerlogin.php'); 
            exit();
        } else {
            $_err['db'] = 'Database error. Please try again.';
        }
    }
}

$_title = 'Sign Up';
include '../header.php';
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
    <div class="login-container">
        <div class="login-form-container">
            <div class="login-tabs">
                <div class="login-tab-active">Sign Up</div>
            </div>

            <form method="POST">
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
