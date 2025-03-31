<?php
require '../base.php';
include '../header.php';

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

    // Login user
    if (!$_err) {
        $stm = $_db->prepare('
            SELECT * FROM user
            WHERE email = ? AND password = SHA1(?)
        ');
        $stm->execute([$email, $password]);
        $u = $stm->fetch();

        if ($u) {
            // Store success message in session
            $_SESSION['message'] = 'Login successfully';
            
            // Store user data in session
            $_SESSION['user_id'] = $u['id'];
            $_SESSION['user_email'] = $u['email'];
            $_SESSION['logged_in'] = true;
            
            // Redirect to home page or dashboard
            header('index.php');
            exit;
        }
        else {
            $_err['password'] = 'Not matched';
        }
    }
}

// ----------------------------------------------------------------------------

$_title = 'Login';

?>
<link rel="stylesheet" href="../css/login.css">

<div class="login-container">
    <div class="login-banner">
        <img src="/assets/images/loginIcon" alt="Login Banner" >
    </div>

    <div class="login-form-container">
        <div class="login-tabs">
            <div class="login-tab-active">Log In</div>
            <!-- <div class="login-tab">Log in with QR</div> -->
        </div>

        <form method="POST">
            <input type="text" name="email" class="login-input" placeholder="Phone number 
            / Username / Email" value="<?= htmlspecialchars(isset($_POST['email']) ? $_POST['email'] : '') ?>">
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
                <a href="#">Log in with Phone Number</a>
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

<?php
include 'footer.php';

























