<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection parameters
$host = 'mysql-farida-sha.alwaysdata.net'; // MySQL server
$username = '334923_ufaz2024';  // MySQL username
$password = 'Dombili2005';      // MySQL password (use your password)
$dbname = 'farida-sha_pw4';     // Database name

$mysqliObject = new mysqli($host, $username, $password, $dbname);

if ($mysqliObject->connect_errno) {
    echo "Error N° " . $mysqliObject->connect_errno . ", Msg : " . $mysqliObject->connect_error . "<br>";
    exit();
}

$query = "
SELECT 
        l.course_date AS lesson_date,
        l.start_time,
        l.end_time,
        l.course_duration AS duration,
        c.class_full_name,
        s.subject,
        CONCAT(p.first_name, ' ', p.last_name) AS professor_name
    FROM 
        lessons l
    JOIN 
        classes c ON l.class_id = c.class_id
    JOIN 
        subjects s ON l.subject_id = s.subject_id
    JOIN 
        professors p ON l.professor_id = p.professor_id
    ORDER BY 
        l.course_date, l.start_time
";

$result = $mysqliObject->query($query);

if ($result->num_rows > 0) {
    // OOP format for styling the table and adding DataTables functionality
    echo '<style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            table { width: 100%; margin-top: 20px; border-collapse: collapse; }
            table, th, td { border: 1px solid black; }
            th, td { padding: 10px; text-align: center; }
            th { background-color: #f2f2f2; }
        </style>';

    // Table HTML
    echo '<h1>Student Timetable</h1>';

    echo '<table id="timetable" class="display">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Duration</th>
                    <th>Class</th>
                    <th>Subject</th>
                    <th>Professor</th>
                </tr>
            </thead>
            <tbody>';

    // Loop through the result set and display each row in the table
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['lesson_date'] . "</td>";
        echo "<td>" . $row['start_time'] . "</td>";
        echo "<td>" . $row['end_time'] . "</td>";
        echo "<td>" . $row['duration'] . " hours</td>";
        echo "<td>" . $row['class_full_name'] . "</td>";
        echo "<td>" . $row['subject'] . "</td>";
        echo "<td>" . $row['professor_name'] . "</td>";
        echo "</tr>";
    }

    echo '</tbody>';
    echo '</table>';

    // Include DataTables CSS and JavaScript
    echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>';
    echo '<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>';

    // Initialize DataTable
    echo '<script>
            $(document).ready(function() {
                $("#timetable").DataTable({
                    "paging": true,         // Enable pagination
                    "searching": true,      // Enable search functionality
                    "ordering": true,       // Enable sorting
                    "info": true,           // Display number of records
                    "lengthChange": false   // Disable the option to change the number of rows per page
                });
            });
          </script>';
} else {
    echo "No records found.";
}

// Close the database connection
$mysqliObject->close();
?>
