<style>
/* ------------------------------------------- */
/* --- Embedded CSS for Step 3 Styling (Enhanced) --- */
/* ------------------------------------------- */

/* --- Base Checkout Layout --- */
:root {
  --primary-green: #4CAF50; /* Stronger action color */
  --light-green: #5D8C5D;
  --dark-text: #333;
  --light-bg: #f9f9f9;
}

.checkout-step-container {
  max-width: 550px;
  margin: 40px auto;
  padding: 30px;
  background: #ffffff;
  border-radius: 12px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
  text-align: center;
  font-family: Arial, sans-serif;
}

.step-header h1 {
  font-size: 1.8em;
  color: var(--dark-text);
  border-bottom: 3px solid var(--primary-green);
  padding-bottom: 15px;
  margin-bottom: 25px;
  font-weight: 600;
}

/* --- Payment QR Specific Styles --- */
.payment-details {
  margin: 25px 0;
  padding: 20px;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  background: var(--light-bg);
}

.payment-details p {
  font-size: 1.05em;
  margin: 10px 0;
  color: #555;
}

/* CSS rule for total-amount kept but unused, in case it is added back later */
.payment-details .total-amount {
  font-size: 2.5em;
  font-weight: 700;
  color: var(--primary-green);
  margin: 10px 0 20px 0;
  padding-bottom: 10px;
  border-bottom: 1px dashed #ccc;
  display: none; /* Hidden */
}

.vpa-info {
  font-size: 1.1em;
  font-weight: bold;
  color: var(--dark-text);
  display: block;
  margin-top: 10px;
  padding: 8px;
  background: #fff;
  border-radius: 4px;
  border: 1px solid #ddd;
}

.order-ref {
  color: #888;
  font-size: 0.9em;
  margin-top: -15px;
}

/* --- QR Code Box --- */
.qr-code-box {
  margin: 30px 0;
  padding: 25px 15px;
  background: white;
  border: 2px solid var(--primary-green);
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}

.qr-code-box h4 {
  margin-top: 0;
  color: var(--dark-text);
  font-weight: 500;
}

#qrcodeContainer {
  width: 220px;
  height: 220px;
  margin: 20px auto;
  padding: 10px;
  border: 2px solid #ccc;
  background: white;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

#qrcodeContainer img {
  /* Ensure the image fills the dedicated container space */
  display: block;
  margin: 0 auto;
  width: 100%; 
  height: 100%;
  object-fit: contain; /* Prevents distortion */
}

.status-pending {
  font-weight: bold;
  color: orange;
  margin-top: 15px;
  padding: 5px;
  border-radius: 4px;
  background: #fffbe6;
}

.instructions {
  color: #777;
  font-size: 0.95em;
  margin-top: 30px;
  line-height: 1.4;
}

/* Button */
#refreshStatusButton {
  background-color: var(--primary-green);
  color: white;
  padding: 12px 25px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 1.1em;
  margin-top: 25px;
  transition: background-color 0.3s ease;
  font-weight: 600;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

#refreshStatusButton:hover {
  background-color: #43A047;
}

.payment-note {
  font-size: 0.85em;
  color: #a0a0a0;
  margin-top: 5px;
}
</style>

<?php
// --- START PHP LOGIC FIX ---

// 1. Ensure $_SESSION is started
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// 2. Define Fallback Constants
$upiVPA = '7972352899@fam'; 
$merchantName = 'Cozyhomes Collections';

// 3. PRIORITY CHECK FOR TOTAL AMOUNT:
// The amount is retrieved here, but only used for the UPI string construction.
$totalAmount = $GLOBALS['totalAmount'] 
    ?? $_SESSION['checkoutData']['totalAmount'] 
    ?? 0.00; 

// 4. Define Order Reference ID
$orderRefId = $GLOBALS['orderRefId'] ?? 'ORD_' . time(); 

// 5. Construct the UPI deep link string
$formattedAmount = number_format($totalAmount, 2, '.', ''); // Format for UPI string

$upiString = "upi://pay?pa={$upiVPA}&pn={$merchantName}&mc=0000&tid={$orderRefId}&tr={$orderRefId}&am={$formattedAmount}&cu=INR";

// --- END PHP LOGIC FIX ---
?>

<div class="checkout-step-container">
  <div class="step-header">
    <h1>Secure UPI Payment Gateway</h1>
  </div>

  <p class="order-ref">Order Reference: <strong><?php echo htmlspecialchars($orderRefId); ?></strong></p>
    
  <div class="payment-details">
    <!-- Removed: <p>Your Final Payment Due:</p> -->
    <!-- Removed: <span class="total-amount">₹...</span> -->
     
    <p>
      You are paying to:<br>
      <span class="vpa-info"><?php echo htmlspecialchars($upiVPA); ?></span>
    </p>
  </div>

  <div class="qr-code-box">
    <h4>Step 1: Scan & Pay</h4>
    <p class="payment-note">Use any UPI app (GPay, PhonePe, Paytm, etc.) to scan the code below. The amount will be pre-filled.</p>
      
    <!-- QR Code Target Element - Static Image -->
    <div id="qrcodeContainer" 
     data-order-ref="<?php echo htmlspecialchars($orderRefId); ?>"
     data-upi-string="<?php echo htmlspecialchars($upiString); ?>">
     <!-- Static QR Code Image -->
     <img src="/assets/images/famqr.jpg" alt="Static UPI QR Code for Payment">
    </div>

    <h4>Step 2: Awaiting Confirmation</h4>
    <p id="paymentStatusText" class="status-pending">
      <svg style="vertical-align: middle; margin-right: 5px;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
        <path d="M8.515 1.776a.5.5 0 0 0-.234-.506 7.5 7.5 0 1 0 2.366 11.815.5.5 0 1 0-.74-.664 6.5 6.5 0 1 1-1.745-10.45l.504.417L8 4.79v-3z"/>
        <path d="M8 8.5a.5.5 0 0 1-.5-.5V3a.5.5 0 0 1 1 0v5a.5.5 0 0 1-.5.5z"/>
      </svg>
      Scanning for payment... Please complete the transaction.
    </p>
  </div>

  <p class="instructions">
    **Important:** Do not refresh or close this window immediately after paying. The system is automatically verifying your payment with the bank.
    <br>This QR code is valid for 10 minutes.
  </p>
    
  <button id="refreshStatusButton" onclick="checkStatus()">
    Force Check: I have already paid
  </button>
</div>

<!-- Removed qrcode.min.js as it is not needed for a static image -->
<script src="/assets/js/paymentPolling.js"></script>