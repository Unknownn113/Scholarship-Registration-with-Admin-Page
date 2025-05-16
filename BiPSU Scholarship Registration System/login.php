<?php
// Include the database connection
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login-button'])) {
    $studentId = $_POST['login-student-id'];
    $password = $_POST['login-password'];

    // Check if the user exists in the admin table
    $query = "SELECT * FROM admin WHERE student_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $studentId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            // Admin login successful
            session_name('admin_session'); // Set a custom session name for admins
            session_start();
            session_regenerate_id(true); // Regenerate session ID for security
            $_SESSION['role'] = 'admin';
            $_SESSION['admin_student_number'] = $admin['student_number'];
            header("Location: admin-login.php");
            exit();
        }
    }

    // Check if the user exists in the users table
    $query = "SELECT * FROM basic_info WHERE student_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $studentId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // User login successful
            session_name('user_session'); // Set a custom session name for users
            session_start();
            session_regenerate_id(true); // Regenerate session ID for security
            $_SESSION['role'] = 'user';
            $_SESSION['user_student_number'] = $user['student_number'];
            $_SESSION['student_number'] = $user['student_number']; // Store student number in session
            header("Location: user-login.php");
            exit();
        }
    }

    // If no match is found, set an error message
    $error = "Invalid Student ID or Password.";
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