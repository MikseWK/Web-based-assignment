<?php
require '../base.php';

header('Content-Type: application/json');

if (!is_post()) {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$email = req('email');
$new_password = req('new_password');
$confirm_password = req('confirm_password');

$response = ['success' => false];

// Validate inputs
if (empty($email)) {
    $response['message'] = 'Email is required';
    echo json_encode($response);
    exit;
}

if (!is_email($email)) {
    $response['message'] = 'Invalid email format';
    echo json_encode($response);
    exit;
}

if (empty($new_password)) {
    $response['message'] = 'New password is required';
    echo json_encode($response);
    exit;
}

if (strlen($new_password) < 8) {
    $response['message'] = 'Password must be at least 8 characters';
    echo json_encode($response);
    exit;
}

if ($new_password !== $confirm_password) {
    $response['message'] = 'Passwords do not match';
    echo json_encode($response);
    exit;
}

// Check if email exists in admin table
$stm = $_db->prepare('SELECT id FROM admin WHERE email = ?');
$stm->execute([$email]);
$admin = $stm->fetch();

if (!$admin) {
    $response['message'] = 'Email not found in admin records';
    echo json_encode($response);
    exit;
}

// Update password
$hashed_password = sha1($new_password);
$stm = $_db->prepare('UPDATE admin SET password = ? WHERE email = ?');
$stm->execute([$hashed_password, $email]);

$response['success'] = true;
$response['message'] = 'Password updated successfully';
echo json_encode($response);