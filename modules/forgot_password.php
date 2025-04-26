<?php
require '../base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    // Check if this is a login request or password reset request
    if (isset($_POST['email']) && isset($_POST['password']) && !isset($_POST['new_password'])) {
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
                SELECT * FROM admin
                WHERE email = ? AND password = SHA1(?)
            ');
            $stm->execute([$email, $password]);
            $u = $stm->fetch(PDO::FETCH_ASSOC);

            if ($u) {
                $_SESSION['role'] = 'Administrator';
                $_SESSION['logged_in'] = true;
                $_SESSION['id'] = $u['id'];
                $_SESSION['name'] = $u['name'];
                $_SESSION['email'] = $u['email'];
                $_SESSION['message'] = 'You have logged in successfully';
                login($u,'/modules/adminProfile.php');
            }
            else {
                $_err['password'] = 'Email or password not matched';
            }
        }
    }
}

// ----------------------------------------------------------------------------

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
                <a href="/modules/register.php">Register</a>
            </div>
        </div>
    </header>
    <div class="login-container">
        <div class="login-form-container">
            <div class="login-tabs">
                <div class="login-tab-active">Log In</div>
            </div>

            <!-- Main Login Form
            <form id="login-form" method="POST">
                <input type="text" name="email" class="login-input" placeholder="Phone number / Username / Email" 
                       value="<?= htmlspecialchars(isset($_POST['email']) ? $_POST['email'] : '') ?>">
                <?php if(isset($_err['email'])): ?>
                    <div class="error-message"><?= $_err['email']?></div>
                <?php endif; ?>
                
                <input type="password" name="password" class="login-input" placeholder="Password">
                <?php if (isset($_err['password'])): ?>
                    <div class="error-message"><?= $_err['password'] ?></div>
                <?php endif; ?>

                <button type="submit" class="login-button">Log In</button>

                <div class="login-options">
                    <a href="#" onclick="showResetForm(); return false;">Forgot Password</a>
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
                    New to our site? <a href="addadmin.php">Sign Up</a>
                </div>
            </form> -->

            <!-- Reset Password Form (initially hidden) -->
            <div id="reset-form" style="display:block;">
                <div class="login-tabs">
                    <div class="login-tab-active">Reset Password</div>
                </div>
                <form id="reset-password-form" method="POST" action="reset_password.php">
                    <input type="email" name="email" class="login-input" placeholder="Your admin email" required>
                    <input type="password" name="new_password" class="login-input" placeholder="New password" required>
                    <input type="password" name="confirm_password" class="login-input" placeholder="Confirm new password" required>
                    <button type="submit" class="login-button">Reset Password</button>
                    <div class="login-options">
                        <a href="../modules/adminlogin.php">Back to Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // function showResetForm() {
        //     document.getElementById('reset-form').style.display = 'block';
        //     document.getElementById('login-form').style.display = 'none';
        // }

        // function hideResetForm() {
        //     document.getElementById('reset-form').style.display = 'none';
        //     document.getElementById('login-form').style.display = 'block';
        // }

        // Handle reset form submission
        document.getElementById('reset-password-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Password reset successfully!');
                    // hideResetForm();
                } else {
                    alert(data.message || 'Error resetting password');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
            window.location.href = '../modules/adminlogin.php'; // Redirect to login page after resetting password
            // window.location.href = 'forgot_password.php'; // Redirect to login page after resetting password
        });
    </script>
</body>
<?php
include '../footer.php';
?>