<?php
// Include the database connection
include 'db.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form inputs
    $studentNumber = $_POST['student-number'];
    $firstName = $_POST['first-name'];
    $middleName = $_POST['middle-name'];
    $lastName = $_POST['last-name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $barangay = $_POST['barangay'];
    $municipality = $_POST['municipality'];
    $province = $_POST['province'];
    $country = $_POST['country'];
    $department = $_POST['department'];
    $program = $_POST['program'];
    $year = $_POST['year'];

    // File uploads
    $grades = $_FILES['grades']['name'];
    $prospectus = $_FILES['prospectus']['name'];
    $schoolId = $_FILES['school-id']['name'];

    // Upload files to the "uploads" directory
    $uploadDir = "uploads/";
    move_uploaded_file($_FILES['grades']['tmp_name'], $uploadDir . $grades);
    move_uploaded_file($_FILES['prospectus']['tmp_name'], $uploadDir . $prospectus);
    move_uploaded_file($_FILES['school-id']['tmp_name'], $uploadDir . $schoolId);

    // Insert data into the database
    $sql = "INSERT INTO registration (student_number, first_name, middle_name, last_name, age, gender, phone_number, email, barangay, municipality, province, country, department, program, year_level, grades, prospectus, school_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssssssssssssss",
        $studentNumber,
        $firstName,
        $middleName,
        $lastName,
        $age,
        $gender,
        $phone,
        $email,
        $barangay,
        $municipality,
        $province,
        $country,
        $department,
        $program,
        $year,
        $grades,
        $prospectus,
        $schoolId
    );

    if ($stmt->execute()) {
        echo "<script>
                alert('Registration successful!');
                window.location.href = 'welcome.php';
              </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheets/register-form.css">
    <title>Scholarship Registration Form</title>
    <style>
        /* Hide sections by default */
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    
    <div class="form-container">
        <a href="register.php"><button class="back">Go Back</button></a>
        <h1>Scholarship Registration Form</h1>
        <form action="#" method="post" enctype="multipart/form-data">
            <!-- Section 1: Personal Information -->
            <div id="section1">
                <h3>Enter Your Information</h3>
                <label for="first-name">First Name</label>
                <input type="text" id="first-name" name="first-name" required>

                <label for="middle-name">Middle Name</label>
                <input type="text" id="middle-name" name="middle-name">

                <label for="last-name">Last Name</label>
                <input type="text" id="last-name" name="last-name" required>

                <label for="student-number">Student Number</label>
                <input type="text" id="student-number" name="student-number" placeholder="Enter your student number" required>
                
                <div class="birthday-gender">
                    <div class="birthday">
                        <label for="age">Age</label>
                        <input type="text" id="age" name="age" placeholder="Enter Your Age" required>
                    </div>
                    <div class="gender">
                        <label>Gender</label>
                        <div class="gender-options">
                            <label for="male">
                                <input type="radio" id="male" name="gender" value="male" required> Male
                            </label>
                            <label for="female">
                                <input type="radio" id="female" name="gender" value="female" required> Female
                            </label>
                            <label for="other">
                                <input type="radio" id="other" name="gender" value="other" required> Other
                            </label>
                        </div>
                    </div>
                </div>

                <h2>Contact</h2>
                <div class="contact-section">
                    <div class="contact-item">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
                    </div>
                    <div class="contact-item">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                </div>

                <h2>Address</h2>
                <div class="address-section">
                    <div class="address-item">
                        <label for="barangay">Barangay</label>
                        <input type="text" id="barangay" name="barangay" placeholder="Enter your barangay" required>
                    </div>
                    <div class="address-item">
                        <label for="municipality">Municipality/City</label>
                        <input type="text" id="municipality" name="municipality" placeholder="Enter your municipality or city" required>
                    </div>
                    <div class="address-item">
                        <label for="province">Province/State</label>
                        <input type="text" id="province" name="province" placeholder="Enter your province or state" required>
                    </div>
                    <div class="address-item">
                        <label for="country">Country</label>
                        <input type="text" id="country" name="country" placeholder="Enter your country" required>
                    </div>
                </div>
                <button type="button" onclick="showSection(2)">Next</button>
            </div>

            <!-- Section 2: Academic Information and File Upload -->
            <div id="section2" class="hidden">
                <h3>Provide More Details</h3>
                <div class="department-program">
                    <div class="department-item">
                        <label for="department">Department/School</label>
                        <select name="department" id="department" required onchange="updatePrograms()">
                            <option value="" selected>Please Select</option>
                            <option value="SOE">(SOE) School of Engineering</option>
                            <option value="STEd">(STEd) School of Teacher Education</option>
                            <option value="SAS">(SAS) School of Arts and Sciences</option>
                            <option value="SME">(SME) School of Management and Entrepreneurship</option>
                            <option value="SNHS">(SNHS) School of Nursing and Health Sciences</option>
                            <option value="STCS">(STCS) School of Technology and Computer Studies</option>
                            <option value="SCJE">(SCJE) School of Criminal Justice Education</option>
                        </select>
                    </div>
                    <div class="program-item">
                        <label for="program">Program</label>
                        <select name="program" id="program" required>
                            <option value="" selected>Please Select a Department First</option>
                        </select>
                    </div>
                </div>

                <label for="year">Year Level</label>
                <div class="year-level">
                    <label>
                        <input type="radio" id="year1" name="year" value="1st Year" required> 1st Year
                    </label>
                    <label>
                        <input type="radio" id="year2" name="year" value="2nd Year" required> 2nd Year
                    </label>
                    <label>
                        <input type="radio" id="year3" name="year" value="3rd Year" required> 3rd Year
                    </label>
                    <label>
                        <input type="radio" id="year4" name="year" value="4th Year" required> 4th Year
                    </label>
                    <label>
                        <input type="radio" id="Other" name="year" value="Other" required> Other
                    </label>
                </div>

                <h3>Upload Requirements</h3>
                <div class="file-upload-section">
                    <div class="file-upload-item">
                        <label for="grades">Certificate of Grades</label>
                        <input type="file" id="grades" name="grades" accept=".pdf,.jpg,.png" required>
                    </div>
                    <div class="file-upload-item">
                        <label for="prospectus">Prospectus/Subject Checklist</label>
                        <input type="file" id="prospectus" name="prospectus" accept=".pdf,.jpg,.png" required>
                    </div>
                    <div class="file-upload-item">
                        <label for="school-id">Validated School ID</label>
                        <input type="file" id="school-id" name="school-id" accept=".pdf,.jpg,.png" required>
                    </div>
                </div>
                <button type="button" onclick="showSection(1)">Back</button>
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>

    <script>
        function showSection(sectionNumber) {
            // Hide all sections
            document.querySelectorAll('.form-container > form > div').forEach(section => {
                section.classList.add('hidden');
            });

            // Show the selected section
            document.getElementById(`section${sectionNumber}`).classList.remove('hidden');
        }
        function updatePrograms() {
        const department = document.getElementById("department").value;
        const programSelect = document.getElementById("program");

        // Clear existing options
        programSelect.innerHTML = "<option value='' selected>Please Select a Program</option>";

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
                programSelect.appendChild(option);
            });
        }
    }
    </script>
</body>
</html>