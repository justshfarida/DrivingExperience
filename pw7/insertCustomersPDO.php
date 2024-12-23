<?php
// Start session to store any session variables
session_start();

$host = 'mysql-farida-sha.alwaysdata.net'; // MySQL server
$username = '334923_ufaz2024';  // MySQL username
$password = 'Dombili2005';      // MySQL password
$dbname = 'farida-sha_pw7';     // Database name

$dsn = "mysql:host=$host;dbname=$dbname;port=3306;charset=utf8";
try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data and sanitize it
    $customerTitle = htmlspecialchars($_POST['customerTitle']);
    $customerFirstName = htmlspecialchars($_POST['customerFirstname']);
    $customerLastName = htmlspecialchars($_POST['customerLastname']);
    $customerStreetAddress = htmlspecialchars($_POST['customerStreetAddress']);
    $customerStreetAddress2 = htmlspecialchars($_POST['customerStreetAddress2']);
    $customerZipCode = htmlspecialchars($_POST['customerZipCode']);
    $customerCity = htmlspecialchars($_POST['customerCity']);
    $customerPhone = htmlspecialchars($_POST['customerPhone']);
    $customerEmail = htmlspecialchars($_POST['customerEmail']);
    $customerRegisterDate = htmlspecialchars($_POST['customerRegisterDate']);

    // Prepare the SQL query to insert customer data
    $query = "INSERT INTO customers 
              (customerTitle, customerLastname, customerFirstname, customerStreetAddress, customerStreetAddress2, customerZipCode, customerCity, customerPhone, customerEmail, customerRegisterDate) 
              VALUES 
              (:customerTitle, :customerLastname, :customerFirstname, :customerStreetAddress, :customerStreetAddress2, :customerZipCode, :customerCity, :customerPhone, :customerEmail, :customerRegisterDate)";
    
    // Prepare the statement
    $stmt = $pdo->prepare($query);

    // Bind parameters
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

    // Execute the statement
    try {
        $stmt->execute();
        echo "Customer data successfully inserted!";
    } catch (PDOException $e) {
        echo "Error inserting data: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driving Experience Form</title>
</head>
<body>

<h1>Customer Data Entry</h1>

<!-- HTML Form to collect customer data -->
<form action="insertCustomersPDO.php" method="POST">
    <label for="customerTitle">Title:</label>
    <input type="radio" name="customerTitle" value="Mr" required> Mr
    <input type="radio" name="customerTitle" value="Mrs"> Mrs<br><br>

    <label for="customerFirstname">First Name:</label>
    <input type="text" name="customerFirstname" required><br><br>

    <label for="customerLastName">Last Name:</label>
    <input type="text" name="customerLastname" required><br><br>

    <label for="customerStreetAddress">Street Address:</label>
    <input type="text" name="customerStreetAddress" required><br><br>

    <label for="customerStreetAddress2">Street Address 2:</label>
    <input type="text" name="customerStreetAddress2"><br><br>

    <label for="customerZipCode">Zip Code:</label>
    <input type="text" name="customerZipCode" required><br><br>

    <label for="customerCity">City:</label>
    <input type="text" name="customerCity" required><br><br>

    <label for="customerPhone">Phone:</label>
    <input type="text" name="customerPhone" required><br><br>

    <label for="customerEmail">Email:</label>
    <input type="email" name="customerEmail" required><br><br>

    <label for="customerRegisterDate">Registration Date:</label>
    <input type="date" name="customerRegisterDate" required><br><br>

    <button type="submit">Submit</button>
</form>

</body>
</html>
