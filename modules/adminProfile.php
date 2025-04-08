<?php 
require '../base.php';
// Set the active page for header navigation
$active_page = 'admin';
include '../header.php';
auth('admin');
// Check if user is logged in and is admin
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    $_SESSION['message'] = "You must be logged in as an admin to access this page.";
    header('Location: ../index.php');
    exit;
}

// Dummy admin data for testing
$admin = [
    'id' => 1,
    'name' => 'Admin Test',
    'email' => 'abc@gmail.com',
    'password' => 'Abc12345!',
    'role' => 'admin',
    'last_login' => date('Y-m-d H:i:s')
];

// Remove database query attempt
?>

<main class="admin-dashboard">
    <div class="container">
        <h1 class="page-title">Admin Dashboard</h1>
        
        <div class="admin-nav">
            <a href="#profile" class="admin-nav-item active" data-target="profile-section">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
            <a href="#sales" class="admin-nav-item" data-target="sales-section">
                <i class="fas fa-chart-line"></i>
                <span>Sales Report</span>
            </a>
            <a href="#inventory" class="admin-nav-item" data-target="inventory-section">
                <i class="fas fa-boxes"></i>
                <span>Inventory Management</span>
            </a>
            <a href="#orders" class="admin-nav-item" data-target="orders-section">
                <i class="fas fa-shopping-bag"></i>
                <span>Orders</span>
            </a>
        </div>
        
        <div class="admin-content">
            <!-- Profile Section -->
            <section id="profile-section" class="admin-section active">
                <div class="profile-card">
                    <div class="profile-header">
                        <div class="profile-avatar">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="profile-info">
                            <h2><?= htmlspecialchars($admin['name'] ?? 'Admin User') ?></h2>
                            <p class="profile-role">Administrator</p>
                        </div>
                    </div>
                    <div class="profile-details">
                        <div class="detail-item">
                            <span class="detail-label">Email:</span>
                            <span class="detail-value"><?= htmlspecialchars($admin['email'] ?? 'admin@example.com') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Last Login:</span>
                            <span class="detail-value"><?= htmlspecialchars($admin['last_login'] ?? date('Y-m-d H:i:s')) ?></span>
                        </div>
                    </div>
                    <div class="profile-actions">
                        <button class="edit-profile-btn">Edit Profile</button>
                        <button class="change-password-btn">Change Password</button>
                    </div>
                </div>
                
                <div class="quick-stats">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Total Orders</h3>
                            <p class="stat-value">254</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Total Customers</h3>
                            <p class="stat-value">1,250</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Total Revenue</h3>
                            <p class="stat-value">RM 25,430</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-ice-cream"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Products</h3>
                            <p class="stat-value">48</p>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Sales Report Section -->
            <section id="sales-section" class="admin-section">
                <h2 class="section-title">Sales Report</h2>
                <div class="report-filters">
                    <div class="filter-group">
                        <label for="date-range">Date Range:</label>
                        <select id="date-range" class="filter-select">
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month" selected>This Month</option>
                            <option value="year">This Year</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="product-filter">Product:</label>
                        <select id="product-filter" class="filter-select">
                            <option value="all" selected>All Products</option>
                            <option value="1">Strawberry Delight</option>
                            <option value="2">Double Chocolate</option>
                            <option value="3">Madagascar Vanilla</option>
                        </select>
                    </div>
                    <button id="generate-report" class="report-btn">Generate Report</button>
                </div>
                
                <div class="sales-chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
                
                <div class="top-products">
                    <h3>Top Selling Products</h3>
                    <div class="product-list">
                        <div class="product-item">
                            <div class="product-rank">1</div>
                            <div class="product-image">
                                <img src="../assets/images/chocolate.jpg" alt="Double Chocolate">
                            </div>
                            <div class="product-details">
                                <h4>Double Chocolate</h4>
                                <p>Units Sold: 156</p>
                            </div>
                            <div class="product-revenue">
                                <p>RM 2,184</p>
                            </div>
                        </div>
                        <div class="product-item">
                            <div class="product-rank">2</div>
                            <div class="product-image">
                                <img src="../assets/images/vanilla.jpg" alt="Madagascar Vanilla">
                            </div>
                            <div class="product-details">
                                <h4>Madagascar Vanilla</h4>
                                <p>Units Sold: 132</p>
                            </div>
                            <div class="product-revenue">
                                <p>RM 1,584</p>
                            </div>
                        </div>
                        <div class="product-item">
                            <div class="product-rank">3</div>
                            <div class="product-image">
                                <img src="../assets/images/strawberry.jpg" alt="Strawberry Delight">
                            </div>
                            <div class="product-details">
                                <h4>Strawberry Delight</h4>
                                <p>Units Sold: 98</p>
                            </div>
                            <div class="product-revenue">
                                <p>RM 1,274</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Inventory Management Section -->
            <section id="inventory-section" class="admin-section">
                <h2 class="section-title">Inventory Management</h2>
                <div class="inventory-actions">
                    <a href="modifystocks.php" class="action-btn">
                        <i class="fas fa-edit"></i>
                        <span>Modify Stock</span>
                    </a>
                    <a href="#" class="action-btn">
                        <i class="fas fa-plus"></i>
                        <span>Add New Product</span>
                    </a>
                    <a href="#" class="action-btn">
                        <i class="fas fa-file-export"></i>
                        <span>Export Inventory</span>
                    </a>
                </div>
                
                <div class="inventory-overview">
                    <h3>Inventory Status</h3>
                    <div class="inventory-stats">
                        <div class="inventory-stat">
                            <div class="stat-circle">
                                <svg viewBox="0 0 36 36">
                                    <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                                    <path class="circle-fill" stroke-dasharray="75, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                                </svg>
                                <div class="stat-percentage">75%</div>
                            </div>
                            <p>In Stock</p>
                        </div>
                        <div class="inventory-stat">
                            <div class="stat-circle warning">
                                <svg viewBox="0 0 36 36">
                                    <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                                    <path class="circle-fill" stroke-dasharray="15, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                                </svg>
                                <div class="stat-percentage">15%</div>
                            </div>
                            <p>Low Stock</p>
                        </div>
                        <div class="inventory-stat">
                            <div class="stat-circle danger">
                                <svg viewBox="0 0 36 36">
                                    <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                                    <path class="circle-fill" stroke-dasharray="10, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                                </svg>
                                <div class="stat-percentage">10%</div>
                            </div>
                            <p>Out of Stock</p>
                        </div>
                    </div>
                </div>
                
                <div class="low-stock-alert">
                    <h3>Low Stock Alert</h3>
                    <div class="alert-list">
                        <div class="alert-item">
                            <div class="alert-image">
                                <img src="../assets/images/matcha.jpg" alt="Matcha Green Tea">
                            </div>
                            <div class="alert-details">
                                <h4>Matcha Green Tea</h4>
                                <p class="stock-level warning">Only 5 left</p>
                            </div>
                            <a href="modifystocks.php" class="restock-btn">Restock</a>
                        </div>
                        <div class="alert-item">
                            <div class="alert-image">
                                <img src="../assets/images/chocolate.jpg" alt="Double Chocolate">
                            </div>
                            <div class="alert-details">
                                <h4>Double Chocolate</h4>
                                <p class="stock-level warning">Only 8 left</p>
                            </div>
                            <a href="modifystocks.php" class="restock-btn">Restock</a>
                        </div>
                        <div class="alert-item">
                            <div class="alert-image">
                                <img src="../assets/images/strawberry.jpg" alt="Strawberry Delight">
                            </div>
                            <div class="alert-details">
                                <h4>Strawberry Delight</h4>
                                <p class="stock-level danger">Out of stock</p>
                            </div>
                            <a href="modifystocks.php" class="restock-btn">Restock</a>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Orders Section -->
            <section id="orders-section" class="admin-section">
                <h2 class="section-title">Recent Orders</h2>
                <div class="orders-table-container">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#ORD-2023-1001</td>
                                <td>John Smith</td>
                                <td>2023-11-15</td>
                                <td>RM 45.80</td>
                                <td><span class="status-badge completed">Completed</span></td>
                                <td><button class="view-order-btn">View</button></td>
                            </tr>
                            <tr>
                                <td>#ORD-2023-1002</td>
                                <td>Sarah Johnson</td>
                                <td>2023-11-15</td>
                                <td>RM 32.50</td>
                                <td><span class="status-badge processing">Processing</span></td>
                                <td><button class="view-order-btn">View</button></td>
                            </tr>
                            <tr>
                                <td>#ORD-2023-1003</td>
                                <td>Michael Chen</td>
                                <td>2023-11-14</td>
                                <td>RM 78.20</td>
                                <td><span class="status-badge shipped">Shipped</span></td>
                                <td><button class="view-order-btn">View</button></td>
                            </tr>
                            <tr>
                                <td>#ORD-2023-1004</td>
                                <td>Emily Davis</td>
                                <td>2023-11-14</td>
                                <td>RM 25.90</td>
                                <td><span class="status-badge completed">Completed</span></td>
                                <td><button class="view-order-btn">View</button></td>
                            </tr>
                            <tr>
                                <td>#ORD-2023-1005</td>
                                <td>David Wilson</td>
                                <td>2023-11-13</td>
                                <td>RM 54.30</td>
                                <td><span class="status-badge cancelled">Cancelled</span></td>
                                <td><button class="view-order-btn">View</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    <a href="#" class="page-link active">1</a>
                    <a href="#" class="page-link">2</a>
                    <a href="#" class="page-link">3</a>
                    <a href="#" class="page-link">Next</a>
                </div>
            </section>
        </div>
    </div>
</main>

<!-- Link to external CSS and JS files -->
<link rel="stylesheet" href="../css/adminProfile.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../js/adminProfile.js"></script>

<?php
include '../footer.php';