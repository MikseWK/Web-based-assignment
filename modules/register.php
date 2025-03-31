<?php
require '../base.php';

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
            header('Location: memberLogin.php'); 
            exit();
        } else {
            $_err['db'] = 'Database error. Please try again.';
        }
    }
}

$_title = 'SignUp';
include '../header.php';
?>

<h1 class="signUpTitle">Sign Up</h1>

<form method="post" class="signUp">
    <div class="label-container">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" value="<?= isset($email) ? htmlspecialchars($email) : ''; ?>">
        <?php if (isset($_err['email'])): ?>
            <div class="error"><?= $_err['email']; ?></div>
        <?php endif; ?>
    </div>

    <div class="label-container">
        <label for="password">Password</label>
        <input type="password" id="password" name="password">
        <?php if (isset($_err['password'])): ?>
            <div class="error"><?= $_err['password']; ?></div>
        <?php endif; ?>
    </div>

    <?php if (isset($_err['db'])): ?>
        <div class="error"><?= $_err['db']; ?></div>
    <?php endif; ?>

    <button type="submit">Sign Up</button>
</form>

<p id="signup">Already have an account? <a href="/module/memberlogin.php">Log In</a></p>

<?php
include '../footer.php';
?>
