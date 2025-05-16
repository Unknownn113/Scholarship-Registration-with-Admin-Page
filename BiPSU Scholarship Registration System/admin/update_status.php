<?php
// Include the database connection
require 'db.php';

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $status = $_POST['status'];

    // Update the status in the database
    $sql = "UPDATE registration SET status = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        echo "Status updated successfully.";
    } else {
        echo "Error updating status: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>