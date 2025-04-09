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
                <img src="/images/caketest1.jpeg"class="flavor-image">
                <div class="flavor-content">
                    <h3 class="flavor-title">Oreo Ice Cream Cake</h3>
                    <p class="flavor-description">Fresh Oreos blended with our premium cream base.</p>
                    <p class="flavor-price">RM 9.99</p>
                    <button class="flavor-button">Add to Cart</button>
                </div>
            </div>
            
            <div class="flavor-card">
                <img src="/images/yogurttest2.jpeg"class="flavor-image">
                <div class="flavor-content">
                    <h3 class="flavor-title">Yogurt Ice Cream</h3>
                    <p class="flavor-description">Rich yogurt ice cream with chocolate chunks.</p>
                    <p class="flavor-price">RM 5.90</p>
                    <button class="flavor-button">Add to Cart</button>
                </div>
            </div>
            
            <div class="flavor-card">
                <img src="/images/gelatotest3.jpeg"class="flavor-image">
                <div class="flavor-content">
                    <h3 class="flavor-title">Gelato Ice Cream</h3>
                    <p class="flavor-description">Classic gelato with premium Madagascar beans.</p>
                    <p class="flavor-price">RM 99.99</p>
                    <button class="flavor-button">Add to Cart</button>
                </div>
            </div>
            
            <div class="flavor-card">
                <img src="/images/sorbettest4.jpeg"class="flavor-image">
                <div class="flavor-content">
                    <h3 class="flavor-title">Sorbet Ice Cream</h3>
                    <p class="flavor-description">Authentic Sorbet in a creamy base.</p>
                    <p class="flavor-price">RM2.99</p>
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