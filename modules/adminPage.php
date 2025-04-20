<?php
require '../base.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in as admin
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'Admin') {
    $_SESSION['message'] = 'Please log in to access admin features';
    header("Location: adminlogin.php");
    exit();
}

// Create database connection
$servername = "localhost";
$username = "root";
$dbpassword = "";
$dbname = "assignment";

// Create connection
$conn = new mysqli($servername, $username, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get today's stats
$today = date('Y-m-d');
$statsQuery = "SELECT * FROM dashboard_stats WHERE stat_date = ? ORDER BY stat_date DESC LIMIT 1";
$stmt = $conn->prepare($statsQuery);
$stmt->bind_param("s", $today);
$stmt->execute();
$todayStats = $stmt->get_result()->fetch_assoc();

// If no stats for today, get the most recent stats
if (!$todayStats) {
    $statsQuery = "SELECT * FROM dashboard_stats ORDER BY stat_date DESC LIMIT 1";
    $todayStats = $conn->query($statsQuery)->fetch_assoc();
}

// Get monthly sales by product
$currentMonth = date('m');
$currentYear = date('Y');
$monthlySalesQuery = "
    SELECT p.name as product_name, ms.total_sales, ms.units_sold 
    FROM monthly_sales ms
    JOIN products p ON ms.product_id = p.id
    WHERE ms.month = ? AND ms.year = ?
    ORDER BY ms.total_sales DESC
";
$stmt = $conn->prepare($monthlySalesQuery);
$stmt->bind_param("ii", $currentMonth, $currentYear);
$stmt->execute();
$monthlySales = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get monthly sales by category
$categorySalesQuery = "
    SELECT pc.name as category_name, SUM(ms.total_sales) as total_sales, SUM(ms.units_sold) as units_sold
    FROM monthly_sales ms
    JOIN product_categories pc ON ms.category_id = pc.id
    WHERE ms.month = ? AND ms.year = ?
    GROUP BY ms.category_id
    ORDER BY total_sales DESC
";
$stmt = $conn->prepare($categorySalesQuery);
$stmt->bind_param("ii", $currentMonth, $currentYear);
$stmt->execute();
$categorySales = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get recent orders
$recentOrdersQuery = "
    SELECT o.order_id, o.order_date, o.total_amount, o.status, c.name as customer_name
    FROM orders o
    JOIN customers c ON o.customer_id = c.id
    ORDER BY o.order_date DESC
    LIMIT 10
";
$recentOrders = $conn->query($recentOrdersQuery)->fetch_all(MYSQLI_ASSOC);

// Get recent customers
$recentCustomersQuery = "
    SELECT c.id, c.name, c.email, crl.registration_date
    FROM customer_registration_log crl
    JOIN customers c ON crl.customer_id = c.id
    ORDER BY crl.registration_date DESC
    LIMIT 5
";
$recentCustomers = $conn->query($recentCustomersQuery)->fetch_all(MYSQLI_ASSOC);

// Close connection
$conn->close();

// Page title
$_title = 'Admin Dashboard';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_title ?></title>
    <link rel="stylesheet" href="../css/admin-dashboard.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin-dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include '../header.php'; ?>
    
    <div class="admin-dashboard-container">
        <h1 class="admin-dashboard-title">Admin Dashboard</h1>
        
        <div class="admin-stats-cards">
            <div class="admin-stat-card customers">
                <i class="fas fa-users"></i>
                <h3 id="new-customers-count"><?= $todayStats['new_customers'] ?? 0 ?></h3>
                <p>New Customers Registration</p>
            </div>
            <div class="admin-stat-card sales">
                <i class="fas fa-dollar-sign"></i>
                <h3 id="total-sales-amount">RM<?= number_format($todayStats['total_sales'] ?? 0, 2) ?></h3>
                <p>Total Sales</p>
            </div>
            <div class="admin-stat-card orders">
                <i class="fas fa-shopping-cart"></i>
                <h3 id="new-orders-count"><?= $todayStats['new_orders'] ?? 0 ?></h3>
                <p>New Orders</p>
            </div>
            <div class="admin-stat-card products">
                <i class="fas fa-ice-cream"></i>
                <h3 id="total-products-count"><?= $todayStats['total_products'] ?? 0 ?></h3>
                <p>Total Products</p>
            </div>
        </div>
        
        <div class="admin-chart-container">
            <div class="admin-chart-card">
                <h2>Monthly Sales by Product</h2>
                <canvas id="productSalesChart"></canvas>
                <div id="product-data" 
                     data-labels='<?= json_encode(array_column($monthlySales, 'product_name')) ?>' 
                     data-sales='<?= json_encode(array_column($monthlySales, 'total_sales')) ?>'></div>
            </div>
            <div class="admin-chart-card">
                <h2>Sales by Category</h2>
                <canvas id="categorySalesChart"></canvas>
                <div id="category-data" 
                     data-labels='<?= json_encode(array_column($categorySales, 'category_name')) ?>' 
                     data-sales='<?= json_encode(array_column($categorySales, 'total_sales')) ?>'></div>
            </div>
        </div>
        
        <div class="admin-data-table">
            <h2>Recent Orders</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentOrders)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">No recent orders found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td>#<?= $order['order_id'] ?></td>
                                <td><?= htmlspecialchars($order['customer_name']) ?></td>
                                <td><?= date('M d, Y H:i', strtotime($order['order_date'])) ?></td>
                                <td>RM<?= number_format($order['total_amount'], 2) ?></td>
                                <td class="status-<?= strtolower($order['status']) ?>"><?= ucfirst($order['status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="admin-data-table">
            <h2>New Customer Registration</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Registration Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentCustomers)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">No recent customers found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentCustomers as $customer): ?>
                            <tr>
                                <td>#<?= $customer['id'] ?></td>
                                <td><?= htmlspecialchars($customer['name']) ?></td>
                                <td><?= htmlspecialchars($customer['email']) ?></td>
                                <td><?= date('M d, Y', strtotime($customer['registration_date'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script src="../js/app.js"></script>
    <?php include '../footer.php'; ?>
</body>
</html>