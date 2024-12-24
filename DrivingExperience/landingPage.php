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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

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
<div class="container">
  <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
    <div class="col-md-4 d-flex align-items-center">
      <a href="/" class="mb-3 me-2 mb-md-0 text-body-secondary text-decoration-none lh-1">
        <img src="img/logo.webp" alt="Logo" width="30" height="30" class="d-inline-block align-text-top circular-logo">
      </a>
      <span class="mb-3 mb-md-0 text-body-secondary">© 2024 Farida's Driving Experience</span>
    </div>

    <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
      <li class="ms-3"><a class="text-body-secondary" href="#"><i class="bi bi-twitter"></i></a></li>
      <li class="ms-3"><a class="text-body-secondary" href="#"><i class="bi bi-instagram"></i></a></li>
      <li class="ms-3"><a class="text-body-secondary" href="#"><i class="bi bi-facebook"></i></a></li>
    </ul>
  </footer>
</div>
</body>
</html>

