<?php
session_name('admin_session'); // Use the admin session name
session_start();

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin' || !isset($_SESSION['admin_student_number'])) {
    header("Location: login.php");
    exit();
}

// Admin-specific logic
header("Location: index-admin.php");
exit();
?>