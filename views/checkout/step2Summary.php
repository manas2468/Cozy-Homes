<style>
/* ------------------------------------------- */
/* --- Embedded CSS for Step 2 Styling (Reusing Step 1 Styles) --- */
/* ------------------------------------------- */

/* --- Base Checkout Layout & Header (Reused from Step 1) --- */
.checkout-step-container {
    max-width: 900px; /* Slightly wider for the summary grid */
    margin: 40px auto;
    padding: 30px;
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.step-header h1 {
    font-size: 2em;
    color: #333;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

.step-header p {
    color: #666;
    margin-bottom: 30px;
}

/* --- Summary Grid Layout --- */
.summary-grid {
    display: flex;
    gap: 30px;
    margin-top: 30px;
}

.summary-shipping {
    flex: 1; /* Equal width for shipping details */
    padding: 20px;
    border-radius: 8px;
    background: #fcfcfc;
    border: 1px solid #eee;
}

.summary-order-details {
    flex: 2; /* Wider area for items and totals */
    padding: 20px;
    border-radius: 8px;
    background: #fcfcfc;
    border: 1px solid #eee;
}

.summary-shipping h2,
.summary-order-details h2 {
    font-size: 1.5em;
    margin-top: 0;
    padding-bottom: 10px;
    border-bottom: 1px dashed #ddd;
    margin-bottom: 20px;
    color: #4C724C; /* Sage Green Header */
}

/* Shipping Details */
.summary-shipping p {
    margin: 5px 0;
    color: #555;
}

.edit-link {
    color: #5D8C5D;
    font-size: 0.9em;
    text-decoration: underline;
    display: block;
    margin-top: 10px;
}

/* Items List */
.summary-item-list {
    list-style: none;
    padding: 0;
    margin-bottom: 20px;
}

.summary-item-list li {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px dotted #eee;
    font-size: 1.05em;
}

.summary-item-list .item-name {
    flex-grow: 1;
}

/* Totals */
.summary-totals {
    margin-top: 20px;
}

.summary-totals p {
    display: flex;
    justify-content: space-between;
    margin: 8px 0;
    padding: 5px 0;
    font-size: 1.1em;
    border-top: 1px solid #f0f0f0;
}

.summary-totals .grand-total {
    font-weight: bold;
    font-size: 1.3em;
    color: #333;
    border-top: 2px solid #5D8C5D; /* Highlight final total */
    padding-top: 10px;
    margin-top: 10px;
}


/* --- Actions and Buttons (Reused from Step 1) --- */
.form-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #f0f0f0;
}

.back-link {
    color: #555;
    text-decoration: none;
    transition: color 0.2s;
}

.back-link:hover {
    color: #333;
}

.cta-button {
    background-color: #5D8C5D;
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1.1em;
    font-weight: 600;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.cta-button:hover {
    background-color: #4C724C;
}

</style>

<div class="checkout-step-container">
    <div class="step-header">
        <h1>2. Order Summary & Confirmation</h1>
        <p>Please review your order details and shipping information before proceeding to payment.</p>
    </div>

    <div class="summary-grid">
        
        <!-- LEFT COLUMN: Shipping Details -->
        <div class="summary-shipping">
            <h2>Shipping To:</h2>
            <?php 
                // Ensure $shippingDetails is set by the controller
                $shippingDetails = $shippingDetails ?? [];
            ?>
            <?php if (!empty($shippingDetails)): ?>
                <p><strong><?php echo htmlspecialchars($shippingDetails['fullName'] ?? 'N/A'); ?></strong></p>
                <p><?php echo htmlspecialchars($shippingDetails['address1'] ?? ''); ?></p>
                <?php if (!empty($shippingDetails['address2'] ?? '')): ?>
                    <p><?php echo htmlspecialchars($shippingDetails['address2']); ?></p>
                <?php endif; ?>
                <p><?php echo htmlspecialchars($shippingDetails['city'] ?? '') . ', ' . htmlspecialchars($shippingDetails['state'] ?? '') . ' - ' . htmlspecialchars($shippingDetails['zipCode'] ?? ''); ?></p>
                <p>Phone: <?php echo htmlspecialchars($shippingDetails['phone'] ?? 'N/A'); ?></p>
                <a href="/checkout/step1Shipping" class="edit-link">Change Shipping Address</a>
            <?php else: ?>
                <p class="error">Shipping details missing. Please return to <a href="/checkout/step1Shipping">Step 1</a>.</p>
            <?php endif; ?>
        </div>
        
        <!-- RIGHT COLUMN: Cart Items & Totals -->
        <div class="summary-order-details">
            <h2>Order Details:</h2>
            
            <?php 
                // Ensure variables are defined, falling back to 0 or empty arrays
                $cartItems = $cartItems ?? [];
                $subtotal = $subtotal ?? 0;
                $shippingCost = $shippingCost ?? 0;
                $totalAmount = $totalAmount ?? 0;
            ?>
            
            <!-- Items Loop -->
            <ul class="summary-item-list">
                <?php if (!empty($cartItems)): ?>
                    <?php foreach ($cartItems as $item): ?>
                        <li>
                            <span class="item-name"><?php echo htmlspecialchars($item['name']); ?></span>
                            <span class="item-quantity">x <?php echo $item['quantity']; ?></span>
                            <span class="item-price">₹<?php echo number_format($item['quantity'] * ($item['priceSnapshot'] ?? 0), 2); ?></span>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>Your cart is empty.</li>
                <?php endif; ?>
            </ul>
            
            <!-- Totals Section -->
            <div class="summary-totals">
                <p>Subtotal: <span>₹<?php echo number_format($subtotal, 2); ?></span></p>
                <p>Shipping Cost: <span>₹<?php echo number_format($shippingCost, 2); ?></span></p>
                <p class="grand-total">Total Due: <span>₹<?php echo number_format($totalAmount, 2); ?></span></p>
            </div>
            
            <!-- Form to proceed to the final step -->
            <form action="/checkout/placeorder" method="POST">
                <div class="form-actions">
                    <a href="/checkout/step1Shipping" class="back-link">← Go back to Shipping</a>
                    <button type="submit" class="cta-button">Confirm & Pay (Step 3)</button>
                </div>
            </form>
        </div>
    </div>
</div>