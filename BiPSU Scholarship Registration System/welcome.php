<?php
session_name('user_session'); // Use the user session name
session_start();

// Check if the user is logged in and has the "user" role
if (!isset($_SESSION['user_student_number']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php?error=not_logged_in");
    exit();
}

// Retrieve the user's name and email from the database
require 'db.php';
$student_number = $_SESSION['user_student_number']; // Use user_student_number from the session

// Check if the student is already registered in the registration table
$registrationStmt = $conn->prepare("SELECT * FROM registration WHERE student_number = ?");
$registrationStmt->bind_param("s", $student_number);
$registrationStmt->execute();
$registrationResult = $registrationStmt->get_result();

$isRegistered = $registrationResult->num_rows > 0; // True if the student is registered

// Fetch the user's name and email
$stmt = $conn->prepare("SELECT name, email FROM basic_info WHERE student_number = ?");
$stmt->bind_param("s", $student_number);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userName = $user['name'];
    $email = $user['email'];
} else {
    $userName = "Student";
    $email = "Not Available";
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
                <p>View and update your personal information, including contact details, scholarship status, and academic records.</p>
            </div>
            <!-- Register for Scholarship Section -->
            <div class="dashboard-section">
                <?php if ($isRegistered): ?>
                    <button class="nav-button disabled" disabled>
                        Already Registered
                    </button>
                    <p>You have already registered for a scholarship. You cannot register again.</p>
                <?php else: ?>
                    <a href="register.php">
                        <button class="nav-button">
                            Register for Scholarship
                        </button>
                    </a>
                    <p>Start your scholarship application by selecting the program that suits you best.</p>
                <?php endif; ?>
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