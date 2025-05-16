<?php
// Start the session with a custom session name for users
session_name('user_session');
session_start();

// Check if the user is logged in and has the "user" role
if (!isset($_SESSION['user_student_number']) || $_SESSION['role'] !== 'user') {
    // Redirect to the login page if not logged in or not a "user"
    header("Location: login.php?error=not_logged_in");
    exit();
}

// Retrieve the user's name from the basic_info table
require 'db.php';
$student_number = $_SESSION['user_student_number']; // Use user_student_number from the session

// Query to fetch the user's name from the basic_info table
$nameStmt = $conn->prepare("SELECT name FROM basic_info WHERE student_number = ?");
$nameStmt->bind_param("s", $student_number);
$nameStmt->execute();
$nameResult = $nameStmt->get_result();

if ($nameResult->num_rows > 0) {
    $nameRow = $nameResult->fetch_assoc();
    $name = $nameRow['name'];
} else {
    $name = "Student"; // Default value if no name is found
}

// Retrieve the user's status and other details from the registration table
$stmt = $conn->prepare("SELECT department, program, status, email, year_level, phone_number FROM registration WHERE student_number = ?");
$stmt->bind_param("s", $student_number);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $department = $user['department'];
    $program = $user['program'];
    $status = $user['status'];
    $email = $user['email'];
    $year_level = $user['year_level'];
    $phone_number = $user['phone_number'];
} else {
    // Default values if no data is found in the registration table
    $department = "Not Available";
    $program = "Not Available";
    $status = "Not Available";
    $email = "Not Available";
    $year_level = "Not Available";
    $phone_number = "Not Available";
}

// Retrieve the user's submitted files from the database
$fileStmt = $conn->prepare("SELECT grades, prospectus, school_id FROM registration WHERE student_number = ?");
$fileStmt->bind_param("s", $student_number);
$fileStmt->execute();
$fileResult = $fileStmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Status</title>
    <link rel="stylesheet" href="stylesheets/welcome.css">
</head>
<body class="status-page">
    <aside class="sidebar">
        <div class="welcome-message">
            <h3>Welcome, <?php echo htmlspecialchars($name); ?>!</h3>
        </div>
        <nav class="sidebar-nav">
            <a href="welcome.php" class="sidebar-link">Back to Dashboard</a>
            <a href="login.php" class="sidebar-link">Log Out</a>
        </nav>
    </aside>
    <div class="status-board">
        <h1>Your Status</h1>
        <div class="status-container">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
            <p><strong>Student Number:</strong> <?php echo htmlspecialchars($student_number); ?></p>
            <p>
                <strong>Status:</strong>
                <?php
                if ($status === "Active") {
                    echo "<span class='status-active'>Active</span>";
                } elseif ($status === "Inactive") {
                    echo "<span class='status-inactive'>Inactive</span>";
                } else {
                    echo "<span class='status-pending'>Pending</span>";
                }
                ?>
            </p>
        </div>
        <!-- Submitted Data Section -->
        <div class="submitted-data">
            <h2>Your Submitted Information</h2>
            <p><strong>Department:</strong> <?php echo htmlspecialchars($department); ?></p>
            <p><strong>Program:</strong> <?php echo htmlspecialchars($program); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            <p><strong>Year Level:</strong> <?php echo htmlspecialchars($year_level); ?></p>
            <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($phone_number); ?></p>
        </div>
        <!-- Submitted Files Section -->
        <div class="submitted-files">
            <h2>Your Submitted Files</h2>
            <?php if ($fileResult->num_rows > 0): ?>
                <?php while ($file = $fileResult->fetch_assoc()): ?>
                    <?php if (!empty($file['grades'])): ?>
                        <div class="file-item">
                            <p><strong>Grades File:</strong> <?php echo htmlspecialchars($file['grades']); ?></p>
                            <iframe src="uploads/<?php echo htmlspecialchars($file['grades']); ?>" width="40%" height="200px"></iframe>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($file['prospectus'])): ?>
                        <div class="file-item">
                            <p><strong>Prospectus File:</strong> <?php echo htmlspecialchars($file['prospectus']); ?></p>
                            <iframe src="uploads/<?php echo htmlspecialchars($file['prospectus']); ?>" width="40%" height="200px"></iframe>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($file['school_id'])): ?>
                        <div class="file-item">
                            <p><strong>School ID File:</strong> <?php echo htmlspecialchars($file['school_id']); ?></p>
                            <img src="uploads/<?php echo htmlspecialchars($file['school_id']); ?>" alt="School ID Preview" style="width: 40%; height: auto;">
                        </div>
                    <?php endif; ?>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No files submitted.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>