<?php
session_name('user_session'); // Ensure the session name is consistent
session_start();

// Include the database connection
require_once 'db.php'; // Ensure this file initializes $conn

// Check if the user is logged in and has the "user" role
if (!isset($_SESSION['user_student_number']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php?error=not_logged_in");
    exit();
}

// Check if the session variable 'student_number' is set
if (!isset($_SESSION['student_number'])) {
    header("Location: login.php?error=missing_student_number");
    exit();
}

// Check if the user already has registered data
$studentNumber = $_SESSION['student_number'];
$checkRegistrationSql = "SELECT * FROM registration WHERE student_number = ?";
$checkRegistrationStmt = $conn->prepare($checkRegistrationSql);

if ($checkRegistrationStmt === false) {
    die("Database error: " . $conn->error);
}

$checkRegistrationStmt->bind_param("s", $studentNumber);
$checkRegistrationStmt->execute();
$checkRegistrationResult = $checkRegistrationStmt->get_result();

if ($checkRegistrationResult->num_rows > 0) {
    // If the user already has registered data
    echo "<script>
            alert('You already have registered data. You cannot register again.');
            window.location.href = 'welcome.php'; // Redirect to the dashboard
          </script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Start your Journey with Us!</title>
    <link rel="stylesheet" href="stylesheets/register-renew.css">
</head>
<body>
    <a href="welcome.php">
        <button class="back">Go Back</button>
    </a>
    
    <h1>Choose A Registration Method</h1>
    <div class="buttons">
        <a href="register-form.php">
            <button>REGISTER <span><p>(Provide your Info)</p></span></button>
        </a>
        <a href="reg.walkin.html">
            <button>WALK-IN <span><p>(Preparation Only)</p></span></button>
        </a>
    </div> 
</body>
</html>