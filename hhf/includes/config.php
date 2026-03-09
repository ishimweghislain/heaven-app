<?php
/**
 * Database Configuration
 * Success Microfinance Institution S.C. Website
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'emailigihepro_heavenlydb');
define('DB_USER', 'emailigihepro_heavenlyus');
define('DB_PASS', 'Hjambobanga1025!');

// Attempt to connect to the database
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS
    );
    
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Set character set to utf8mb4
    $pdo->exec("SET NAMES utf8mb4");
    $pdo->exec("ALTER DATABASE `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
} catch(PDOException $e) {
    // Log error message
    error_log("Connection failed: " . $e->getMessage());

    // Display error detail to the user or console
    if (php_sapi_name() === 'cli') {
        // Console output
        fwrite(STDERR, "Database connection failed: " . $e->getMessage() . PHP_EOL);
    } else {
        // Web output
        echo "Database connection failed: " . htmlspecialchars($e->getMessage());
    }
    exit;
}
