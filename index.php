<?php
require 'base.php';
include 'header.php';
?>

<main class="main-content">
    <!-- Hero Banner Section with Slideshow -->
    <section class="hero-banner">
        <!-- Slideshow background -->
        <div class="slideshow-container">
            <div class="slideshow-slide active" style="background-image: url('images/pexels-roman-odintsov-5061197.jpg');"></div>
            <div class="slideshow-slide" style="background-image: url('images/pexels-kampus-8777827.jpg');"></div>
            <div class="slideshow-slide" style="background-image: url('images/pexels-micheile-7886449.jpg');"></div>
        </div>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title">Indulge in Premium Ice Cream</h1>
            <p class="hero-subtitle">Handcrafted with love, served with passion</p>
            <button class="hero-button">Explore Our Flavors</button>
        </div>
        <!-- Animated ice cream icons -->
        <div class="floating-icons">
            <div class="floating-icon" style="animation: float 3s ease-in-out infinite, rotate 20s linear infinite;">🍦</div>
            <div class="floating-icon" style="animation: float 4s ease-in-out infinite 1s, rotate 15s linear infinite;">🍧</div>
            <div class="floating-icon" style="animation: float 5s ease-in-out infinite 0.5s, rotate 25s linear infinite;">🍨</div>
        </div>
    </section>

    <!-- Featured Flavors Section -->
    <section class="featured-section">
        <h2 class="section-title">Our Signature Flavors</h2>
        <div class="flavors-grid">
            <div class="flavor-card" style="animation: fadeInUp 0.8s ease 0.1s; animation-fill-mode: both;">
                <div class="flavor-image-container">
                    <img src="/images/caketest1.jpeg" class="flavor-image">
                    <div class="flavor-badge">Popular</div>
                </div>
                <div class="flavor-content">
                    <h3 class="flavor-title">Oreo Ice Cream Cake</h3>
                    <p class="flavor-description">Fresh Oreos blended with our premium cream base.</p>
                    <p class="flavor-price">RM 9.99</p>
                    <button class="flavor-button">Add to Cart</button>
                </div>
            </div>
            
            <div class="flavor-card" style="animation: fadeInUp 0.8s ease 0.3s; animation-fill-mode: both;">
                <div class="flavor-image-container">
                    <img src="/images/yogurttest2.jpeg" class="flavor-image">
                    <div class="flavor-badge">New</div>
                </div>
                <div class="flavor-content">
                    <h3 class="flavor-title">Yogurt Ice Cream</h3>
                    <p class="flavor-description">Rich yogurt ice cream with chocolate chunks.</p>
                    <p class="flavor-price">RM 5.90</p>
                    <button class="flavor-button">Add to Cart</button>
                </div>
            </div>
            
            <div class="flavor-card" style="animation: fadeInUp 0.8s ease 0.5s; animation-fill-mode: both;">
                <div class="flavor-image-container">
                    <img src="/images/gelatotest3.jpeg" class="flavor-image">
                    <div class="flavor-badge">Premium</div>
                </div>
                <div class="flavor-content">
                    <h3 class="flavor-title">Gelato Ice Cream</h3>
                    <p class="flavor-description">Classic gelato with premium Madagascar beans.</p>
                    <p class="flavor-price">RM 99.99</p>
                    <button class="flavor-button">Add to Cart</button>
                </div>
            </div>
            
            <div class="flavor-card" style="animation: fadeInUp 0.8s ease 0.7s; animation-fill-mode: both;">
                <div class="flavor-image-container">
                    <img src="/images/sorbettest4.jpeg" class="flavor-image">
                    <div class="flavor-badge">Best Value</div>
                </div>
                <div class="flavor-content">
                    <h3 class="flavor-title">Sorbet Ice Cream</h3>
                    <p class="flavor-description">Authentic Sorbet in a creamy base.</p>
                    <p class="flavor-price">RM 2.99</p>
                    <button class="flavor-button">Add to Cart</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Showcase Section -->
    <section class="video-section">
        <div class="container">
            <h2 class="section-title">See How We Make Our Ice Cream</h2>
            <div class="video-container">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/bDDazMFfW2g" 
                        title="Ice Cream Making Process" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen></iframe>
            </div>
            <p class="video-description">Watch our artisans craft the perfect scoop using only the finest ingredients</p>
        </div>
    </section>

    <!-- Customer Reviews Section in Card Style -->
    <section class="about-section">
        <h2 class="section-title">Our Coolest Fans</h2>
        <div class="about-reviews-grid">
            <div class="about-card">
                <div class="about-quote">
                    <span class="about-stars">★★★★★</span>
                    <p class="about-quote-text">
                        "The ice cream was incredibly creamy and flavorful, with a wide variety of unique and delicious options. Highly recommended!"
                    </p>
                    <div class="about-author">— Muhd H.</div>
                </div>
            </div>
            <div class="about-card">
                <div class="about-quote">
                    <span class="about-stars">★★★★★</span>
                    <p class="about-quote-text">
                        "I am super in love with the matcha ice cream. The waffles are good as well and love love love the vibe."
                    </p>
                    <div class="about-author">— Deeba F.</div>
                </div>
            </div>
            <div class="about-card">
                <div class="about-quote">
                    <span class="about-stars">★★★★★</span>
                    <p class="about-quote-text">
                        "Their durian series ice cream is hard to resist once you try it. The ice cream is rich with durian flavor, just like eating real durian."
                    </p>
                    <div class="about-author">— S.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Scroll to top button with animation -->
    <button id="scrollToTop" class="scroll-top-btn" style="animation: pulse 2s infinite;">
        <i class="fas fa-arrow-up"></i>
    </button>
</main>

<!-- Include external JavaScript file -->
<script src="js/app.js"></script>

<?php
include 'footer.php';