<?php
// Ensure this is the absolute first thing executed

// CRITICAL: Controller relies on the global ROOTPATH constant for pathing.
require_once ROOTPATH . '/models/UserModel.php'; 

class AuthController {
    
    private $userModel;

    public function __construct() {
        // Instantiate the model for database interaction. 
        // This implicitly relies on the global Database connection setup.
        $this->userModel = new UserModel();
    }

    /**
    * Handles the form submission for user registration.
    */
    public function handleRegister(array $data): array {
        if (empty($data['email']) || empty($data['password']) || empty($data['firstName'])) {
            return ['success' => false, 'message' => 'Please fill in all fields.'];
        }

        $firstName = trim($data['firstName']);
        $email = trim($data['email']);
        $password = $data['password'];

        $result = $this->userModel->registerUser($firstName, $email, $password);

        if ($result === true) {
            // Registration successful
            return ['success' => true, 'message' => 'Registration successful. You can now log in.'];
        } elseif ($result === false) {
            // Registration failed (likely email already exists, handled in Model)
            return ['success' => false, 'message' => 'Registration failed. Email address may already be in use.'];
        } else {
            // Generic database failure
            return ['success' => false, 'message' => 'An internal server error occurred during registration.'];
        }
    }

    /**
    * Handles the form submission for user login.
    */
    public function handleLogin(array $data): array {
        if (empty($data['email']) || empty($data['password'])) {
            return ['success' => false, 'message' => 'Please enter email and password.'];
        }

        $email = trim($data['email']);
        $password = $data['password'];

        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user['passwordHash'])) {
            // Login successful: Set necessary session variables
            $_SESSION['logged_in'] = true;
            $_SESSION['userId'] = $user['userId'];
            $_SESSION['email'] = $user['email'];
            
            // Critical for security: Prevents session fixation attacks
            session_regenerate_id(true); 
            
            return ['success' => true, 'message' => 'Login successful.'];
        } else {
            return ['success' => false, 'message' => 'Invalid email or password.'];
        }
    }

    /**
    * Logs the user out and destroys the session.
    * FIX APPLIED HERE for ArgumentCountError on setcookie()
    */
    public function logoutAction() {
        
        $_SESSION = []; // Clear session variables first
        
        // Remove the session cookie from the client
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            
            // FIX: Use the array syntax for setcookie() to safely handle 
            // the SameSite argument, which older PHP versions do not support 
            // in the simple argument list format.
            setcookie(session_name(), '', [
                'expires' => time() - 42000,
                'path' => $params["path"],
                'domain' => $params["domain"],
                'secure' => $params["secure"],
                'httponly' => $params["httponly"],
                'samesite' => $params["samesite"] // Safely included here
            ]);
        }
        
        session_destroy();
        
        // Redirect to login page
        header("Location: /auth/login");
        exit;
    }
}