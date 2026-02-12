<?php
// Assumed $products variable is available from ProductController
?>

<style>
/* ------------------------------------------- */
/* --- GLOBAL AND BASE STYLING --- */
/* ------------------------------------------- */

body {
  font-family: 'Inter', sans-serif; 
  background-color: #F9F9F9; /* Very light, clean background */
  color: #333333;
  margin: 0;
  padding: 0;
  line-height: 1.6;
}

/* ------------------------------------------- */
/* --- CATALOG HEADER --- */
/* ------------------------------------------- */

.catalog-header {
  text-align: center;
  padding: 100px 20px 60px;
  margin-bottom: 50px;
  background: #FFFFFF;
  border-bottom: 3px solid #EAEAEA;
}

.catalog-header h2 {
  font-family: 'Playfair Display', serif;
  font-size: 4em;
  color: #1A202C; /* Deep Charcoal */
  margin: 0;
  letter-spacing: -0.04em;
  font-weight: 800;
}

.catalog-header p {
  font-size: 1.35em;
  color: #718096;
  margin-top: 15px;
  font-weight: 400;
}

/* ------------------------------------------- */
/* --- PRODUCT GRID LAYOUT & ANIMATION --- */
/* ------------------------------------------- */

.product-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 50px;
  max-width: 1500px;
  margin: 0 auto;
  padding: 0 60px 120px;
}

/* --- ANIMATION KEYFRAMES (Bouncing Slide In) --- */
@keyframes fadeInBounce {
    from { opacity: 0; transform: translateY(40px); }
    to { opacity: 1; transform: translateY(0); }
}

.product-card {
  /* Animation Setup */
  animation: fadeInBounce 0.9s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; /* Overshoot/bounce */
  opacity: 0; 
  
  /* 3D Context for the Tilt Effect */
  perspective: 1000px;
  transform-style: preserve-3d;

  /* Styling */
  background: #FFFFFF;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08); 
  transition: all 0.5s ease-out;
  text-align: center;
  display: flex;
  flex-direction: column;
  position: relative;
}

/* --- 3D TILT EFFECT on HOVER --- */
.product-card:hover {
  transform: translateY(-15px) rotateX(1deg) rotateY(1deg);
  box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15); 
}

/* --- Pure CSS Staggered Animation --- */
<?php
// Generates CSS rules for staggered animation delay for the first 16 items
for ($i = 1; $i <= 16; $i++) {
    echo ".product-grid > div:nth-child({$i}) { animation-delay: " . (0.08 * $i) . "s; }\n";
}
?>

/* --- Image Container & Zoom --- */
.product-image-container {
    height: 380px;
    overflow: hidden;
    position: relative;
}

.product-card img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  transition: transform 0.6s cubic-bezier(0.2, 0.8, 0.2, 1);
}

.product-card:hover img {
    transform: scale(1.08); /* Stronger image zoom */
}

/* --- NEW ELEMENT: FEATURE BADGE (Top Left) --- */
.product-badge {
    position: absolute;
    top: 15px;
    left: -5px;
    background-color: #FF416C; /* Hot Pink/Red */
    color: white;
    padding: 6px 15px;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.85em;
    z-index: 10;
    border-radius: 0 5px 5px 0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

/* --- NEW ELEMENT: WISHLIST BUTTON (Top Right) --- */
.wishlist-button {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(255, 255, 255, 0.9);
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: background 0.3s, transform 0.3s;
    z-index: 10;
    font-size: 18px; /* Assuming you're using an icon font like FontAwesome */
    color: #4A4A4A;
}

.wishlist-button:hover {
    background-color: #00BCD4; /* Teal highlight */
    color: white;
    transform: scale(1.1);
}


/* --- Interactive Overlay (Quick View) --- */
.product-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 380px;
    background: rgba(0, 188, 212, 0.1); /* Very light teal overlay */
    opacity: 0;
    transition: opacity 0.4s ease;
    display: flex;
    justify-content: center;
    align-items: center;
    pointer-events: none;
}

