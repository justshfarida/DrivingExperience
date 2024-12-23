<?php
$host = 'mysql-farida-sha.alwaysdata.net'; // MySQL server
$username = '334923_ufaz2024';  // MySQL username
$password = 'Dombili2005';      // MySQL password (use your password)
$dbname = 'farida-sha_pw4';  
// Record the start time before the operation
$startTime = microtime(true);

// Create a new MySQL connection (OOP)
$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Prepare the SQL query for insertion
$stmt = $mysqli->prepare("INSERT INTO customers 
(customerTitle, customerLastName, customerFirstName, customerStreetAddress, customerStreetAddress2, customerZipCode, customerCity, customerPhone, customerEmail, customerRegisterDate) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

if (($handle = fopen("customers.csv", "r")) !== FALSE) {
    // Skip the header row of the CSV
    fgetcsv($handle, 1000, ";");

    // Loop through the rows of the CSV file
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        // Use list() to assign CSV data to variables
        list(
            $dummy,            // Skip the first column (no need for customerTitle)
            $customerTitle,
            $customerLastName,
            $customerFirstName,
            $customerStreetAddress,
            $customerStreetAddress2,
            $customerZipCode,
            $customerCity,
            $customerPhone,
            $customerEmail,
            $customerRegisterDate
        ) = $data; // Assign each CSV element to its respective variable

        // Bind the CSV data to the prepared statement
        $stmt->bind_param("ssssssssss", $customerTitle, $customerLastName, $customerFirstName, $customerStreetAddress, $customerStreetAddress2, $customerZipCode, $customerCity, $customerPhone, $customerEmail, $customerRegisterDate);
        
        // Execute the prepared statement
        $stmt->execute();
    }

    // Close the file handle
    fclose($handle);
}

// Close the prepared statement and MySQL connection
$stmt->close();
$mysqli->close();

// Record the end time after the operation
$endTime = microtime(true);

// Calculate the elapsed time
$elapsedTime = $endTime - $startTime;

// Display the success message and the time taken
echo "CSV data successfully imported into the 'customers' table!<br>";
echo "Time taken for CSV import: " . number_format($elapsedTime, 4) . " seconds";
?>
