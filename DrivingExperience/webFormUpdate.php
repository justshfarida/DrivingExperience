<?php
ini_set('display_errors', 1);
session_start();
include("connectDB.inc.php");

$db = Database::getInstance();
$pdo = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code']; // Retrieve session code
    if (!isset($_SESSION['code'][$code])) {
        exit("Invalid or expired code.");
    }

    $experience_id = $_SESSION['code'][$code]; // Retrieve experience_id from session
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];
    $distance = $_POST['distance'];
    $weather_id = $_POST['weather_id'];
    $road_id = $_POST['road_id'];
    $traffic_id = $_POST['traffic_id'];
    $maneuvers = isset($_POST['maneuvers']) ? $_POST['maneuvers'] : [];

    // Validate input
    if (empty($startTime) || empty($endTime) || empty($distance) || empty($weather_id) || empty($road_id) || empty($traffic_id)) {
        echo "Please fill in all required fields.";
        exit();
    }
    if (empty($maneuvers)) {
        echo "Please select at least one maneuver.";
        exit();
    }

    try {
        if ($experience_id == 0) {
            // Insert new experience
            $query = "INSERT INTO Driving_Experience (start_time, end_time, distance, weather_id, road_id, traffic_id)
                      VALUES (:startTime, :endTime, :distance, :weather_id, :road_id, :traffic_id)";
            $stmt = $pdo->prepare($query);

            $stmt->bindParam(':startTime', $startTime, PDO::PARAM_STR);
            $stmt->bindParam(':endTime', $endTime, PDO::PARAM_STR);
            $stmt->bindParam(':distance', $distance, PDO::PARAM_INT);
            $stmt->bindParam(':weather_id', $weather_id, PDO::PARAM_INT);
            $stmt->bindParam(':road_id', $road_id, PDO::PARAM_INT);
            $stmt->bindParam(':traffic_id', $traffic_id, PDO::PARAM_INT);

            $stmt->execute();
            $experience_id = $pdo->lastInsertId();
        } else {
            // Update existing experience
            $query = "UPDATE Driving_Experience 
                      SET start_time = :startTime, end_time = :endTime, distance = :distance, 
                          weather_id = :weather_id, road_id = :road_id, traffic_id = :traffic_id
                      WHERE experience_id = :experience_id";
            $stmt = $pdo->prepare($query);

            $stmt->bindParam(':startTime', $startTime, PDO::PARAM_STR);
            $stmt->bindParam(':endTime', $endTime, PDO::PARAM_STR);
            $stmt->bindParam(':distance', $distance, PDO::PARAM_INT);
            $stmt->bindParam(':weather_id', $weather_id, PDO::PARAM_INT);
            $stmt->bindParam(':road_id', $road_id, PDO::PARAM_INT);
            $stmt->bindParam(':traffic_id', $traffic_id, PDO::PARAM_INT);
            $stmt->bindParam(':experience_id', $experience_id, PDO::PARAM_INT);

            $stmt->execute();
        }

        // Update maneuvers
        $query_clear_maneuvers = "DELETE FROM Driving_Maneuvers WHERE experience_id = :experience_id";
        $stmt_clear = $pdo->prepare($query_clear_maneuvers);
        $stmt_clear->execute([':experience_id' => $experience_id]);

        $query_maneuvers = "INSERT INTO Driving_Maneuvers (experience_id, maneuver_id) VALUES (:experience_id, :maneuver_id)";
        $stmt_maneuvers = $pdo->prepare($query_maneuvers);
        foreach ($maneuvers as $maneuver_id) {
            $stmt_maneuvers->execute([':experience_id' => $experience_id, ':maneuver_id' => $maneuver_id]);
        }

        // Redirect on success
        header("Location: dashboard.php?status=success");
        exit();
    } catch (PDOException $e) {
        error_log("Error saving data: " . $e->getMessage());
        header("Location: errorPage.php?error=SavingFailed");
        exit();
    }
}
?>
