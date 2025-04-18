// Cart functionality
$(document).ready(function() {
    // Show cart popup
    $('.cart-icon').click(function(e) {
        e.preventDefault();
        $('#cart-popup').toggleClass('active');
    });

    // Close cart popup
    $('.close-cart').click(function() {
        $('#cart-popup').removeClass('active');
    });

    // Add to cart
    $('.add-to-cart').click(function() {
        const productId = $(this).data('id');
        const productName = $(this).data('name');
        const productPrice = $(this).data('price');
        
        $.ajax({
            url: 'modules/add_to_cart.php',
            type: 'POST',
            data: {
                id: productId,
                name: productName,
                price: productPrice,
                quantity: 1
            },
            success: function(response) {
                // Update cart display
                updateCartDisplay();
                // Show success message
                showMessage('Item added to cart!');
            },
            error: function() {
                showMessage('Failed to add item to cart.', 'error');
            }
        });
    });

    // Remove from cart
    $(document).on('click', '.remove-item', function() {
        const index = $(this).data('index');
        
        $.ajax({
            url: 'modules/remove_from_cart.php',
            type: 'POST',
            data: {
                index: index
            },
            success: function(response) {
                // Update cart display
                updateCartDisplay();
                // Show success message
                showMessage('Item removed from cart!');
            },
            error: function() {
                showMessage('Failed to remove item from cart.', 'error');
            }
        });
    });

    // Function to update cart display
    function updateCartDisplay() {
        $.ajax({
            url: 'modules/get_cart.php',
            type: 'GET',
            success: function(response) {
                const cartData = JSON.parse(response);
                
                // Update cart count
                $('.cart-count').text(cartData.count);
                
                // Update cart items
                let cartItemsHtml = '';
                if (cartData.items.length === 0) {
                    cartItemsHtml = '<p class="empty-cart-message">Your cart is empty!</p>';
                    $('.checkout-btn').prop('disabled', true);
                } else {
                    cartData.items.forEach(function(item, index) {
                        cartItemsHtml += `
                            <div class="cart-item">
                                <div class="item-details">
                                    <h4>${item.name} (x${item.quantity})</h4>
                                    <p>RM ${parseFloat(item.price).toFixed(2)}</p>
                                </div>
                                <button class="remove-item" data-index="${index}">×</button>
                            </div>
                        `;
                    });
                    $('.checkout-btn').prop('disabled', false);
                }
                
                $('.cart-items').html(cartItemsHtml);
                
                // Update cart total
                $('.cart-total span:last-child').text(`RM${parseFloat(cartData.total).toFixed(2)}`);
            }
        });
    }

    // Function to show messages
    function showMessage(message, type = 'success') {
        const messageDiv = $('<div>').addClass('message-popup').addClass(type).text(message);
        $('body').append(messageDiv);
        
        setTimeout(function() {
            messageDiv.addClass('show');
            
            setTimeout(function() {
                messageDiv.removeClass('show');
                setTimeout(function() {
                    messageDiv.remove();
                }, 500);
            }, 3000);
        }, 100);
    }
});