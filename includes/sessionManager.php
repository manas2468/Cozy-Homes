<?php
// Secure session configuration
ini_set('session.use_strict_mode', 1);
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1); // Prevents JS access to session cookie

// Set specific session cookie parameters
session_set_cookie_params([
    // Max age of the cookie (0 means session cookie, expires when browser closes)
    'lifetime' => 0, 
    
    // Cookie path must be the root (/) to be accessible across all pages/APIs
    'path' => '/',   
    
    // Use empty string to default to the current host/domain
    'domain' => '',  
    
    // Set 'secure' based on connection type. InfinityFree usually provides HTTPS, 
    // but setting false is safer for environments where HTTPS might be absent.
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on', 
    
    // Prevents JS access
    'httponly' => true,
    
    // Lax provides good balance for cross-site requests (like some form submissions)
    'samesite' => 'Lax' 
]);

// Start the session if it hasn't been started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Checks if the user is authenticated and active.
 */
function isUserLoggedIn(): bool {
    // Check for user ID and valid session
    return isset($_SESSION['userId']) && $_SESSION['userId'] > 0;
}

// Basic security check: Session timeout (30 minutes)
$inactivityLimit = 1800; // Seconds (30 minutes)
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $inactivityLimit)) {
    session_unset();     
    session_destroy();   
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp