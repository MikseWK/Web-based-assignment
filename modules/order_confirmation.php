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
<style>
    .main-content {
        padding: 40px 0;
        background-color: #f9f9f9;
    }
    
    .checkout-container {
        max-width: 800px;
        margin: 0 auto;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }
    
    .checkout-items {
        padding: 30px;
    }
    
    .checkout-title {
        color: #3a3a3a;
        margin-bottom: 25px;
        font-size: 28px;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 15px;
    }
    
    .alert-success {
        background-color: #e8f5e9;
        color: #2e7d32;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 25px;
        border-left: 5px solid #4caf50;
        font-size: 16px;
    }
    
    .alert-danger {
        background-color: #ffebee;
        color: #c62828;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 25px;
        border-left: 5px solid #f44336;
    }
    
    .order-summary {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 25px;
        border: 1px solid #e9ecef;
    }
    
    .order-summary h3 {
        color: #495057;
        margin-top: 0;
        margin-bottom: 15px;
        font-size: 22px;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 10px;
    }
    
    .order-summary ul {
        list-style-type: none;
        padding-left: 10px;
        margin-bottom: 0;
    }
    
    .order-summary li {
        margin-bottom: 10px;
        color: #495057;
        font-size: 16px;
    }
    
    .order-summary li strong {
        color: #212529;
    }
    
    .btn-primary {
        background-color: #ff4081;
        color: white !important;
        border: none;
        padding: 12px 25px;
        border-radius: 25px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s;
        text-decoration: none;
        display: inline-block;
        margin-top: 10px;
    }
    
    .btn-primary:hover {
        background-color: #e91e63;
    }
</style>
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