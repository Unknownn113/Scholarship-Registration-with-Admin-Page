<?php
// Include the database connection
require 'db.php';

// Handle the signup logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup-button'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $studentnumber = $_POST['student-number'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    // Check if passwords match
    if ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // Check if the student number already exists
        $checkStmt = $conn->prepare("SELECT * FROM basic_info WHERE student_number = ?");
        $checkStmt->bind_param("s", $studentnumber);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            $error = "A user with this student number already exists.";
        } else {
            // Hash the password for security
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert the user into the database
            $stmt = $conn->prepare("INSERT INTO basic_info (name, email, student_number, password, role) VALUES (?, ?, ?, ?, 'user')");
            $stmt->bind_param("ssss", $name, $email, $studentnumber, $hashedPassword);

            if ($stmt->execute()) {
                // Redirect to the login page on successful signup
                header("Location: login.php?success=1");
                exit();
            } else {
                $error = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
        $checkStmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheets/login-register.css">
    <title>Sign Up</title>
</head>
<body class="signup">
    <div class="signup">
        <h1>Sign Up</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p class="success"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>
        <form method="POST" action="signup.php">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="student-number">Student Number:</label>
            <input type="text" id="student-number" name="student-number" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="confirm-password">Confirm Password:</label>
            <input type="password" id="confirm-password" name="confirm-password" required>
            
            <button type="submit" name="signup-button">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Log in here</a>.</p>
    </div>
</body>
</html>