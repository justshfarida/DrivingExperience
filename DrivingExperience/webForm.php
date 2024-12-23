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
    <title><?php echo $mode === 'new' ? "Add New Driving Experience" : "Edit Driving Experience"; ?></title>
    <link rel="stylesheet" href="css/webForm.css"/>
    <link rel="stylesheet" href="css/shared.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('select[name="maneuvers[]"]').select2({
                placeholder: "Select maneuvers",
                allowClear: true,
                dropdownAutoWidth: true,
                width: '100%',
            });

            // Progress bar update on input
            $('input, select').on('input', function() {
                const filledInputs = $('input:valid, select:valid').length;
                const totalInputs = $('input, select').length;
                const progressPercent = (filledInputs / totalInputs) * 100;
                $('.progress-bar .progress').css('width', progressPercent + '%');
            });
        });
    </script>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <img src="img/car-icon.png" alt="Car Icon">
            <h1><?php echo $mode === 'new' ? "Add New Driving Experience" : "Edit Driving Experience"; ?></h1>
        </div>

        <div class="progress-bar">
            <div class="progress" style="width: 0%;"></div>
        </div>

        <form action="webFormUpdate.php" method="POST">
            <input type="hidden" name="code" value="<?php echo htmlspecialchars($code); ?>">

            <label for="startTime">Start Time:</label>
            <input type="time" name="startTime" value="<?php echo htmlspecialchars($startTime); ?>" required>

            <label for="endTime">End Time:</label>
            <input type="time" name="endTime" value="<?php echo htmlspecialchars($endTime); ?>" required>

            <label for="distance">Distance (km):</label>
            <input type="number" name="distance" value="<?php echo htmlspecialchars($distance); ?>" min="1" required>

            <label for="weather_id">Weather:</label>
            <select name="weather_id" required>
                <?php foreach ($weatherOptions as $weather): ?>
                    <option value="<?php echo $weather['weather_id']; ?>" 
                        <?php echo $weather_id == $weather['weather_id'] ? 'selected' : ''; ?>>
                        <?php echo $weather['weather_condition']; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="road_id">Road Condition:</label>
            <select name="road_id" required>
                <?php foreach ($roadOptions as $road): ?>
                    <option value="<?php echo $road['road_id']; ?>" 
                        <?php echo $road_id == $road['road_id'] ? 'selected' : ''; ?>>
                        <?php echo $road['road_condition']; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="traffic_id">Traffic:</label>
            <select name="traffic_id" required>
                <?php foreach ($trafficOptions as $traffic): ?>
                    <option value="<?php echo $traffic['traffic_id']; ?>" 
                        <?php echo $traffic_id == $traffic['traffic_id'] ? 'selected' : ''; ?>>
                        <?php echo $traffic['traffic_condition']; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="maneuvers">Maneuvers:</label>
            <select name="maneuvers[]" multiple="multiple" required>
                <?php foreach ($maneuverOptions as $maneuver): ?>
                    <option value="<?php echo $maneuver['maneuver_id']; ?>" 
                        <?php echo in_array($maneuver['maneuver_id'], $selectedManeuvers) ? 'selected' : ''; ?>>
                        <?php echo $maneuver['maneuver_type']; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit"><?php echo $mode === 'new' ? "Add Experience" : "Update Experience"; ?></button>
        </form>

        <div class="fun-fact">
            🚗 Fun Fact: The world's longest road, the Pan-American Highway, spans over 19,000 miles!
        </div>
    </div>
</body>
</html>
