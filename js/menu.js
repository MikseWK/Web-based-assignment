// ============================================================================
// (Cart) (jQuery)
// ============================================================================
// Category dropdown toggle

document.addEventListener('DOMContentLoaded', function() {
    // Define searchInput at the beginning
    const searchInput = document.getElementById('product-search');
    const cartIcon = document.querySelector('.cart');
    const sidebar = document.querySelector('.sidebar');
    const closeButton = document.querySelector('.sidebar-close');
    const addToCartButtons = document.querySelectorAll('.fa-cart-plus');
    const cartItemsList = document.querySelector('.cart-item');
    const cartTotal = document.querySelector('.cart-total');
    const cartItemCount = document.querySelector('.cart span');
    
    // Create toast message container
    const toastContainer = document.createElement('div');
    toastContainer.className = 'toast-container';
    document.body.appendChild(toastContainer);
    
    // Load cart items when page loads
    loadCartItems();
    
    // Cart icon click handler - show sidebar and load cart items
    cartIcon.addEventListener('click', () => {
        sidebar.classList.add('open');
        loadCartItems(); // Refresh cart items when opening sidebar
    });

    // Close button handler
    closeButton.addEventListener('click', () => {
        sidebar.classList.remove('open');
    });
    
    // Add to cart button click handler
    addToCartButtons.forEach((button) => {
        button.addEventListener('click', (event) => {
            // Prevent multiple clicks
            event.preventDefault();
            event.stopPropagation();
            
            // Visual feedback
            button.style.transform = 'scale(1.2)';
            setTimeout(() => {
                button.style.transform = 'scale(1)';
            }, 200);
            
            // Get product ID from the closest product container
            const productContainer = button.closest('.product');
            if (!productContainer) {
                console.error('Could not find product container');
                return;
            }
            
            // Get product ID directly from data-name attribute
            const productId = productContainer.getAttribute('data-name');
            console.log('Product container:', productContainer);
            console.log('Product ID from data-name:', productId);
            
            if (!productId || productId === '0') {
                console.error('Invalid product ID:', productId);
                alert('Could not add product to cart. Please try again.');
                return;
            }
            
            // Add to cart via AJAX with quantity 1
            // Use a timeout to prevent duplicate requests
            if (!button.dataset.processing) {
                button.dataset.processing = 'true';
                addToCart(productId, 1);
                
                // Reset processing flag after a delay
                setTimeout(() => {
                    delete button.dataset.processing;
                }, 1000);
            }
        });
    });
    
    // Function to add product to cart
    function addToCart(productId, quantity = 1) {
        console.log('Adding product to cart:', productId, 'quantity:', quantity);
        
        // Send AJAX request to add item to cart
        $.ajax({
            url: '/modules/cart_actions.php',
            type: 'POST',
            data: {
                action: 'add',
                product_id: productId,
                quantity: quantity
            },
            beforeSend: function() {
                console.log('Sending request to add item to cart');
            },
            success: function(response) {
                console.log('Received response:', response);
                try {
                    const result = JSON.parse(response);
                    if (result.success) {
                        // Update cart UI
                        loadCartItems();
                        // Show success toast instead of alert
                        showToast('Item added to cart!', 'success');
                    } else {
                        showToast(result.message || 'Failed to add item to cart, please provide feedback to customer service!', 'error');
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                    showToast('Error adding item to cart. Please try again.', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', status, error);
                console.log('Response text:', xhr.responseText);
                showToast('Error connecting to server. Please try again.', 'error');
            }
        });
    }
    
    // Function to show toast message
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.textContent = message;
        
        toastContainer.appendChild(toast);
        
        // Trigger animation
        setTimeout(() => {
            toast.classList.add('show');
        }, 10);
        
        // Remove toast after 2 seconds
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.remove();
            }, 200); // Wait for fade out animation
        }, 2000);
    }
    
    // Function to load cart items from server
    function loadCartItems() {
        $.ajax({
            url: '/modules/cart_actions.php',
            type: 'GET',
            data: {
                action: 'get_items'
            },
            success: function(response) {
                try {
                    const result = JSON.parse(response);
                    if (result.success) {
                        updateCartUI(result.items, result.total, result.count);
                    } else {
                        console.error(result.message || 'Failed to load cart items');
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                }
            },
            error: function() {
                console.error('Error connecting to server');
            }
        });
    }
    
    // Function to update cart UI
    function updateCartUI(items, total, count) {
        // Update cart count
        cartItemCount.textContent = count;
        
        // Update cart items list
        cartItemsList.innerHTML = '';
        if (items.length === 0) {
            cartItemsList.innerHTML = '<div class="empty-cart">Your cart is empty</div>';
        } else {
            items.forEach(item => {
                const cartItem = document.createElement('div');
                cartItem.classList.add('cart-item', 'individual-cart-item');
                cartItem.innerHTML = `
                    <span>(${item.quantity}x) ${item.name}</span>
                    <span class="cart-item-price">RM${(item.price * item.quantity).toFixed(2)}
                        <button class="remove-btn" data-id="${item.product_id}"><i class="fa-solid fa-trash"></i></button>
                    </span>
                `;
                cartItemsList.appendChild(cartItem);
            });
            
            // Add event listeners to remove buttons
            document.querySelectorAll('.remove-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const productId = button.dataset.id;
                    removeFromCart(productId);
                });
            });
        }
        
        // Update cart total
        cartTotal.textContent = `RM${parseFloat(total).toFixed(2)}`;
    }
    
    // Function to remove item from cart
    function removeFromCart(productId) {
        $.ajax({
            url: '/modules/cart_actions.php',
            type: 'POST',
            data: {
                action: 'remove',
                product_id: productId
            },
            success: function(response) {
                try {
                    const result = JSON.parse(response);
                    if (result.success) {
                        loadCartItems();
                    } else {
                        alert(result.message || 'Failed to remove item from cart');
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                }
            },
            error: function() {
                alert('Error connecting to server');
            }
        });
    }
    
    // Add event listener for checkout button
    const checkoutBtn = document.querySelector('.checkout-btn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', () => {
            window.location.href = '/modules/checkout.php';
        });
    }
    
    // Category dropdown toggle - make sure this is properly defined
    const categoryMenuToggle = document.getElementById('category-menu-toggle');
    const categoryDropdown = document.getElementById('category-dropdown');
    
    if (categoryMenuToggle && categoryDropdown) {
        console.log('Category menu elements found');
        
        // Add a direct click handler
        categoryMenuToggle.onclick = function(event) {
            event.preventDefault();
            event.stopPropagation();
            categoryDropdown.classList.toggle('show');
            console.log('Category menu clicked, dropdown toggled', categoryDropdown.classList.contains('show'));
            
            // Force repaint
            categoryDropdown.offsetHeight;
        };
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!categoryMenuToggle.contains(event.target) && !categoryDropdown.contains(event.target)) {
                categoryDropdown.classList.remove('show');
            }
        });
    } else {
        console.error('Category menu elements not found:', {
            toggle: categoryMenuToggle,
            dropdown: categoryDropdown
        });
    }
    
    // Search functionality
    if (searchInput) {
        console.log('Search input found');
        
        // Handle Enter key press in search box
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const searchTerm = searchInput.value.trim();
                const currentCategory = new URLSearchParams(window.location.search).get('category') || '';
                const categoryParam = currentCategory ? `&category=${currentCategory}` : '';
                const url = `/modules/menu.php${searchTerm ? `?search=${encodeURIComponent(searchTerm)}${categoryParam}` : ''}`;
                console.log('Navigating to:', url);
                window.location.href = url;
            }
        });
        
        // Add search icon click handler
        const searchIcon = searchInput.previousElementSibling;
        if (searchIcon) {
            searchIcon.addEventListener('click', function() {
                const searchTerm = searchInput.value.trim();
                const currentCategory = new URLSearchParams(window.location.search).get('category') || '';
                const categoryParam = currentCategory ? `&category=${currentCategory}` : '';
                const url = `/modules/menu.php${searchTerm ? `?search=${encodeURIComponent(searchTerm)}${categoryParam}` : ''}`;
                console.log('Navigating to:', url);
                window.location.href = url;
            });
        }
    } else {
        console.error('Search input not found');
    }
});


