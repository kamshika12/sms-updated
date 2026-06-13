<?php
/**
 * Student Management System - Delete Student Handler
 * 
 * STUDENT PRACTICE TODO:
 * Complete the deletion backend logic below to:
 * 1. Validate the incoming 'id' query parameter.
 * 2. Write and execute a SQL DELETE statement using prepared statements.
 * 3. Redirect back to index.php with success or error parameters.
 */

// Include database connection
require_once 'db.php';

// Check if a valid ID was passed
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // ==========================================
    // TODO: Student Practice - Delete Record
    // ==========================================
    
    /* Uncomment and implement:
    
    try {
        if ($pdo === null) {
            throw new Exception("Database connection is not configured in db.php!");
        }

        // Prepare and execute delete query
        $stmt = $pdo->prepare("DELETE FROM students WHERE id = :id");
        $stmt->execute(['id' => $id]);

        // Redirect back to dashboard on success
        header("Location: index.php?status=success&msg=Student+record+deleted+successfully!");
        exit();

    } catch (Exception $e) {
        // Redirect with error message
        header("Location: index.php?status=error&msg=" . urlencode("Delete failed: " . $e->getMessage()));
        exit();
    }
    */
    
    // For front-end preview demonstration (students will remove this mock block)
    header("Location: index.php?status=success&msg=Student+record+deleted+successfully+(Mock+Preview)!");
    exit();

} else {
    // Redirect back to dashboard if no valid ID provided
    header("Location: index.php?status=error&msg=Invalid+student+ID.");
    exit();
}
?>
