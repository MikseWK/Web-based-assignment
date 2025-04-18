// ============================================================================
// General Functions
// ============================================================================



// ============================================================================
// Page Load (jQuery)
// ============================================================================

$(() => {

    // Autofocus
    $('form :input:not(button):first').focus();
    $('.err:first').prev().focus();
    $('.err:first').prev().find(':input:first').focus();
    
    // Confirmation message
    $('[data-confirm]').on('click', e => {
        const text = e.target.dataset.confirm || 'Are you sure?';
        if (!confirm(text)) {
            e.preventDefault();
            e.stopImmediatePropagation();
        }
    });

    // Initiate GET request
    $('[data-get]').on('click', e => {
        e.preventDefault();
        const url = e.target.dataset.get;
        location = url || location;
    });

    // Initiate POST request
    $('[data-post]').on('click', e => {
        e.preventDefault();
        const url = e.target.dataset.post;
        const f = $('<form>').appendTo(document.body)[0];
        f.method = 'POST';
        f.action = url || location;
        f.submit();
    });

    // Reset form
    $('[type=reset]').on('click', e => {
        e.preventDefault();
        location = location;
    });

    // Auto uppercase
    $('[data-upper]').on('input', e => {
        const a = e.target.selectionStart;
        const b = e.target.selectionEnd;
        e.target.value = e.target.value.toUpperCase();
        e.target.setSelectionRange(a, b);
    });

});

//




//modify stocks
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('stockSearch');
    searchInput.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const stockItems = document.querySelectorAll('.stock-item');
        
        stockItems.forEach(item => {
            const name = item.querySelector('.item-name').textContent.toLowerCase();
            const description = item.querySelector('.item-description').textContent.toLowerCase();
            
            if (name.includes(searchTerm) || description.includes(searchTerm)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
    
    // Filter functionality
    const filterSelect = document.getElementById('stockFilter');
    filterSelect.addEventListener('change', function() {
        const filterValue = this.value;
        const stockItems = document.querySelectorAll('.stock-item');
        
        stockItems.forEach(item => {
            if (filterValue === 'all') {
                item.style.display = '';
            } else if (filterValue === 'low') {
                const stockValue = parseInt(item.querySelector('.item-stock').textContent.trim());
                item.style.display = (stockValue < 10 && stockValue > 0) ? '' : 'none';
            } else if (filterValue === 'out') {
                const stockValue = parseInt(item.querySelector('.item-stock').textContent.trim());
                item.style.display = (stockValue <= 0) ? '' : 'none';
            }
        });
    });
    
    // Increment and decrement buttons
    const incrementBtns = document.querySelectorAll('.increment-btn');
    const decrementBtns = document.querySelectorAll('.decrement-btn');
    
    incrementBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.stock-input');
            input.value = parseInt(input.value) + 1;
        });
    });
    
    decrementBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.stock-input');
            const currentValue = parseInt(input.value);
            if (currentValue > 0) {
                input.value = currentValue - 1;
            }
        });
    });
});


