<?php
// Set robust error reporting for development
ini_set('display_errors', '0'); // Never show errors directly to users
ini_set('log_errors', '1');
ini_set('error_log', ROOTPATH . '/logs/php-error.log');

// Set a custom error handler
set_exception_handler(function ($exception) {
    error_log("Uncaught Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine());
    
    // Show a generic error page to the user
    http_response_code(500);
    include ROOTPATH . '/views/error/500.php'; 
    exit;
});