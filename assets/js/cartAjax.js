document.addEventListener('DOMContentLoaded', function() {
    const cartButton = document.getElementById('cartIconBadge'); // Element to update
    
    document.querySelectorAll('.addToCartButton').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const productId = this.getAttribute('data-product-id');
            const quantity = 1; // Default quantity

            fetch('/api/addToCart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ productId, quantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the cart icon badge dynamically
                    cartButton.textContent = data.totalItems;
                    alert('Success: ' + data.message); 
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Network Error:', error);
                alert('A network error occurred.');
            });
        });
    });
});