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

document.addEventListener('DOMContentLoaded', () => {
    const addToCartButtons = document.querySelectorAll('.fa-cart-plus');
    const cartItemCount = document.querySelector('.cart span');
    const cartItemsList = document.querySelector('.cart-item');
    const cartTotal = document.querySelector('.cart-total');
    const cartIcon = document.querySelector('.cart');
    const sidebar = document.querySelector('.sidebar');
    const closeButton = document.querySelector('.sidebar-close');

    let cartItems = [];
    let totalAmount = 0;

    // Cart icon click handler
    cartIcon.addEventListener('click', () => {
        sidebar.classList.add('open');
    });

    // Close button handler
    closeButton.addEventListener('click', () => {
        sidebar.classList.remove('open');
    });

    // Add to cart functionality
    addToCartButtons.forEach((button, index) => {
        button.addEventListener('click', () => {
            const productElement = button.closest('.product');
            const item = {
                name: productElement.querySelector('.product-name').textContent,
                price: parseFloat(
                    productElement.querySelector('.price').textContent.replace('RM ', '')
                ),
                quantity: 1,
            };

            const existingItem = cartItems.find(
                (cartItem) => cartItem.name === item.name
            );
            
            if (existingItem) {
                existingItem.quantity++;
            } else {
                cartItems.push(item);
            }

            totalAmount += item.price;
            updateCartUI();
        });
    });

    function updateCartUI() {
        updateCartItemCount();
        updateCartItemList();
        updateCartTotal();
    }

    function updateCartItemCount() {
        const totalQuantity = cartItems.reduce((sum, item) => sum + item.quantity, 0);
        cartItemCount.textContent = totalQuantity;
    }

    function updateCartItemList() {
        cartItemsList.innerHTML = '';
        cartItems.forEach((item, index) => {
            const cartItem = document.createElement('div');
            cartItem.classList.add('cart-item', 'individual-cart-item');
            cartItem.innerHTML = `
                <span>(${item.quantity}x) ${item.name}</span>
                <span class="cart-item-price">RM${(item.price * item.quantity).toFixed(2)}
                    <button class="remove-btn" data-index="${index}"><i class="fa-solid fa-trash"></i></button>
                </span>
            `;
            cartItemsList.appendChild(cartItem);
        });

        // Add event listeners to remove buttons
        document.querySelectorAll('.remove-btn').forEach((button) => {
            button.addEventListener('click', (event) => {
                const index = event.currentTarget.dataset.index;
                removeItemFromCart(index);
            });
        });
    }

    function removeItemFromCart(index) {
        const removedItem = cartItems.splice(index, 1)[0];
        totalAmount -= removedItem.price * removedItem.quantity;
        updateCartUI();
    }

    function updateCartTotal() {
        cartTotal.textContent = `RM${totalAmount.toFixed(2)}`;
    }
});


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


// admin profile 
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