// admin page
document.addEventListener('DOMContentLoaded', function() {
    // Tab navigation functionality
    const navItems = document.querySelectorAll('.admin-nav-item');
    const sections = document.querySelectorAll('.admin-section');
    
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all nav items and sections
            navItems.forEach(nav => nav.classList.remove('active'));
            sections.forEach(section => section.classList.remove('active'));
            
            // Add active class to clicked nav item
            this.classList.add('active');
            
            // Show corresponding section
            const targetId = this.getAttribute('data-target');
            document.getElementById(targetId).classList.add('active');
        });
    });
    
    // Sales chart initialization
    if (document.getElementById('salesChart')) {
        const ctx = document.getElementById('salesChart').getContext('2d');
        
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Sales (RM)',
                    data: [1200, 1900, 2100, 2500, 2200, 2800, 3100, 3500, 3200, 3800, 4100, 4500],
                    backgroundColor: 'rgba(255, 105, 180, 0.2)',
                    borderColor: '#ff69b4',
                    borderWidth: 2,
                    pointBackgroundColor: '#ff69b4',
                    pointBorderColor: '#fff',
                    pointRadius: 5,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: {
                            size: 16
                        },
                        bodyFont: {
                            size: 14
                        },
                        callbacks: {
                            label: function(context) {
                                return 'RM ' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return 'RM ' + value;
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                }
            }
        });
        
        // Handle report generation button
        document.getElementById('generate-report').addEventListener('click', function() {
            const dateRange = document.getElementById('date-range').value;
            const product = document.getElementById('product-filter').value;
            
            // Simulate data change based on filters
            let newData;
            
            if (dateRange === 'today') {
                newData = [120, 150, 180, 210, 190, 220, 250, 280, 260, 290, 310, 340];
            } else if (dateRange === 'week') {
                newData = [850, 920, 980, 1050, 990, 1100, 1200, 1300, 1250, 1400, 1500, 1600];
            } else if (dateRange === 'year') {
                newData = [12000, 15000, 18000, 21000, 19000, 22000, 25000, 28000, 26000, 29000, 31000, 34000];
            } else {
                newData = [1200, 1900, 2100, 2500, 2200, 2800, 3100, 3500, 3200, 3800, 4100, 4500];
            }
            
            // Apply product filter (simplified for demo)
            if (product !== 'all') {
                newData = newData.map(value => value * 0.3); // Show only 30% for specific product
            }
            
            // Update chart data
            salesChart.data.datasets[0].data = newData;
            salesChart.update();
        });
    }
    
    // Profile buttons functionality
    const editProfileBtn = document.querySelector('.edit-profile-btn');
    if (editProfileBtn) {
        editProfileBtn.addEventListener('click', function() {
            alert('Edit profile functionality will be implemented here.');
        });
    }
    
    const changePasswordBtn = document.querySelector('.change-password-btn');
    if (changePasswordBtn) {
        changePasswordBtn.addEventListener('click', function() {
            alert('Change password functionality will be implemented here.');
        });
    }
    
    // Order view buttons
    const viewOrderBtns = document.querySelectorAll('.view-order-btn');
    viewOrderBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.closest('tr').querySelector('td:first-child').textContent;
            alert(`Viewing details for order ${orderId}`);
        });
    });
});

// admin dashboard
document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on the admin dashboard page
    if (document.querySelector('.admin-dashboard-container')) {
        // Initialize dashboard charts
        initializeProductSalesChart();
        initializeCategorySalesChart();
        
        // Set up dashboard refresh timer (every 5 minutes)
        setInterval(refreshDashboardStats, 300000);
    }
    
    // Animation for bars in chart
    const bars = document.querySelectorAll('.bar');
    bars.forEach((bar, index) => {
        setTimeout(() => {
            const height = bar.style.height;
            bar.style.height = '0';
            setTimeout(() => {
                bar.style.height = height;
            }, 100);
        }, index * 100);
    });
    
    // Add click event listeners to menu items
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            // Remove active class from all menu items
            menuItems.forEach(i => i.classList.remove('active'));
            // Add active class to clicked item
            this.classList.add('active');
            
            // Handle logout
            if (this.textContent.trim() === 'Logout') {
                window.location.href = 'logout.php';
            }
        });
    });
    
    // Add click event listeners to action cards
    const actionCards = document.querySelectorAll('.action-card');
    actionCards.forEach(card => {
        card.addEventListener('click', function() {
            const title = this.querySelector('h3').textContent.trim();
            
            // Redirect based on card title
            switch(title) {
                case 'Add New Product':
                    window.location.href = 'product-add.php';
                    break;
                case 'Manage Inventory':
                    window.location.href = 'inventory.php';
                    break;
                case 'Customer Reviews':
                    window.location.href = 'reviews.php';
                    break;
                case 'Sales Reports':
                    window.location.href = 'sales-reports.php';
                    break;
            }
        });
    });
    
    // Update greeting based on time of day
    const greeting = document.querySelector('.greeting-header h2');
    if (greeting) {
        const hour = new Date().getHours();
        let greetingText = 'Good ';
        
        if (hour < 12) {
            greetingText += 'Morning';
        } else if (hour < 18) {
            greetingText += 'Afternoon';
        } else {
            greetingText += 'Evening';
        }
        
        greeting.textContent = greetingText + ', Admin!';
    }
    
    // Update current date
    const dateElement = document.querySelector('.greeting-header span');
    if (dateElement) {
        const options = { month: 'short', day: 'numeric' };
        const currentDate = new Date().toLocaleDateString('en-US', options);
        dateElement.textContent = currentDate;
    }
});

