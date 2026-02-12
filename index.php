<?php
// === DEBUGGING: Must be at the very top to catch errors early ===
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ================================================================
// 1. SYSTEM INITIALIZATION & PATH DEFINITION
// ================================================================

// Define ROOTPATH now that index.php is in the root directory
define('ROOTPATH', __DIR__); 

// Core Includes
require_once ROOTPATH . '/includes/config.php';
require_once ROOTPATH . '/includes/sessionManager.php'; // Starts session
require_once ROOTPATH . '/includes/database.php';

// Controller Includes
require_once ROOTPATH . '/controllers/AuthController.php';
require_once ROOTPATH . '/controllers/ProductController.php';
require_once ROOTPATH . '/controllers/CartController.php';
// --- ENSURE THIS LINE IS PRESENT AND CORRECT ---
require_once ROOTPATH . '/controllers/CheckoutController.php'; 

// ================================================================
// 2. ROUTING LOGIC SETUP
// ================================================================

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Clean the URI path to get our route (e.g., 'auth/login')
$route = trim($requestUri, '/');

// Handle the scenario where the host uses a subdirectory structure 
$route = preg_replace('/^index\.php\//', '', $route);

$routeParts = explode('/', $route);

// Determine the controller and action
$controllerName = strtolower($routeParts[0] ?? 'product');
$actionName = strtolower($routeParts[1] ?? 'catalog');

// Check Login Status
$isLoggedIn = isset($_SESSION['userId']);

// ================================================================
// 3. CONTROLLER INSTANTIATION
// ================================================================

// Instantiating all necessary controllers once
$authController = new AuthController();
$productController = new ProductController();
$cartController = new CartController();
$checkoutController = new CheckoutController(); // Fixed: Class is now included and should instantiate correctly.

// ================================================================
// 4. ROUTING EXECUTION
// ================================================================

switch ($controllerName) {
    
    // --- Authentication Routes ---
    case 'auth':
        if ($actionName === 'login') {
            $result = [];
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $result = $authController->handleLogin($_POST);
                if ($result['success']) {
                    header("Location: /product/catalog"); // Redirect upon successful login
                    exit();
                }
            }
            $pageTitle = "Login";
            include ROOTPATH . '/views/layout/header.php';
            include ROOTPATH . '/views/auth/login.php'; // $result is available here
            include ROOTPATH . '/views/layout/footer.php';
            
        } elseif ($actionName === 'register') {
            $result = [];
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $result = $authController->handleRegister($_POST);
                if ($result['success']) {
                    header("Location: /auth/login?registered=true"); // Redirect to login after success
                    exit();
                }
            }
            $pageTitle = "Register";
            include ROOTPATH . '/views/layout/header.php';
            include ROOTPATH . '/views/auth/register.php'; // $result is available here
            include ROOTPATH . '/views/layout/footer.php';

        } elseif ($actionName === 'logout') {
            $authController->logoutAction(); // Handles redirect internally
            
        } else {
            http_response_code(404);
            echo "404 Not Found: Auth action not supported.";
        }
        break;

    // --- Product & Catalog Routes ---
    case 'product':
        // No login required for catalog view
        $productController->catalogAction(); // Handles view inclusion internally
        break;

    // --- Protected Routes (Require Login) ---
    default:
        // Default catch for controllers that require authentication
        if (!$isLoggedIn) {
            header("Location: /auth/login");
            exit();
        }

        switch ($controllerName) {
            case 'cart':
                $cartController->viewAction();
                break;
                
            case 'checkout':
                switch ($actionName) {
                    case 'step1shipping': // Handles GET /checkout/step1shipping
                        $checkoutController->step1Shipping();
                        break;
                        
                    case 'summary': // Handles POST /checkout/summary (Submission from step 1 form)
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $checkoutController->summaryAction();
                        } else {
                            // Enforce POST method for summary processing
                            header("Location: /checkout/step1shipping"); 
                            exit;
                        }
                        break;

                    case 'placeorder': // Handles POST /checkout/placeorder (Final confirmation)
                        $checkoutController->placeOrderAction();
                        break;
                        
                    default:
                        http_response_code(404);
                        echo "404 Not Found: Checkout action not supported.";
                        break;
                }
                break; // End of checkout case

            default:
                http_response_code(404);
                echo "404 Not Found: Unknown Controller.";
                break;
        }
        break;
}