// Product description popup functionality
$(document).ready(function() {
    // Get the popup elements
    const popup = document.getElementById('product-description-popup');
    const closePopup = document.querySelector('.close-popup');
    const popupProductName = document.getElementById('popup-product-name');
    const popupProductImage = document.getElementById('popup-product-image');
    const popupProductDescription = document.getElementById('popup-product-description');
    const popupProductPrice = document.getElementById('popup-product-price');
    const popupCloseDescriptionBtn = document.getElementById('popup-close-description');
    
    // Add click event to product images
    $('.product img').on('click', function() {
        const productElement = $(this).closest('.product');
        const productId = productElement.data('name');
        const productName = productElement.find('.product-name').text();
        const productPrice = productElement.find('.price').text();
        const productImageSrc = $(this).attr('src');
        
        console.log('Product clicked:', productId, productName);
        
        // Fetch product description from the server
        $.ajax({
            url: '/modules/cart_actions.php',
            type: 'GET',
            data: { 
                action: 'get_description',
                product_id: productId 
            },
            success: function(response) {
                try {
                    const result = JSON.parse(response);
                    if (result.success) {
                        // Populate the popup with product details
                        popupProductName.textContent = productName;
                        popupProductImage.innerHTML = `<img src="${productImageSrc}" alt="${productName}">`;
                        popupProductDescription.textContent = result.description;
                        popupProductPrice.textContent = productPrice;
                        
                        // Show the popup
                        popup.style.display = 'flex';
                        
                        // Prevent scrolling on the body
                        document.body.style.overflow = 'hidden';
                    } else {
                        showToast(result.message || 'Failed to load product description', 'error');
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                    showToast('Error loading product description', 'error');
                }
            },
            error: function() {
                showToast('Error connecting to server', 'error');
            }
        });
    });
    
    // Function to close the popup
    function closeProductPopup() {
        popup.style.display = 'none';
        document.body.style.overflow = 'auto'; // Restore scrolling
    }
    
    // Close popup when clicking the close button (X)
    closePopup.addEventListener('click', closeProductPopup);
    
    // Close popup when clicking the "Close Description" button
    $(popupCloseDescriptionBtn).on('click', closeProductPopup);
    
    // Close popup when clicking outside the content
    window.addEventListener('click', function(event) {
        if (event.target === popup) {
            closeProductPopup();
        }
    });
});