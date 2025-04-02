<?php
require '../base.php';
// ----------------------------------------------------------------------------

$arr = $_db->query('SELECT * FROM member.product');

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
        <span>0</span>
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
                <i class="fa-solid fa-cart-plus"></i>
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
        <div class="cart-item"></div>
    </div>

    <div class="sidebar-footer">
        <div class="total-amount">
            <h5>Total</h5>
            <div class="cart-total">RM0.00</div>
        </div>
        <button class="checkout-btn">Checkout</button>
    </div>

</div>

<script src="../js/app.js"></script>
<?php
include '../footer.php';
?>