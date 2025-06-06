<?php
require '../base.php';
// ----------------------------------------------------------------------------

// Check if search query is provided
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

// Get all unique categories from the product table
$categoriesStmt = $_db->query('SELECT DISTINCT category FROM assignment.product ORDER BY category');
$categories = $categoriesStmt->fetchAll(PDO::FETCH_COLUMN);

// Build the query based on filters
if (!empty($search) && !empty($category)) {
    // Filter by both search and category
    $stmt = $_db->prepare('SELECT * FROM assignment.product WHERE name LIKE ? AND category = ?');
    $stmt->execute(['%' . $search . '%', $category]);
    $arr = $stmt->fetchAll(PDO::FETCH_OBJ);
} elseif (!empty($search)) {
    // Filter by search only
    $stmt = $_db->prepare('SELECT * FROM assignment.product WHERE name LIKE ?');
    $stmt->execute(['%' . $search . '%']);
    $arr = $stmt->fetchAll(PDO::FETCH_OBJ);
} elseif (!empty($category)) {
    // Filter by category only
    $stmt = $_db->prepare('SELECT * FROM assignment.product WHERE category = ?');
    $stmt->execute([$category]);
    $arr = $stmt->fetchAll(PDO::FETCH_OBJ);
} else {
    // No filters, get all products
    $stmt = $_db->query('SELECT * FROM assignment.product');
    $arr = $stmt->fetchAll(PDO::FETCH_OBJ);
}

// ----------------------------------------------------------------------------

include '../header.php';

// Get the current category name if selected
$categoryName = "All Products";
if (!empty($category)) {
    $categoryName = $category;
}
?>

<h1><?= $categoryName ?></h1>

<nav class="menu">
    <div class="menu-bar" id="category-menu-toggle">
        <i class="fa-solid fa-bars-staggered"></i>
    </div>
    <div class="search-box">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" id="product-search" placeholder="Search" value="<?= htmlspecialchars($search) ?>"/>
    </div>
    <div class="cart">
        <i class="fa-solid fa-cart-shopping"></i>
        <span>0</span>
    </div>
    
    <!-- Move the dropdown inside the menu for better positioning -->
    <div class="category-dropdown" id="category-dropdown">
        <ul>
            <li<?= empty($category) ? ' class="active"' : '' ?>><a href="?<?= !empty($search) ? 'search='.urlencode($search) : '' ?>">All Products</a></li>
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $cat): ?>
                    <li<?= ($category === $cat) ? ' class="active"' : '' ?>><a href="?category=<?= urlencode($cat) ?><?= !empty($search) ? '&search='.urlencode($search) : '' ?>"><?= htmlspecialchars($cat) ?></a></li>
                <?php endforeach; ?>
            <?php else: ?>
                <li><a href="#">No categories found</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<div class="product-grid">
    <?php if (!empty($arr)): ?>
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
    <?php else: ?>
        <div class="no-products">No products found matching your criteria</div>
    <?php endif; ?>
</div>

<!-- Product Description Popup -->
<div id="product-description-popup" class="product-popup">
    <div class="popup-content">
        <span class="close-popup">&times;</span>
        <h3 id="popup-product-name"></h3>
        <div id="popup-product-image"></div>
        <p id="popup-product-description"></p>
        <div class="popup-price-container">
            <span id="popup-product-price"></span>
            <button id="popup-close-description" class="close-description-btn">
                Close Description
            </button>
        </div>
    </div>
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
<button id="scrollToTop" class="scroll-top-btn" style="animation: pulse 2s infinite;">
        <i class="fas fa-arrow-up"></i>
</button>

<script src="../js/app.js"></script>
<script src="../js/menu.js"></script>

<script>
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Show or hide the button based on scroll position
window.addEventListener('scroll', function() {
    var scrollBtn = document.getElementById('scrollToTop');
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        scrollBtn.style.display = "block";
    } else {
        scrollBtn.style.display = "none";
    }
});

// Add click event listener to the button
document.getElementById('scrollToTop').addEventListener('click', function(e) {
    e.preventDefault();
    scrollToTop();
});
</script>
<?php
include '../footer.php';
?>