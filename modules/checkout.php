<?php
require_once '../base.php';
require_once '../vendor/autoload.php';
$stripe_config = require_once '../config/stripe_config.php';

// Set your Stripe API key
\Stripe\Stripe::setApiKey($stripe_config['secret_key']);

// Check if user is logged in
if (!is_logged_in()) {
    header('Location: customerlogin.php');
    exit;
}

$customer_id = get_user_id();

// Get cart items directly from the cart table
$cartItems = get_cart_items();
$cartTotal = get_cart_total();

include '../header.php';
?>

<link rel="stylesheet" href="../css/checkout.css">

<main class="main-content">
    <?php if (empty($cartItems)): ?>
        <div class="checkout-container">
            <div class="checkout-items">
                <h2 class="checkout-title">Checkout</h2>
                <div class="alert alert-info">Your cart is empty.</div>
                <a href="menu.php" class="btn btn-primary">Continue Shopping</a>
            </div>
        </div>
    <?php else: ?>
        <div class="checkout-container">
            <div class="checkout-items">
                <h2 class="checkout-title">Checkout</h2>
                
                <!-- Product List -->
                <div class="product-list">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="checkout-item">
                            <img src="../images/<?php echo $item->photo ?? 'default.jpg'; ?>" alt="<?php echo htmlspecialchars($item->name); ?>" class="checkout-item-image">
                            <div class="checkout-item-details">
                                <div class="checkout-item-name"><?php echo htmlspecialchars($item->name); ?></div>
                                <div class="checkout-item-price">RM<?php echo number_format($item->price, 2); ?></div>
                            </div>
                            <div class="checkout-item-quantity">
                                <span>Quantity: <?php echo $item->quantity; ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Voucher Section -->
                <div class="voucher-section">
                    <h3>Voucher / Discount</h3>
                    <div class="voucher-input">
                        <input type="text" placeholder="Enter voucher code">
                        <button type="button">Apply</button>
                    </div>
                </div>
                
                <!-- Shipping Options -->
                <div class="shipping-options">
                    <h3>Shipping Option</h3>
                    <div class="shipping-option selected">
                        <input type="radio" name="shipping" id="doorstep" checked>
                        <div class="shipping-details">
                            <label for="doorstep">Doorstep Delivery</label>
                            <div class="shipping-eta">Estimated delivery: 2-3 days</div>
                        </div>
                        <div class="shipping-price">RM5.00</div>
                    </div>
                    <div class="shipping-option">
                        <input type="radio" name="shipping" id="pickup">
                        <div class="shipping-details">
                            <label for="pickup">Self Collection</label>
                            <div class="shipping-eta">Available for pickup: Next day</div>
                        </div>
                        <div class="shipping-price">Free</div>
                    </div>
                </div>
                
                <!-- Payment Methods -->
                <div class="payment-methods">
                    <h3 class="payment-method-title">Payment Method</h3>
                    <div class="payment-options">
                        <div class="payment-option active">
                            <img src="../images/visa.png" alt="Visa Card">
                            <span>Credit/Debit Card (Stripe)</span>
                        </div>
                    </div>
                </div>
            </div>
            
<!-- Order Summary -->
<div class="checkout-summary">
    <h2 class="checkout-title">Order Summary</h2>
    <div class="checkout-summary-row">
        <span>Subtotal</span>
        <span>RM<?php echo number_format($cartTotal, 2); ?></span>
    </div>
    <div class="checkout-summary-row">
        <span>Shipping</span>
        <span>RM5.00</span>
    </div>
    <div class="checkout-summary-row">
        <span>Discount</span>
        <span>-RM0.00</span>
    </div>
    <div class="checkout-summary-row checkout-total">
        <span>Total</span>
        <span>RM<?php echo number_format($cartTotal + 5.00, 2); ?></span>
    </div>
    
    <form id="payment-form" action="process_payment.php" method="POST">
        <input type="hidden" name="total" value="<?php echo $cartTotal + 5.00; ?>">
        <input type="hidden" name="shipping_method" id="shipping_method" value="doorstep">
        <div id="card-element">
            <!-- A Stripe Element will be inserted here. -->
        </div>
        <!-- Used to display form errors. -->
        <div id="card-errors" role="alert" class="payment-error"></div>
        <button type="submit" class="checkout-btn" id="submit-button">
            <span id="button-text">Pay Now</span>
            <span id="spinner" class="spinner hidden"></span>
        </button>
    </form>
    <p style="text-align: center; margin-top: 10px; font-size: 12px; color: #666;">
        By placing your order, you agree to our Terms of Service and Privacy Policy
    </p>
</div>
        </div>
    <?php endif; ?>
</main>

<script src="https://js.stripe.com/v3/"></script>
<script>
$(document).ready(function() {
    const stripe = Stripe('<?php echo $stripe_config['publishable_key']; ?>');
    const elements = stripe.elements();
    const card = elements.create('card');
    card.mount('#card-element');

    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-button');
    const spinner = document.getElementById('spinner');
    const buttonText = document.getElementById('button-text');
    const errorElement = document.getElementById('card-errors');

    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        submitButton.disabled = true;
        spinner.classList.remove('hidden');
        buttonText.classList.add('hidden');
        errorElement.textContent = "";

        // Step 1: Create PaymentIntent on the server
        const formData = new FormData(form);
        let clientSecret = null;
        try {
            const response = await fetch('process_payment.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            if (!result.clientSecret) {
                throw new Error(result.error || "Payment initialization failed.");
            }
            clientSecret = result.clientSecret;
        } catch (err) {
            errorElement.textContent = err.message;
            submitButton.disabled = false;
            spinner.classList.add('hidden');
            buttonText.classList.remove('hidden');
            return;
        }

        // Step 2: Confirm the card payment
        const {paymentIntent, error} = await stripe.confirmCardPayment(clientSecret, {
            payment_method: {
                card: card
            }
        });

        if (error) {
            errorElement.textContent = error.message;
            submitButton.disabled = false;
            spinner.classList.add('hidden');
            buttonText.classList.remove('hidden');
        } else if (paymentIntent && paymentIntent.status === 'succeeded') {
            // Payment successful, redirect or show success
            window.location.href = 'order_confirmation.php?payment_intent=' + paymentIntent.id;
        }
    });

    // Update shipping method when option changes
    $('.shipping-option').click(function() {
        const shippingMethod = $(this).find('input[type="radio"]').attr('id');
        $('#shipping_method').val(shippingMethod);
    });
});
</script>

<style>
.spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: #fff;
    animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.hidden {
    display: none;
}

.payment-error {
    color: #dc3545;
    margin-top: 10px;
    margin-bottom: 10px;
    font-size: 14px;
}

#card-element {
    margin-bottom: 20px;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: white;
}
</style>

<?php include '../footer.php'; ?>