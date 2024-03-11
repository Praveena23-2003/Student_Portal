<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Student Portal</title>
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
        ul {
            list-style-type: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        li {
            margin: 10px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            width: 200px;
        }
        li:hover {
            transform: translateY(-5px);
        }
        a {
            display: block;
            padding: 20px;
            color: #333;
            text-decoration: none;
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Welcome to Your Dashboard</h2>
    <ul>
        <li>
            <a href="course_enrollment.php">Course Enrollment</a>
        </li>
        <li>
            <a href="document_management.php">Document Management</a>
        </li>
        <li>
            <a href="student_profile.php">Student Profile Management</a>
        </li>
        <li>
            <a href="report.php">Report</a>
        </li>
    </ul>
</body>
</html>
