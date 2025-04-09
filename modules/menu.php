<?php
require '../base.php';
// ----------------------------------------------------------------------------

$arr = $_db->query('SELECT * FROM assignment.product');

// Get cart count for the current user
$cartCount = get_cart_count();
$cartItems = get_cart_items();
$cartTotal = get_cart_total();

// Handle add to cart action
if (is_post() && isset($_POST['add_to_cart'])) {
    $product_id = post('product_id');
    $quantity = post('quantity', 1);
    
    // Add debug output to see what's being submitted
    echo "<div style='background-color: #f8d7da; padding: 10px; margin: 10px;'>";
    echo "Adding to cart: Product ID = $product_id, Quantity = $quantity<br>";
    echo "</div>";
    
    if (is_logged_in()) {
        $result = add_to_cart($product_id, $quantity);
        
        if ($result) {
            // Refresh page to update cart
            redirect($_SERVER['REQUEST_URI']);
        }
    } else {
        // Redirect to login if not logged in
        redirect('/modules/customerlogin.php');
    }
}

// Handle remove from cart action
if (is_post() && isset($_POST['remove_from_cart'])) {
    $product_id = post('product_id');
    
    if (remove_from_cart($product_id)) {
        // Refresh page to update cart
        redirect($_SERVER['REQUEST_URI']);
    }
}

// ----------------------------------------------------------------------------

include '../header.php';
?>

<h1>Our Products</h1>

<nav class="menu">
    <div class="menu-bar">
    <i class="fa-solid fa-bars-staggered"></i>
    </div>
    <div class="search-box">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" placeholder="Search" />
    </div>
    <div class="cart">
        <i class="fa-solid fa-cart-shopping"></i>
        <span><?= $cartCount ?></span>
    </div>
</nav>
    
<div class="product-grid">
    <?php foreach ($arr as $product): ?>
        
    <div class="product_class">
        <div class="product" data-name="<?= $product->id ?>">
            <img src="/images/<?= $product->photo ?>">
            <h4 class="product-name"><?= $product->name?></h4>

            <div class="product-price">
                <div class="price">RM <?= $product->price?></div>
                <!-- Modify form to include a unique ID and prevent default submission -->
                <form method="post">
                    <input type="hidden" name="product_id" value="<?= $product->id ?>">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" name="add_to_cart" class="cart-button" style="background:none; border:none; cursor:pointer; padding:0;">
                        <i class="fa-solid fa-cart-plus"></i>
                    </button>
                </form>
            </div>
        </div>
                
    </div>
    <?php endforeach; ?>
</div>

<!-- cart slideshow -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-close">
        <i class="fa-solid fa-xmark"></i>
    </div>

    <div class="cart-menu">
        <h3>My Cart</h3>
        <div class="cart-item">
            <?php if (empty($cartItems)): ?>
                <p>Your cart is empty</p>
            <?php else: ?>
                <?php foreach ($cartItems as $item): ?>
                    <div class="individual-cart-item">
                        <div class="cart-item-details">
                            <?= $item->name ?> (x<?= $item->quantity ?>)
                        </div>
                        <div class="cart-item-price">
                            <div>RM <?= number_format($item->price * $item->quantity, 2) ?></div>
                            <form method="post">
                                <input type="hidden" name="product_id" value="<?= $item->product_id ?>">
                                <button type="submit" name="remove_from_cart" class="remove-btn">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="sidebar-footer">
        <div class="total-amount">
            <h5>Total</h5>
            <div class="cart-total">RM<?= number_format($cartTotal, 2) ?></div>
        </div>
        <button class="checkout-btn" onclick="window.location.href='checkout.php'">Checkout</button>
    </div>
</div>

<script src="../js/app.js"></script>
<?php
include '../footer.php';
?>