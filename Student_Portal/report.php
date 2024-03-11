<?php
// Include the TCPDF library
require_once('tcpdf/tcpdf.php');

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

// Fetch student profile data for the logged-in user
$profileData = fetchStudentProfile($db, $student_id);

// Function to fetch student profile data for the logged-in user
function fetchStudentProfile($db, $student_id) {
    // Query to fetch data from the student_profile view for the logged-in user
    $query = $db->prepare("SELECT * FROM student_profile WHERE student_id = ?");
    $query->execute([$student_id]);
    $profileData = $query->fetchAll(PDO::FETCH_ASSOC);

    return $profileData;
}

// Check if PDF generation is requested
if (isset($_GET['generate_pdf'])) {
    if (!empty($profileData)) {
        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Student Profile Report');
        $pdf->SetSubject('Student Profile Report');
        $pdf->SetKeywords('TCPDF, PDF, student, profile, report');

        // Set default header and footer data
        $pdf->SetHeaderData('', PDF_HEADER_LOGO_WIDTH, 'Student Profile Report', '');

        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', '', 10);

        // Loop through the profile data and add to PDF
        foreach ($profileData as $profile) {
            // Generate PDF content here
            foreach ($profile as $key => $value) {
                $pdf->Cell(50, 10, ucfirst(str_replace('_', ' ', $key)), 1, 0, 'C');
                $pdf->Cell(140, 10, $value, 1, 1, 'C');
            }
        }

        // Close and output PDF
        $pdf->Output('student_profile_report.pdf', 'I');
        exit;
    } else {
        echo "No data available for the student profile.";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #0056b3;
        }

        p {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
    </style>
</head>
<body>
    <h2>Student Profile Report</h2>
    <?php if (!empty($profileData)): ?>
        <table border="1">
            <thead>
                <tr>
                    <?php foreach ($profileData[0] as $key => $value): ?>
                        <th><?php echo ucfirst(str_replace('_', ' ', $key)); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($profileData as $profile): ?>
                    <tr>
                        <?php foreach ($profile as $value): ?>
                            <td><?php echo $value; ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <a href="?generate_pdf=true">Generate PDF</a>
       
    <?php else: ?>
        
        <p>No data available for the student profile.</p>
    <?php endif; ?>
    <a href="dashboard.php">Back to Dashboard</a> 
</body>
</html>
