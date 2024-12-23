<?php
// $_SESSION to anonymize data
session_start();
session_destroy();
session_start();
$code = bin2hex(random_bytes(10)); // Generate a random code
$_SESSION['code'][$code] = 0; // Default for a new experience
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Homepage</title>
    <link rel="stylesheet" href="css/landingPage.css">
    <link rel="stylesheet" href="css/shared.css">
</head>
<body>

<div class="hero">
    <div class="hero-content">
        <h1>Welcome to Driving Experience!</h1>
        <p>Your journey starts here. Track your driving experiences and create new adventures!</p>
        <div class="buttons">
            <a href="dashboard.php" class="btn">Dashboard</a>
            <a href="webForm.php?mode=new&code=<?php echo $code; ?>" class="btn">Web Form</a>
        </div>
    </div>
    <div class="hero-image">
        <img src="img/car.jpeg" alt="Car on road">
    </div>
</div>

</body>
</html>

