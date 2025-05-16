<?php
// Start the session
session_start();

// Include the database connection
require 'db.php';

// Handle the login logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login-button'])) {
    $studentId = $_POST['login-student-id'];
    $password = $_POST['login-password'];

    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT * FROM basic_info WHERE student_number = ?");
    $stmt->bind_param("s", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the hashed password
        if (password_verify($password, $user['password'])) {
            // Store user details in the session
            $_SESSION['student_number'] = $user['student_number'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: index-admin.php");
            } else {
                header("Location: welcome.php");
            }
            exit();
        } else {
            // Invalid password
            $error = "Invalid password.";
        }
    } else {
        // User not found
        $error = "User not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheets/login-register.css">
    <title>Login</title>
</head>
<body>
    <div class="login">
        <h1>Log In</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <label for="login-student-id">Student ID:</label>
            <input type="text" id="login-student-id" name="login-student-id" required>
            
            <label for="login-password">Password:</label>
            <input type="password" id="login-password" name="login-password" required>
            
            <button type="submit" name="login-button">Log In</button>
        </form>
        <p>New here? <a href="signup.php">Sign up here</a>.</p>
    </div>
</body>
</html>