.product-card:hover .product-overlay {
    opacity: 1;
    pointer-events: all;
}

.quick-view-button {
    background-color: #FFFFFF;
    color: #00BCD4; /* Electric Teal text */
    border: 2px solid #00BCD4;
    padding: 12px 30px;
    border-radius: 50px;
    font-weight: 700;
    text-transform: uppercase;
    opacity: 0;
    transform: translateY(10px);
    transition: all 0.4s ease 0.1s; 
}

.product-card:hover .quick-view-button {
    opacity: 1;
    transform: translateY(0);
}

.quick-view-button:hover {
    background-color: #00BCD4;
    color: white;
}


.product-info {
  padding: 30px 25px;
  display: flex;
  flex-direction: column;
  align-items: center;
  flex-grow: 1;
}

.product-info h3 {
  font-size: 1.6em;
  color: #1A202C;
  margin: 0 0 8px 0;
  font-weight: 700;
}

.product-info .price {
  font-size: 1.7em;
  font-weight: 800;
  color: #E91E63; /* Pink/Magenta accent for price */
  margin-bottom: 25px;
}

/* --- Call to Action Button --- */
.cta-button {
  background-color: #00BCD4; /* Electric Teal */
  color: white;
  padding: 18px 50px;
  border: none;
  border-radius: 50px;
  cursor: pointer;
  font-size: 1.2em;
  font-weight: 700;
  transition: background-color 0.2s, transform 0.1s;
  letter-spacing: 0.05em;
  margin-top: auto;
  box-shadow: 0 10px 20px rgba(0, 188, 212, 0.4);
}

.cta-button:hover {
  background-color: #00A6B9;
  box-shadow: 0 12px 25px rgba(0, 188, 212, 0.6);
}

.cta-button:active {
    transform: scale(0.95);
}

.empty-catalog {
  text-align: center;
  grid-column: 1 / -1;
  padding: 150px 50px;
  color: #A0AEC0;
  font-size: 1.8em;
  font-style: italic;
  font-weight: 300;
}
</style>

<section class="catalog-header">
  <h2>Our Curated Collections</h2>
  <p>Immersive designs and premium quality for your perfect home.</p>
</section>

<div class="product-grid">
  <?php 
  if (!empty($products)):
    foreach ($products as $product):
  ?>
  <div class="product-card">
    
    <!-- NEW ELEMENT: FEATURE BADGE -->
    <span class="product-badge">New Arrival</span>
    
    <!-- NEW ELEMENT: WISHLIST (Placeholder for an icon, e.g., <i class="fas fa-heart"></i>) -->
    <button class="wishlist-button" title="Add to Wishlist">★</button> 

    <div class="product-image-container">
      <!-- Interactive Overlay -->
      <div class="product-overlay">
        <button class="quick-view-button">Quick View</button> 
      </div>
      
      <img 
        src="<?php echo htmlspecialchars($product['imageUrl'] ?? ''); ?>" 
        alt="<?php echo htmlspecialchars($product['name'] ?? 'Product'); ?>">
    </div>
    
    <div class="product-info">
      <h3><?php echo htmlspecialchars($product['name'] ?? 'Untitled Product'); ?></h3>
      <p class="price">₹<?php echo number_format($product['price'] ?? 0.00, 2); ?></p>
      
      <button 
        class="addToCartButton cta-button" 
        data-product-id="<?php echo htmlspecialchars($product['productId'] ?? 0); ?>"> 
        Add to Cart
      </button>
    </div>
  </div>
  <?php 
    endforeach; 
  ?>
<?php else: ?>
  <p class="empty-catalog">We are currently curating the finest selection. Please check back soon!</p>
<?php endif; ?>
</div>

<script src="/assets/js/cartAjax.js"></script>