// Functions for admin dashboard charts
function initializeProductSalesChart() {
    const productDataElement = document.getElementById('product-data');
    if (!productDataElement) return;
    
    const productLabels = JSON.parse(productDataElement.dataset.labels || '[]');
    const productSalesData = JSON.parse(productDataElement.dataset.sales || '[]');
    
    const productSalesChart = new Chart(
        document.getElementById('productSalesChart'),
        {
            type: 'bar',
            data: {
                labels: productLabels,
                datasets: [{
                    label: 'Sales ($)',
                    data: productSalesData,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        }
    );
}

function initializeCategorySalesChart() {
    const categoryDataElement = document.getElementById('category-data');
    if (!categoryDataElement) return;
    
    const categoryLabels = JSON.parse(categoryDataElement.dataset.labels || '[]');
    const categorySalesData = JSON.parse(categoryDataElement.dataset.sales || '[]');
    
    const categorySalesChart = new Chart(
        document.getElementById('categorySalesChart'),
        {
            type: 'pie',
            data: {
                labels: categoryLabels,
                datasets: [{
                    data: categorySalesData,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        }
    );
}

function refreshDashboardStats() {
    fetch('admin-ajax.php?action=refresh_stats')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateDashboardCards(data.stats);
            }
        })
        .catch(error => console.error('Error refreshing dashboard:', error));
}

function updateDashboardCards(stats) {
    const newCustomersElement = document.getElementById('new-customers-count');
    const totalSalesElement = document.getElementById('total-sales-amount');
    const newOrdersElement = document.getElementById('new-orders-count');
    const totalProductsElement = document.getElementById('total-products-count');
    
    if (newCustomersElement) newCustomersElement.textContent = stats.new_customers;
    if (totalSalesElement) totalSalesElement.textContent = '$' + parseFloat(stats.total_sales).toFixed(2);
    if (newOrdersElement) newOrdersElement.textContent = stats.new_orders;
    if (totalProductsElement) totalProductsElement.textContent = stats.total_products;
}

// Admin Profile functionality
document.addEventListener('DOMContentLoaded', function() {
    // Only run this code if we're on the admin profile page
    if (document.querySelector('.admin-profile-container')) {
        console.log('Admin profile page detected');
        
        // Tab navigation
        const adminMenuItems = document.querySelectorAll('.admin-profile-menu-item');
        
        adminMenuItems.forEach(item => {
            item.addEventListener('click', function() {
                // Remove active class from all menu items
                adminMenuItems.forEach(mi => mi.classList.remove('active'));
                
                // Add active class to clicked menu item
                this.classList.add('active');
                
                // Here you would normally show/hide content sections
                // This will be implemented when we have all sections
            });
        });
        
        // Edit profile functionality
        const adminEditProfileBtn = document.getElementById('adminEditProfileBtn');
        const adminCancelEditBtn = document.getElementById('adminCancelEdit');
        const adminProfileViewMode = document.getElementById('adminProfileViewMode');
        const adminProfileForm = document.getElementById('adminProfileForm');
        
        if (adminEditProfileBtn && adminCancelEditBtn && adminProfileViewMode && adminProfileForm) {
            console.log('Admin profile edit elements found');
            
            // Show edit form
            adminEditProfileBtn.addEventListener('click', function() {
                console.log('Admin edit button clicked');
                adminProfileViewMode.style.display = 'none';
                adminProfileForm.style.display = 'block';
                adminEditProfileBtn.style.display = 'none';
            });
            
            // Cancel editing
            adminCancelEditBtn.addEventListener('click', function(e) {
                console.log('Admin cancel button clicked');
                e.preventDefault(); // Prevent form submission
                adminProfileViewMode.style.display = 'block';
                adminProfileForm.style.display = 'none';
                adminEditProfileBtn.style.display = 'inline-block';
            });
            
            // Form submission with file upload
            adminProfileForm.addEventListener('submit', function(e) {
                e.preventDefault();
                console.log('Admin form submitted');
                
                const formData = new FormData(this);
                
                // Get the form action URL
                const actionUrl = this.getAttribute('action');
                
                // Send the form data to the server
                fetch(actionUrl, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Response received:', data);
                    
                    // Use the actual response data
                    const success = data.success;
                    const message = data.message || 'Profile updated successfully!';
                    
                    if (success) {
                        // Update the view mode with new values
                        const name = document.getElementById('adminFullName').value;
                        const email = document.getElementById('adminEmail').value;
                        const phone = document.getElementById('adminPhone').value;
                        const role = document.getElementById('adminRole').value;
                        
                        // Update displayed values
                        const profileUserName = document.querySelector('.admin-profile-user-name');
                        if (profileUserName) {
                            profileUserName.textContent = 'Hello\n' + name;
                        }
                        
                        // Find and update the profile info values
                        const infoValues = document.querySelectorAll('.admin-profile-info-value');
                        if (infoValues.length > 0) {
                            infoValues[0].textContent = name; // Name
                            infoValues[1].textContent = role; // Role
                            if (infoValues.length > 2) infoValues[2].textContent = phone; // Phone
                            if (infoValues.length > 3) infoValues[3].textContent = email; // Email
                        }
                        
                        // Switch back to view mode
                        adminProfileViewMode.style.display = 'block';
                        adminProfileForm.style.display = 'none';
                        adminEditProfileBtn.style.display = 'inline-block';
                        
                        // Show success message
                        const successMessage = document.createElement('div');
                        successMessage.className = 'admin-profile-success-message';
                        successMessage.innerHTML = '<i class="fas fa-check-circle"></i> ' + message;
                        
                        const profileCard = document.querySelector('.admin-profile-card');
                        if (profileCard) {
                            profileCard.insertBefore(successMessage, adminProfileViewMode);
                            
                            // Remove success message after 3 seconds
                            setTimeout(function() {
                                successMessage.remove();
                            }, 3000);
                        }
                    } else {
                        // Show error message
                        alert(message || 'Failed to update profile');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating your profile');
                });
            });
        }
        
        // Profile picture edit functionality
        const adminProfilePictureInput = document.getElementById('adminProfilePictureInput');
        const adminProfilePreview = document.getElementById('adminProfilePreview');
        const adminPhotoEditBtns = document.querySelectorAll('.admin-profile-photo-edit');
        
        if (adminProfilePictureInput && adminProfilePreview) {
            adminProfilePictureInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        adminProfilePreview.src = e.target.result;
                        
                        // Also update any other profile images on the page
                        const profileImages = document.querySelectorAll('.admin-profile-user-photo img, .admin-profile-user-info img');
                        profileImages.forEach(img => {
                            img.src = e.target.result;
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });
            
            // Open file dialog when clicking on any camera icon
            adminPhotoEditBtns.forEach(btn => {
                if (btn) {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        adminProfilePictureInput.click();
                    });
                }
            });
        }
        
        // Admin Management functionality
        const adminAddBtn = document.getElementById('adminAddBtn');
        const adminAddForm = document.getElementById('adminAddForm');
        const adminCancelAddBtn = document.getElementById('adminCancelAdd');
        
        if (adminAddBtn && adminAddForm && adminCancelAddBtn) {
            // // Show add admin form
            // adminAddBtn.addEventListener('click', function() {
            //     adminAddForm.style.display = 'block';
            //     adminAddBtn.style.display = 'none';
            // });
            
            // // Cancel adding admin
            // adminCancelAddBtn.addEventListener('click', function() {
            //     adminAddForm.style.display = 'none';
            //     adminAddBtn.style.display = 'inline-block';
            //     adminAddForm.reset();
            // });
            
            // Form validation for adding admin
            adminAddForm.addEventListener('submit', function(e) {
                const password = document.getElementById('newAdminPassword').value;
                const confirmPassword = document.getElementById('newAdminConfirmPassword').value;
                
                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('Passwords do not match!');
                }
            });
        }
        
        // Edit/Delete Admin functionality
        const adminEditBtns = document.querySelectorAll('.admin-edit-btn:not([disabled])');
        const adminDeleteBtns = document.querySelectorAll('.admin-delete-btn:not([disabled])');
        
        adminEditBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const adminItem = this.closest('.admin-list-item');
                const adminName = adminItem.querySelector('.admin-list-col:first-child').textContent;
                alert('Edit admin: ' + adminName + ' (To be implemented)');
            });
        });
        
        adminDeleteBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const adminItem = this.closest('.admin-list-item');
                const adminName = adminItem.querySelector('.admin-list-col:first-child').textContent;
                if (confirm('Are you sure you want to delete admin: ' + adminName + '?')) {
                    alert('Delete admin: ' + adminName + ' (To be implemented)');
                }
            });
        });
    }
});
// Admin Profile Page - Add Admin functionality
$(document).ready(function() {
    // Show Add Admin form when button is clicked
    $('#adminAddBtn').click(function() {
        $('#adminAddForm').slideDown();
    });
    
    // Hide Add Admin form when cancel button is clicked
    $('#adminCancelAdd').click(function() {
        $('#adminAddForm').slideUp();
        $('#adminAddForm')[0].reset(); // Reset form fields
    });
    
    // Validate password match on form submission
    $('#adminAddForm').submit(function(e) {
        const password = $('#newAdminPassword').val();
        const confirmPassword = $('#newAdminConfirmPassword').val();
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Passwords do not match!');
        }
    });
});

