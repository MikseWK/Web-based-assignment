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
exit;
?>
session_start();

if (!isset($_SESSION['customer_id']) || !isset($_POST['stripeToken'])) {
    header('Location: checkout.php');
    exit;
}

$customer_id = $_SESSION['customer_id'];

if (!isset($_SESSION['payment_data'])) {
    $_SESSION['error'] = "Payment session expired.";
    header('Location: checkout.php');
    exit;
}

$payment_data = $_SESSION['payment_data'];
$payment_id = $payment_data['payment_id'];
$total_amount = $payment_data['amount'];

try {
    // Create and confirm the payment
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => intval($total_amount * 100),
        'currency' => 'usd',
        'payment_method_types' => ['card'],
        'description' => 'Order payment',
        'metadata' => [
            'payment_id' => $payment_id,
            'customer_id' => $customer_id
        ]
    ]);

    $paymentIntent->confirm([
        'payment_method_data' => [
            'type' => 'card',
            'card' => [
                'token' => $_POST['stripeToken'],
            ],
        ],
    ]);

    if ($paymentIntent->status === 'succeeded') {
        // Update DB
        $update_payment = "UPDATE payment SET status = 'completed' WHERE id = ?";
        $stmt = $mysqli->prepare($update_payment);
        $stmt->bind_param("i", $payment_id);
        $stmt->execute();

        $order_ids = $payment_data['order_ids'];
        $order_ids_string = implode(',', array_map('intval', $order_ids));
        $update_orders = "UPDATE orders SET status = 'paid' WHERE id IN ($order_ids_string)";
        $mysqli->query($update_orders);

        unset($_SESSION['payment_data']);
        $_SESSION['success'] = "Payment completed successfully!";
        header('Location: payment_success.php');
        exit;
    } else {
        $_SESSION['error'] = "Payment requires additional verification.";
        header('Location: checkout.php');
        exit;
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Payment failed: " . $e->getMessage();
    header('Location: checkout.php');
    exit;
}
