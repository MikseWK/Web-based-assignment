<?php
require '../base.php';
// ----------------------------------------------------------------------------

include '../header.php';
?>

<main>
    <!-- Hero Banner -->
    <section class="about-hero">
        <div class="about-hero-overlay"></div>
        <div class="about-hero-content">
            <h1>Our Story</h1>
            <p>The journey of Frost Delights - From passion to perfection</p>
        </div>
    </section>

    <!-- Our Journey Section -->
    <section class="journey-section">
        <div class="container">
            <h2 class="section-title">Our Journey</h2>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-year">
                        <span>2010</span>
                    </div>
                    <div class="timeline-content">
                        <h3>Where It All Began</h3>
                        <p>Frost Delights was born from a simple passion for creating the perfect scoop of ice cream. Our founder started experimenting with recipes in a small kitchen, determined to create ice cream that would bring joy to everyone who tasted it.</p>
                        <img src="assets/images/timeline-1.jpg" alt="Frost Delights Beginning">
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-year">
                        <span>2013</span>
                    </div>
                    <div class="timeline-content">
                        <h3>First Store Opening</h3>
                        <p>After years of perfecting recipes and gathering a loyal following at local markets, we opened our first store. A small but charming location that quickly became a neighborhood favorite.</p>
                        <img src="assets/images/timeline-2.jpg" alt="First Store Opening">
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-year">
                        <span>2016</span>
                    </div>
                    <div class="timeline-content">
                        <h3>Expanding Our Flavors</h3>
                        <p>As our popularity grew, so did our menu. We began experimenting with unique flavor combinations and premium ingredients sourced from around the world, creating signature flavors that couldn't be found anywhere else.</p>
                        <img src="assets/images/timeline-3.jpg" alt="Expanding Flavors">
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-year">
                        <span>2020</span>
                    </div>
                    <div class="timeline-content">
                        <h3>Growing Together</h3>
                        <p>Despite global challenges, our community's support allowed us to expand to multiple locations. We've remained committed to our core values: quality ingredients, handcrafted preparation, and bringing joy through every scoop.</p>
                        <img src="assets/images/timeline-4.jpg" alt="Growing Together">
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-year">
                        <span>Today</span>
                    </div>
                    <div class="timeline-content">
                        <h3>Continuing Our Mission</h3>
                        <p>Today, Frost Delights continues to innovate while staying true to our roots. We're committed to sustainability, community involvement, and creating moments of joy for our customers through the simple pleasure of exceptional ice cream.</p>
                        <img src="assets/images/timeline-5.jpg" alt="Our Mission Today">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Values Section -->
    <section class="values-section">
        <div class="container">
            <h2 class="section-title">Our Values</h2>
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3>Quality Ingredients</h3>
                    <p>We source only the finest ingredients, working with local farmers and premium suppliers to ensure every scoop is exceptional.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-hands"></i>
                    </div>
                    <h3>Handcrafted Care</h3>
                    <p>Every batch of our ice cream is made with meticulous attention to detail, following traditional methods that preserve flavor and texture.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-recycle"></i>
                    </div>
                    <h3>Sustainability</h3>
                    <p>We're committed to environmentally friendly practices, from our packaging to our production processes.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Community</h3>
                    <p>We believe in giving back to the communities that support us through partnerships with local organizations and charitable initiatives.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section">
        <div class="container">
            <h2 class="section-title">Meet Our Team</h2>
            <div class="team-grid">
                <div class="team-member">
                    <img src="assets/images/team-1.jpg" alt="Team Member">
                    <h3>Sarah Johnson</h3>
                    <p class="member-title">Founder & Head Chef</p>
                    <p class="member-bio">With a background in culinary arts and a passion for desserts, Sarah leads our flavor innovation and ensures quality in every batch.</p>
                </div>
                
                <div class="team-member">
                    <img src="assets/images/team-2.jpg" alt="Team Member">
                    <h3>Michael Chen</h3>
                    <p class="member-title">Operations Director</p>
                    <p class="member-bio">Michael oversees our daily operations, ensuring that every store delivers the exceptional experience our customers expect.</p>
                </div>
                
                <div class="team-member">
                    <img src="assets/images/team-3.jpg" alt="Team Member">
                    <h3>Aisha Patel</h3>
                    <p class="member-title">Creative Director</p>
                    <p class="member-bio">Aisha brings our brand to life through creative marketing and store design, creating immersive experiences for our customers.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact CTA Section -->
    <section class="contact-cta">
        <div class="container">
            <h2>Come Visit Us</h2>
            <p>Experience the magic of Frost Delights at any of our locations. We can't wait to serve you!</p>
            <a href="contact.php" class="cta-button">Find Our Stores</a>
        </div>
    </section>
</main>

<?php
include '../footer.php';