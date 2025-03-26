<?php
require '../base.php';

if (is_post()) {
    $email = req('email');
    $password = req('password');
    $_err = [];

    // Validate email
    if ($email == '') {
        $_err['email'] = 'Required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_err['email'] = 'Invalid email format';
    }

    // Validate password
    if ($password == '') {
        $_err['password'] = 'Required';
    }

    if (empty($_err)) {
        $stm = $_db->prepare('SELECT id, password FROM member WHERE emailAddress = ?');
        $stm->execute([$email]);
        $user = $stm->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Store user ID and email in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $email;

            // Redirect to home page (or another location)
            header('Location: home.php');
            exit();
        } else {
            $_err['login'] = 'Invalid email or password';
        }
    }
}

$_title = 'Login';
include '../header.php';
?>

<h1 class="memberLoginTitle">Login</h1>

<form method="post" class="login">
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
        <?php if (isset($_err['login'])): ?>
            <div class="error"><?= $_err['login']; ?></div>
        <?php endif; ?>
    </div>

    <button type="submit">Sign In</button>
</form>

<p id="signup">Don't have an account? <a href="/module/signup.php">Create Account</a></p>

<?php
include '../footer.php';
?>