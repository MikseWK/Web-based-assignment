<?php
require '../base.php';
include '../header.php';
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="../css/style.css">
</head>


<body>
   

    <main class="main-content">
        <section class="hero-section">
            <h1 class="fade-in">Welcome to Our Ice Cream Shop</h1>
            <p class="fade-in">Discover the best flavors and enjoy a delightful experience.</p>
            <button class="cta-button">Explore Now</button>
        </section>

        <section class="featured-products">
            <h2 class="fade-in">Featured Flavors</h2>
            <div class="product-grid">
                <div class="product-item slide-up">
                    <img src="../assets/images/flavor1.jpg" alt="Flavor 1">
                    <h3>Flavor 1</h3>
                </div>
                <div class="product-item slide-up">
                    <img src="../assets/images/flavor2.jpg" alt="Flavor 2">
                    <h3>Flavor 2</h3>
                </div>
                <!-- Add more product items as needed -->
            </div>
        </section>
    </main>

    
</body>
</html>

<?php
include '../footer.php';