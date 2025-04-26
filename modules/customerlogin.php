<?php
require '../base.php';

// Function to generate verification token (needs to be here too)
function generateVerificationToken() {
    return bin2hex(random_bytes(32)); // 64-character token
}

// Function that writes verification links to a file instead of sending emails
function saveVerificationToFile($email, $token) {
    $verificationUrl = "http://localhost:8000/verify.php?token=$token";
    $content = date('Y-m-d H:i:s') . " - Email to: $email - Verification link: $verificationUrl\n";
    
    // Create a verification_links.txt file in the root directory
    file_put_contents('../verification_links.txt', $content, FILE_APPEND);
    return true;
}

// Only process login if it's a POST request
if (is_post()) {
    $email = req('email');
    $password = req('password');
    $resendVerification = req('resend_verification');

    // Handle resend verification request
    if ($resendVerification) {
        try {
            $stmt = $_db->prepare('SELECT id, email FROM customer WHERE email = ? AND email_verified = FALSE');
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                $newToken = generateVerificationToken();
                $expires = date('Y-m-d H:i:s', strtotime('+24 hours'));
                
                $update = $_db->prepare('UPDATE customer SET verification_token = ?, token_expires = ? WHERE id = ?');
                $update->execute([$newToken, $expires, $user['id']]);
                
                // Save verification link to file
                if (saveVerificationToFile($user['email'], $newToken)) {
                    $_SESSION['message'] = 'Verification link has been resent. Check the verification_links.txt file.';
                } else {
                    $_SESSION['message'] = 'Error generating verification link.';
                }
            } else {
                $_SESSION['message'] = 'No unverified account found with that email or account is already verified.';
            }
        } catch (PDOException $e) {
            $_SESSION['message'] = 'Database error: ' . $e->getMessage();
        }
        
        redirect('/modules/customerlogin.php');
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

        if ($stm->fetchColumn() == 0) {
            $_err['email'] = 'Email not registered';
        }
    }

    // Validate: password
    if ($password == '') {
        $_err['password'] = 'Password Required';
    }

    // Login user
    if (empty($_err)) {
        $stm = $_db->prepare('
            SELECT * FROM customer
            WHERE email = ? AND password = SHA1(?)
        ');
        $stm->execute([$email, $password]);
        $u = $stm->fetch();

        if ($u) {
            // Check if email is verified
            if ($u->email_verified == 0) {
                $_SESSION['message'] = 'Please verify your email before logging in. <a href="customerlogin.php?email=' . urlencode($email) . '&resend_verification=1">Resend verification email</a>';
                redirect('/modules/customerlogin.php');
            }
            
            $_SESSION['role'] = 'Customer';
            $_SESSION['logged_in'] = true;
            $_SESSION['message'] = 'You have logged in successfully';
            $_SESSION['user'] = $u; // Store user data in session
            redirect('/index.php'); // Use the redirect function instead of login
        }
        else {
            $_err['password'] = 'Email or password not matched';
        }
    }
}

// ----------------------------------------------------------------------------

$_title = 'Login';

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
                        <img src="/images/loginIcon.png" alt="Login" class="profile-pic">
                    </div>
                    <div class="dropdown-content">
                        <a href="/modules/customerlogin.php">Customer Login</a>
                        <a href="/modules/adminlogin.php">Admin Login</a>
                    </div>
                </div>
    </header>

    <div class="login-container">
        <div class="login-form-container">
            <div class="login-tabs">
                <div class="login-tab-active">Log In</div>
            </div>

            <form method="POST">
                <input type="text" name="email" class="login-input" placeholder="Email" 
                    value="<?= isset($_GET['email']) ? htmlspecialchars($_GET['email']) : (isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '') ?>">
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
                    New to our site? <a href="register.php">Sign Up</a>
                </div>
            </form>
        </div>
    </div>
    <?php include '../footer.php'; ?>
</body>
</html>