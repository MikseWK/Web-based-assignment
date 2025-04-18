document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("payment-form");
    const submitButton = document.getElementById("submit-button");

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

            // Append token to form and submit to server
            const hiddenInput = document.createElement("input");
            hiddenInput.setAttribute("type", "hidden");
            hiddenInput.setAttribute("name", "stripeToken");
            hiddenInput.setAttribute("value", token.id);
            form.appendChild(hiddenInput);

            form.submit();
        } catch (err) {
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
    document.getElementById("apply-voucher").addEventListener("click", function () {
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
});
