<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("connectDB.inc.php");

if (!isset($_GET['mode']) || !isset($_GET['code']) || !isset($_SESSION['code'][$_GET['code']])) {
    exit("Invalid or missing mode or code.");
}

$mode = $_GET['mode'];
$code = $_GET['code'];
$experience_id = $_SESSION['code'][$code];

$db = Database::getInstance();
$pdo = $db->getConnection();

$weatherOptions = $pdo->query("SELECT * FROM Weather")->fetchAll(PDO::FETCH_ASSOC);
$roadOptions = $pdo->query("SELECT * FROM Road")->fetchAll(PDO::FETCH_ASSOC);
$trafficOptions = $pdo->query("SELECT * FROM Traffic")->fetchAll(PDO::FETCH_ASSOC);
$maneuverOptions = $pdo->query("SELECT * FROM Maneuvers")->fetchAll(PDO::FETCH_ASSOC);

$startTime = $endTime = $distance = $weather_id = $road_id = $traffic_id = '';
$selectedManeuvers = [];

if ($mode === 'edit' && $experience_id != 0) {
    $query = "
        SELECT 
            de.start_time, 
            de.end_time, 
            de.distance, 
            de.weather_id, 
            de.road_id, 
            de.traffic_id, 
            GROUP_CONCAT(dm.maneuver_id) AS maneuvers
        FROM 
            Driving_Experience de
        LEFT JOIN 
            Driving_Maneuvers dm ON de.experience_id = dm.experience_id
        WHERE 
            de.experience_id = :experience_id
        GROUP BY 
            de.experience_id;
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':experience_id' => $experience_id]);
    $experienceData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($experienceData) {
        $startTime = $experienceData['start_time'];
        $endTime = $experienceData['end_time'];
        $distance = $experienceData['distance'];
        $weather_id = $experienceData['weather_id'];
        $road_id = $experienceData['road_id'];
        $traffic_id = $experienceData['traffic_id'];
        $selectedManeuvers = explode(',', $experienceData['maneuvers']);
    }
} elseif ($mode === 'new') {
    $experience_id = 0; 
} else {
    exit("Invalid mode.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farida's Driving Experience</title>
    <link rel="stylesheet" href="css/webForm.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('select[name="maneuvers[]"]').select2({
                placeholder: "Select maneuvers",
                allowClear: true,
                dropdownAutoWidth: true,
                width: '100%',
            });

            // Client-side validation for form
            document.querySelector('form').addEventListener('submit', function(e) {
                const startTime = document.querySelector('input[name="startTime"]').value;
                const endTime = document.querySelector('input[name="endTime"]').value;
                const distance = document.querySelector('input[name="distance"]').value;

                if (!startTime || !endTime || startTime >= endTime) {
                    alert("Start Time must be earlier than End Time.");
                    e.preventDefault();
                }

                if (distance <= 0) {
                    alert("Distance must be greater than 0.");
                    e.preventDefault();
                }
            });
        });
    </script>
</head>
<body>

    <div class="container">
        <header class="header">
            <h1>Farida's Driving Experience</h1>
            <p>Track your driving journeys with ease and precision.</p>
        </header>

        <div class="content">
            <!-- Form Section -->
            <div class="form-section">
                <form action="webFormUpdate.php" method="POST">
                    <?php
                    $heading = ($mode === 'edit') ? 'Edit Driving Experience' : 'Add New Driving Experience';
                    ?>
                    <h2><?php echo $heading; ?></h2>
                    <input type="hidden" name="code" value="<?php echo htmlspecialchars($code); ?>">

                    <div class="form-row">
                        <div>
                            <label for="startTime">Start Time:</label>
                            <input type="time" name="startTime" value="<?php echo htmlspecialchars($startTime); ?>" required>
                        </div>
                        <div>
                            <label for="endTime">End Time:</label>
                            <input type="time" name="endTime" value="<?php echo htmlspecialchars($endTime); ?>" required>
                        </div>
                        <div>
                            <label for="distance">Distance (km):</label>
                            <input type="number" name="distance" value="<?php echo htmlspecialchars($distance); ?>" min="1" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div>
                            <label for="weather_id">Weather:</label>
                            <select name="weather_id" required>
                                <?php foreach ($weatherOptions as $weather): ?>
                                    <option value="<?php echo $weather['weather_id']; ?>" 
                                        <?php echo $weather_id == $weather['weather_id'] ? 'selected' : ''; ?>>
                                        <?php echo $weather['weather_condition']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="road_id">Road Condition:</label>
                            <select name="road_id" required>
                                <?php foreach ($roadOptions as $road): ?>
                                    <option value="<?php echo $road['road_id']; ?>" 
                                        <?php echo $road_id == $road['road_id'] ? 'selected' : ''; ?>>
                                        <?php echo $road['road_condition']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div>
                            <label for="traffic_id">Traffic:</label>
                            <select name="traffic_id" required>
                                <?php foreach ($trafficOptions as $traffic): ?>
                                    <option value="<?php echo $traffic['traffic_id']; ?>" 
                                        <?php echo $traffic_id == $traffic['traffic_id'] ? 'selected' : ''; ?>>
                                        <?php echo $traffic['traffic_condition']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="maneuvers">Maneuvers:</label>
                            <select name="maneuvers[]" multiple="multiple" required>
                                <?php foreach ($maneuverOptions as $maneuver): ?>
                                    <option value="<?php echo $maneuver['maneuver_id']; ?>" 
                                        <?php echo in_array($maneuver['maneuver_id'], $selectedManeuvers) ? 'selected' : ''; ?>>
                                        <?php echo $maneuver['maneuver_type']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <button type="submit">Submit</button>
                </form>
            </div>

            <!-- Image Section -->
            <div class="image-section">
                <img src="img/img1.webp" alt="Driving Image 1" class="image1">
                <img src="img/img2.webp" alt="Driving Image 2" class="image2">
            </div>
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
