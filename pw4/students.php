<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection parameters
$host = 'localhost:3306'; // MySQL server
$username = 'root';  // MySQL username
$password = '';      // MySQL password
$dbname = 'pw4';     // Database name

// Establish the MySQL connection
$mysqliObject = new mysqli($host, $username, $password, $dbname);

// Check for connection errors
if ($mysqliObject->connect_errno) {
    echo "Error N° " . $mysqliObject->connect_errno . ", Msg : " . $mysqliObject->connect_error . "<br>";
    exit();
}

// Query to get the timetable for each student
$query = "
    SELECT c.class_full_name, sub.subject, p.first_name AS professor_first_name, p.last_name AS professor_last_name, l.course_date, l.start_time, l.end_time FROM lessons l JOIN classes c ON l.class_id = c.class_id JOIN subjects sub ON l.subject_id = sub.subject_id JOIN professors p ON l.professor_id = p.professor_id ORDER BY l.course_date, l.start_time; 
";

// Execute the query
$result = $mysqliObject->query($query);

// Check if any rows were returned
if ($result->num_rows > 0) {
    // Display timetable for each student
    $currentStudent = '';
    echo '<style>
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                padding: 10px;
                border: 1px solid #ddd;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
            h2 {
                margin-top: 20px;
            }
          </style>';

    // Start of the table
    echo '<table>';
    echo '<thead>
            <tr>
                <th>Student Name</th>
                <th>Class</th>
                <th>Subject</th>
                <th>Professor</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
          </thead>';
    echo '<tbody>';

    // Loop through the results
    while ($row = $result->fetch_assoc()) {

        echo "<tr>";
        echo "<td>" . $row['class_full_name'] . "</td>";
        echo "<td>" . $row['subject'] . "</td>";
        echo "<td>" . $row['professor_first_name'] . " " . $row['professor_last_name'] . "</td>";
        echo "<td>" . $row['course_date'] . "</td>";
        echo "<td>" . $row['start_time'] . " - " . $row['end_time'] . "</td>";
        echo "</tr>";
    }

    echo '</tbody>';
    echo '</table>';
} else {
    echo "No records found.";
}

// Close the database connection
$mysqliObject->close();
?>
