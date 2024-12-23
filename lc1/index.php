<?php
// Check if form data has been submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data from $_POST
    $amount = $_POST['amount'];
    $deposit = $_POST['deposit'];
    $num_payments = $_POST['months'];

    // Calculate the balance and monthly payment
    $balance = $amount - $deposit;
    $monthly_payment = $balance / $num_payments;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Payment Schedule</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 60%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        input, button {
            padding: 10px;
            font-size: 16px;
            margin: 10px 0;
        }
    </style>
</head>
<body>

    <h2>Loan Payment Schedule Form</h2>
    
    <!-- Form to input loan data -->
    <form action="index.php" method="POST">
        <label for="amount">Amount (€):</label>
        <input type="number" id="amount" name="amount" required><br><br>

        <label for="deposit">Initial Deposit (€):</label>
        <input type="number" id="deposit" name="deposit" required><br><br>

        <label for="months">Number of Monthly Payments:</label>
        <input type="number" id="months" name="months" required><br><br>

        <button type="submit">Generate Schedule</button>
    </form>

    <?php
    // If the form is submitted, display the table
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Display loan details
        echo "<h3>Loan Details:</h3>";
        echo "<p>Amount: €" . number_format($amount, 2) . "</p>";
        echo "<p>Initial Deposit: €" . number_format($deposit, 2) . "</p>";
        echo "<p>Balance: €" . number_format($balance, 2) . "</p>";
        echo "<p>Monthly Payment: €" . number_format($monthly_payment, 2) . "</p>";

        // Start the HTML table
        echo "<h3>Loan Repayment Schedule:</h3>";
        echo "<table>";
        echo "<tr><th>Payment #</th><th>Payment Date</th><th>Amount</th><th>Remaining Balance</th></tr>";

        // Loop to generate each row of the table
        for ($i = 1; $i <= $num_payments; $i++) {
            // Calculate the payment date (next month)
            $payment_date = date('Y-m-d', strtotime("+$i month"));
            
            // Calculate the remaining balance after each payment
            $remaining_balance = $balance - ($i * $monthly_payment);

            // Print the row for the current payment
            echo "<tr>";
            echo "<td>" . $i . "</td>";
            echo "<td>" . $payment_date . "</td>";
            echo "<td>" . number_format($monthly_payment, 2) . " €</td>";
            echo "<td>" . number_format($remaining_balance, 2) . " €</td>";
            echo "</tr>";
        }

        // End the HTML table
        echo "</table>";
    }
    ?>

</body>
</html>
