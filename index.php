<?php
require 'base.php';
include 'header.php';
?>

<main class="main-content">
    <!-- Hero Banner Section -->
    <section class="hero-banner">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title">Indulge in Premium Ice Cream</h1>
            <p class="hero-subtitle">Handcrafted with love, served with passion</p>
            <button class="hero-button">Explore Our Flavors</button>
        </div>
    </section>

    <!-- Featured Flavors Section -->
    <section class="featured-section">
        <h2 class="section-title">Our Signature Flavors</h2>
        <div class="flavors-grid">
            <div class="flavor-card">
                <img src="assets/images/strawberry.jpg" alt="Strawberry Delight" class="flavor-image">
                <div class="flavor-content">
                    <h3 class="flavor-title">Strawberry Delight</h3>
                    <p class="flavor-description">Fresh strawberries blended with our premium cream base.</p>
                    <p class="flavor-price">RM 12.90</p>
                    <button class="flavor-button">Add to Cart</button>
                </div>
            </div>
            
            <div class="flavor-card">
                <img src="assets/images/chocolate.jpg" alt="Double Chocolate" class="flavor-image">
                <div class="flavor-content">
                    <h3 class="flavor-title">Double Chocolate</h3>
                    <p class="flavor-description">Rich chocolate ice cream with chocolate chunks.</p>
                    <p class="flavor-price">RM 13.90</p>
                    <button class="flavor-button">Add to Cart</button>
                </div>
            </div>
            
            <div class="flavor-card">
                <img src="assets/images/vanilla.jpg" alt="Madagascar Vanilla" class="flavor-image">
                <div class="flavor-content">
                    <h3 class="flavor-title">Madagascar Vanilla</h3>
                    <p class="flavor-description">Classic vanilla with premium Madagascar beans.</p>
                    <p class="flavor-price">RM 11.90</p>
                    <button class="flavor-button">Add to Cart</button>
                </div>
            </div>
            
            <div class="flavor-card">
                <img src="assets/images/matcha.jpg" alt="Matcha Green Tea" class="flavor-image">
                <div class="flavor-content">
                    <h3 class="flavor-title">Matcha Green Tea</h3>
                    <p class="flavor-description">Authentic Japanese matcha in a creamy base.</p>
                    <p class="flavor-price">RM 14.90</p>
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
                <iframe width="560" height="315" src="https://www.youtube.com/embed/dQw4w9WgXcQ" 
                        title="Ice Cream Making Process" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen></iframe>
            </div>
            <p class="video-description">Watch our artisans craft the perfect scoop using only the finest ingredients</p>
        </div>
    </section>

    <!-- About Us Section remains unchanged -->
    <section class="about-section">
        <div class="about-container">
            <div class="about-image">
                <img src="assets/images/about-us.jpg" alt="About Frost Delights">
            </div>
            <div class="about-content">
                <h2 class="about-title">Our Story</h2>
                <p class="about-text">Founded in 2010, Frost Delights has been serving premium handcrafted ice cream made from the finest ingredients. Our passion for quality and innovation has made us a beloved destination for ice cream lovers.</p>
                <p class="about-text">We take pride in creating unique flavors that delight the senses and bring joy to our customers. Every scoop is made with love and attention to detail.</p>
                <button class="about-button">Learn More About Us</button>
            </div>
        </div>
    </section>
</main>

<?php
include 'footer.php';
?>