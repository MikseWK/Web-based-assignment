<?php
session_start();

if (isset($_POST['add_to_cart'])) {
    // Store cart in session for testing
    $_SESSION['cart'] = [
        'product_id' => $_POST['product_id'],
        'product_name' => $_POST['product_name'],
        'unit_price' => $_POST['unit_price'],
        'quantity' => $_POST['quantity'],
        'subtotal' => $_POST['unit_price'] * $_POST['quantity'],
    ];
}

// Prepare cart data for display
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : null;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Cart Page</title>
</head>
<body>
    <h1>Your Cart</h1>
    <?php if ($cart): ?>
        <p>Product: <?php echo htmlspecialchars($cart['product_name']); ?></p>
        <p>Unit Price: $<?php echo number_format($cart['unit_price'], 2); ?></p>
        <p>Quantity: <?php echo (int)$cart['quantity']; ?></p>
        <p>Subtotal: $<?php echo number_format($cart['subtotal'], 2); ?></p>
        <form action="process_payment.php" method="post">
            <input type="hidden" name="selected_items[]" value="1">
            <button type="submit">Checkout with Stripe</button>
        </form>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
    <p><a href="product.php">Back to Product</a></p>
</body>
</html>