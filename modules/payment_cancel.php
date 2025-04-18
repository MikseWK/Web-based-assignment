<?php
require_once 'base.php';

session_start();
if (!isset($_SESSION['customer_id']) || !isset($_SESSION['payment_data'])) {
    header('Location: login.php');
    exit;
}

$payment_id = $_SESSION['payment_data']['payment_id'];

// Update payment status to cancelled
$update_payment = "UPDATE payment SET status = 'cancelled' WHERE id = ?";
$stmt = $mysqli->prepare($update_payment);
$stmt->bind_param("i", $payment_id);
$stmt->execute();

// Clear payment data from session
unset($_SESSION['payment_data']);

// Set message
$_SESSION['error'] = "Payment was cancelled.";

header('Location: checkout.php');
exit;