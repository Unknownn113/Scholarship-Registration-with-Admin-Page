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


// Check if the user is registered and their status
$studentNumber = $_SESSION['student_number'];
$checkStatusSql = "SELECT status FROM registration WHERE student_number = ?";
$checkStatusStmt = $conn->prepare($checkStatusSql);
$checkStatusStmt->bind_param("s", $studentNumber);
$checkStatusStmt->execute();
$checkStatusResult = $checkStatusStmt->get_result();

if ($checkStatusResult->num_rows > 0) {
    $row = $checkStatusResult->fetch_assoc();
    if ($row['status'] === "Active") {
        echo "<script>
                alert('You already have an Active status. You cannot renew.');
                window.location.href = 'welcome.php'; // Redirect to the dashboard
              </script>";
        exit();
    }
    elseif ($row['status'] === "Pending") {
        echo "<script>
                alert('You already have a Pending status. You cannot renew.');
                window.location.href = 'welcome.php'; // Redirect to the dashboard
              </script>";
        exit();
    }
} else {
    // If the user is not registered
    echo "<script>
            alert('You are not registered. You cannot access the renewal form.');
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
    <link rel="stylesheet" href="stylesheets/register-renew.css">
    <title>Choose A Renewal Method</title>
</head>
<body>
    <a href="welcome.php">
        <button class="back">Go Back</button>
    </a>
    
   <h1>Choose A Renewal Method</h1>
   <div class="buttons">
    <a href="renew-form.php"><button>RENEW<span><p>(Provide your Info)</p></span></button></a>
    <a href="renew.walkin.html"><button>WALK-IN <span><p>(Preparation Only)</p></span></button></a>
   </div> 
</body>
</html>