// index page
document.addEventListener('DOMContentLoaded', function() {
    // Slideshow functionality for all slideshow containers
    const slideshowContainers = document.querySelectorAll('.slideshow-container');
    
    slideshowContainers.forEach(container => {
        const slides = container.querySelectorAll('.slideshow-slide');
        let currentSlide = 0;
        
        function showSlide(index) {
            slides.forEach(slide => slide.classList.remove('active'));
            slides[index].classList.add('active');
        }
        
        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }
        
        // Only set up slideshow if slides exist
        if (slides.length > 0) {
            // Initialize first slide
            showSlide(0);
            // Change slide every 5 seconds
            setInterval(nextSlide, 5000);
        }
    });
    
    // Scroll to top button
    const scrollBtn = document.getElementById('scrollToTop');
    
    if (scrollBtn) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollBtn.style.display = 'block';
            } else {
                scrollBtn.style.display = 'none';
            }
        });
        
        scrollBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // Animate elements when they come into view
    const animateOnScroll = function() {
        const elements = document.querySelectorAll('.animate-on-scroll');
        
        elements.forEach(element => {
            const elementPosition = element.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            
            if (elementPosition < windowHeight - 50) {
                element.classList.add('animated');
            }
        });
    };
    
    // Add animate-on-scroll class to elements
    document.querySelectorAll('.flavor-card, .section-title, .video-container, .about-image, .about-content').forEach(el => {
        el.classList.add('animate-on-scroll');
    });
    
    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll(); // Run once on page load
});

