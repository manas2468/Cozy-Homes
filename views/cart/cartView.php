<?php
// NOTE: These variables MUST be defined and populated by your backend controller
// with the current and correct cart data before this file is executed.
$cartItems = $cartItems ?? [];
$subtotal = $subtotal ?? 0.00;
$shippingCost = $shippingCost ?? 0.00;
$discount = $discount ?? 0.00;

$grandTotal = $subtotal - $discount + $shippingCost;
?>

<!-- ================================================================= -->
<!-- EMBEDDED STYLING -->
<!-- ================================================================= -->
<style>
/* --- Color Variables --- */
:root {
    --primary-color: #2ECC71; /* Vibrant Green (Positive Action) */
    --secondary-color: #3498DB; /* Blue (Links/Info) */
    --accent-color: #E74C3C; /* Red (Remove/Discount) */
    --dark-text: #2C3E50;
    --light-bg: #ECF0F1; 
    --card-bg: #FFFFFF;
}

body {
    font-family: 'Poppins', sans-serif;
    position: relative;
    padding: 0;
    margin: 0;
    min-height: 100vh;
    background-color: var(--light-bg);
}

.ad-banner { display: none; } 

.cart-view {
    max-width: 1200px;
    margin: 60px auto;
    padding: 40px;
    background: var(--card-bg);
    border-radius: 16px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}

.cart-view h2 {
    font-size: 2.5em;
    color: var(--dark-text);
    border-bottom: 3px solid var(--primary-color);
    padding-bottom: 20px;
    margin-bottom: 40px;
    text-align: left;
    font-weight: 700;
}

.cart-actions-top {
    margin-bottom: 30px;
    text-align: left;
}

.continue-shopping-link {
    color: var(--secondary-color);
    text-decoration: none;
    font-weight: 600;
    padding: 10px 15px;
    border: 2px solid var(--secondary-color);
    border-radius: 50px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
}

.continue-shopping-link:hover {
    background: var(--secondary-color);
    color: var(--card-bg);
    box-shadow: 0 4px 10px rgba(52, 152, 219, 0.3);
}

.empty-cart {
    text-align: center;
    padding: 80px 0;
    font-size: 1.4em;
    color: #95A5A6;
}

.empty-cart a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: bold;
    transition: color 0.2s;
}

.cart-content-wrapper {
    display: flex;
    gap: 40px;
}

.cart-items-list {
    flex: 2.5; 
}

.cart-item {
    display: flex;
    align-items: center;
    padding: 20px;
    margin-bottom: 15px;
    background: #F9F9F9;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    transition: box-shadow 0.3s;
}

