<?php
require '../base.php';

auth('Admin');

// Dummy data for dashboard
$dashboardData = [
    'newClients' => 236,
    'clientsGrowth' => '+11.3%',
    'earnings' => 18306,
    'newProjects' => 1538,
    'projectsDecline' => '-14.5%',
    'totalProjects' => 864,
    'salesData' => [1200, 1800, 1400, 2800, 1600, 1200, 2000],
    'directSales' => 32346,
    'referralSales' => 52138,
    'affiliateSales' => 41004,
];

$_title = 'Admin Dashboard';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_title ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="message-container">
            <div class="message"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
        </div>
    <?php endif; ?>
    
    <?php include '../header.php'; ?>

    <div class="admin-dashboard">
        <div class="sidebar slide-in">
            <div class="sidebar-header">
                <img src="../assets/images/user-icon.png" alt="Admin" class="profile-pic">
                <div class="admin-info">
                    <h3>Admin User</h3>
                    <p>Administrator</p>
                </div>
            </div>
            
            <div class="sidebar-menu">
                <div class="menu-category">Dashboards</div>
                <div class="menu-item active">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </div>
                
                <div class="menu-category">Management</div>
                <div class="menu-item">
                    <i class="fas fa-ice-cream"></i> Products
                </div>
                <div class="menu-item">
                    <i class="fas fa-boxes"></i> Inventory
                </div>
                <div class="menu-item">
                    <i class="fas fa-shopping-cart"></i> Orders
                </div>
                <div class="menu-item">
                    <i class="fas fa-users"></i> Customers
                </div>
                
                <div class="menu-category">Analytics</div>
                <div class="menu-item new">
                    <i class="fas fa-chart-line"></i> Sales Reports
                </div>
                <div class="menu-item">
                    <i class="fas fa-star"></i> Reviews
                </div>
                
                <div class="menu-category">Settings</div>
                <div class="menu-item">
                    <i class="fas fa-user-cog"></i> Account
                </div>
                <div class="menu-item">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </div>
            </div>
        </div>
        
        <div class="dashboard-content">
            <div class="greeting-header fade-in">
                <div>
                    <h2>Good Morning, Admin!</h2>
                    <p>Dashboard</p>
                </div>
                <div>
                    <span>Aug 19</span>
                </div>
            </div>
            
            <div class="stats-container">
                <div class="stat-card slide-up" style="animation-delay: 0.1s">
                    <div class="stat-info">
                        <h3><?= $dashboardData['newClients'] ?> <span class="growth positive"><?= $dashboardData['clientsGrowth'] ?></span></h3>
                        <p>New Customers</p>
                    </div>
                    <div class="stat-icon" style="background-color: rgba(108, 92, 231, 0.2); color: var(--primary-color)">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                
                <div class="stat-card slide-up" style="animation-delay: 0.2s">
                    <div class="stat-info">
                        <h3>$<?= number_format($dashboardData['earnings']) ?></h3>
                        <p>Earnings of Month</p>
                    </div>
                    <div class="stat-icon" style="background-color: rgba(255, 62, 142, 0.2); color: var(--secondary-color)">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
                
                <div class="stat-card slide-up" style="animation-delay: 0.3s">
                    <div class="stat-info">
                        <h3><?= $dashboardData['newProjects'] ?> <span class="growth negative"><?= $dashboardData['projectsDecline'] ?></span></h3>
                        <p>New Orders</p>
                    </div>
                    <div class="stat-icon" style="background-color: rgba(0, 210, 211, 0.2); color: var(--accent-color)">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                </div>
                
                <div class="stat-card slide-up" style="animation-delay: 0.4s">
                    <div class="stat-info">
                        <h3><?= $dashboardData['totalProjects'] ?></h3>
                        <p>Total Products</p>
                    </div>
                    <div class="stat-icon" style="background-color: rgba(253, 203, 110, 0.2); color: #fdcb6e">
                        <i class="fas fa-ice-cream"></i>
                    </div>
                </div>
            </div>
            
            <div class="charts-container">
                <div class="chart-card fade-in">
                    <div class="chart-header">
                        <h3>Sales Statistics</h3>
                        <i class="fas fa-ellipsis-v"></i>
                    </div>
                    <div class="chart-container">
                        <div class="bar-chart">
                            <?php foreach ($dashboardData['salesData'] as $index => $value): ?>
                                <?php $height = ($value / 3000) * 100; ?>
                                <div class="bar" style="height: <?= $height ?>%" title="Jun<?= $index + 1 ?>: $<?= $value ?>"></div>
                            <?php endforeach; ?>
                        </div>
                        <div style="text-align: center; margin-top: 10px; font-size: 12px; opacity: 0.7;">
                            Sales for this month
                        </div>
                    </div>
                </div>
                
                <div class="chart-card fade-in">
                    <div class="chart-header">
                        <h3>Total Sales</h3>
                    </div>
                    <div class="chart-container">
                        <div class="donut-chart">
                            <div class="donut-center">
                                <span>Sales</span>
                            </div>
                        </div>
                        <div class="sales-legend">
                            <div class="legend-item">
                                <div class="legend-color" style="background-color: var(--primary-color)"></div>
                                <span>Direct Sales</span>
                                <span class="legend-value">$<?= number_format($dashboardData['directSales']) ?></span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background-color: var(--secondary-color)"></div>
                                <span>Referral Sales</span>
                                <span class="legend-value">$<?= number_format($dashboardData['referralSales']) ?></span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background-color: var(--accent-color)"></div>
                                <span>Affiliate Sales</span>
                                <span class="legend-value">$<?= number_format($dashboardData['affiliateSales']) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <h3 class="fade-in">Quick Actions</h3>
            <div class="admin-actions">
                <div class="action-card slide-up" style="animation-delay: 0.1s">
                    <div class="action-icon" style="background-color: rgba(108, 92, 231, 0.2); color: var(--primary-color)">
                        <i class="fas fa-plus"></i>
                    </div>
                    <h3>Add New Product</h3>
                    <p>Create a new ice cream product</p>
                </div>
                
                <div class="action-card slide-up" style="animation-delay: 0.2s">
                    <div class="action-icon" style="background-color: rgba(255, 62, 142, 0.2); color: var(--secondary-color)">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h3>Manage Inventory</h3>
                    <p>Update stock levels and ingredients</p>
                </div>
                
                <div class="action-card slide-up" style="animation-delay: 0.3s">
                    <div class="action-icon" style="background-color: rgba(0, 210, 211, 0.2); color: var(--accent-color)">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3>Customer Reviews</h3>
                    <p>View and respond to customer feedback</p>
                </div>
                
                <div class="action-card slide-up" style="animation-delay: 0.4s">
                    <div class="action-icon" style="background-color: rgba(253, 203, 110, 0.2); color: #fdcb6e">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <h3>Sales Reports</h3>
                    <p>View detailed sales analytics</p>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/app.js"></script>
</body>
</html>

<?php
include '../footer.php';