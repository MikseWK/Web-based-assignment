<?php
require_once '../base.php';
require_once '../vendor/autoload.php';
$stripe_config = require_once '../config/stripe_config.php';

// Set your Stripe API key
\Stripe\Stripe::setApiKey($stripe_config['secret_key']);

include '../header.php';

$payment_intent_id = isset($_GET['payment_intent']) ? $_GET['payment_intent'] : null;
$orderSuccess = false;
$errorMsg = '';
$paymentDetails = null;

if ($payment_intent_id) {
    try {
        $intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);
        if ($intent && $intent->status === 'succeeded') {
            $orderSuccess = true;
            $paymentDetails = $intent;
        } else {
            $errorMsg = 'Payment not successful or invalid payment intent.';
        }
    } catch (Exception $e) {
        $errorMsg = 'Error retrieving payment details: ' . $e->getMessage();
    }
} else {
    $errorMsg = 'Missing payment intent.';
}
?>
<link rel="stylesheet" href="../css/checkout.css">
<main class="main-content">
    <div class="checkout-container">
        <div class="checkout-items">
            <h2 class="checkout-title">Order Confirmation</h2>
            <?php if ($orderSuccess): ?>
                <div class="alert alert-success">Thank you! Your payment was successful.</div>
                <div class="order-summary">
                    <h3>Payment Details</h3>
                    <ul>
                        <li><strong>Payment ID:</strong> <?php echo htmlspecialchars($paymentDetails->id); ?></li>
                        <li><strong>Amount Paid:</strong> RM<?php echo number_format($paymentDetails->amount / 100, 2); ?></li>
                        <li><strong>Status:</strong> <?php echo htmlspecialchars($paymentDetails->status); ?></li>
                    </ul>
                </div>
                <a href="menu.php" class="btn btn-primary">Continue Shopping</a>
            <?php else: ?>
                <div class="alert alert-danger">Order could not be confirmed.<br><?php echo htmlspecialchars($errorMsg); ?></div>
                <a href="checkout.php" class="btn btn-primary">Back to Checkout</a>
            <?php endif; ?>
        </div>
    </div>
</main>
<?php include '../footer.php'; ?>