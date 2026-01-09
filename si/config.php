<?php
// config.php - IMPROVED VERSION

// Error reporting (comment out in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

// Session security settings - MUST come before session_start()
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Change to 1 if using HTTPS
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.cookie_lifetime', 0); // Until browser closes
ini_set('session.gc_maxlifetime', 1800); // 30 minutes

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Regenerate session ID every 5 minutes for security
if (!isset($_SESSION['last_regeneration'])) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
} elseif (time() - $_SESSION['last_regeneration'] > 300) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'aspironet_db');

// Create database connection with error handling
function getDBConnection() {
    static $conn = null;
    
    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($conn->connect_error) {
            error_log("Database connection failed: " . $conn->connect_error);
            // Don't die in production, handle gracefully
            if (session_status() === PHP_SESSION_ACTIVE) {
                $_SESSION['error'] = "Database connection error. Please try again later.";
            }
            return null;
        }
        
        // Set charset to UTF-8
        $conn->set_charset("utf8mb4");
    }
    
    return $conn;
}

// Initialize session arrays if they don't exist
function initSessionVars() {
    // User session variables
    if (!isset($_SESSION['user'])) {
        $_SESSION['user'] = [
            'id' => null,
            'name' => null,
            'email' => null,
            'phone' => null,
            'type' => 'guest',
            'logged_in' => false
        ];
    }
    
    // Admin session variables
    if (!isset($_SESSION['admin'])) {
        $_SESSION['admin'] = [
            'logged_in' => false,
            'id' => null,
            'name' => null,
            'email' => null
        ];
    }
    
    // General session variables
    if (!isset($_SESSION['created'])) {
        $_SESSION['created'] = time();
    }
    
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}

// Initialize session variables
initSessionVars();

// Helper functions
function isUserLoggedIn() {
    return isset($_SESSION['user']['logged_in']) && $_SESSION['user']['logged_in'] === true;
}

function isAdminLoggedIn() {
    return isset($_SESSION['admin']['logged_in']) && $_SESSION['admin']['logged_in'] === true;
}

function getUserInfo($key = null) {
    if ($key === null) {
        return $_SESSION['user'];
    }
    return $_SESSION['user'][$key] ?? null;
}

function getAdminInfo($key = null) {
    if ($key === null) {
        return $_SESSION['admin'];
    }
    return $_SESSION['admin'][$key] ?? null;
}

// CSRF token functions
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Clean input function
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Redirect function
function redirect($url) {
    header("Location: " . $url);
    exit();
}

// Check for session timeout (30 minutes)
function checkSessionTimeout() {
    if (isset($_SESSION['created']) && (time() - $_SESSION['created'] > 1800)) {
        // Session expired
        session_unset();
        session_destroy();
        
        // Start new session
        session_start();
        session_regenerate_id(true);
        $_SESSION['created'] = time();
        $_SESSION['message'] = 'Your session has expired. Please login again.';
        
        return false;
    }
    
    // Update last activity time
    $_SESSION['last_activity'] = time();
    return true;
}

// Check session timeout on every request
checkSessionTimeout();
?>