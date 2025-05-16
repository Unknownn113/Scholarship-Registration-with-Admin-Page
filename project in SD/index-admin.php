<?php
session_start();
// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['student_number']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Include the database connection
require 'db.php';

// Initialize search variables
$searchName = isset($_GET['name']) ? $_GET['name'] : '';
$searchDepartment = isset($_GET['department']) ? $_GET['department'] : '';
$searchProgram = isset($_GET['program']) ? $_GET['program'] : '';

// Fetch filtered students from the database
$sql = "SELECT student_number, first_name, middle_name, last_name, department, program, year_level, email, status 
        FROM registration 
        WHERE 
        (first_name LIKE ? OR last_name LIKE ? OR CONCAT(first_name, ' ', last_name) LIKE ?) 
        AND (department = ? OR ? = '') 
        AND (program = ? OR ? = '')";
$stmt = $conn->prepare($sql);
$searchNameWildcard = '%' . $searchName . '%';
$stmt->bind_param( 
    "sssssss",
    $searchNameWildcard,
    $searchNameWildcard,
    $searchNameWildcard,
    $searchDepartment,
    $searchDepartment,
    $searchProgram,
    $searchProgram
);
$stmt->execute();
$result = $stmt->get_result();

// Fetch submitted requirements
// Fetch submitted requirements
$requirementsSql = "SELECT student_number, first_name, middle_name, last_name, grades, prospectus, school_id FROM registration";
$requirementsResult = $conn->query($requirementsSql);

