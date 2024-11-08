
<?php
// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 0);

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'rajambal_admin');
//define('DB_USERNAME', 'theangel_admin'); PROD
define('DB_PASSWORD', 's3DqGWV22-A%');
//define('DB_NAME', 'theangel_Rajambal'); - This is for prod
define('DB_NAME', 'rajambal_rajambal');

// Create database connection
try {
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    if (!$conn) {
        throw new Exception("Connection failed: " . mysqli_connect_error());
    }
    
    // Set charset to ensure proper handling of special characters
    if (!mysqli_set_charset($conn, "utf8mb4")) {
        throw new Exception("Error setting charset: " . mysqli_error($conn));
    }
    
} catch (Exception $e) {
    // Log error and return JSON error response
    error_log("Database connection error: " . $e->getMessage());
    if (stripos(php_sapi_name(), 'cli') === false) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'error',
            'message' => 'Database connection failed'
        ]);
        exit;
    }
    throw $e;
}
?>