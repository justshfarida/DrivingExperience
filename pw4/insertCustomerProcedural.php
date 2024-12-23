<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Function to get the current time in microseconds
function getmicrotime() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

// Database connection parameters
$host = 'mysql-farida-sha.alwaysdata.net'; // MySQL server
$username = '334923_ufaz2024';  // MySQL username
$password = 'Dombili2005';      // MySQL password (use your password)
$dbname = 'farida-sha_pw4';     // Database name

// Record the start time before the operation
$startTime = getmicrotime();

// Create a MySQL connection using procedural style
$link = mysqli_connect($host, $username, $password, $dbname);

// Check if the connection is successful
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

// Open the CSV file for reading
if (($handle = fopen("customers.csv", "r")) !== FALSE) {
    // Skip the header row
    fgetcsv($handle, 1000, ";");

    // Loop through the rows of the CSV
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        // Escape special characters to avoid SQL injection
        $customerTitle = mysqli_real_escape_string($link, $data[1]);
        $customerLastName = mysqli_real_escape_string($link, $data[2]);
        $customerFirstName = mysqli_real_escape_string($link, $data[3]);
        $customerStreetAddress = mysqli_real_escape_string($link, $data[4]);
        $customerStreetAddress2 = mysqli_real_escape_string($link, $data[5]);
        $customerZipCode = mysqli_real_escape_string($link, $data[6]);
        $customerCity = mysqli_real_escape_string($link, $data[7]);
        $customerPhone = mysqli_real_escape_string($link, $data[8]);
        $customerEmail = mysqli_real_escape_string($link, $data[9]);
        $customerRegisterDate = mysqli_real_escape_string($link, $data[10]);

        // Create the SQL query to insert the data
        $query = "INSERT INTO customers (customerTitle, customerLastName, customerFirstName, customerStreetAddress, customerStreetAddress2, customerZipCode, customerCity, customerPhone, customerEmail, customerRegisterDate) 
                  VALUES ('$customerTitle', '$customerLastName', '$customerFirstName', '$customerStreetAddress', '$customerStreetAddress2', '$customerZipCode', '$customerCity', '$customerPhone', '$customerEmail', '$customerRegisterDate')";

        // Execute the query
        mysqli_query($link, $query);
    }

    // Close the file handle
    fclose($handle);
}

// Close the MySQL connection
mysqli_close($link);

// Record the end time after the operation
$endTime = getmicrotime();

// Calculate the elapsed time
$elapsedTime = $endTime - $startTime;

// Display the success message and the time taken
echo "CSV data successfully imported into the 'customers' table!<br>";
echo "Time taken for CSV import (procedural): " . number_format($elapsedTime, 4) . " seconds";
?>
