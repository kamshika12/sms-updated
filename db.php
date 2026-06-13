<?php
/**
 * Database Connection Configuration
 * 
 * STUDENT PRACTICE TODO:
 * Complete the database connection code below using PDO.
 * Make sure to handle exceptions properly.
 */

// Database Credentials (Update these to match your local database settings)
define('DB_HOST', 'localhost');
define('DB_PORT', '3306'); // Default MySQL port
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'student_db');

$pdo = null;

try {
    // ==========================================
    // TODO: Student Practice
    // 1. Create a DSN (Data Source Name) string for MySQL connection.
    // 2. Initialize the $pdo variable as a new PDO instance.
    // 3. Set the PDO error mode attribute to throw exceptions (ERRMODE_EXCEPTION).
    // 4. Set the default fetch mode to fetch associative arrays (FETCH_ASSOC).
    // ==========================================
    
    // Uncomment and complete the lines below:
     $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
     $pdo = new PDO($dsn, DB_USER, DB_PASS);
     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // In case of a database connection error, output the message and stop execution
    die("Database connection failed: " . $e->getMessage());
}
?>
