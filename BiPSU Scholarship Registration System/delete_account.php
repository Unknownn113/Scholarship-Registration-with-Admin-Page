<?php
// Start the session with a custom session name for admins
session_name('admin_session');
session_start();

// Include the database connection
require 'db.php';

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['admin_student_number']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Check if the student_number is provided in the URL
if (isset($_GET['student_number'])) {
    $studentNumber = $_GET['student_number'];

    // Begin a transaction to ensure both deletions succeed or fail together
    $conn->begin_transaction();

    try {
        // Prepare the SQL statement to delete the student account from the basic_info table
        $stmt = $conn->prepare("DELETE FROM basic_info WHERE student_number = ?");
        $stmt->bind_param("s", $studentNumber);
        $stmt->execute();

        // Prepare the SQL statement to delete the student's registration info from the registration table
        $stmt = $conn->prepare("DELETE FROM registration WHERE student_number = ?");
        $stmt->bind_param("s", $studentNumber);
        $stmt->execute();

        // Commit the transaction
        $conn->commit();

        // Redirect back to the admin dashboard with a success message
        header("Location: index-admin.php?message=Account and registration info deleted successfully");
        exit();
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $conn->rollback();

        // Redirect back to the admin dashboard with an error message
        header("Location: index-admin.php?error=Failed to delete account and registration info");
        exit();
    }
} else {
    // Redirect back to the admin dashboard if no student_number is provided
    header("Location: index-admin.php?error=No student number provided");
    exit();
}
?>