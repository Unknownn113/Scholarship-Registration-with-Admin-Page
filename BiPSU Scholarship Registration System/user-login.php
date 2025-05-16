<?php
session_name('user_session'); // Use the user session name
session_start();

// Check if the user is a regular user
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user' || !isset($_SESSION['user_student_number'])) {
    header("Location: login.php");
    exit();
}

// User-specific logic
header("Location: welcome.php");
exit();
?>