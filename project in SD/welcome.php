<?php
// Start the session
session_start();

// Check if the user is logged in and has the "user" role
if (!isset($_SESSION['student_number']) || $_SESSION['role'] !== 'user') {
    // Redirect to the login page if not logged in or not a "user"
    header("Location: login.php?error=not_logged_in");
    exit();
}

// Retrieve the user's name and email from the database
require 'db.php';
$student_number = $_SESSION['student_number']; // Use student_number from the session

// Debugging: Check if session variables are set correctly
if (empty($student_number)) {
    die("Error: 'student_number' is not set in the session.");
}

$stmt = $conn->prepare("SELECT name, email FROM basic_info WHERE student_number = ?");
if (!$stmt) {
    die("Error preparing statement: " . $conn->error); // Debugging: Check if the statement preparation failed
}

$stmt->bind_param("s", $student_number);
if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error); // Debugging: Check if the statement execution failed
}

$result = $stmt->get_result();
if (!$result) {
    die("Error fetching result: " . $stmt->error); // Debugging: Check if fetching the result failed
}

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userName = $user['name'];
    $email = $user['email']; // Fetch the email from the database
} else {
    $userName = "Student"; // Default fallback name
    $email = "Not Available"; // Default fallback email
    // Debugging: Add a message if no user is found
    error_log("No user found with student_number: " . $student_number);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheets/welcome.css">
    <title>Student Dashboard</title>
</head>
<body>
    <aside class="sidebar">
        <div class="welcome-message">
            <h3>Welcome, <?php echo htmlspecialchars($userName); ?>!</h3>
        </div>
        <nav class="sidebar-nav">
            <a href="#" class="sidebar-link" onclick="showProfile()">My Profile</a>
            <a href="login.php" class="sidebar-link">Log Out</a>
        </nav>
    </aside>
    <div class="dashboard">
        <h1>Student Dashboard</h1>
        <div class="navigation">
            <!-- View Profile Section -->
            <div class="dashboard-section">
                <a href="status.php">
                    <button class="nav-button">
                        View Submitted Information
                    </button>
                </a>
                <p>View and update your personal information, including contact details, scholarship status and academic records.</p>
            </div>
            <!-- Register for Scholarship Section -->
            <div class="dashboard-section">
                <a href="register.php">
                    <button class="nav-button">
                        Register for Scholarship
                    </button>
                </a>
                <p>Start your scholarship application by selecting the program that suits you best.</p>
            </div> 
            <!-- Renew Status Section -->
            <div class="dashboard-section">
                <a href="renewal.php">
                    <button class="nav-button">
                        Renew Status
                    </button>
                </a>
                <p>Keep your scholarship active by completing the renewal process.</p>
            </div>
        </div>
        <!-- Profile Section -->
        <div id="profile-section" class="profile-section" style="display: none;">
            <h2>Your Profile</h2>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($userName); ?></p>
            <p><strong>Student Number:</strong> <?php echo htmlspecialchars($student_number); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p> <!-- Display the email -->
            <button onclick="hideProfile()" class="nav-button">Close</button>
        </div>
        <footer>
            Having Trouble With The Website?
            <br>
            Please Contact: <a href="mailto:scholarshipadmins@gmail.com">ScholarshipAdmins@gmail.com</a>
        </footer>
    </div>
    <script src="welcome.js"></script>
</body>
</html>