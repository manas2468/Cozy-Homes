<section class="auth-form-container">
    <h2>Create Your Account</h2>
    
    <?php 
    if (isset($result['success']) && !$result['success']): 
    ?>
        <p class="error-message">
            <?php echo htmlspecialchars($result['message'] ?? 'An error occurred during registration.'); ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="/auth/register" class="auth-form">
        
        <!-- First Name Field -->
        <label for="firstName">First Name</label>
        <input type="text" id="firstName" name="firstName" required>

        <!-- Email Field -->
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" required>
        
        <!-- Password Field -->
        <label for="password">Choose Password</label>
        <input type="password" id="password" name="password" required>
        
        <button type="submit" class="cta-button">Register Account</button>
    </form>
    
    <p>Already have an account? <a href="/auth/login">Sign In Here</a></p>
</section>

<!-- ================================================================= -->
<!-- SPLINE VIEWER INTEGRATION -->
<!-- ================================================================= -->
<script type="module" src="https://unpkg.com/@splinetool/viewer@1.10.76/build/spline-viewer.js"></script>
<spline-viewer 
    class="spline-background" 
    url="https://prod.spline.design/27y6nHu1xm4sg96x/scene.splinecode">
</spline-viewer>


<!-- ================================================================= -->
<!-- EMBEDDED STYLING -->
<!-- ================================================================= -->
<style>
/* Reset basic form styles */
body {
    /* Set up environment for absolute positioning */
    margin: 0;
    padding: 0;
    min-height: 100vh;
    position: relative;
    overflow: hidden; 
}

/* ------------------------------------ */
/* SPLINE STYLING (Background) */
/* ------------------------------------ */
.spline-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0; /* Behind everything */
    opacity: 0.9; 
}

/* ------------------------------------ */
/* FORM STYLING (Transparent Container) */
/* ------------------------------------ */
.auth-form * { box-sizing: border-box; }
.auth-form-container {
    max-width: 420px; 
    margin: 100px auto; 
    padding: 45px 50px; 
    
    /* REMOVES THE WHITE BOX */
    background: transparent; 
    box-shadow: none; 
    
    text-align: center;
    position: relative; 
    z-index: 1; /* Above the background */
}

/* Adjust text color for contrast */
.auth-form-container h2 {
    font-family: 'Playfair Display', serif;
    font-size: 2.2em;
    color: white; /* Changed for background contrast */
    text-shadow: 0 0 5px rgba(0,0,0,0.5); 
    margin-bottom: 35px;
    font-weight: 400;
}

.auth-form label {
    display: block;
    text-align: left;
    margin-top: 20px; 
    margin-bottom: 8px;
    font-weight: 600;
    color: #DDDDDD; /* Light gray for labels */
    font-size: 0.9em;
    text-transform: uppercase; 
}

/* Keep input fields usable and slightly opaque */
.auth-form input {
    width: 100%;
    padding: 14px 15px;
    border: 1px solid #E8E4E0;
    border-radius: 4px;
    font-size: 1em;
    background-color: rgba(255, 255, 255, 0.9); /* Slightly transparent white */
    outline: none;
}

.auth-form input:focus {
    border-color: #A87C6F;
    box-shadow: 0 0 0 1px #A87C6F;
}

.cta-button {
    display: block;
    width: 100%;
    padding: 14px;
    margin-top: 35px; 
    background-color: #A87C6F; /* Terracotta */
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    font-size: 1.1em;
    text-transform: uppercase;
}
.cta-button:hover {
    background-color: #8c6a61; 
}

.auth-form-container p {
    margin-top: 25px;
    font-size: 0.9em;
    color: white; /* Changed for background contrast */
}

.auth-form-container a {
    color: #F0E0D6; /* Lighter terracotta for visibility */
    text-decoration: none;
    font-weight: 600;
}
.auth-form-container a:hover {
    color: #FFFFFF;
}


/* Error/Success Message Styles */
.error-message {
    color: #fff;
    background-color: rgba(169, 68, 66, 0.7); 
    border: 1px solid #ebccd1;
    padding: 10px;
    border-radius: 4px;
    margin-bottom: 15px;
}
.success-message {
    color: #fff;
    background-color: rgba(92, 184, 92, 0.7); 
    border: 1px solid #d6e9c6;
    padding: 10px;
    border-radius: 4px;
    margin-bottom: 15px;
}
</style>