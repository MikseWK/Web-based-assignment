<?php
require_once '../base.php';
require_once '../vendor/autoload.php';
$stripe_config = require_once '../config/stripe_config.php';

\Stripe\Stripe::setApiKey($stripe_config['secret_key']);

header('Content-Type: application/json');

try {
    $total = isset($_POST['total']) ? floatval($_POST['total']) : 0;
    // Stripe expects amount in cents
    $amount = intval($total * 100);

    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $amount,
        'currency' => 'myr', // or your currency
        'payment_method_types' => ['card'],
        // Optionally, add metadata, receipt_email, etc.
    ]);

    echo json_encode(['clientSecret' => $paymentIntent->client_secret]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

?>
