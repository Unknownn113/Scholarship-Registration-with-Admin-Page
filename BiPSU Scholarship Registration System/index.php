<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheets/style.css">
    <script src="https://kit.fontawesome.com/a086c48a6b.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Newsreader:ital,opsz,wght@0,6..72,200..800;1,6..72,200..800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <title>BiPSU Scholarship Registration</title>
</head>
<body>
    <header>
        <div class="BiPSU">
            <img src="stylesheets/images/BiPSU Logo.png" alt="BiPSU Logo" id="Logo">
            <h3>BiPSU</h3>
        </div>
        <div class="profile">
            <p>Already have an account? Go to:</p>
            <div class="login-button">
                <a href="login.php"><button id="LogIn">Log In</button></a>
            </div>
            
        </div>
    </header>

    <div class="welcome">
        <h1>WELCOME</h1>
        <h4>BiPSU Scholarship Registration and Renewal</h4>
        <div class="buttons">
            <a href="signup.php">
                <button id="getStarted">
                    Sign Up Here
              </button>
            </a>
        </div>
    </div>
    <br>
    <div class="about">
        <h4>About this website</h4>
        <p>This website's pre-registration for the scholarship program simplifies document verification
            ,organizes applicants, and enhances tracking efficiency.</p>
    </div>
</body>
<footer>
    Having Trouble With The Website?
    <br>
    Please Contact: <a href="mailto:timothyshortland@gmail.com">ScholarshipAdmins@gmail.com</a>
</footer>
</html>

<?php
    include 'db.php';
?>