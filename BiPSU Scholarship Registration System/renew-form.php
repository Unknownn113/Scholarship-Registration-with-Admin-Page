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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the logged-in user's student number
    $studentNumber = $_SESSION['student_number'];

    // File uploads
    $grades = $_FILES['grades']['name'];
    $prospectus = $_FILES['prospectus']['name'];

    // Upload files to the "renewal" directory
    $uploadDir = "renewal/";
    $gradesPath = $uploadDir . basename($grades);
    $prospectusPath = $uploadDir . basename($prospectus);

    if (move_uploaded_file($_FILES['grades']['tmp_name'], $gradesPath) &&
        move_uploaded_file($_FILES['prospectus']['tmp_name'], $prospectusPath)) {
        
        // Update the user's status to "Pending" in the database
        $updateStatusSql = "UPDATE registration SET status = 'Pending', grades = ?, prospectus = ? WHERE student_number = ?";
        $updateStatusStmt = $conn->prepare($updateStatusSql);
        $updateStatusStmt->bind_param("sss", $grades, $prospectus, $studentNumber);

        if ($updateStatusStmt->execute()) {
            echo "<script>
                    alert('Your renewal request has been submitted successfully. Your status is now Pending.');
                    window.location.href = 'welcome.php'; // Redirect to the dashboard
                  </script>";
        } else {
            echo "<script>
                    alert('An error occurred while submitting your renewal request. Please try again.');
                    window.location.href = 'renew-form.php'; // Redirect back to the form
                  </script>";
        }

        $updateStatusStmt->close();
    } else {
        echo "<script>
                alert('Failed to upload files. Please try again.');
                window.location.href = 'renew-form.php'; // Redirect back to the form
              </script>";
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheets/renew-form.css">
    <title>Renewal Form</title>
</head>
<body>
    <a href="renewal.php">
        <button class="back">Go Back</button>
    </a>
    <div class="form-container">
        <h1>Renewal Form</h1>
        <form action="renew-form.php" method="post" enctype="multipart/form-data">
            <!-- Upload Certificate of Grades -->
            <label for="grades">Upload Certificate of Grades (.pdf or .jpg)</label>
            <input type="file" id="grades" name="grades" accept=".pdf,.jpg,.jpeg,.png" required>

            <!-- Upload Prospectus -->
            <label for="prospectus">Upload Prospectus (.pdf or .jpg)</label>
            <input type="file" id="prospectus" name="prospectus" accept=".pdf,.jpg,.jpeg,.png" required>

            <button type="submit">Submit Renewal</button>
        </form>
    </div>
</body>
</html>