<?php
// Include the database connection
require 'db.php';

// Check if the student_number is provided
if (isset($_GET['student_number'])) {
    $studentNumber = $_GET['student_number'];

    // Prepare the SQL statement to delete the student
    $sql = "DELETE FROM registration WHERE student_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $studentNumber);

    if ($stmt->execute()) {
        echo "<script>
                alert('Student record deleted successfully.');
                window.location.href = 'index-admin.php'; // Redirect back to the admin page
              </script>";
    } else {
        echo "<script>
                alert('Error deleting student record.');
                window.location.href = 'index-admin.php'; // Redirect back to the admin page
              </script>";
    }

    $stmt->close();
} else {
    echo "<script>
            alert('No student number provided.');
            window.location.href = 'index-admin.php'; // Redirect back to the admin page
          </script>";
}

$conn->close();
?>