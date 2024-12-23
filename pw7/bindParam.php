<?php

// Database connection parameters
$host = 'mysql-farida-sha.alwaysdata.net'; // MySQL server
$username = '334923_ufaz2024';  // MySQL username
$password = 'Dombili2005';      // MySQL password
$dbname = 'farida-sha_pw7';     // Database name
$startTime = microtime(true);

// Establish PDO connection
$dsn = "mysql:host=$host;dbname=$dbname;port=3306;charset=utf8";
try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

// Prepare SQL query for inserting data
$query = "INSERT INTO customers (customerTitle, customerLastname, customerFirstname, customerStreetAddress, customerStreetAddress2, customerZipCode, customerCity, customerPhone, customerEmail, customerRegisterDate) 
          VALUES (:customerTitle, :customerLastname, :customerFirstname, :customerStreetAddress, :customerStreetAddress2, :customerZipCode, :customerCity, :customerPhone, :customerEmail, :customerRegisterDate)";

$stmt = $pdo->prepare($query);

// Open the CSV file for reading
if (($handle = fopen("customers.csv", "r")) !== FALSE) {
    fgetcsv($handle, 1000, ";");  // Skip the header row

    // Loop through each row in the CSV file
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        list(
            $dummy,  // Skip the first column (no need for customerTitle)
            $customerTitle,
            $customerLastname,
            $customerFirstname,
            $customerStreetAddress,
            $customerStreetAddress2,
            $customerZipCode,
            $customerCity,
            $customerPhone,
            $customerEmail,
            $customerRegisterDate
        ) = $data;

        // Bind parameters to the prepared statement
        $stmt->bindParam(':customerTitle', $customerTitle, PDO::PARAM_STR);
        $stmt->bindParam(':customerLastname', $customerLastname, PDO::PARAM_STR);
        $stmt->bindParam(':customerFirstname', $customerFirstname, PDO::PARAM_STR);
        $stmt->bindParam(':customerStreetAddress', $customerStreetAddress, PDO::PARAM_STR);
        $stmt->bindParam(':customerStreetAddress2', $customerStreetAddress2, PDO::PARAM_STR);
        $stmt->bindParam(':customerZipCode', $customerZipCode, PDO::PARAM_STR);
        $stmt->bindParam(':customerCity', $customerCity, PDO::PARAM_STR);
        $stmt->bindParam(':customerPhone', $customerPhone, PDO::PARAM_STR);
        $stmt->bindParam(':customerEmail', $customerEmail, PDO::PARAM_STR);
        $stmt->bindParam(':customerRegisterDate', $customerRegisterDate, PDO::PARAM_STR);

        // Execute the prepared statement
        $stmt->execute();
    }

    fclose($handle);  // Close the file handle
}

// Calculate the time taken for the import process
$endTime = microtime(true);
$elapsedTime = $endTime - $startTime;

// Fetch all the data from the table to display it
$query = "SELECT * FROM customers";
$customers = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);

// Close the prepared statement and PDO connection
$stmt = null;
$pdo = null;

echo "CSV data successfully imported into the 'customers' table!<br>";
echo "Time taken for CSV import: " . number_format($elapsedTime, 4) . " seconds";

// Display the table of inserted customer data
echo "<h2>Customer Data</h2>";
echo "<table border='1'>";
echo "<tr><th>Title</th><th>Last Name</th><th>First Name</th><th>Street Address</th><th>Street Address 2</th><th>Zip Code</th><th>City</th><th>Phone</th><th>Email</th><th>Register Date</th></tr>";

foreach ($customers as $customer) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($customer['customerTitle']) . "</td>";
    echo "<td>" . htmlspecialchars($customer['customerLastname']) . "</td>";
    echo "<td>" . htmlspecialchars($customer['customerFirstname']) . "</td>";
    echo "<td>" . htmlspecialchars($customer['customerStreetAddress']) . "</td>";
    echo "<td>" . htmlspecialchars($customer['customerStreetAddress2']) . "</td>";
    echo "<td>" . htmlspecialchars($customer['customerZipCode']) . "</td>";
    echo "<td>" . htmlspecialchars($customer['customerCity']) . "</td>";
    echo "<td>" . htmlspecialchars($customer['customerPhone']) . "</td>";
    echo "<td>" . htmlspecialchars($customer['customerEmail']) . "</td>";
    echo "<td>" . htmlspecialchars($customer['customerRegisterDate']) . "</td>";
    echo "</tr>";
}
echo "</table>";
?>