// Fetch renewal forms from the database
$renewalFormsSql = "SELECT student_number, first_name, middle_name, last_name, grades, prospectus, status FROM registration WHERE status = 'Pending'";
$renewalFormsResult = $conn->query($renewalFormsSql);
if ($renewalFormsResult->num_rows === 0) {
    echo "<script>console.log('No renewal forms found.');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Dashboard</title>
    <link rel="stylesheet" href="stylesheets/style-admin.css">
    <script>
        function toggleSection(sectionIdToShow) {
            const sections = ["students-section", "requirements-section", "renewal-section"];
            sections.forEach(sectionId => {
                const section = document.getElementById(sectionId);
                if (sectionId === sectionIdToShow) {
                    section.style.display = "block";
                } else {
                    section.style.display = "none";
                }
            });
        }
    </script>
</head>
<body>
<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="javascript:void(0);" onclick="toggleSection('students-section')">Registered Students</a>
    <a href="javascript:void(0);" onclick="toggleSection('requirements-section')">Submitted Requirements</a>
    <a href="javascript:void(0);" onclick="toggleSection('renewal-section')">Renewal Forms</a>
    <a href="login.php">Logout</a>
</div>

<div class="main">
    <!-- Registered Students Section -->
    <div id="students-section">
        <h1>Registered Students</h1>
        <form method="get" action="index-admin.php" class="search-form">
            <input type="text" name="name" placeholder="Search by name" value="<?php echo htmlspecialchars($searchName); ?>">
            <select name="department" id="department" onchange="updatePrograms()">
                <option value="">All Departments</option>
                <option value="SOE" <?php echo $searchDepartment == 'SOE' ? 'selected' : ''; ?>>(SOE) School of Engineering</option>
                <option value="STEd" <?php echo $searchDepartment == 'STEd' ? 'selected' : ''; ?>>(STEd) School of Teacher Education</option>
                <option value="SAS" <?php echo $searchDepartment == 'SAS' ? 'selected' : ''; ?>>(SAS) School of Arts and Sciences</option>
                <option value="SME" <?php echo $searchDepartment == 'SME' ? 'selected' : ''; ?>>(SME) School of Management and Entrepreneurship</option>
                <option value="SNHS" <?php echo $searchDepartment == 'SNHS' ? 'selected' : ''; ?>>(SNHS) School of Nursing and Health Sciences</option>
                <option value="STCS" <?php echo $searchDepartment == 'STCS' ? 'selected' : ''; ?>>(STCS) School of Technology and Computer Studies</option>
                <option value="SCJE" <?php echo $searchDepartment == 'SCJE' ? 'selected' : ''; ?>>(SCJE) School of Criminal Justice Education</option>
            </select>
            <select name="program" id="program">
                <option value="">All Programs</option>
                <?php
                if (!empty($searchDepartment)) {
                    $programs = [
                        "SOE" => ["Bachelor of Science in Civil Engineering", "Bachelor of Science in Mechanical Engineering", "Bachelor of Science in Electrical Engineering", "Bachelor of Science in Computer Engineering"],
                        "STEd" => ["Bachelor in Elementary Education", "Bachelor in Secondary Education", "Bachelor in Technology and Livelihood Education"],
                        "SAS" => ["Bachelor of Arts in Communication", "Bachelor of Arts in Economics", "Bachelor of Arts in English", "Bachelor of Science in Psychology", "Bachelor of Science in Biology"],
                        "SME" => ["Bachelor of Science in Business Administration", "Bachelor of Science in Accountancy", "Bachelor of Science in Tourism Management", "Bachelor of Science in Hospitality Management"],
                        "SNHS" => ["Bachelor of Science in Nursing"],
                        "STCS" => ["Bachelor of Science in Information Technology", "Bachelor of Science in Computer Science", "Bachelor of Science in Information Systems", "Bachelor of Science in Industrial Technology"],
                        "SCJE" => ["Bachelor of Science in Criminology"]
                    ];
                    foreach ($programs[$searchDepartment] as $program) {
                        $selected = $searchProgram == $program ? 'selected' : '';
                        echo "<option value='$program' $selected>$program</option>";
                    }
                }
                ?>
            </select>
            <button type="submit">Search</button>
            <a href="index-admin.php"><button type="button">Reset</button></a>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Student Number</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Program</th>
                    <th>Year Level</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $fullName = $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'];
                        $status = $row['status'];
                        echo "<tr>
                            <td>{$row['student_number']}</td>
                            <td>{$fullName}</td>
                            <td>{$row['department']}</td>
                            <td>{$row['program']}</td>
                            <td>{$row['year_level']}</td>
                            <td>{$row['email']}</td>
                            <td>{$status}</td>
                            <td>
                                <a href='edit_student.php?student_number={$row['student_number']}'>Edit</a> |
                                <a href='delete_student.php?student_number={$row['student_number']}' onclick='return confirm(\"Delete this student?\")'>Delete</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No students found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Submitted Requirements Section -->
    <div id="requirements-section" style="display: none;">
        <h1>Submitted Requirements</h1>
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Certificate of Grades</th>
                    <th>Subject Checklist</th>
                    <th>Valid School ID</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($requirementsResult->num_rows > 0) {
                    while ($row = $requirementsResult->fetch_assoc()) {
                        $fullName = $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'];

                        // Extract file names from file paths
                        $gradesFileName = basename($row['grades']);
                        $prospectusFileName = basename($row['prospectus']);
                        $schoolIdFileName = basename($row['school_id']);

                        echo "<tr>
                            <td>{$fullName}</td>
                            <td><a href='uploads/{$row['grades']}' target='_blank'>{$gradesFileName}</a></td>
                            <td><a href='uploads/{$row['prospectus']}' target='_blank'>{$prospectusFileName}</a></td>
                            <td><a href='uploads/{$row['school_id']}' target='_blank'>{$schoolIdFileName}</a></td>
                            <td>
                                <a href='accept_registration.php?student_number={$row['student_number']}' onclick='return confirm(\"Accept this registration form?\")'>Accept</a> |
                                <a href='deny_registration.php?student_number={$row['student_number']}' onclick='return confirm(\"Deny this registration form?\")'>Deny</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No submitted requirements found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- Renewal Forms Section -->
    <div id="renewal-section" style="display: none;">
    <h1>Renewal Forms</h1>
    <table>
        <thead>
            <tr>
                <th>Student Number</th>
                <th>Name</th>
                <th>Certificate of Grades</th>
                <th>Prospectus</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($renewalFormsResult->num_rows > 0) {
                while ($row = $renewalFormsResult->fetch_assoc()) {
                    $fullName = $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'];
                    $gradesFileName = basename($row['grades']);
                    $prospectusFileName = basename($row['prospectus']);
                    echo "<tr>
                        <td>{$row['student_number']}</td>
                        <td>{$fullName}</td>
                        <td><a href='renewal/{$row['grades']}' target='_blank'>{$gradesFileName}</a></td>
                        <td><a href='renewal/{$row['prospectus']}' target='_blank'>{$prospectusFileName}</a></td>
                        <td>{$row['status']}</td>
                        <td>
                            <a href='accept_renewal.php?student_number={$row['student_number']}' onclick='return confirm(\"Accept this renewal request?\")'>Accept</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No renewal forms found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</div>
</body>
</html>