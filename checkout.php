<?php
require 'base.php';
// Store the original output buffer
ob_start();
include 'header.php';
$header = ob_get_clean();

// Output the header
echo $header;
?>

<!-- Add more comprehensive styles to override everything -->
<style>
    /* Reset styles for checkout page */
    .main-content * {
        box-sizing: border-box;
    }
    
    /* Hide the original checkout content */
    .main-content > h1,
    .main-content > table,
    .main-content > form,
    .main-content > p {
        display: none !important;
    }
    
    .checkout-container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .checkout-items {
        flex: 2;
        padding: 20px;
    }
    
    .checkout-summary {
        flex: 1;
        background-color: #f9f9f9;
        border-radius: 8px;
        padding: 20px;
    }
    
    .checkout-title {
        font-size: 24px;
        margin-bottom: 20px;
        color: #333;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }
    
    .checkout-item {
        display: flex;
        border-bottom: 1px solid #eee;
        padding: 15px 0;
        align-items: center;
    }
    
    .checkout-item-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 4px;
        margin-right: 15px;
    }
    
    .checkout-item-details {
        flex: 1;
    }
    
    .checkout-item-name {
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .checkout-item-price {
        color: #666;
    }
    
    .checkout-item-quantity {
        margin-left: 20px;
    }
    
    .checkout-summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        padding: 5px 0;
    }
    
    .checkout-total {
        font-weight: bold;
        font-size: 18px;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #eee;
    }
    
    .checkout-btn {
        background-color: #ff4757;
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
        width: 100%;
        margin-top: 20px;
    }
    
    .voucher-section {
        margin: 20px 0;
        padding: 15px;
        border: 1px dashed #ddd;
        border-radius: 4px;
        background-color: #f9f9f9;
    }
    
    .voucher-input {
        display: flex;
        gap: 10px;
    }
    
    .voucher-input input {
        flex: 1;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    
    .voucher-input button {
        background-color: #333;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 4px;
        cursor: pointer;
    }
    
    .shipping-options {
        margin: 20px 0;
    }
    
    .shipping-option {
        display: flex;
        align-items: center;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-bottom: 10px;
        cursor: pointer;
    }
    
    .shipping-option.selected {
        border-color: #ff4757;
        background-color: rgba(255, 71, 87, 0.05);
    }
    
    .shipping-option input[type="radio"] {
        margin-right: 10px;
    }
    
    .shipping-details {
        flex: 1;
    }
    
    .shipping-eta {
        font-size: 12px;
        color: #666;
        margin-top: 5px;
    }
    
    .payment-methods {
        margin-top: 30px;
    }
    
    .payment-options {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
    }
    
    .payment-option {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 10px 15px;
        cursor: pointer;
        display: flex;
        align-items: center;
    }
    
    .payment-option.active {
        border-color: #ff4757;
        background-color: rgba(255, 71, 87, 0.05);
    }
    
    .payment-option img {
        height: 30px;
        margin-right: 10px;
    }
</style>

<div class="checkout-container">
    <div class="checkout-items">
        <h2 class="checkout-title">Checkout</h2>
        
        <!-- Product List -->
        <div class="product-list">
            <?php
            // Get cart items from session
            $cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
            $subtotal = 0;
            
            // If cart is empty, use sample data
            if (empty($cartItems)) {
                $sampleItems = [
                    ['name' => 'Vanilla Ice Cream', 'price' => 4.99, 'quantity' => 1, 'image' => 'vanilla.jpg'],
                    ['name' => 'Chocolate Ice Cream', 'price' => 5.99, 'quantity' => 1, 'image' => 'chocolate.jpg'],
                    ['name' => 'Strawberry Ice Cream', 'price' => 5.99, 'quantity' => 2, 'image' => 'strawberry.jpg'],
                    ['name' => 'Neapolitan Ice Cream', 'price' => 5.99, 'quantity' => 1, 'image' => 'neapolitan.jpg']
                ];
                
                foreach ($sampleItems as $item) {
                    $itemTotal = $item['price'] * $item['quantity'];
                    $subtotal += $itemTotal;
                    ?>
                    <div class="checkout-item">
                        <img src="images/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="checkout-item-image">
                        <div class="checkout-item-details">
                            <div class="checkout-item-name"><?php echo $item['name']; ?></div>
                            <div class="checkout-item-price">RM<?php echo number_format($item['price'], 2); ?></div>
                        </div>
                        <div class="checkout-item-quantity">
                            <span>Quantity: <?php echo $item['quantity']; ?></span>
                        </div>
                    </div>
                    <?php
                }
            } else {
                // Display actual cart items
                foreach ($cartItems as $item) {
                    $itemTotal = $item['price'] * $item['quantity'];
                    $subtotal += $itemTotal;
                    ?>
                    <div class="checkout-item">
                        <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="checkout-item-image">
                        <div class="checkout-item-details">
                            <div class="checkout-item-name"><?php echo $item['name']; ?></div>
                            <div class="checkout-item-price">RM<?php echo number_format($item['price'], 2); ?></div>
                        </div>
                        <div class="checkout-item-quantity">
                            <span>Quantity: <?php echo $item['quantity']; ?></span>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        
        <!-- Voucher Section -->
        <div class="voucher-section">
            <h3>Voucher / Discount</h3>
            <div class="voucher-input">
                <input type="text" placeholder="Enter voucher code">
                <button>Apply</button>
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
                    <img src="images/visa.png" alt="Visa Card">
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
            <span>RM<?php echo number_format($subtotal, 2); ?></span>
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
            <span>RM<?php echo number_format($subtotal + 5.00, 2); ?></span>
        </div>
        
        <form action="payment.php" method="post">
            <input type="hidden" name="total" value="<?php echo $subtotal + 5.00; ?>">
            <button type="submit" class="checkout-btn">Pay Now</button>
        </form>
        <p style="text-align: center; margin-top: 10px; font-size: 12px; color: #666;">
            By placing your order, you agree to our Terms of Service and Privacy Policy
        </p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentOptions = document.querySelectorAll('.payment-option');
        paymentOptions.forEach(option => {
            option.addEventListener('click', function() {
                paymentOptions.forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
            });
        });
        
        const shippingOptions = document.querySelectorAll('.shipping-option');
        shippingOptions.forEach(option => {
            option.addEventListener('click', function() {
                shippingOptions.forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
                
                // Update shipping cost based on selection
                const shippingCost = this.querySelector('.shipping-price').textContent;
                document.querySelector('.checkout-summary-row:nth-child(2) span:last-child').textContent = shippingCost;
                
                // Recalculate total
                updateTotal();
            });
        });
        
        function updateTotal() {
            const subtotal = parseFloat(document.querySelector('.checkout-summary-row:nth-child(1) span:last-child').textContent.replace('RM', ''));
            const shipping = parseFloat(document.querySelector('.checkout-summary-row:nth-child(2) span:last-child').textContent.replace('RM', '')) || 0;
            const discount = parseFloat(document.querySelector('.checkout-summary-row:nth-child(3) span:last-child').textContent.replace('-RM', '')) || 0;
            
            const total = subtotal + shipping - discount;
            document.querySelector('.checkout-total span:last-child').textContent = 'RM' + total.toFixed(2);
            document.querySelector('input[name="total"]').value = total.toFixed(2);
        }
    });
</script>

<?php
include 'footer.php';
?>