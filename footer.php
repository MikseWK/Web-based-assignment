</main>

<footer class="modern-footer">
    <div class="footer-container">
        <div class="footer-section">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </div>
        
        <div class="footer-section">
            <h3>Our Products</h3>
            <ul>
                <li><a href="products.php?category=ice-cream">Ice Cream</a></li>
                <li><a href="products.php?category=frozen-yogurt">Frozen Yogurt</a></li>
                <li><a href="products.php?category=desserts">Desserts</a></li>
                <li><a href="products.php?category=specials">Specials</a></li>
            </ul>
        </div>
        
        <div class="footer-section">
            <h3>Connect With Us</h3>
            <ul class="social-links">
                <li><a href="#"><i class="fa fa-facebook"></i> Facebook</a></li>
                <li><a href="#"><i class="fa fa-instagram"></i> Instagram</a></li>
                <li><a href="#"><i class="fa fa-twitter"></i> Twitter</a></li>
                <li><a href="#"><i class="fa fa-youtube"></i> YouTube</a></li>
            </ul>
        </div>
    </div>
    
    <div class="footer-bottom">
        <p>Copyright &copy; <?= date('Y')?>, Frost Delights Sdn Bhd (1069204-T)</p>
        <p>All Rights Reserved by Frosty Delights Sdn Bhd</p>
    </div>
</footer>

<style>
.modern-footer {
    background-color: #000;
    color: #fff;
    padding: 40px 0 20px;
    font-family: Arial, sans-serif;
}

.footer-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.footer-section {
    flex: 1;
    min-width: 200px;
    margin-bottom: 20px;
    padding: 0 15px;
}

.footer-section h3 {
    color: #ff69b4; /* Hot pink */
    font-size: 18px;
    margin-bottom: 15px;
    font-weight: 600;
    position: relative;
    padding-bottom: 10px;
}

.footer-section h3:after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 50px;
    height: 2px;
    background-color: #ff69b4;
}

.footer-section ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-section ul li {
    margin-bottom: 10px;
}

.footer-section ul li a {
    color: #fff;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-section ul li a:hover {
    color: #ff69b4;
}

.social-links li a {
    display: flex;
    align-items: center;
}

.social-links li a i {
    margin-right: 8px;
}

.footer-bottom {
    text-align: center;
    padding-top: 20px;
    margin-top: 20px;
    border-top: 1px solid #333;
}

.footer-bottom p {
    margin: 5px 0;
    font-size: 14px;
}

@media (max-width: 768px) {
    .footer-container {
        flex-direction: column;
    }
    
    .footer-section {
        width: 100%;
        margin-bottom: 30px;
    }
}
</style>

<!-- Font Awesome for social icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</body>
</html>