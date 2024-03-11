<?php
session_start();
// Include the database configuration file
include_once 'config.php';

// Initialize variables
$title = $description = '';
$title_err = $file_err = '';

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    // Validate title
    if (empty(trim($_POST["title"]))) {
        $title_err = "Please enter a title.";
    } else {
        $title = trim($_POST["title"]);
    }

    // Check if file was uploaded without errors
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
        $file_name = $_FILES["file"]["name"];
        $file_temp = $_FILES["file"]["tmp_name"];
        $file_size = $_FILES["file"]["size"];
        $file_type = $_FILES["file"]["type"];

        // Check file size
        if ($file_size > 10485760) { // 10MB
            $file_err = "File size must be less than 10MB.";
        }

        // Allow certain file formats
        $allowed_types = array("application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        if (!in_array($file_type, $allowed_types)) {
            $file_err = "Only PDF, DOC, and DOCX files are allowed.";
        }

        // Check if file_err is set to 0 by an error
        if (empty($file_err)) {
            $file_path = "uploads/" . uniqid() . "_" . $file_name;

            // Move uploaded file to designated directory
            if (move_uploaded_file($file_temp, $file_path)) {
                // Insert document details into database
                $query = $db->prepare("INSERT INTO documents (student_id, title, description, file_path) VALUES (:student_id, :title, :description, :file_path)");
                $query->execute(array(
                    ':student_id' => $_SESSION["student_id"],
                    ':title' => $title,
                    ':description' => $_POST["description"],
                    ':file_path' => $file_path
                ));
            } else {
                $file_err = "Failed to upload file.";
            }
        }
    } else {
        $file_err = "Please select a file to upload.";
    }
}

// Fetch user's documents
$documents = [];
if (isset($_SESSION["student_id"])) {
    $query = $db->prepare("SELECT * FROM documents WHERE student_id = :student_id ORDER BY upload_date DESC");
    $query->execute(array(':student_id' => $_SESSION["student_id"]));
    $documents = $query->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Management - Student Portal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-top: 30px;
        }

        div {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h3 {
            color: #007bff;
            margin-top: 0;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        input[type="file"] {
            margin-top: 5px;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>Document Management</h2>
    <div>
        <h3>Upload New Document</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div>
                <label>Title:</label><br>
                <input type="text" name="title" value="<?php echo $title; ?>"><br>
                <span><?php echo $title_err; ?></span><br>
            </div>
            <div>
                <label>Description:</label><br>
                <textarea name="description"></textarea><br>
            </div>
            <div>
                <label>Choose File:</label><br>
                <input type="file" name="file"><br>
                <span><?php echo $file_err; ?></span><br>
            </div>
            <div>
                <input type="submit" name="submit" value="Upload">
            </div>
        </form>
    </div>

    <div>
        <h3>My Documents</h3>
        <?php if (count($documents) > 0): ?>
            <ul>
                <?php foreach ($documents as $document): ?>
                    <li>
                        <a href="<?php echo $document['file_path']; ?>" target="_blank"><?php echo $document['title']; ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No documents found.</p>
        <?php endif; ?>
    </div>
    <div>
        <!-- Back Button -->
        <a href="dashboard.php" style="display: block; text-align: center; margin-top: 20px; color: #007bff; text-decoration: none;">Back to Dashboard</a>
    </div>
</body>
</html>
