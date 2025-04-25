document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("payment-form");
    const submitButton = document.getElementById("submit-button");
    const orderId = document.getElementById("order_id").value; // Make sure you have this hidden input in your form

    // Stripe Elements setup
    const stripe = Stripe(STRIPE_PUBLISHABLE_KEY); // Set this variable in your HTML or replace directly
    const elements = stripe.elements();
    const cardElement = elements.getElement("card") || elements.create("card");
    cardElement.mount("#card-element");

    // Real-time validation errors
    cardElement.on("change", function (event) {
        const displayError = document.getElementById("card-errors");
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = "";
        }
    });

    form.addEventListener("submit", async function (event) {
        event.preventDefault();

        submitButton.disabled = true;
        submitButton.textContent = "Processing...";

        try {
            // Create token
            const { token, error } = await stripe.createToken(cardElement);

            if (error) {
                showError(error.message);
                submitButton.disabled = false;
                submitButton.textContent = "Pay Now";
                return;
            }

            // Process payment on the server
            const response = await fetch('../modules/process_payment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    token: token.id,
                    order_id: orderId,
                    amount: document.getElementById("total_amount").value // Make sure you have this hidden input
                })
            });

            const result = await response.json();

            if (result.success) {
                // Payment was successful, update order status
                handlePaymentSuccess({
                    id: result.payment_intent || token.id
                });
            } else {
                showError(result.error || "Payment failed");
                submitButton.disabled = false;
                submitButton.textContent = "Pay Now";
            }
        } catch (err) {
            console.error("Payment error:", err);
            showError("Unexpected error occurred.");
            submitButton.disabled = false;
            submitButton.textContent = "Pay Now";
        }
    });

    function showError(message) {
        const displayError = document.getElementById("card-errors");
        displayError.textContent = message;
    }

    // Optional: Voucher button redirects to Stripe section
    const placeOrderBtn = document.getElementById("place-order-btn");
    if (placeOrderBtn) {
        placeOrderBtn.addEventListener("click", () => {
            document.getElementById("submit-button").scrollIntoView({ behavior: "smooth" });
        });
    }

    // Optional: Apply voucher (front-end only, backend logic needed separately)
    const applyVoucherBtn = document.getElementById("apply-voucher");
    if (applyVoucherBtn) {
        applyVoucherBtn.addEventListener("click", function () {
            const code = document.getElementById("voucher-code").value.trim();
            const discountText = document.getElementById("discount-amount");
            const totalText = document.getElementById("final-amount");
            const messageBox = document.getElementById("voucher-message");

            // Dummy example: You should replace this with actual AJAX logic
            if (code === "SAVE10") {
                const currentTotal = parseFloat(totalText.textContent.replace('$', ''));
                const newTotal = Math.max(currentTotal - 10, 0);
                discountText.textContent = "-$10.00";
                totalText.textContent = `$${newTotal.toFixed(2)}`;
                messageBox.textContent = "Voucher applied!";
                messageBox.className = "text-success";
            } else {
                messageBox.textContent = "Invalid voucher code.";
                messageBox.className = "text-danger";
            }
        });
    }
});

// Function to handle successful payment and update order status
function handlePaymentSuccess(paymentIntent) {
    const orderId = document.getElementById("order_id").value;
    
    console.log("Updating order status for order ID:", orderId);
    console.log("Payment Intent ID:", paymentIntent.id);
    
    // Update order status to 'Success' - make sure this matches your enum value exactly
    $.ajax({
        url: '../modules/update_order_status.php',
        type: 'POST',
        data: {
            order_id: orderId,
            status: 'Success', // This must match exactly one of your enum values
            payment_intent: paymentIntent.id
        },
        success: function(response) {
            console.log("Response received:", response);
            try {
                const result = typeof response === 'string' ? JSON.parse(response) : response;
                
                // Log debug info if available
                if (result.debug) {
                    console.log("Debug info:", result.debug);
                }
                
                if (result.success) {
                    console.log("Order status updated successfully");
                    // Redirect to success page
                    window.location.href = '../modules/payment_success.php';
                } else {
                    // Handle error
                    console.error("Failed to update order status:", result.error);
                    showError('Failed to update order status: ' + (result.error || 'Unknown error'));
                }
            } catch (e) {
                console.error('Error parsing response:', e, response);
                showError('Error processing payment');
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX error:", status, error);
            console.error("Response text:", xhr.responseText);
            showError('Error connecting to server: ' + error);
        }
    });
}

function showError(message) {
    const displayError = document.getElementById("card-errors");
    if (displayError) {
        displayError.textContent = message;
        console.error("Error displayed to user:", message);
    } else {
        alert(message);
        console.error("Alert shown to user:", message);
    }
}
