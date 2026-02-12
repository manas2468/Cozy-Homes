document.addEventListener('DOMContentLoaded', function() {
    console.log("Cozyhomes application loaded successfully.");
    
    // Example: Simple mobile menu toggle (if UI requires it)
    const navToggle = document.querySelector('.nav-toggle');
    const navLinks = document.querySelector('.nav-links');

    if (navToggle) {
        navToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    }
});

// Note: cartAjax.js and paymentPolling.js are included separately where needed.