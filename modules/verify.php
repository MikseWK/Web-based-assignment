<?php
require '../base.php';

if (!isset($_db) || $_db === null) {
    try {
        $_db = new PDO('mysql:host=localhost;dbname=assignment;charset=utf8mb4', 'root', '');
        $_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die('Database connection failed: ' . $e->getMessage());
    }
}

$token = req('token');

if ($token) {
    try {
        // Find user with this token
        $stmt = $_db->prepare('SELECT * FROM customer WHERE verification_token = ? AND token_expires > NOW()');
        $stmt->execute([$token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Mark email as verified and clear token
            $update = $_db->prepare('UPDATE customer SET email_verified = TRUE, verification_token = NULL, token_expires = NULL WHERE id = ?');
            $update->execute([$user['id']]);
            
            $_SESSION['message'] = 'Email verified successfully! You can now log in.';
            redirect('/modules/customerlogin.php');
        } else {
            $_SESSION['message'] = 'Invalid or expired verification link.';
            redirect('/modules/register.php');
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = 'Database error: ' . $e->getMessage();
        redirect('/modules/register.php');
    }
} else {
    $_SESSION['message'] = 'No verification token provided.';
    redirect('/modules/register.php');
}