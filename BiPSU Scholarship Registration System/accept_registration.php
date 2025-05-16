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

// Include the database connection
require 'db.php';

// Get the student number from the URL
if (isset($_GET['student_number'])) {
    $studentNumber = $_GET['student_number'];

    // Update the student's status to "Active"
    $updateStatusSql = "UPDATE registration SET status = 'Active' WHERE student_number = ?";
    $updateStatusStmt = $conn->prepare($updateStatusSql);
    $updateStatusStmt->bind_param("s", $studentNumber);

    if ($updateStatusStmt->execute()) {
        echo "<script>
                alert('Registration form accepted. The student\'s status is now Active.');
                window.location.href = 'index-admin.php'; // Redirect back to the admin dashboard
              </script>";
    } else {
        echo "<script>
                alert('An error occurred while accepting the registration form. Please try again.');
                window.location.href = 'index-admin.php'; // Redirect back to the admin dashboard
              </script>";
    }

    $updateStatusStmt->close();
} else {
    echo "<script>
            alert('Invalid request.');
            window.location.href = 'index-admin.php'; // Redirect back to the admin dashboard
          </script>";
}

$conn->close();
?>