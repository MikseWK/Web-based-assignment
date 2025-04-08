<?php 
require '../base.php';
include '../header.php';
auth('admin');
// Check if user is logged in and is admin
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    $_SESSION['message'] = "You must be logged in as an admin to access this page.";
    header('Location: ../index.php');
    exit;
}

// Database operations
$message = '';
$items = [];

// Handle stock update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_stock'])) {
    foreach ($_POST['stock'] as $id => $quantity) {
        $id = (int)$id;
        $quantity = (int)$quantity;
        
        if ($quantity < 0) {
            $message = "Stock quantity cannot be negative.";
            break;
        }
        
        $stmt = $pdo->prepare("UPDATE products SET stock = ? WHERE id = ?");
        if ($stmt->execute([$quantity, $id])) {
            $message = "Stock updated successfully!";
        } else {
            $message = "Error updating stock. Please try again.";
            break;
        }
    }
}

// Fetch all products
try {
    $stmt = $pdo->query("SELECT id, name, description, price, image_path, stock FROM products ORDER BY name");
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message = "Error fetching products: " . $e->getMessage();
}
?>

<main class="stock-management">
    <div class="container">
        <h1 class="page-title">Inventory Management</h1>
        
        <?php if (!empty($message)): ?>
            <div class="alert <?= strpos($message, 'successfully') !== false ? 'alert-success' : 'alert-danger' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>
        
        <div class="stock-controls">
            <div class="search-container">
                <input type="text" id="stockSearch" placeholder="Search products..." class="search-input">
                <i class="fas fa-search search-icon"></i>
            </div>
            
            <div class="filter-container">
                <select id="stockFilter" class="filter-select">
                    <option value="all">All Products</option>
                    <option value="low">Low Stock (< 10)</option>
                    <option value="out">Out of Stock</option>
                </select>
            </div>
        </div>
        
        <form method="POST" action="" class="stock-form">
            <div class="table-responsive">
                <table class="stock-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Description</th>
                            <th>Price (RM)</th>
                            <th>Current Stock</th>
                            <th>New Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($items)): ?>
                            <tr>
                                <td colspan="6" class="no-items">No products found in the database.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($items as $item): ?>
                                <tr class="stock-item <?= $item['stock'] <= 0 ? 'out-of-stock' : ($item['stock'] < 10 ? 'low-stock' : '') ?>">
                                    <td class="item-image">
                                        <img src="<?= !empty($item['image_path']) ? '../' . $item['image_path'] : '../assets/images/no-image.jpg' ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                    </td>
                                    <td class="item-name"><?= htmlspecialchars($item['name']) ?></td>
                                    <td class="item-description"><?= htmlspecialchars($item['description']) ?></td>
                                    <td class="item-price"><?= number_format($item['price'], 2) ?></td>
                                    <td class="item-stock <?= $item['stock'] <= 0 ? 'out-of-stock' : ($item['stock'] < 10 ? 'low-stock' : '') ?>">
                                        <?= $item['stock'] ?>
                                    </td>
                                    <td class="item-update">
                                        <div class="stock-control">
                                            <button type="button" class="decrement-btn">-</button>
                                            <input type="number" name="stock[<?= $item['id'] ?>]" value="<?= $item['stock'] ?>" min="0" class="stock-input">
                                            <button type="button" class="increment-btn">+</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="form-actions">
                <button type="submit" name="update_stock" class="update-btn">Update Inventory</button>
            </div>
        </form>
    </div>
</main>

<!-- Link to external CSS and JS files -->
<link rel="stylesheet" href="../css/modifystocks.css">
<script src="../js/modifystocks.js"></script>

<?php
include '../footer.php';
?>