// Add these functions to your existing app.js file

// Initialize the payment page
function initPaymentPage() {
    // Initialize Stripe Elements
    const stripe = Stripe('pk_test_YourPublishableKeyHere'); // Replace with your actual publishable key
    const elements = stripe.elements();
    
    // Create card Element and mount it
    const cardElement = elements.create('card', {
        style: {
            base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        }
    });
    
    cardElement.mount('#card-element');
    
    // Handle real-time validation errors
    cardElement.on('change', function(event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });
    
    // Handle form submission
    const form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        
        // Disable the submit button to prevent repeated clicks
        document.getElementById('submit-button').disabled = true;
        
        stripe.createToken(cardElement).then(function(result) {
            if (result.error) {
                // Show error to customer
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
                document.getElementById('submit-button').disabled = false;
            } else {
                // Send the token to your server
                stripeTokenHandler(result.token);
            }
        });
    });
    
    // Submit the form with the token ID
    function stripeTokenHandler(token) {
        // Insert the token ID into the form so it gets submitted to the server
        const form = document.getElementById('payment-form');
        const hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);
        
        // Submit the form
        form.submit();
    }
    
    // Handle voucher application
    $('#apply-voucher').click(function() {
        const voucherCode = $('#voucher-code').val().trim();
        
        if (!voucherCode) {
            $('#voucher-message').text('Please enter a voucher code');
            return;
        }
        
        // This is a placeholder for future voucher implementation
        // In a real application, you would make an AJAX call to validate the voucher
        $('#voucher-message').text('Voucher functionality will be implemented in the future');
        
        // For demonstration purposes, let's pretend the voucher gives a $5 discount
        const currentTotal = parseFloat($('#final-amount').text().replace('$', ''));
        const discount = 5.00;
        const newTotal = Math.max(0, currentTotal - discount).toFixed(2);
        
        $('#discount-amount').text('-$' + discount.toFixed(2));
        $('#final-amount').text('$' + newTotal);
    });
    
    // Connect the Place Order button to the payment form submission
    $('#place-order-btn').click(function() {
        $('#payment-form').submit();
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const cartIcon = document.querySelector('.menu .cart'); 
    const sidebar = document.getElementById('sidebar');
    const closeButton = document.querySelector('.sidebar-close i');
    const checkoutButton = document.querySelector('.checkout');

    // Cart icon click handler to open sidebar
    if (cartIcon && sidebar) {
        cartIcon.addEventListener('click', (event) => {
            event.stopPropagation();
            sidebar.classList.add('open');
        });
    }

    // Close button handler
    if (closeButton && sidebar) {
        closeButton.addEventListener('click', () => {
            sidebar.classList.remove('open');
        });
    }

    // Add checkout button handler to check if cart is empty
    if (checkoutButton) {
        checkoutButton.addEventListener('click', (event) => {
            const cartItems = document.querySelectorAll('.individual-cart-item');
            const cartTotal = parseFloat(document.querySelector('.cart-total')?.textContent.replace('RM', '') || '0');
            
            if (cartItems.length === 0 || cartTotal <= 0) {
                event.preventDefault(); // Prevent the default action (navigation)
                alert('Your cart is empty!'); // Show alert
                // Don't redirect - just stay on the current page
            }
            // If cart has items, the default action will proceed to checkout
        });
    }

    // Optional: Close sidebar when clicking outside of it
    if (sidebar) {
        document.addEventListener('click', (event) => {
            if (sidebar.classList.contains('open') &&
                !sidebar.contains(event.target) &&
                !cartIcon.contains(event.target)) {
                sidebar.classList.remove('open');
            }
        });
    }
});
