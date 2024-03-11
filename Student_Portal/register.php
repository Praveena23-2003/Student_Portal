<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile - Student Portal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-top: 50px;
        }

        .section {
            margin: 20px auto;
            width: 80%;
            max-width: 600px;
        }

        .section h3 {
            color: #333;
            margin-top: 0;
        }

        .card {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .card input[type="text"],
        .card input[type="email"],
        .card input[type="password"],
        .card input[type="date"],
        .card textarea {
            width: calc(100% - 22px);
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .card input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .card input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .card textarea {
            height: 100px;
        }
    </style>
</head>
<body>
<h2>Student Profile</h2>
    <!-- Registration Form -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <!-- Student Details Section -->
        <div class="section">
            <h3>Student Details</h3>
   
    <?php
include_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve student details
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone_number = $_POST['phone_number'];
    $emergency_contact_name = $_POST['emergency_contact_name'];
    $emergency_contact_phone = $_POST['emergency_contact_phone'];
    $address = $_POST['address'];
    $date_of_birth = $_POST['date_of_birth'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert student details into students table
    $query_student = $db->prepare("INSERT INTO students (first_name, last_name, email, password, phone_number, emergency_contact_name, emergency_contact_phone, address, date_of_birth) 
                               VALUES (:first_name, :last_name, :email, :password, :phone_number, :emergency_contact_name, :emergency_contact_phone, :address, :date_of_birth)");
    $query_student->execute(array(
        ':first_name' => $first_name,
        ':last_name' => $last_name,
        ':email' => $email,
        ':password' => $hashed_password,
        ':phone_number' => $phone_number,
        ':emergency_contact_name' => $emergency_contact_name,
        ':emergency_contact_phone' => $emergency_contact_phone,
        ':address' => $address,
        ':date_of_birth' => $date_of_birth
    ));

    // Retrieve student_id of the newly registered student
    $student_id = $db->lastInsertId();

    // Insert education details into education table
    $query_education = $db->prepare("INSERT INTO education (student_id, degree, major, institution, start_date, end_date) 
                               VALUES (:student_id, :degree, :major, :institution, :edu_start_date, :edu_end_date)");
    $query_education->execute(array(
        ':student_id' => $student_id,
        ':degree' => $_POST['degree'],
        ':major' => $_POST['major'],
        ':institution' => $_POST['institution'],
        ':edu_start_date' => $_POST['edu_start_date'],
        ':edu_end_date' => $_POST['edu_end_date']
    ));

    // Insert work experience details into work_experience table
    $query_experience = $db->prepare("INSERT INTO work_experience (student_id, company_name, position, description) 
                               VALUES (:student_id, :company_name, :position,  :description)");
    $query_experience->execute(array(
        ':student_id' => $student_id,
        ':company_name' => $_POST['company_name'],
        ':position' => $_POST['position'],
        ':description' => $_POST['description']
    ));

    // Insert skills details into skills table
    $query_skills = $db->prepare("INSERT INTO skills (student_id, skill_name, proficiency) 
                               VALUES (:student_id, :skill_name, :proficiency)");
    $query_skills->execute(array(
        ':student_id' => $student_id,
        ':skill_name' => $_POST['skill_name'],
        ':proficiency' => $_POST['proficiency']
    ));

    // Redirect to login page after successful registration
    header("Location: login.php");
    exit;
}
?>

<body>
   
            <div class="card">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" required><br>
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" required><br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br>
                <label for="phone_number">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number"><br>
                <label for="emergency_contact_name">Emergency Contact Name:</label>
                <input type="text" id="emergency_contact_name" name="emergency_contact_name"><br>
                <label for="emergency_contact_phone">Emergency Contact Phone:</label>
                <input type="text" id="emergency_contact_phone" name="emergency_contact_phone"><br>
                <label for="address">Address:</label>
                <textarea id="address" name="address"></textarea><br>
                <label for="date_of_birth">Date of Birth:</label>
                <input type="date" id="date_of_birth" name="date_of_birth"><br>
            </div>
        </div>

        <!-- Education Section -->
        <div class="section">
            <h3>Education</h3>
            <div class="card">
                <label for="degree">Degree:</label>
                <input type="text" id="degree" name="degree"><br>
                <label for="major">Major:</label>
                <input type="text" id="major" name="major"><br>
                <label for="institution">Institution:</label>
                <input type="text" id="institution" name="institution"><br>
                <label for="edu_start_date">Start Date:</label>
                <input type="date" id="edu_start_date" name="edu_start_date"><br>
                <label for="edu_end_date">End Date:</label>
                <input type="date" id="edu_end_date" name="edu_end_date"><br>
            </div>
        </div>

        <!-- Work Experience Section -->
        <div class="section">
            <h3>Work Experience</h3>
            <div class="card">
                <label for="company_name">Company Name:</label>
                <input type="text" id="company_name" name="company_name"><br>
                <label for="position">Position:</label>
                <input type="text" id="position" name="position"><br>
                <label for="description">Description:</label>
                <textarea id="description" name="description"></textarea><br>
            </div>
        </div>

        <!-- Skills Section -->
        <div class="section">
            <h3>Skills</h3>
            <div class="card">
                <label for="skill_name">Skill Name:</label>
                <input type="text" id="skill_name" name="skill_name"><br>
                <label for="proficiency">Proficiency:</label>
                <input type="text" id="proficiency" name="proficiency"><br>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="section">
            <input type="submit" value="Register">
        </div>
    </form>
</body>
</html>
