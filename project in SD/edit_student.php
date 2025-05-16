<?php
// filepath: c:\xampp\htdocs\project in SD\edit_student.php

// Include the database connection
require 'db.php';

// Check if the student number is provided
if (!isset($_GET['student_number'])) {
    echo "<script>
            alert('No student number provided.');
            window.location.href = 'index-admin.php';
          </script>";
    exit();
}

$student_number = $_GET['student_number'];

// Fetch the student's current data
$sql = "SELECT * FROM registration WHERE student_number = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_number);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<script>
            alert('Student not found.');
            window.location.href = 'index-admin.php';
          </script>";
    exit();
}

$student = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_number = $_POST['student_number'];
    $department = $_POST['department'];
    $program = $_POST['program'];
    $status = $_POST['status'];
    $email = $_POST['email'];

    // Update the student's data in the database
    $sql = "UPDATE registration SET department = ?, program = ?, status = ?, email = ? WHERE student_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $department, $program, $status, $email, $student_number);

    if ($stmt->execute()) {
        echo "<script>
                alert('Student updated successfully.');
                window.location.href = 'index-admin.php';
              </script>";
    } else {
        echo "<script>
                alert('Error updating student.');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link rel="stylesheet" href="stylesheets/style-admin.css">
    <script>
        function updatePrograms() {
            const department = document.getElementById("department").value;
            const programDropdown = document.getElementById("program");

            // Clear existing options
            programDropdown.innerHTML = "<option value='' selected>Please Select a Program</option>";

            // Define programs for each department
            const programs = {
                "SOE": ["Bachelor of Science in Civil Engineering", "Bachelor of Science in Mechanical Engineering", "Bachelor of Science in Electrical Engineering", "Bachelor of Science in Computer Engineering"],
                "STEd": ["Bachelor in Elementary Education", "Bachelor in Secondary Education", "Bachelor in Technology and Livelihood Education"],
                "SAS": ["Bachelor of Arts in Communication", "Bachelor of Arts in Economics", "Bachelor of Arts in English", "Bachelor of Science in Psychology", "Bachelor of Science in Biology"],
                "SME": ["Bachelor of Science in Business Administration", "Bachelor of Science in Accountancy", "Bachelor of Science in Tourism Management", "Bachelor of Science in Hospitality Management"],
                "SNHS": ["Bachelor of Science in Nursing"],
                "STCS": ["Bachelor of Science in Information Technology", "Bachelor of Science in Computer Science", "Bachelor of Science in Information Systems", "Bachelor of Science in Industrial Technology"],
                "SCJE": ["Bachelor of Science in Criminology"]
            };

            // Add new options based on the selected department
            if (programs[department]) {
                programs[department].forEach(program => {
                    const option = document.createElement("option");
                    option.value = program;
                    option.textContent = program;
                    programDropdown.appendChild(option);
                });
            }
        }
    </script>
</head>
<body class="centered-container">
    <div class="centered-box">
        <form method="post" action="">
            <h2>Edit Student</h2>
            <input name="student_number" placeholder="Student Number" value="<?php echo $student['student_number']; ?>" readonly><br>
            <input name="name" placeholder="Name" value="<?php echo $student['first_name'] . ' ' . $student['middle_name'] . ' ' . $student['last_name']; ?>" readonly><br>

            <!-- Department Dropdown -->
            <label for="department">Department</label>
            <select name="department" id="department" required onchange="updatePrograms()">
                <option value="" selected>Please Select</option>
                <option value="SOE" <?php echo $student['department'] == 'SOE' ? 'selected' : ''; ?>>(SOE) School of Engineering</option>
                <option value="STEd" <?php echo $student['department'] == 'STEd' ? 'selected' : ''; ?>>(STEd) School of Teacher Education</option>
                <option value="SAS" <?php echo $student['department'] == 'SAS' ? 'selected' : ''; ?>>(SAS) School of Arts and Sciences</option>
                <option value="SME" <?php echo $student['department'] == 'SME' ? 'selected' : ''; ?>>(SME) School of Management and Entrepreneurship</option>
                <option value="SNHS" <?php echo $student['department'] == 'SNHS' ? 'selected' : ''; ?>>(SNHS) School of Nursing and Health Sciences</option>
                <option value="STCS" <?php echo $student['department'] == 'STCS' ? 'selected' : ''; ?>>(STCS) School of Technology and Computer Studies</option>
                <option value="SCJE" <?php echo $student['department'] == 'SCJE' ? 'selected' : ''; ?>>(SCJE) School of Criminal Justice Education</option>
            </select><br>

            <!-- Program Dropdown -->
            <label for="program">Program</label>
            <select name="program" id="program" required>
                <option value="" selected>Please Select a Program</option>
                <?php
                // Populate the program dropdown based on the current department
                $programs = [
                    "SOE" => ["Bachelor of Science in Civil Engineering", "Bachelor of Science in Mechanical Engineering", "Bachelor of Science in Electrical Engineering", "Bachelor of Science in Computer Engineering"],
                    "STEd" => ["Bachelor in Elementary Education", "Bachelor in Secondary Education", "Bachelor in Technology and Livelihood Education"],
                    "SAS" => ["Bachelor of Arts in Communication", "Bachelor of Arts in Economics", "Bachelor of Arts in English", "Bachelor of Science in Psychology", "Bachelor of Science in Biology"],
                    "SME" => ["Bachelor of Science in Business Administration", "Bachelor of Science in Accountancy", "Bachelor of Science in Tourism Management", "Bachelor of Science in Hospitality Management"],
                    "SNHS" => ["Bachelor of Science in Nursing"],
                    "STCS" => ["Bachelor of Science in Information Technology", "Bachelor of Science in Computer Science", "Bachelor of Science in Information Systems", "Bachelor of Science in Industrial Technology"],
                    "SCJE" => ["Bachelor of Science in Criminology"]
                ];
                if (!empty($student['department']) && isset($programs[$student['department']])) {
                    foreach ($programs[$student['department']] as $program) {
                        $selected = $student['program'] == $program ? 'selected' : '';
                        echo "<option value='$program' $selected>$program</option>";
                    }
                }
                ?>
            </select><br>

            <label for="status">Status</label>
            <select name="status" required>
                <option value="Pending" <?php echo $student['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="Active" <?php echo $student['status'] == 'Active' ? 'selected' : ''; ?>>Active</option>
                <option value="Inactive" <?php echo $student['status'] == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
            </select><br>

            <input name="email" placeholder="Email" value="<?php echo $student['email']; ?>" required><br>
            <div class="form-buttons">
                <button type="submit" class="update-btn">Update</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='index-admin.php'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>