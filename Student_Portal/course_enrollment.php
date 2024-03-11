<?php
// Include the database configuration file
include_once 'config.php';

// Start the session
session_start();

// Check if the student is logged in
if (!isset($_SESSION['student_id'])) {
    // Redirect the user to the login page or display an error message
    header("Location: login.php");
    exit;
}

// Get the student ID from the session
$student_id = $_SESSION['student_id'];

// Fetch enrolled courses for the logged-in student
$query = $db->prepare("SELECT courses.course_id, courses.course_name FROM student_courses JOIN courses ON student_courses.course_id = courses.course_id WHERE student_courses.student_id = ?");
$query->execute([$student_id]);
$enrolled_courses = $query->fetchAll(PDO::FETCH_ASSOC);

// Fetch available courses for enrollment
$query = $db->query("SELECT * FROM courses");
$courses = $query->fetchAll(PDO::FETCH_ASSOC);

// Process course enrollment when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['course_id'])) {
        $course_id = $_POST['course_id'];
        
        // Check if the student is already enrolled in the course
        $enrollment_query = $db->prepare("SELECT * FROM student_courses WHERE student_id = :student_id AND course_id = :course_id");
        $enrollment_query->execute(array(':student_id' => $student_id, ':course_id' => $course_id));
        $enrollment = $enrollment_query->fetch(PDO::FETCH_ASSOC);
        
        if (!$enrollment) {
            // Insert enrollment data into student_courses table
            $insert_query = $db->prepare("INSERT INTO student_courses (student_id, course_id) VALUES (:student_id, :course_id)");
            $insert_query->execute(array(':student_id' => $student_id, ':course_id' => $course_id));
            
            if ($insert_query) {
                // Enrollment successful, redirect to a success page or display a message
                header("Location: enrollment_success.php");
                exit;
            } else {
                echo "Error enrolling in the course.";
            }
        } else {
            echo "You are already enrolled in this course.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Enrollment</title>
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
        div {
            margin: 0 auto;
            width: 80%;
            max-width: 600px;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h3 {
            color: #333;
            margin-top: 0;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-bottom: 10px;
        }
        form {
            margin-top: 20px;
        }
        select, input[type="submit"] {
            padding: 10px;
            margin-right: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: calc(100% - 100px);
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>Course Enrollment</h2>
    <div>
        <h3>Enrolled Courses</h3>
        <ul>
            <?php foreach ($enrolled_courses as $course): ?>
                <li><?php echo $course['course_name']; ?></li>
            <?php endforeach; ?>
        </ul>
        <h3>Available Courses</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <select name="course_id">
                    <?php foreach ($courses as $course): ?>
                        <option value="<?php echo $course['course_id']; ?>"><?php echo $course['course_name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="submit" value="Enroll">
            </div>
        </form>
    </div>
    <!-- Back button -->
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
