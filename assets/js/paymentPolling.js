document.addEventListener('DOMContentLoaded', function() {
    // Ensure the QR container and reference ID are available on the page
    const qrContainer = document.getElementById('qrcodeContainer');
    const orderRefId = qrContainer ? qrContainer.getAttribute('data-order-ref') : null;

    if (!orderRefId) return;

    // --- 1. QR Code Generation ---
    // Assumes the PHP view passes the raw UPI string into a data attribute or JS variable
    const upiString = qrContainer.getAttribute('data-upi-string');
    
    // Assuming qrcode.js is loaded
    if (typeof QRCode !== 'undefined') {
        new QRCode(qrContainer, {
            text: upiString,
            width: 256,
            height: 256,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    }

    // --- 2. Polling Logic ---
    const pollingInterval = 5000; // Poll every 5 seconds
    let pollAttempts = 0;
    const maxPolls = 60; // Stop polling after 5 minutes (60 attempts * 5s)

    const paymentStatusIndicator = document.getElementById('paymentStatusText');

    function checkStatus() {
        if (pollAttempts >= maxPolls) {
            paymentStatusIndicator.textContent = 'Payment expired. Please try again.';
            clearInterval(pollTimer);
            return;
        }

        fetch('/public/api/checkPaymentStatus.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ orderRefId: orderRefId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'paid') {
                clearInterval(pollTimer);
                paymentStatusIndicator.textContent = 'Payment Confirmed! Redirecting...';
                // Success: Redirect to Order Confirmation Page
                window.location.href = '/checkout/confirmation?ref=' + orderRefId;
            } else if (data.status === 'pending') {
                paymentStatusIndicator.textContent = `Scanning for payment... (Attempt ${++pollAttempts})`;
            } else {
                // Handle error or other statuses
                clearInterval(pollTimer);
                paymentStatusIndicator.textContent = 'Payment Status: ' + data.orderStatus;
            }
        })
        .catch(error => {
            console.error("Polling Error:", error);
            pollAttempts++;
        });
    }

    // Start polling immediately
    checkStatus();
    const pollTimer = setInterval(checkStatus, pollingInterval);
});