.cart-item:hover {
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.item-thumb {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 8px;
    margin-right: 30px;
    border: 1px solid #E0E0E0;
    flex-shrink: 0;
}

.item-details {
    flex-grow: 1;
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 0.5fr; 
    align-items: center;
    gap: 15px;
}

.item-details h4 {
    margin: 0;
    font-size: 1.3em;
    font-weight: 600;
    color: var(--dark-text);
}

.unit-price {
    font-weight: 500;
    color: #7F8C8D;
    font-size: 1em;
}

.line-total {
    text-align: right;
    font-size: 1.2em;
    font-weight: 700;
}

.line-total strong {
    color: var(--primary-color);
}

.quantity-control {
    font-size: 1em;
    color: #7F8C8D;
    display: flex;
    align-items: center;
}

.quantity-input {
    width: 60px;
    padding: 10px;
    border: 2px solid #BDC3C7;
    border-radius: 8px;
    text-align: center;
    margin-left: 10px;
    font-weight: 600;
    transition: border-color 0.2s;
}

.quantity-input:focus {
    border-color: var(--secondary-color);
    outline: none;
}

.remove-item-button {
    background: none;
    border: none;
    color: var(--accent-color);
    cursor: pointer;
    font-size: 1.8em; 
    padding: 5px;
    margin: 0;
    transition: color 0.2s, transform 0.1s;
    justify-self: end;
    line-height: 1; 
}

.remove-item-button:hover {
    color: #C0392B;
    transform: scale(1.1);
}

/* --- Cart Summary Styling --- */
.cart-summary {
    flex: 1;
    background: var(--light-bg);
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    align-self: flex-start;
    border: 1px solid #E0E0E0;
}

.coupon-area {
    padding-bottom: 25px;
    margin-bottom: 25px;
    border-bottom: 1px solid #DCDCDC;
}
.coupon-area label {
    display: block;
    font-weight: 600;
    margin-bottom: 10px;
    color: var(--dark-text);
}
.coupon-area input {
    width: 65%;
    padding: 10px;
    border: 1px solid #BDC3C7;
    border-radius: 6px 0 0 6px;
    box-sizing: border-box;
    float: left;
}
.apply-coupon-button {
    width: 35%;
    padding: 10px;
    background-color: var(--secondary-color);
    color: white;
    border: 1px solid var(--secondary-color);
    border-radius: 0 6px 6px 0;
    cursor: pointer;
    font-weight: 600;
    transition: background-color 0.2s;
}
.apply-coupon-button:hover {
    background-color: #2980B9;
}
.coupon-area form::after {
    content: "";
    display: table;
    clear: both;
}

.cart-summary h3 {
    font-size: 1.6em;
    color: var(--dark-text);
    margin-top: 0;
    margin-bottom: 15px;
}

.cart-summary p {
    display: flex;
    justify-content: space-between;
    margin: 12px 0;
    font-size: 1.1em;
    color: #555;
}

.cart-summary .discount-applied {
    color: var(--accent-color);
    font-weight: 600;
}

.cart-summary .grand-total {
    font-size: 1.8em;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 3px solid var(--primary-color);
    font-weight: bold;
    color: var(--dark-text);
}

.cart-summary .summary-value {
    font-weight: 700;
    color: var(--dark-text);
}

.checkout-button {
    display: block;
    width: 100%;
    text-align: center;
    margin-top: 30px;
    background-color: var(--primary-color);
    color: white;
    padding: 15px 25px;
    border: none;
    border-radius: 50px; 
    cursor: pointer;
    font-size: 1.25em;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 8px 15px rgba(46, 204, 113, 0.4);
}

.checkout-button:hover {
    background-color: #27AE60;
    transform: translateY(-3px);
    box-shadow: 0 12px 20px rgba(46, 204, 113, 0.6);
}

/* --- Responsive Adjustments --- */
@media (max-width: 992px) {
    .cart-content-wrapper {
        flex-direction: column;
    }
    .cart-summary { order: -1; margin-bottom: 30px; }
    .cart-item { flex-wrap: wrap; }
    .item-details {
        grid-template-columns: 1fr 1fr;
        grid-template-areas: "name total" "price remove" "quantity .";
        width: 100%;
    }
    .item-details h4 { grid-area: name; text-align: left; }
    .line-total { grid-area: total; text-align: right; }
    .unit-price { grid-area: price; text-align: left; }
    .remove-item-button { grid-area: remove; justify-self: end; }
    .quantity-control { grid-area: quantity; justify-content: flex-start; }
    .item-thumb { width: 80px; height: 80px; margin-right: 15px; }
}
@media (max-width: 576px) {
    .cart-view { margin: 20px; padding: 20px; }
    .item-details { display: block; }
    .item-thumb { margin: 0 auto 15px; display: block; }
    .item-details > * { text-align: center !important; margin-bottom: 10px; }
    .remove-item-button { margin: 10px auto 0; display: block; }
    .quantity-control { justify-content: center; }
}
</style>

<div class="page-wrapper">
    <section class="cart-view">
        <h2>Your Shopping Cart</h2>
        
        <?php if (empty($cartItems)): ?>
            <p class="empty-cart">Your cart is feeling a little empty. <a href="/product/catalog">Start shopping!</a></p>
        <?php else: ?>
            
            <div class="cart-actions-top">
                <a href="/product/catalog" class="continue-shopping-link">
                    ← Continue Shopping
                </a>
            </div>

            <div class="cart-content-wrapper">
                <div class="cart-items-list">
                    <?php foreach ($cartItems as $item): 
                        // Defensive Programming: Ensure data structure consistency
                        $item = (array)$item;
                        $item['imageUrl'] = $item['imageUrl'] ?? '/assets/images/placeholder.jpg';
                        $item['name'] = $item['name'] ?? 'Product';
                        $item['priceSnapshot'] = $item['priceSnapshot'] ?? 0.00;
                        $item['quantity'] = $item['quantity'] ?? 1;
                        $item['lineTotal'] = $item['lineTotal'] ?? ($item['priceSnapshot'] * $item['quantity']);
                        $item['itemId'] = $item['itemId'] ?? $item['productId'] ?? uniqid(); 
                        ?>
                        <div class="cart-item" data-item-id="<?php echo $item['itemId']; ?>">
                            <img src="<?php echo htmlspecialchars($item['imageUrl']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="item-thumb">
                            
                            <div class="item-details">
                                <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                <p class="unit-price">₹<?php echo number_format($item['priceSnapshot'], 2); ?> per item</p>

                                <p class="quantity-control">
                                    Qty:
                                    <input type="number" value="<?php echo $item['quantity']; ?>" min="1" data-item-id="<?php echo $item['itemId']; ?>" class="quantity-input">
                                </p>
                                <p class="line-total"><strong>₹<?php echo number_format($item['lineTotal'], 2); ?></strong></p>

                                <!-- FIX: Cross is now inside the button and is clickable -->
                                <button class="remove-item-button" data-item-id="<?php echo $item['itemId']; ?>" title="Remove Item">✖</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart-summary">
                    
                    <div class="coupon-area">
                        <form action="/cart/applyCoupon" method="POST">
                            <label for="couponCode">Have a coupon?</label>
                            <input type="text" id="couponCode" name="couponCode" placeholder="Enter code">
                            <button type="submit" class="apply-coupon-button">Apply</button>
                        </form>
                    </div>

                    <h3>Order Summary</h3>
                    <p>Subtotal: <span class="summary-value" id="summarySubtotal">₹<?php echo number_format($subtotal, 2); ?></span></p>
                    <p class="discount-applied">Discount: <span class="summary-value" id="summaryDiscount">− ₹<?php echo number_format($discount, 2); ?></span></p>
                    <p class="shipping-note">Shipping: <span class="summary-value" id="summaryShipping"><?php echo ($shippingCost > 0) ? '₹' . number_format($shippingCost, 2) : 'Free'; ?></span></p>
                    
                    <p class="grand-total">Grand Total: <span class="summary-value" id="summaryGrandTotal">₹<?php echo number_format($grandTotal, 2); ?></span></p>
                    
                    <a href="/checkout/step1Shipping" class="cta-button checkout-button">Secure Checkout</a>
                </div>
            </div>
        <?php endif; ?>
    </section>
</div>
<!-- End of cart.php -->

<script>
document.addEventListener('DOMContentLoaded', () => {

    const cartContainer = document.querySelector('.cart-view');

    // Helper function to update the entire cart summary display
    function updateSummary(newSummaryData) {
        // Example: newSummaryData = { subtotal: 15000.00, discount: 500.00, grandTotal: 14500.00 }
        
        if (newSummaryData) {
            document.getElementById('summarySubtotal').innerText = `₹${newSummaryData.subtotal.toFixed(2)}`;
            document.getElementById('summaryDiscount').innerText = `− ₹${newSummaryData.discount.toFixed(2)}`;
            // Shipping should be handled dynamically too
            document.getElementById('summaryGrandTotal').innerText = `₹${newSummaryData.grandTotal.toFixed(2)}`;
        } else {
            // Reload the page if the backend doesn't return summary data
            window.location.reload(); 
        }
    }
    
    // --- 1. REMOVE ITEM LOGIC ---
    cartContainer.querySelectorAll('.remove-item-button').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            const itemId = this.dataset.itemId;
            const itemElement = this.closest('.cart-item');

            if (confirm("Are you sure you want to remove this item?")) {
                try {
                    // Start visual removal immediately while waiting for server response
                    itemElement.style.opacity = 0.5;

                    // *** REPLACE THIS URL WITH YOUR ACTUAL BACKEND ROUTE ***
                    const response = await fetch('/cart/remove-item', { 
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ itemId: itemId })
                    });

                    if (!response.ok) throw new Error('Network response was not ok.');
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        // Successfully removed from backend. Remove from frontend DOM
                        itemElement.remove(); 
                        
                        // Update totals if the backend returned them
                        if (data.summary) {
                            updateSummary(data.summary);
                        } else {
                            // Fallback: Reload to ensure consistency
                            window.location.reload();
                        }
                    } else {
                        alert('Error removing item: ' + data.message);
                        itemElement.style.opacity = 1; // Restore opacity on error
                    }
                } catch (error) {
                    console.error('AJAX Error:', error);
                    alert('There was a problem contacting the server. Please try again.');
                    itemElement.style.opacity = 1; // Restore opacity on error
                }
            }
        });
    });

    // --- 2. QUANTITY UPDATE LOGIC ---
    cartContainer.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', async function() {
            let newQuantity = parseInt(this.value);
            const itemId = this.dataset.itemId;
            
            // Basic validation
            if (isNaN(newQuantity) || newQuantity < 1) {
                newQuantity = 1;
                this.value = 1;
            }

            try {
                // *** REPLACE THIS URL WITH YOUR ACTUAL BACKEND ROUTE ***
                const response = await fetch('/cart/update-quantity', { 
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ itemId: itemId, quantity: newQuantity })
                });

                if (!response.ok) throw new Error('Network response was not ok.');
                
                const data = await response.json();
                
                if (data.success) {
                    // Update the line total and the summary totals dynamically
                    const itemElement = this.closest('.cart-item');
                    itemElement.querySelector('.line-total strong').innerText = `₹${data.lineTotal.toFixed(2)}`;
                    updateSummary(data.summary);
                } else {
                    alert('Error updating quantity: ' + data.message);
                    // Reload to show the server's truth if update fails
                    window.location.reload(); 
                }
            } catch (error) {
                console.error('AJAX Error:', error);
                alert('Could not update quantity. Please check your connection.');
            }
        });
    });

});
</script>