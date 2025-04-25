<?php
require '../base.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Create a direct database connection if $_db is not available
if (!isset($_db) || $_db === null) {
    try {
        $_db = new PDO('mysql:host=localhost;dbname=assignment;charset=utf8mb4', 'root', '');
        $_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die('Database connection failed: ' . $e->getMessage());
    }
}

// Function to generate verification token
function generateVerificationToken() {
    return bin2hex(random_bytes(32)); // 64-character token
}

// Function to send verification email
function sendVerificationEmail($email, $token) {
    require '../vendor/autoload.php';
    
    $mail = new PHPMailer(true);
    $verificationUrl = "http://localhost:8000/modules/verify.php?token=$token";
    
    try {
        // Server settings for Gmail SMTP (Free)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'frostdelights11@gmail.com'; // REPLACE WITH YOUR GMAIL
        $mail->Password   = 'uycw rkyn oddy aukr'; // USE APP PASSWORD, NOT REGULAR PASSWORD
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Alternative: Use PHPMailer's local mail sending (doesn't require SMTP credentials)
        // Comment out the above server settings and uncomment this if Gmail doesn't work
        // $mail->isMail(); // Use PHP's mail() function instead of SMTP

        $mail->setFrom('hello@frostdelights.com', 'Frost Delights');
        $mail->addAddress($email);
        
        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Verify Your Email Address';
        $mail->Body    = "Please click the following link to verify your email: <a href=\"$verificationUrl\">Verify Email</a>";
        $mail->AltBody = "Please click the following link to verify your email: $verificationUrl";
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return $mail->ErrorInfo; // Return error message instead of false
    }
}

// FOR DEVELOPMENT/TESTING ONLY: 
// Alternative function that writes verification links to a file instead of sending emails
function saveVerificationToFile($email, $token) {
    $verificationUrl = "http://localhost:8000/verify.php?token=$token";
    $content = date('Y-m-d H:i:s') . " - Email to: $email - Verification link: $verificationUrl\n";
    
    // Create a verification_links.txt file in the root directory
    file_put_contents('../verification_links.txt', $content, FILE_APPEND);
    return true;
}

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
        // Use try-catch to handle potential database errors
        try {
            $stm = $_db->prepare('SELECT COUNT(*) FROM customer WHERE email = ?');
            $stm->execute([$email]);

            if ($stm->fetchColumn() > 0) {
                $_err['email'] = 'Email already exists';
            }
        } catch (PDOException $e) {
            $_err['db'] = 'Database error: ' . $e->getMessage();
        }
    }

    // Validate: password
    if ($password == '') {
        $_err['password'] = 'Required';
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        $_err['password'] = 'Password must have at least 8 characters with at least 1 uppercase, 1 lowercase, 1 number, and 1 special character.';
    }

    // If no errors, insert into database
    if (empty($_err)) {
        $verificationToken = generateVerificationToken();
        $tokenExpires = date('Y-m-d H:i:s', strtotime('+24 hours')); // Token expires in 24 hours
        
        try {
            $stm = $_db->prepare('INSERT INTO customer (name, password, phoneNumber, email, verification_token, token_expires) 
                                VALUES (?, SHA1(?), ?, ?, ?, ?)');
            
            if ($stm->execute([$name, $password, $phoneNumber, $email, $verificationToken, $tokenExpires])) {
                // FOR DEVELOPMENT: Use file-based verification instead of email
                if (saveVerificationToFile($email, $verificationToken)) {
                    $_SESSION['message'] = 'Registration successful! Check the verification_links.txt file for your verification link.';
                } else {
                    $_SESSION['message'] = 'Registration successful, but verification link couldn\'t be saved.';
                }
                
                // Uncomment below to use actual email sending when ready
                $mailResult = sendVerificationEmail($email, $verificationToken);
                if ($mailResult === true) {
                    $_SESSION['message'] = 'Registration successful! Please check your email to verify your account.';
                } else {
                    $_SESSION['message'] = 'Registration successful, but email verification failed: ' . $mailResult;
                }
                
                
                redirect('/modules/customerlogin.php');
            } else {
                $_err['db'] = 'Database error. Please try again.';
            }
        } catch (PDOException $e) {
            $_err['db'] = 'Database error: ' . $e->getMessage();
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