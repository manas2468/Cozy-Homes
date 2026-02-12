<style>
/* ------------------------------------------- */
/* --- Embedded CSS for Step 1 Styling --- */
/* ------------------------------------------- */

/* --- Base Checkout Layout & Header --- */
.checkout-step-container {
    max-width: 700px;
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

/* --- Form Elements --- */
.form-group {
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-weight: bold;
    margin-bottom: 8px;
    color: #555;
}

.form-group input[type="text"],
.form-group input[type="tel"] {
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 1em;
    transition: border-color 0.2s;
}

.form-group input:focus {
    border-color: #A0C8A0; /* Light green highlight on focus */
    outline: none;
    box-shadow: 0 0 5px rgba(160, 200, 160, 0.5);
}

/* --- Multi-Column Layout (City, State, Zip) --- */
.form-row {
    display: flex;
    gap: 20px; /* Space between columns */
    margin-bottom: 20px;
}

.form-row .form-group {
    flex-grow: 1; /* Allows groups to take up space */
}

/* Specific width adjustments for the smaller fields */
.form-row .quarter-width {
    flex-basis: 25%;
    min-width: 100px;
}

.form-row .half-width {
    flex-basis: 50%;
}

/* --- Actions and Buttons --- */
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

/* CTA Button Styling */
.cta-button {
    background-color: #5D8C5D; /* Cozy Green/Sage */
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
    background-color: #4C724C; /* Darker green on hover */
}
</style>


<div class="checkout-step-container">
    <div class="step-header">
        <h1>1. Shipping Information</h1>
        <p>Please enter your desired shipping address.</p>
    </div>

    <form action="/checkout/summary" method="POST" class="shipping-form">
        
        <div class="form-group">
            <label for="fullName">Full Name</label>
            <input type="text" id="fullName" name="fullName" required>
        </div>

        <div class="form-group">
            <label for="address1">Address Line 1</label>
            <input type="text" id="address1" name="address1" required>
        </div>

        <div class="form-group">
            <label for="address2">Address Line 2 (Optional)</label>
            <input type="text" id="address2" name="address2">
        </div>

        <!-- Row for City and State/Zip -->
        <div class="form-row">
            <div class="form-group half-width">
                <label for="city">City</label>
                <input type="text" id="city" name="city" required>
            </div>
            
            <div class="form-group quarter-width">
                <label for="state">State</label>
                <input type="text" id="state" name="state" required>
            </div>

            <div class="form-group quarter-width">
                <label for="zipCode">Zip/Postal Code</label>
                <input type="text" id="zipCode" name="zipCode" required>
            </div>
        </div>
        
        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" required>
        </div>
        
        <div class="form-actions">
            <a href="/cart/view" class="back-link">← Return to Cart</a>
            <button type="submit" class="cta-button">Continue to Summary (Step 2)</button>
        </div>
    </form>
</div>

<script>
// Any specific JavaScript logic for validation or interactivity goes here
</script>